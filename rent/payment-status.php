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
    // Fetch booking details from database
    $stmt = $conn->prepare("SELECT b.*, c.brand, c.model, c.number_plate, u.name as user_name, u.email
                           FROM bookings b 
                           JOIN cars c ON b.car_id = c.car_id
                           JOIN users u ON b.user_id = u.user_id
                           WHERE b.booking_id = ?");
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $booking = $result->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Status - ReadyWheel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
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
        .status-icon {
            width: 120px;
            height: 120px;
            margin: 0 auto 30px;
            border-radius: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: white;
            animation: scaleIn 0.5s ease-out;
        }
        .success .status-icon {
            background: linear-gradient(135deg, #4158d0, #c850c0);
            box-shadow: 0 8px 25px rgba(65, 88, 208, 0.25);
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
        }
        .status-details {
            color: #697386;
            margin-bottom: 30px;
            animation: fadeInUp 0.5s ease-out 0.4s both;
        }
        .receipt {
            background: #f8f9ff;
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
        .btn-download {
            background: linear-gradient(135deg, #4158d0, #c850c0);
            border: none;
            padding: 16px 30px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 12px;
            color: white;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            animation: fadeInUp 0.5s ease-out 0.8s both;
        }
        .btn-download:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(65, 88, 208, 0.25);
        }
        @keyframes scaleIn {
            from {
                transform: scale(0);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
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
        .confetti {
            position: absolute;
            width: 10px;
            height: 10px;
            background-color: #4158d0;
            animation: confetti 1s ease-out forwards;
        }
        @keyframes confetti {
            0% {
                transform: translateY(0) rotateZ(0deg);
                opacity: 1;
            }
            100% {
                transform: translateY(1000%) rotateZ(720deg);
                opacity: 0;
            }
        }
        @media print {
            body {
                background: white;
            }
            .status-container {
                box-shadow: none;
                padding: 0;
            }
            .btn-download {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="status-container <?php echo $payment_status; ?>">
        <div class="status-icon">
            <i class="ri-<?php echo $payment_status === 'completed' ? 'checkbox-circle-line' : 'close-circle-line'; ?>"></i>
        </div>
        
        <h1 class="status-message">
            <?php echo $payment_status === 'completed' ? 'Payment Successful!' : 'Payment Failed'; ?>
        </h1>
        
        <p class="status-details">
            <?php if ($payment_status === 'completed'): ?>
                Your booking has been confirmed. You can download your receipt below.
            <?php else: ?>
                Something went wrong with your payment. Please try again or contact support.
            <?php endif; ?>
        </p>

        <?php if ($payment_status === 'completed' && isset($booking)): ?>
        <div class="receipt" id="receipt">
            <div class="receipt-header">
                <h2>Payment Receipt</h2>
                <p>Receipt #<?php echo $booking['receipt_number']; ?></p>
            </div>

            <div class="receipt-row">
                <span class="receipt-label">Transaction ID</span>
                <span class="receipt-value"><?php echo $booking['transaction_id']; ?></span>
            </div>

            <div class="receipt-row">
                <span class="receipt-label">Payment Date</span>
                <span class="receipt-value"><?php echo date('F j, Y, g:i a', strtotime($booking['created_at'])); ?></span>
            </div>

            <div class="receipt-row">
                <span class="receipt-label">Car Details</span>
                <span class="receipt-value"><?php echo $booking['brand'] . ' ' . $booking['model']; ?></span>
            </div>

            <div class="receipt-row">
                <span class="receipt-label">Pickup Location</span>
                <span class="receipt-value"><?php echo $booking['pickup_location']; ?></span>
            </div>

            <div class="receipt-row">
                <span class="receipt-label">Pickup Date</span>
                <span class="receipt-value"><?php echo date('F j, Y', strtotime($booking['pickup_date'])); ?></span>
            </div>

            <div class="receipt-row">
                <span class="receipt-label">Drop-off Date</span>
                <span class="receipt-value"><?php echo date('F j, Y', strtotime($booking['dropoff_date'])); ?></span>
            </div>

            <div class="receipt-row">
                <span class="receipt-label">Payment Method</span>
                <span class="receipt-value"><?php echo ucfirst($booking['payment_method']); ?></span>
            </div>

            <div class="receipt-row">
                <span class="receipt-label">Amount Paid</span>
                <span class="receipt-value">₹<?php echo number_format($booking['total_amount'], 2); ?></span>
            </div>
        </div>

        <button class="btn btn-download" onclick="downloadReceipt()">
            <i class="ri-download-line"></i> Download Receipt
        </button>
        <?php endif; ?>
    </div>

    <script>
        // Create confetti effect for successful payment
        function createConfetti() {
            const colors = ['#4158d0', '#c850c0', '#ffdd40', '#ff6b6b'];
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

        // Download receipt as PDF
        function downloadReceipt() {
            const receipt = document.getElementById('receipt');
            const opt = {
                margin: 1,
                filename: 'ReadyWheel-Receipt-<?php echo $booking['receipt_number']; ?>.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
            };

            html2pdf().set(opt).from(receipt).save();
        }

        // Show confetti for successful payment
        <?php if ($payment_status === 'completed'): ?>
        window.onload = createConfetti;
        <?php endif; ?>
    </script>
</body>
</html> 