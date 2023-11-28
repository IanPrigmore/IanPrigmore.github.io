<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input from the form
    $patientID = $_POST["patientID"];
    $password = $_POST["password"];

    // Create connection
    $conn = new mysqli("localhost", "root", "", "medical");

    $sql = "SELECT Fname, Lname, Contact, DOB, Address
            FROM Patient
            WHERE patientID = ? and Password = ?";
    if($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ss", $patientID, $password);
        $stmt->execute();

        $stmt->bind_result($Fname, $Lname, $Contact, $DOB, $Address);
        while($stmt->fetch()) {
            printf("Patient ID: %s<br />
             Name: %s %s<br />
             Date of Birth: %s<br />
             Phone Number: %s<br />
             Address: %s<br />", $patientID, $Fname, $Lname, $DOB, $Contact, $Address);
        }
    }
    $stmt->close();
    $conn->close();

    // $result = $conn->query($sql);


    // if($result) {
    //     // Print out results
    //     while ($row = $result->fetch_assoc()) {
    //         printf("Name: %s %s -- Phone Number: %s\n",
    //             $row["Fname"], $row["Lname"], $row["Contact"]);
    //     }
    //     $result->free();
    // }
}
?>