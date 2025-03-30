<?php
session_start();
require_once '../config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch user's listed cars with the first photo for each car
$user_id = $_SESSION['user_id'];
$sql = "SELECT v.*, 
        (SELECT photo_path FROM vehicle_photos WHERE vehicle_id = v.id LIMIT 1) as photo_path
        FROM vehicles v 
        WHERE v.owner_id = ? 
        ORDER BY v.created_at DESC";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $user_id);
if (!$stmt->execute()) {
    die("Execute failed: " . $stmt->error);
}

$result = $stmt->get_result();
$cars = $result->fetch_all(MYSQLI_ASSOC);

// Debug information
if (empty($cars)) {
    error_log("No cars found for user ID: " . $user_id);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Listed Cars - ReadyWheel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet"/>
    <link rel="stylesheet" href="../style.css">
    <style>
        .car-card {
            border: none;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            margin-bottom: 20px;
        }
        .car-card:hover {
            transform: translateY(-5px);
        }
        .car-image {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }
        .car-details {
            padding: 1rem;
        }
        .car-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .car-specs {
            color: #666;
            font-size: 0.9rem;
        }
        .car-price {
            color: #2ecc71;
            font-weight: 600;
            font-size: 1.1rem;
        }
        .status-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 0.25rem 0.5rem;
            border-radius: 20px;
            font-size: 0.8rem;
        }
        .status-available {
            background-color: #2ecc71;
            color: white;
        }
        .status-unavailable {
            background-color: #e74c3c;
            color: white;
        }
        .no-image {
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 200px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <header>
        <nav class="nav_bar">
            <div class="nav__header">
                <div class="nav__logo">
                    <a href="../index.php" class="logo">
                        <img src="../assets/logo-white.png" alt="logo" class="logo-white" />
                        <span>ReadyWheel</span>
                    </a>
                </div>
                <div class="nav__menu__btn" id="menu-btn">
                    <i class="ri-menu-line"></i>
                </div>
            </div>
            <ul class="nav__links" id="nav-links">
                <li><a href="../index.php">Home</a></li>
                <li><a href="../About/about.php">About</a></li>
                <li><a href="../why-choose-us/choose.php">Why Choose Us</a></li>
                <li><a href="../rent/rent.php">Rent</a></li>
                <li><a href="owner.php">List Your Car</a></li>
            </ul>
            <div class="nav__btns">
                <div class="profile-icon-container" id="profile-container">
                    <img src="../assets/profile-placeholder.jpg" alt="Profile" class="profile-icon" id="profile-icon">
                    <div class="profile-dropdown">
                        <a href="profile.php">My Profile</a>
                        <a href="my_listed_cars.php">My Listed Cars</a>
                        <a href="#">Settings</a>
                        <a href="../logout.php" id="logout-btn">Logout</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>My Listed Cars</h2>
            <a href="owner.php" class="btn btn-primary">
                <i class="ri-add-line"></i> List New Car
            </a>
        </div>

        <?php if (empty($cars)): ?>
            <div class="alert alert-info">
                You haven't listed any cars yet. <a href="owner.php">List your first car</a>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($cars as $car): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="car-card">
                            <div class="position-relative">
                                <?php if (!empty($car['photo_path'])): ?>
                                    <img src="<?php echo htmlspecialchars($car['photo_path']); ?>" 
                                         alt="<?php echo htmlspecialchars($car['car_name']); ?>" 
                                         class="car-image">
                                <?php else: ?>
                                    <div class="no-image">
                                        <i class="ri-car-line" style="font-size: 3rem;"></i>
                                    </div>
                                <?php endif; ?>
                                <span class="status-badge <?php echo $car['is_available'] ? 'status-available' : 'status-unavailable'; ?>">
                                    <?php echo $car['is_available'] ? 'Available' : 'Unavailable'; ?>
                                </span>
                            </div>
                            <div class="car-details">
                                <h3 class="car-title"><?php echo htmlspecialchars($car['car_name']); ?></h3>
                                <div class="car-specs mb-2">
                                    <span class="me-2"><i class="ri-gas-station-line"></i> <?php echo htmlspecialchars($car['fuel_type']); ?></span>
                                    <span class="me-2"><i class="ri-user-line"></i> <?php echo htmlspecialchars($car['seating_capacity']); ?> seats</span>
                                    <span><i class="ri-calendar-line"></i> <?php echo htmlspecialchars($car['year']); ?></span>
                                </div>
                                <div class="car-price mb-2">
                                    ₹<?php echo number_format($car['price_per_day']); ?> / day
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted"><i class="ri-map-pin-line"></i> <?php echo htmlspecialchars($car['location']); ?></span>
                                    <a href="../rent/car-detail.php?id=<?php echo $car['id']; ?>" class="btn btn-outline-primary btn-sm">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <footer class="footer">
        <div class="section__container footer__container">
            <div class="footer__col">
                <div class="footer__logo">
                    <a href="#" class="logo">
                        <img src="../assets/logo-white.png" alt="logo" />
                        <span>ReadyWheel</span>
                    </a>
                </div>
                <p>
                    We're here to provide you with the best vehicles and a seamless
                    rental experience. Stay connected for updates, special offers, and
                    more. Drive with confidence!
                </p>
                <ul class="footer__socials">
                    <li><a href="#"><i class="ri-facebook-fill"></i></a></li>
                    <li><a href="#"><i class="ri-twitter-fill"></i></a></li>
                    <li><a href="#"><i class="ri-linkedin-fill"></i></a></li>
                    <li><a href="#"><i class="ri-instagram-line"></i></a></li>
                    <li><a href="#"><i class="ri-youtube-fill"></i></a></li>
                </ul>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../app.js"></script>
</body>
</html> 