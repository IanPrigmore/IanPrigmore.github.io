<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input from the form
    $patientID = $_POST["patientID"];
    $password = $_POST["password"];
    $fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $dob = $_POST["dob"];
    $contact = $_POST["contact"];
    $address = $_POST["address"];

    // Create connection
    $conn = new mysqli("localhost", "root", "", "medical");
    $patientID = mysqli_real_escape_string($conn, $patientID);
    $password = mysqli_real_escape_string($conn, $password);
    $fname = mysqli_real_escape_string($conn, $fname);
    $lname = mysqli_real_escape_string($conn, $lname);
    $dob = mysqli_real_escape_string($conn, $dob);
    $contact = mysqli_real_escape_string($conn, $contact);
    $address = mysqli_real_escape_string($conn, $address);

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
        $sql = "";
        if($fname) {
            $sql = $sql . "UPDATE Patient SET Fname='$fname' WHERE PatientID='$authenticatedPatientID'; ";
        }
        if($lname) {
            $sql = $sql . "UPDATE Patient SET Lname='$lname' WHERE PatientID='$authenticatedPatientID'; ";
        }
        if($dob) {
            $sql = $sql . "UPDATE Patient SET DOB='$dob' WHERE PatientID='$authenticatedPatientID'; ";
        }
        if($contact) {
            $sql = $sql . "UPDATE Patient SET Contact='$contact' WHERE PatientID='$authenticatedPatientID'; ";
        }
        if($address) {
            $sql = $sql . "UPDATE Patient SET Address='$address' WHERE PatientID='$authenticatedPatientID'; ";
        }
        $insertResult = mysqli_multi_query($conn, $sql);
        if($insertResult) {
            printf("Patient profile updated. Patient ID: %s\n",
                $patientID);
            // Print out results
        } else {
            print("An error occurred\n");
        }
    } else {
        // Handle authentication failure
        echo "Invalid patient credentials.";
    }
}
    $conn->close();
?>