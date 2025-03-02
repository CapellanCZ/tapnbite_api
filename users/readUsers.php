<?php

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Create connection
$conn = mysqli_connect('localhost', 'root', '', 'dbtapnbite1');

// Check connection
if ($conn->connect_error) {
    die(json_encode(array('error' => "Connection failed: " . $conn->connect_error)));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['email']; // Ensure this matches the key sent from Android
    $password = $_POST['password'];

    // Check in customers table
    $sql = "SELECT 'customer' as user_type, password FROM tblCustomers WHERE school_email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_type, $hashed_password);
    $stmt->fetch();

    if ($stmt->num_rows > 0) {
        if (password_verify($password, $hashed_password)) {
            echo json_encode(array('user_type' => $user_type));
        } else {
            echo json_encode(array('error' => 'Invalid password.'));
        }
    } else {
        // Check in staff table
        $sql = "SELECT 'canteen staff' as user_type, password FROM tblCanteenStaff WHERE email=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($user_type, $hashed_password);
        $stmt->fetch();

        if ($stmt->num_rows > 0) {
            if (password_verify($password, $hashed_password)) {
                echo json_encode(array('user_type' => $user_type));
            } else {
                echo json_encode(array('error' => 'Invalid password.'));
            }
        } else {
            // Check in admin table
            $sql = "SELECT 'admin' as user_type, password FROM tblAdmin WHERE username=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($user_type, $stored_password);
            $stmt->fetch();

            if ($stmt->num_rows > 0) {
                // Compare the plain text password directly
                if ($password === $stored_password) {
                    echo json_encode(array('user_type' => $user_type));
                } else {
                    echo json_encode(array('error' => 'Invalid password.'));
                }
            } else {
                echo json_encode(array('error' => 'User  not found.'));
            }
        }
    }

    $stmt->close();
} else {
    echo json_encode(array('error' => 'Invalid request method.'));
}

$conn->close();
?>