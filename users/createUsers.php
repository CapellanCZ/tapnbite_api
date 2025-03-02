<?php

// Create connection
$conn = mysqli_connect('localhost', 'root', '', 'dbtapnbite1');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = $_POST['password'];
    $user_type = $_POST['user_type'];

    // Additional fields for customers
    $full_name = isset($_POST['full_name']) ? $_POST['full_name'] : '';
    $school_id = isset($_POST['school_id']) ? $_POST['school_id'] : '';
    $school_email = isset($_POST['school_email']) ? $_POST['school_email'] : '';

    // Additional fields for staff
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $store_name = isset($_POST['store_name']) ? $_POST['store_name'] : '';
    $canteen_location = isset($_POST['canteen_location']) ? $_POST['canteen_location'] : '';

    // Set initial balance to 0
    $initial_balance = 0;

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    if ($user_type == 'customer') {
        $sql = "INSERT INTO tblCustomers (full_name, school_id, school_email, password, balance) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $full_name, $school_id, $school_email, $hashed_password, $initial_balance);
    } else if ($user_type == 'canteen staff') {
        $sql = "INSERT INTO tblCanteenStaff (email, store_name, canteen_location, password, balance) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $email, $store_name, $canteen_location, $hashed_password, $initial_balance);
    }

    // Execute the statement
    if ($stmt->execute()) {
        echo "Registration successful";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

$conn->close();
?>