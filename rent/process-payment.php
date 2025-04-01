<?php
session_start();
require_once '../config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// Get payment details from POST
$car_id = $_POST['car_id'] ?? '';
$pickup_date = $_POST['pickup_date'] ?? '';
$dropoff_date = $_POST['dropoff_date'] ?? '';
$pickup_location = $_POST['pickup_location'] ?? '';
$total_amount = $_POST['total_amount'] ?? '';
$payment_method = $_POST['payment_method'] ?? '';
$payment_gateway = $_POST['payment_gateway'] ?? '';

if (!$car_id || !$pickup_date || !$dropoff_date || !$total_amount) {
    header('Location: rent.php');
    exit();
}

try {
    // Start transaction
    $conn->begin_transaction();

    // Generate unique reference number and receipt number
    $reference_number = 'REF' . time() . rand(1000, 9999);
    $receipt_number = 'RW' . date('Ymd') . rand(1000, 9999);
    $transaction_id = 'TXN' . time() . rand(1000, 9999);

    // Create booking record
    $stmt = $conn->prepare("INSERT INTO bookings (
        user_id, vehicle_id, start_date, end_date, pickup_location, 
        total_amount, payment_method, payment_gateway, payment_status,
        transaction_id, reference_number, receipt_number, booking_status
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'completed', ?, ?, ?, 'confirmed')");
    
    $stmt->bind_param("iisssdsssss", 
        $_SESSION['user_id'], 
        $car_id, 
        $pickup_date, 
        $dropoff_date, 
        $pickup_location,
        $total_amount,
        $payment_method,
        $payment_gateway,
        $transaction_id,
        $reference_number,
        $receipt_number
    );
    $stmt->execute();
    $booking_id = $conn->insert_id;

    // Create payment record
    $stmt = $conn->prepare("INSERT INTO payments (
        booking_id, user_id, vehicle_id, amount, transaction_id,
        reference_number, payment_method, payment_gateway,
        payment_status, payment_date, receipt_number
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'completed', NOW(), ?)");
    
    $stmt->bind_param("iiidsssss", 
        $booking_id,
        $_SESSION['user_id'],
        $car_id,
        $total_amount,
        $transaction_id,
        $reference_number,
        $payment_method,
        $payment_gateway,
        $receipt_number
    );
    $stmt->execute();

    // Commit transaction
    $conn->commit();

    // Set success message in session
    $_SESSION['success_message'] = "Payment processed successfully!";
    
    // Redirect to success page with booking details
    header("Location: payment-status.php?status=completed&booking_id=" . $booking_id);
    exit();

} catch (Exception $e) {
    // If any error occurred, rollback transaction
    $conn->rollback();
    $_SESSION['error_message'] = "Payment processing failed: " . $e->getMessage();
    header("Location: payment-status.php?status=failed");
    exit();
}

$conn->close();
?> 