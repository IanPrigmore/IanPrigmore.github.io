<?php

function getDiagnosis($patientID) {
    global $conn;

    // Sanitize user inputs to prevent SQL injection

    // Your SQL query to retrieve DiagnosisLists based on patient ID
    $sql = "SELECT * FROM Diagnosis 
            INNER JOIN Consultation ON Diagnosis.ConsultationID = Consultation.ConsultationID 
            WHERE Consultation.PatientID = '$patientID'";
    $result = mysqli_query($conn, $sql);

    // Check for errors and fetch DiagnosisLists
    $diagnoses = [];
    while ($row = mysqli_fetch_assoc($result)) {
        printf("Diagnosis ID: %s<br />
             Consultation ID: %s<br />
             Name: %s<br />
             Description: %s<br />
             Severity: %s<br />
             Prescription: %s<br /><br />",
             $row["DiagnosisID"], $row["ConsultationID"], $row["Name"], $row["Description"], $row["Severity"], $row["Prescription"]);
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = new mysqli("localhost", "root", "", "medical");
    $patientID = $_POST['patientID'];
    $password = $_POST['password'];

    // Sanitize user inputs to prevent SQL injection
    $patientID = mysqli_real_escape_string($conn, $patientID);
    $password = mysqli_real_escape_string($conn, $password);

    // Check if patient exists and the password is correct
    $query = "SELECT * FROM Patient WHERE PatientID = '$patientID' AND Password = '$password'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) == 1) {
        // Patient authenticated successfully
        $authenticatedPatientID = $patientID;
    } else {
        // Authentication failed
        $authenticatedPatientID = -1;
    }

    if ($authenticatedPatientID > 0) {
        // getDiagnosis list for the authenticated patient
        $diagnoses = getDiagnosis($authenticatedPatientID);
    } else {
        // Handle authentication failure
        echo "Invalid patient credentials.";
    }
}

// Close the database connection
mysqli_close($conn);
?>
