<?php
session_start();
require_once '../config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// Check if booking ID is provided
if (!isset($_GET['id'])) {
    header('Location: rent.php');
    exit();
}

$booking_id = $_GET['id'];

// Fetch booking details
$sql = "SELECT b.*, v.car_name, v.photo_path, u.fullname as owner_name, u.mobile as owner_mobile 
        FROM bookings b 
        JOIN vehicles v ON b.car_id = v.id 
        JOIN users u ON v.owner_id = u.id 
        WHERE b.id = ? AND b.user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $booking_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();

if (!$booking) {
    header('Location: rent.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmed - ReadyWheel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet"/>
    <link rel="stylesheet" href="../style.css">
    <style>
        .success-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            text-align: center;
        }
        .success-icon {
            font-size: 80px;
            color: #2ecc71;
            margin-bottom: 20px;
        }
        .booking-details {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 20px;
            margin-top: 30px;
        }
        .car-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <main class="success-container">
        <div class="success-icon">
            <i class="ri-checkbox-circle-fill"></i>
        </div>
        
        <h2>Booking Confirmed!</h2>
        <p class="lead">Thank you for choosing ReadyWheel. Your booking has been confirmed.</p>
        
        <div class="booking-details">
            <h4>Booking Details</h4>
            <img src="<?php echo htmlspecialchars($booking['photo_path']); ?>" alt="<?php echo htmlspecialchars($booking['car_name']); ?>" class="car-image">
            
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Car:</strong> <?php echo htmlspecialchars($booking['car_name']); ?></p>
                    <p><strong>Pick-up Date:</strong> <?php echo date('F j, Y', strtotime($booking['start_date'])); ?></p>
                    <p><strong>Drop-off Date:</strong> <?php echo date('F j, Y', strtotime($booking['end_date'])); ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Owner:</strong> <?php echo htmlspecialchars($booking['owner_name']); ?></p>
                    <p><strong>Contact:</strong> <?php echo htmlspecialchars($booking['owner_mobile']); ?></p>
                    <p><strong>Total Amount:</strong> ₹<?php echo number_format($booking['total_amount'], 2); ?></p>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <a href="my-bookings.php" class="btn btn-primary">View My Bookings</a>
            <a href="rent.php" class="btn btn-outline-primary">Book Another Car</a>
        </div>
    </main>

    <?php include '../includes/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 