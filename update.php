<?php
header('Content-Type: application/json'); // Set the content type to JSON

if (!empty($_POST['data']) && !empty($_POST['ID'])) {
    $id = $_POST['ID'];
    $data = $_POST['data'];

    // Create a connection to the database
    $con = mysqli_connect('localhost', 'root', '', 'dbtapnbite');

    // Check if the connection was successful
    if (!$con) {
        echo json_encode(array("error" => "Failed to connect to database: " . mysqli_connect_error()));
        exit; // Exit the script if the connection fails
    }

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $con->prepare("UPDATE tblCustomer SET fullName = ? WHERE ID = ?");

    // Bind the parameters to the SQL query
    $stmt->bind_param("si", $data, $id); // "si" means string and integer

    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode(array("message" => "Record updated successfully"));
    } else {
        echo json_encode(array("error" => "Update failed: " . $stmt->error));
    }

    // Close the statement and the database connection
    $stmt->close();
    mysqli_close($con);
} else {
    echo json_encode(array("error" => "Invalid input: data and ID are required."));
}
?>