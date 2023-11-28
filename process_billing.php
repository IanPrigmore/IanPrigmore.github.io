<?php

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
        // Patient authenticated successfully, proceed to process billing information

        // Sanitize billing inputs
        $paymentOption = mysqli_real_escape_string($conn, $_POST['payment_option']);
        $paymentAmount = mysqli_real_escape_string($conn, $_POST['payment_amount']);
        $dateIssued = mysqli_real_escape_string($conn, $_POST['date_issued']);

        // Insert billing information into the database
        $num = rand(0,999999);
        $insertQuery = "INSERT INTO Billing (BillingID, PatientID, Payment_option, Payment_amount, Date_issued) 
                        VALUES ('$num', '$authenticatedPatientID', '$paymentOption', '$paymentAmount', '$dateIssued')";
        
        $insertResult = mysqli_query($conn, $insertQuery);

        if ($insertResult) {
            // Billing information successfully inserted
            echo "Billing information successfully recorded.";
            printf(" Billing ID: %s", $num);
        } else {
            // Handle insertion failure
            echo "Error recording billing information: " . mysqli_error($conn);
        }
    } else {
        // Handle authentication failure
        echo "Invalid patient credentials.";
    }
}

// Close the database connection
mysqli_close($conn);
?>
