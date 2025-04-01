<?php
session_start();
require_once '../config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "Please login to view your bookings.";
    header('Location: ../login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

try {
    // Fetch user's bookings from database with all necessary details
    $stmt = $conn->prepare("SELECT 
        b.booking_id,
        b.start_date,
        b.end_date,
        b.pickup_location,
        b.booking_status,
        b.created_at,
        v.car_name,
        v.brand,
        v.car_type,
        v.price_per_day,
        p.payment_status,
        p.payment_date,
        p.transaction_id,
        p.reference_number,
        p.total_amount,
        p.payment_method
        FROM bookings b 
        JOIN vehicles v ON b.vehicle_id = v.id
        LEFT JOIN payments p ON b.booking_id = p.booking_id
        WHERE b.user_id = ?
        ORDER BY b.created_at DESC");
        
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $bookings = $result->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
    $_SESSION['error_message'] = "Error fetching bookings: " . $e->getMessage();
    $bookings = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - ReadyWheel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet"/>
    <link rel="icon" type="image/png" href="../assets/logo-white.png">
    <style>
        body {
            background-color: #f6f9fc;
            min-height: 100vh;
            padding: 20px;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        .page-header {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.04);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .page-title {
            margin: 0;
            color: #2c3e50;
            font-size: 24px;
            font-weight: 600;
        }
        .booking-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.04);
            margin-bottom: 20px;
            overflow: hidden;
            transition: transform 0.2s;
        }
        .booking-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.08);
        }
        .booking-header {
            background: #f8f9fa;
            padding: 15px 20px;
            border-bottom: 1px solid #eaecef;
        }
        .booking-body {
            padding: 20px;
        }
        .booking-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }
        .detail-group {
            margin-bottom: 15px;
        }
        .detail-label {
            color: #697386;
            font-size: 13px;
            margin-bottom: 4px;
        }
        .detail-value {
            color: #1a1f36;
            font-weight: 500;
        }
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
        }
        .status-completed {
            background: #e8f5e9;
            color: #2e7d32;
        }
        .status-pending {
            background: #fff3e0;
            color: #ef6c00;
        }
        .status-cancelled {
            background: #ffebee;
            color: #c62828;
        }
        .back-button {
            text-decoration: none;
            color: #697386;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .back-button:hover {
            color: #1a1f36;
        }
        .status-badge.status-completed {
            background: linear-gradient(135deg, #00c853, #69f0ae);
            color: white;
        }
        .status-badge.status-pending {
            background: linear-gradient(135deg, #ff9800, #ffd54f);
            color: white;
        }
        .status-badge.status-cancelled {
            background: linear-gradient(135deg, #f44336, #ff8a80);
            color: white;
        }
        .view-details-btn {
            background: linear-gradient(135deg, #4158d0, #c850c0);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            transition: transform 0.2s;
            text-decoration: none;
            display: inline-block;
            margin-top: 15px;
        }
        .view-details-btn:hover {
            transform: translateY(-1px);
            color: white;
            opacity: 0.95;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <a href="../index.php" class="back-button">
                <i class="ri-arrow-left-line"></i>
                Back to Home
            </a>
            <h1 class="page-title">My Bookings</h1>
            <div></div>
        </div>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger">
                <?php 
                    echo $_SESSION['error_message'];
                    unset($_SESSION['error_message']);
                ?>
            </div>
        <?php endif; ?>

        <?php if (empty($bookings)): ?>
            <div class="text-center py-5">
                <img src="../assets/no-bookings.png" alt="No Bookings" style="width: 200px; margin-bottom: 20px;">
                <h3>No Bookings Found</h3>
                <p class="text-muted">You haven't made any bookings yet.</p>
                <a href="rent.php" class="btn btn-primary">Browse Vehicles</a>
            </div>
        <?php else: ?>
            <?php foreach ($bookings as $booking): ?>
                <div class="booking-card">
                    <div class="booking-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><?php echo htmlspecialchars($booking['car_name'] . ' (' . $booking['brand'] . ')'); ?></h5>
                            <span class="status-badge status-<?php echo strtolower($booking['payment_status'] ?? 'pending'); ?>">
                                <?php echo ucfirst($booking['payment_status'] ?? 'Pending'); ?>
                            </span>
                        </div>
                    </div>
                    <div class="booking-body">
                        <div class="booking-details">
                            <div class="detail-group">
                                <div class="detail-label">Booking ID</div>
                                <div class="detail-value">#<?php echo $booking['booking_id']; ?></div>
                            </div>
                            <div class="detail-group">
                                <div class="detail-label">Vehicle Type</div>
                                <div class="detail-value"><?php echo htmlspecialchars($booking['car_type']); ?></div>
                            </div>
                            <div class="detail-group">
                                <div class="detail-label">Pickup Date</div>
                                <div class="detail-value"><?php echo date('F j, Y', strtotime($booking['start_date'])); ?></div>
                            </div>
                            <div class="detail-group">
                                <div class="detail-label">Drop-off Date</div>
                                <div class="detail-value"><?php echo date('F j, Y', strtotime($booking['end_date'])); ?></div>
                            </div>
                            <div class="detail-group">
                                <div class="detail-label">Pickup Location</div>
                                <div class="detail-value"><?php echo htmlspecialchars($booking['pickup_location']); ?></div>
                            </div>
                            <div class="detail-group">
                                <div class="detail-label">Amount</div>
                                <div class="detail-value">₹<?php echo number_format($booking['total_amount'] ?? 0, 2); ?></div>
                            </div>
                            <?php if (isset($booking['payment_status']) && $booking['payment_status'] === 'completed'): ?>
                                <div class="detail-group">
                                    <div class="detail-label">Transaction ID</div>
                                    <div class="detail-value"><?php echo htmlspecialchars($booking['transaction_id']); ?></div>
                                </div>
                                <div class="detail-group">
                                    <div class="detail-label">Reference Number</div>
                                    <div class="detail-value"><?php echo htmlspecialchars($booking['reference_number']); ?></div>
                                </div>
                                <div class="detail-group">
                                    <div class="detail-label">Payment Date</div>
                                    <div class="detail-value">
                                        <?php echo date('F j, Y, g:i a', strtotime($booking['payment_date'])); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php if ($booking['payment_status'] === 'completed'): ?>
                            <a href="payment-status.php?status=completed&booking_id=<?php echo $booking['booking_id']; ?>" 
                               class="view-details-btn">
                                <i class="ri-file-list-3-line"></i> View Receipt
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 