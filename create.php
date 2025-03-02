<?php
// Check if all required POST parameters are set
if (!empty($_POST['email']) && !empty($_POST['customerID']) && !empty($_POST['password']) && !empty($_POST['fullName']) && !empty($_POST['schoolID']) && !empty($_POST['pelletBalance'])) {

    // Retrieve the parameters from the POST request
    $email = $_POST['email'];
    $customerID = $_POST['customerID'];
    $password = $_POST['password'];
    $fullName = $_POST['fullName'];
    $schoolID = $_POST['schoolID'];
    $pelletBalance = $_POST['pelletBalance'];

    // Create a connection to the database
    $con = mysqli_connect('localhost', 'root', '', 'dbtapnbite');

    // Check if the connection was successful
    if ($con) {

        // Hash the password before storing it
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL statement
        $stmt = $con->prepare("INSERT INTO tblCustomer (nuEmail, customerID, password, fullName, nuID, balance) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $email, $customerID, $hashedPassword, $fullName, $schoolID, $pelletBalance);

        // Execute the statement
        if ($stmt->execute()) {
            echo "New record created successfully";
        } else {
            echo "Failed to insert data: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Failed to connect to database: " . mysqli_connect_error();
    }

    // Close the database connection
    $con->close();
} else {
    echo "All fields are required.";
}
?>