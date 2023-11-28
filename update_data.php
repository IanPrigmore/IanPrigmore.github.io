<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input from the form
    $patientID = $_POST["patientID"];
    $old_password = $_POST["old_password"];
    $new_password = $_POST["new_password"];

    // Create connection
    $conn = new mysqli("localhost", "root", "", "medical");
    $patientID = mysqli_real_escape_string($conn, $patientID);
    $old_password = mysqli_real_escape_string($conn, $old_password);
    $new_password = mysqli_real_escape_string($conn, $new_password);
    $sql = "UPDATE Patient
            SET Password = '$new_password'
            WHERE patientID = '$patientID' and Password = '$old_password'";

    $result = $conn->query($sql);
    if($conn->affected_rows > 0) {
        printf("New password: %s\n",
            $new_password);
        // Print out results
    } else {
        print("An error occurred\n");
    }
}
    $conn->close();
?>