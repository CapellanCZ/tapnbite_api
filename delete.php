<?php
header('Content-Type: application/json'); // Set the content type to JSON

if (!empty($_POST['ID'])) {
    $id = $_POST['ID'];

    // Create a connection to the database
    $con = mysqli_connect('localhost', 'root', '', 'dbtapnbite');

    // Check if the connection was successful
    if (!$con) {
        echo json_encode(array("error" => "Failed to connect to database: " . mysqli_connect_error()));
        exit; // Exit the script if the connection fails
    }

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $con->prepare("DELETE FROM tblCustomer WHERE ID = ?");

    // Bind the parameter to the SQL query
    $stmt->bind_param("i", $id); // "i" means the parameter is an integer

    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode(array("message" => "Deleted successfully"));
    } else {
        echo json_encode(array("error" => "Failed to delete data: " . $stmt->error));
    }

    // Close the statement and the database connection
    $stmt->close();
    mysqli_close($con);
} else {
    echo json_encode(array("error" => "Invalid input: ID is required."));
}
?>