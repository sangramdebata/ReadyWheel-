<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// Get payment details from session or database
$payment_status = $_GET['status'] ?? 'failed';
$booking_id = $_GET['booking_id'] ?? '';

if ($booking_id) {
    // Fetch booking and payment details from database
    $stmt = $conn->prepare("SELECT b.*, p.payment_date, v.car_name, v.brand, v.car_type, u.fullname as user_name, u.email
                           FROM bookings b 
                           JOIN payments p ON b.booking_id = p.booking_id
                           JOIN vehicles v ON b.vehicle_id = v.id
                           JOIN users u ON b.user_id = u.id
                           WHERE b.booking_id = ?");
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $booking = $result->fetch_assoc();
}

// Display success/error message if set
$success_message = $_SESSION['success_message'] ?? '';
$error_message = $_SESSION['error_message'] ?? '';
unset($_SESSION['success_message'], $_SESSION['error_message']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Status - ReadyWheel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet"/>
    <link rel="icon" type="image/png" href="../assets/logo-white.png">
    <link rel="shortcut icon" href="favicon.ico">
    <style>
        body {
            background-color: #f6f9fc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        .status-container {
            max-width: 600px;
            width: 100%;
            background: white;
            border-radius: 24px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.06);
            padding: 35px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .logo-container {
            margin-bottom: 30px;
            text-align: center;
        }
        .payment-logo {
            width: 150px;
            height: auto;
            margin: 0 auto 10px;
            animation: scaleIn 0.5s ease-out;
        }
        .status-icon {
            width: 120px;
            height: 120px;
            margin: 0 auto 20px;
            border-radius: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: white;
            animation: scaleIn 0.5s ease-out;
        }
        .success .status-icon {
            background: linear-gradient(135deg, #00c853, #69f0ae);
            box-shadow: 0 8px 25px rgba(0, 200, 83, 0.25);
        }
        .failed .status-icon {
            background: linear-gradient(135deg, #ff6b6b, #ff8e8e);
            box-shadow: 0 8px 25px rgba(255, 107, 107, 0.25);
        }
        .status-message {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
            animation: fadeInUp 0.5s ease-out 0.2s both;
            color: #2c3e50;
        }
        .status-details {
            color: #697386;
            margin-bottom: 30px;
            animation: fadeInUp 0.5s ease-out 0.4s both;
        }
        .receipt {
            border-radius: 16px;
            padding: 25px;
            margin: 30px 0;
            text-align: left;
            border: 1px solid #eaecef;
            animation: fadeInUp 0.5s ease-out 0.6s both;
        }
        .receipt-header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #eaecef;
        }
        .receipt-header h2 {
            color: #2c3e50;
            margin-bottom: 5px;
        }
        .receipt-header p {
            color: #697386;
            margin: 0;
        }
        .receipt-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eaecef;
        }
        .receipt-row:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
            background: #e8f5e9;
            padding: 15px;
            border-radius: 8px;
        }
        .receipt-label {
            color: #697386;
            font-size: 14px;
            font-weight: 500;
        }
        .receipt-value {
            color: #1a1f36;
            font-weight: 600;
            text-align: right;
        }
        .confetti {
            position: fixed;
            width: 10px;
            height: 10px;
            background-color: #4158d0;
            position: absolute;
            animation: fall linear forwards;
        }
        @keyframes fall {
            to {
                transform: translateY(100vh);
            }
        }
        @keyframes scaleIn {
            from { transform: scale(0); }
            to { transform: scale(1); }
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="status-container <?php echo $payment_status; ?>">
        <?php if ($payment_status === 'completed'): ?>
        <div class="logo-container">
            <img src="../assets/payment-done.png" alt="Payment Success" class="payment-logo">
        </div>
        <?php endif; ?>
        
        <div class="status-icon">
            <i class="ri-<?php echo $payment_status === 'completed' ? 'checkbox-circle-fill' : 'close-circle-fill'; ?>"></i>
        </div>
        
        <h1 class="status-message">
            <?php echo $payment_status === 'completed' ? 'Payment Successful!' : 'Payment Failed'; ?>
        </h1>
        
        <p class="status-details">
            <?php if ($payment_status === 'completed'): ?>
                Payment processed successfully! Your booking has been confirmed.
            <?php else: ?>
                <?php echo $error_message ?: 'Something went wrong with your payment. Please try again or contact support.'; ?>
            <?php endif; ?>
        </p>

        <?php if ($payment_status === 'completed' && isset($booking)): ?>
        <div class="receipt" id="receipt">
            <div class="receipt-header">
                <h2>Payment Receipt</h2>
                <p>Receipt #<?php echo $booking['receipt_number']; ?></p>
                <p style="margin-top: 5px; font-size: 12px;">Date: <?php echo date('F j, Y, g:i a'); ?></p>
            </div>

            <div class="receipt-row">
                <span class="receipt-label">Transaction ID</span>
                <span class="receipt-value"><?php echo $booking['transaction_id']; ?></span>
            </div>

            <div class="receipt-row">
                <span class="receipt-label">Reference Number</span>
                <span class="receipt-value"><?php echo $booking['reference_number']; ?></span>
            </div>

            <div class="receipt-row">
                <span class="receipt-label">Payment Date</span>
                <span class="receipt-value"><?php echo date('F j, Y, g:i a', strtotime($booking['payment_date'])); ?></span>
            </div>

            <div class="receipt-row">
                <span class="receipt-label">Customer Name</span>
                <span class="receipt-value"><?php echo htmlspecialchars($booking['user_name']); ?></span>
            </div>

            <div class="receipt-row">
                <span class="receipt-label">Vehicle Details</span>
                <span class="receipt-value"><?php echo htmlspecialchars($booking['car_name'] . ' (' . $booking['brand'] . ')'); ?></span>
            </div>

            <div class="receipt-row">
                <span class="receipt-label">Vehicle Type</span>
                <span class="receipt-value"><?php echo htmlspecialchars($booking['car_type']); ?></span>
            </div>

            <div class="receipt-row">
                <span class="receipt-label">Pickup Location</span>
                <span class="receipt-value"><?php echo htmlspecialchars($booking['pickup_location']); ?></span>
            </div>

            <div class="receipt-row">
                <span class="receipt-label">Pickup Date</span>
                <span class="receipt-value"><?php echo date('F j, Y', strtotime($booking['start_date'])); ?></span>
            </div>

            <div class="receipt-row">
                <span class="receipt-label">Drop-off Date</span>
                <span class="receipt-value"><?php echo date('F j, Y', strtotime($booking['end_date'])); ?></span>
            </div>

            <div class="receipt-row">
                <span class="receipt-label">Payment Method</span>
                <span class="receipt-value"><?php echo ucfirst($booking['payment_method']); ?></span>
            </div>

            <div class="receipt-row">
                <span class="receipt-label">Payment Gateway</span>
                <span class="receipt-value"><?php echo ucfirst($booking['payment_gateway']); ?></span>
            </div>

            <div class="receipt-row">
                <span class="receipt-label">Amount Paid</span>
                <span class="receipt-value">₹<?php echo number_format($booking['total_amount'], 2); ?></span>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <script>
        // Create confetti effect for successful payment
        function createConfetti() {
            const colors = ['#00c853', '#69f0ae', '#ffdd40', '#ff6b6b'];
            for (let i = 0; i < 50; i++) {
                const confetti = document.createElement('div');
                confetti.className = 'confetti';
                confetti.style.left = Math.random() * 100 + 'vw';
                confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.animationDelay = Math.random() * 3 + 's';
                document.body.appendChild(confetti);
                
                // Remove confetti after animation
                setTimeout(() => confetti.remove(), 2000);
            }
        }

        // Show confetti for successful payment
        <?php if ($payment_status === 'completed'): ?>
        window.onload = createConfetti;
        <?php endif; ?>
    </script>
</body>
</html> 