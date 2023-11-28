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

    $sql = "INSERT INTO Patient (PatientID, Password, Fname, Lname, DOB, Contact, Address) 
            VALUES ('$patientID', '$password', '$fname', '$lname', '$dob', '$contact', '$address')";

    $insertResult = mysqli_query($conn, $sql);
    if($insertResult) {
        printf("Patient acccount created. Patient ID: %s\n",
            $patientID);
        // Print out results
    } else {
        print("An error occurred\n");
    }
}
    $conn->close();
?>