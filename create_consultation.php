<?php
// Include your database connection file

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patientID = $_POST['patientID'];
    $doctorID = $_POST['doctorID'];
    $password = $_POST['password'];
    $date = $_POST['date'];
    $status = $_POST['status'];
    $conn = new mysqli("localhost", "root", "", "medical");

    // Sanitize user inputs to prevent SQL injection
    $patientID = mysqli_real_escape_string($conn, $patientID);
    $doctorID = mysqli_real_escape_string($conn, $doctorID);
    $password = mysqli_real_escape_string($conn, $password);
    $date = mysqli_real_escape_string($conn, $date);
    $status = mysqli_real_escape_string($conn, $status);

    // Check if both patient and doctor exist and their passwords are correct
    $patientQuery = "SELECT * FROM Patient WHERE PatientID = '$patientID'";
    $doctorQuery = "SELECT * FROM Doctor WHERE DoctorID = '$doctorID' AND Password = '$password'";

    $patientResult = mysqli_query($conn, $patientQuery);
    $doctorResult = mysqli_query($conn, $doctorQuery);

    if ($patientResult && mysqli_num_rows($patientResult) == 1 && $doctorResult && mysqli_num_rows($doctorResult) == 1) {
        // Both patient and doctor authenticated successfully, proceed to create a new consultation

        // Insert new consultation record
        $num = rand(0,999999);
        $insertQuery = "INSERT INTO Consultation (ConsultationID, PatientID, DoctorID, Date, Status)
                        VALUES ('$num', '$patientID', '$doctorID', '$date', '$status')";
        $insertResult = mysqli_query($conn, $insertQuery);

        if ($insertResult) {
            echo "New consultation created successfully.";
            printf(" Consultation ID: %s", $num);
        } else {
            echo "Error creating consultation: " . mysqli_error($conn);
        }
    } else {
        // Authentication failed for patient or doctor
        echo "Invalid patient credentials or doctor does not exist.";
    }
}

// Close the database connection
mysqli_close($conn);
?>
