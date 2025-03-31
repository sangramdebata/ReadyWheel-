<?php
session_start();
require_once '../config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// Check if car ID and dates are provided
if (!isset($_GET['car_id']) || !isset($_GET['start_date']) || !isset($_GET['end_date'])) {
    header('Location: rent.php');
    exit();
}

$car_id = $_GET['car_id'];
$start_date = $_GET['start_date'];
$end_date = $_GET['end_date'];

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
$start = new DateTime($start_date);
$end = new DateTime($end_date);
$interval = $start->diff($end);
$days = $interval->days + 1; // Include both start and end date
$total_amount = $days * $car['price_per_day'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Summary - ReadyWheel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet"/>
    <link rel="stylesheet" href="../style.css">
    <style>
        .booking-summary {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
        }
        .summary-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        .car-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }
        .price-details {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
        }
        .total-amount {
            font-size: 24px;
            font-weight: bold;
            color: #2ecc71;
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <main class="booking-summary">
        <h2 class="mb-4">Booking Summary</h2>
        
        <div class="summary-card">
            <h4>Car Details</h4>
            <img src="<?php echo htmlspecialchars($car['photo_path']); ?>" alt="<?php echo htmlspecialchars($car['car_name']); ?>" class="car-image mb-3">
            <h5><?php echo htmlspecialchars($car['car_name']); ?></h5>
            <p class="text-muted"><?php echo htmlspecialchars($car['transmission']); ?> • <?php echo htmlspecialchars($car['fuel_type']); ?> • <?php echo htmlspecialchars($car['seating_capacity']); ?> seats</p>
        </div>

        <div class="summary-card">
            <h4>Booking Details</h4>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Pick-up Date:</strong> <?php echo date('F j, Y', strtotime($start_date)); ?></p>
                    <p><strong>Drop-off Date:</strong> <?php echo date('F j, Y', strtotime($end_date)); ?></p>
                    <p><strong>Duration:</strong> <?php echo $days; ?> days</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Owner:</strong> <?php echo htmlspecialchars($car['owner_name']); ?></p>
                    <p><strong>Contact:</strong> <?php echo htmlspecialchars($car['owner_mobile']); ?></p>
                </div>
            </div>
        </div>

        <div class="summary-card">
            <h4>Price Details</h4>
            <div class="price-details">
                <div class="d-flex justify-content-between mb-2">
                    <span>Price per day:</span>
                    <span>₹<?php echo number_format($car['price_per_day'], 2); ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Number of days:</span>
                    <span><?php echo $days; ?></span>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <span class="total-amount">Total Amount:</span>
                    <span class="total-amount">₹<?php echo number_format($total_amount, 2); ?></span>
                </div>
            </div>
        </div>

        <div class="d-grid gap-2">
            <form action="process-payment.php" method="POST">
                <input type="hidden" name="car_id" value="<?php echo $car_id; ?>">
                <input type="hidden" name="start_date" value="<?php echo $start_date; ?>">
                <input type="hidden" name="end_date" value="<?php echo $end_date; ?>">
                <input type="hidden" name="total_amount" value="<?php echo $total_amount; ?>">
                <button type="submit" class="btn btn-primary btn-lg">Proceed to Payment</button>
            </form>
        </div>
    </main>

    <?php include '../includes/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 