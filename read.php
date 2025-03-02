<?php
header('Content-Type: application/json'); // Set the content type to JSON

$data = array();
$con = mysqli_connect('localhost', 'root', '', 'dbtapnbite');

// Check if the connection was successful
if (!$con) {
    echo json_encode(array("error" => "Failed to connect to database: " . mysqli_connect_error()));
    exit; // Exit the script if the connection fails
}

// Prepare the SQL query
$sql = "SELECT * FROM tblCustomer";
$result = mysqli_query($con, $sql);

// Check if the query was successful
if ($result) {
    // Check if there are any rows returned
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row; // Append each row to the data array
        }
        echo json_encode($data); // Return the data as JSON
    } else {
        echo json_encode(array("message" => "No records found")); // Return a message if no records found
    }
} else {
    echo json_encode(array("error" => "Query failed: " . mysqli_error($con))); // Return error if query fails
}

// Close the database connection
mysqli_close($con);
?>