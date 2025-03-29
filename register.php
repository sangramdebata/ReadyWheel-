<?php
session_start();
require_once 'config.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set header to return JSON
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Validate required fields
        if (empty($_POST['FName']) || empty($_POST['mob']) || empty($_POST['mail']) || empty($_POST['reg-password'])) {
            throw new Exception('All fields are required');
        }

        // Sanitize and validate input
        $fullname = mysqli_real_escape_string($conn, $_POST['FName']);
        $mobile = mysqli_real_escape_string($conn, $_POST['mob']);
        $email = mysqli_real_escape_string($conn, $_POST['mail']);
        
        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email format');
        }
        
        // Hash the password
        $password = password_hash($_POST['reg-password'], PASSWORD_DEFAULT);
        
        // Check if email already exists
        $check_email = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($check_email);
        
        if ($result->num_rows > 0) {
            throw new Exception('Email already exists');
        }
        
        // Insert new user
        $sql = "INSERT INTO users (fullname, mobile, email, password) VALUES ('$fullname', '$mobile', '$email', '$password')";
        
        if ($conn->query($sql) === TRUE) {
            $_SESSION['user_id'] = $conn->insert_id;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_name'] = $fullname;
            $_SESSION['is_logged_in'] = true;
            echo json_encode(['success' => true, 'message' => 'Registration successful']);
        } else {
            throw new Exception('Database error: ' . $conn->error);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$conn->close();
?> 