<?php
session_start();
require_once '../config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// Check if required data is provided
if (!isset($_GET['car_id']) || !isset($_GET['pickup_date']) || !isset($_GET['dropoff_date'])) {
    header('Location: rent.php');
    exit();
}

$car_id = $_GET['car_id'];
$pickup_date = $_GET['pickup_date'];
$dropoff_date = $_GET['dropoff_date'];
$pickup_location = isset($_GET['pickup_location']) ? $_GET['pickup_location'] : '';

// Fetch car details
$sql = "SELECT v.*, u.fullname as owner_name, u.mobile as owner_mobile, u.email as owner_email 
        FROM vehicles v 
        JOIN users u ON v.owner_id = u.id 
        WHERE v.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $car_id);
$stmt->execute();
$result = $stmt->get_result();
$car = $result->fetch_assoc();

if (!$car) {
    header('Location: rent.php');
    exit();
}

// Calculate number of days and total amount
$start = new DateTime($pickup_date);
$end = new DateTime($dropoff_date);
$interval = $start->diff($end);
$days = $interval->days + 1; // Include both start and end date
$total_amount = $days * $car['price_per_day'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Summary - ReadyWheel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet"/>
    <link rel="icon" type="image/png" href="../assets/logo-white.png">
    <link rel="shortcut icon" href="favicon.ico">
    <style>
        body {
                background: linear-gradient(
                to bottom,
                rgba(67, 51, 237, 0.4),
                rgba(165, 150, 247, 0.1)
            );
            min-height: 100vh;
            padding: 40px 20px;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        .order-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 24px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.06);
            padding: 35px;
            backdrop-filter: blur(10px);
        }
        .section-title {
            color: #1a1f36;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #eaecef;
        }
        .booking-details, .price-details {
            background: #f8f9ff;
            border-radius: 16px;
            padding: 25px;
            margin-bottom: 30px;
            border: 1px solid #eaecef;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .booking-details:hover, .price-details:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 20px rgba(65, 88, 208, 0.15);
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eaecef;
        }
        .detail-row:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }
        .detail-label {
            color: #697386;
            font-size: 14px;
            font-weight: 500;
        }
        .detail-value {
            color: #1a1f36;
            font-weight: 600;
            text-align: right;
        }
        .total-amount {
            background: linear-gradient(135deg, #8a79f0, #2e2a40);
            color: white;
            padding: 25px;
            border-radius: 16px;
            margin: 30px 0;
            position: relative;
            overflow: hidden;
        }
        .total-amount::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 100%);
            z-index: 1;
        }
        .total-amount .detail-label {
            color: rgba(255, 255, 255, 0.9);
            font-size: 16px;
            position: relative;
            z-index: 2;
        }
        .total-amount .detail-value {
            color: white;
            font-size: 24px;
            font-weight: 700;
            position: relative;
            z-index: 2;
        }
        .btn-pay {
            background: linear-gradient(135deg, #8a79f0, #2e2a40);
            border: none;
            padding: 16px 30px;
            font-size: 16px;
            font-weight: 600;
            width: 100%;
            border-radius: 12px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            color: white;
            position: relative;
            overflow: hidden;
        }
        .btn-pay::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 100%);
            z-index: 1;
        }
        .btn-pay:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(65, 88, 208, 0.25);
        }
        .btn-pay:active {
            transform: translateY(0);
        }
        .contact-info {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #697386;
            font-size: 14px;
            margin-top: 5px;
        }
        .contact-info i {
            color: #4158d0;
            font-size: 16px;
        }
        @media (max-width: 768px) {
            .order-container {
                padding: 25px;
            }
            .detail-row {
                flex-direction: column;
                gap: 5px;
            }
            .detail-value {
                text-align: left;
            }
        }
    </style>
</head>
<body>
    <div class="order-container">
        <h2 class="section-title">Order Summary</h2>
        
        <div class="booking-details">
            <h3 class="section-title">Booking Details</h3>
            <div class="detail-row">
                <span class="detail-label">Pickup Location</span>
                <span class="detail-value"><?php echo $pickup_location; ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Pickup Date</span>
                <span class="detail-value"><?php echo $pickup_date; ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Drop-off Date</span>
                <span class="detail-value"><?php echo $dropoff_date; ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Duration</span>
                <span class="detail-value"><?php echo $days; ?> days</span>
            </div>
        </div>

        <div class="price-details">
            <h3 class="section-title">Price Details</h3>
            <div class="detail-row">
                <span class="detail-label">Price per Day</span>
                <span class="detail-value">₹<?php echo number_format($car['price_per_day'], 2); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Number of Days</span>
                <span class="detail-value"><?php echo $days; ?> days</span>
            </div>
        </div>

        <div class="total-amount">
            <div class="detail-row">
                <span class="detail-label">Total Amount</span>
                <span class="detail-value">₹<?php echo number_format($total_amount, 2); ?></span>
            </div>
        </div>

        <form action="payment.php" method="POST">
            <input type="hidden" name="car_id" value="<?php echo $car_id; ?>">
            <input type="hidden" name="pickup_date" value="<?php echo $pickup_date; ?>">
            <input type="hidden" name="dropoff_date" value="<?php echo $dropoff_date; ?>">
            <input type="hidden" name="pickup_location" value="<?php echo $pickup_location; ?>">
            <input type="hidden" name="total_amount" value="<?php echo $total_amount; ?>">
            <button type="submit" class="btn btn-pay">Proceed to Pay</button>
        </form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 