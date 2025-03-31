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

    // Create booking record with payment details
    $stmt = $conn->prepare("INSERT INTO bookings (
        user_id, car_id, pickup_date, dropoff_date, pickup_location, 
        total_amount, payment_method, payment_gateway, payment_status,
        transaction_id, reference_number, receipt_number, status
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', ?, ?, ?, 'pending')");
    
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

    // Simulate payment processing
    // In a real implementation, this would integrate with the actual payment gateway
    $payment_successful = true; // This would be determined by the payment gateway response

    if ($payment_successful) {
        // Update booking status and payment status
        $stmt = $conn->prepare("UPDATE bookings SET 
            status = 'confirmed',
            payment_status = 'completed'
            WHERE booking_id = ?");
        $stmt->bind_param("i", $booking_id);
        $stmt->execute();

        // Commit transaction
        $conn->commit();

        // Redirect to success page
        header("Location: payment-status.php?status=completed&booking_id=" . $booking_id);
    } else {
        // If payment failed, update status
        $stmt = $conn->prepare("UPDATE bookings SET 
            status = 'cancelled',
            payment_status = 'failed'
            WHERE booking_id = ?");
        $stmt->bind_param("i", $booking_id);
        $stmt->execute();

        // Commit transaction
        $conn->commit();

        // Redirect to failure page
        header("Location: payment-status.php?status=failed&booking_id=" . $booking_id);
    }

} catch (Exception $e) {
    // If any error occurred, rollback transaction
    $conn->rollback();
    header("Location: payment-status.php?status=failed");
}

$conn->close();
?> 