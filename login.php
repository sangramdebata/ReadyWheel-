<?php
session_start();
require_once 'config.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log the request method and POST data
error_log("Request Method: " . $_SERVER["REQUEST_METHOD"]);
error_log("POST Data: " . print_r($_POST, true));

// Set header to return JSON
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Validate required fields
        if (empty($_POST['login-username']) || empty($_POST['login-password'])) {
            throw new Exception('All fields are required');
        }

        // Sanitize input
        $email = mysqli_real_escape_string($conn, $_POST['login-username']);
        $password = $_POST['login-password'];
        
        // Log the sanitized email
        error_log("Attempting login for email: " . $email);
        
        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email format');
        }
        
        // Get user from database
        $sql = "SELECT * FROM users WHERE email = '$email'";
        error_log("SQL Query: " . $sql);
        
        $result = $conn->query($sql);
        
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name'] = $user['fullname'];
                $_SESSION['is_logged_in'] = true;
                
                error_log("Login successful for user: " . $user['email']);
                
                // Return user data along with success message
                echo json_encode([
                    'success' => true, 
                    'message' => 'Login successful',
                    'user' => [
                        'id' => $user['id'],
                        'name' => $user['fullname'],
                        'email' => $user['email']
                    ]
                ]);
            } else {
                error_log("Invalid password for user: " . $email);
                throw new Exception('Invalid password');
            }
        } else {
            error_log("User not found: " . $email);
            throw new Exception('User not found');
        }
    } catch (Exception $e) {
        error_log("Login error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    error_log("Invalid request method: " . $_SERVER["REQUEST_METHOD"]);
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$conn->close();
?> 