<?php

function getConsultation($patientID) {
    global $conn;

    // Sanitize user inputs to prevent SQL injection

    // Your SQL query to retrieve ConsultationLists based on patient ID
    $sql = "SELECT * FROM Consultation WHERE PatientID = '$patientID'";
    $result = mysqli_query($conn, $sql);

    // Check for errors and fetch ConsultationLists
    $consultations = [];
    while ($row = mysqli_fetch_assoc($result)) {
        printf("Consultation ID: %s<br />
             Patient ID: %s<br />
             Doctor ID: %s<br />
             Date: %s<br />
             Status: %s<br /><br />",
             $row["ConsultationID"], $row["PatientID"], $row["DoctorID"], $row["Date"], $row["Status"]); }
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
        // getConsultation list for the authenticated patient
        $consultations = getConsultation($authenticatedPatientID);
    } else {
        // Handle authentication failure
        echo "Invalid patient credentials.";
    }
}

// Close the database connection
mysqli_close($conn);
?>
