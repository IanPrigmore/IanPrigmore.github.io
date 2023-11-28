<?php
// Include your database connection file

function createDiagnosis($authenticatedDoctorID, $consultationID, $name, $description, $severity, $prescription) {
    $conn = new mysqli("localhost", "root", "", "medical");

    // Insert new diagnosis record
    $num = rand(0,999999);
    $insertQuery = "INSERT INTO Diagnosis (DiagnosisID, ConsultationID, Name, Description, Severity, Prescription)
                    VALUES ('$num', '$consultationID', '$name', '$description', '$severity', '$prescription')";
    $insertResult = mysqli_query($conn, $insertQuery);

    if ($insertResult) {
        echo "New diagnosis created successfully.";
        printf(" Diagnosis ID: %s", $num);
    } else {
        echo "Error creating diagnosis: " . mysqli_error($conn);
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = new mysqli("localhost", "root", "", "medical");
    $doctorID = $_POST['doctorID'];
    $password = $_POST['password'];
    $description = $_POST['description'];
    $severity = $_POST['severity'];
    $prescription = $_POST['prescription'];
    $name = $_POST['name'];
    $consultationID = $_POST['consultationID'];

    // Authenticate patient and get patient ID
    // Sanitize user inputs to prevent SQL injection
    $doctorID = mysqli_real_escape_string($conn, $doctorID);
    $password = mysqli_real_escape_string($conn, $password);

    $name = mysqli_real_escape_string($conn, $name);
    $description = mysqli_real_escape_string($conn, $description);
    $severity = mysqli_real_escape_string($conn, $severity);
    $prescription = mysqli_real_escape_string($conn, $prescription);
    $consultationID = mysqli_real_escape_string($conn, $consultationID);

    // Check if patient exists and the password is correct
    $query = "SELECT * FROM Doctor WHERE DoctorID = '$doctorID' AND Password = '$password'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) == 1) {
        // Patient authenticated successfully
        $authenticatedDoctorID = $doctorID;
    } else {
        // Authentication failed
        $authenticatedDoctorID = -1;
    }

    $checkConsult = "SELECT * FROM Consultation WHERE ConsultationID='$consultationID'";
    $result = mysqli_query($conn, $checkConsult);
    $validConsult = false;
    if($result && mysqli_num_rows($result) == 1) {
        $validConsult = true;
    }
            
    if ($authenticatedDoctorID > 0 && $validConsult) {
        // Patient authenticated successfully, proceed to create a diagnosis

        // Create a new diagnosis for the authenticated patient
        createDiagnosis($authenticatedDoctorID, $consultationID, $name, $description, $severity, $prescription);
    } else {
        // Handle authentication failure
        echo "Invalid doctor credentials or consultation ID.";
    }
}

// Close the database connection
mysqli_close($conn);
?>
