<?php
session_start();
require_once '../config.php';

// Check if car ID is provided
if (!isset($_GET['id'])) {
    header('Location: rent.php');
    exit();
}

$car_id = $_GET['id'];

// Fetch car details
$sql = "SELECT v.*, u.fullname as owner_name, u.mobile as owner_mobile, u.email as owner_email, 
        v.transmission, v.location, v.fuel_type, v.seating_capacity, v.year, v.price_per_day, v.is_available
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

// Fetch car photos
$sql = "SELECT photo_path FROM vehicle_photos WHERE vehicle_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $car_id);
$stmt->execute();
$photos_result = $stmt->get_result();
$photos = $photos_result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($car['car_name']); ?> - ReadyWheel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="../assets/logo-white.png">
    <link rel="shortcut icon" href="favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="../style.css">
    <style>
        .car-gallery {
            position: relative;
            height: 400px;
            overflow: hidden;
            border-radius: 10px;
        }
        .swiper {
            width: 100%;
            height: 100%;
        }
        .swiper-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .swiper-button-next,
        .swiper-button-prev {
            color: white;
        }
        .car-specs {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }
        .spec-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .spec-item i {
            margin-right: 10px;
            color: #2ecc71;
        }
        .owner-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }
        .booking-form {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
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
                <li><a href="rent.php">Rent</a></li>
                <li><a href="../Profiles/owner.php">List Your Car</a></li>
            </ul>
            <div class="nav__btns">
                <div class="profile-icon-container" id="profile-container">
                    <img src="../assets/profile-placeholder.jpg" alt="Profile" class="profile-icon" id="profile-icon">
                    <div class="profile-dropdown">
                        <a href="../Profiles/profile.php">My Profile</a>
                        <a href="../Profiles/my_listed_cars.php">My Listed Cars</a>
                        <a href="#">Settings</a>
                        <a href="../logout.php" id="logout-btn">Logout</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main class="container py-5">
        <div class="row">
            <div class="col-lg-8">
                <h1 class="mb-4"><?php echo htmlspecialchars($car['car_name']); ?></h1>
                <div class="car-gallery">
                    <div class="swiper">
                        <div class="swiper-wrapper">
                            <?php foreach ($photos as $photo): ?>
                                <div class="swiper-slide">
                                    <img src="<?php echo htmlspecialchars($photo['photo_path']); ?>" 
                                         alt="<?php echo htmlspecialchars($car['car_name']); ?>">
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                </div>

                <div class="car-specs">
                    <h3 class="mb-4">Car Specifications</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="spec-item">
                                <i class="ri-gas-station-line"></i>
                                <span>Fuel Type: <?php echo htmlspecialchars($car['fuel_type']); ?></span>
                            </div>
                            <div class="spec-item">
                                <i class="ri-user-line"></i>
                                <span>Seating Capacity: <?php echo htmlspecialchars($car['seating_capacity']); ?> seats</span>
                            </div>
                            <div class="spec-item">
                                <i class="ri-calendar-line"></i>
                                <span>Year: <?php echo htmlspecialchars($car['year']); ?></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="spec-item">
                                <i class="ri-settings-3-line"></i>
                                <span>Transmission: <?php echo htmlspecialchars($car['transmission']); ?></span>
                            </div>
                            <div class="spec-item">
                                <i class="ri-map-pin-line"></i>
                                <span>Location: <?php echo htmlspecialchars($car['location']); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="owner-info">
                    <h3 class="mb-4">Owner Information</h3>
                    <div class="spec-item">
                        <i class="ri-user-line"></i>
                        <span>Name: <?php echo htmlspecialchars($car['owner_name']); ?></span>
                    </div>
                    <div class="spec-item">
                        <i class="ri-phone-line"></i>
                        <span>Mobile: <?php echo htmlspecialchars($car['owner_mobile']); ?></span>
                    </div>
                    <div class="spec-item">
                        <i class="ri-mail-line"></i>
                        <span>Email: <?php echo htmlspecialchars($car['owner_email']); ?></span>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="booking-form">
                    <h3 class="mb-4">Book This Car</h3>
                    <div class="price mb-4">
                        <h4>₹<?php echo number_format($car['price_per_day']); ?> <small>/ day</small></h4>
                    </div>
                    <form action="order-summary.php" method="GET" id="bookingForm">
                        <input type="hidden" name="car_id" value="<?php echo $car_id; ?>">
                        <div class="mb-3">
                            <label for="pickupLocation" class="form-label">Pickup Location</label>
                            <input type="text" class="form-control" id="pickupLocation" name="pickup_location" required>
                        </div>
                        <div class="mb-3">
                            <label for="pickupDate" class="form-label">Pickup Date</label>
                            <input type="date" class="form-control" id="pickupDate" name="pickup_date" required 
                                   min="<?php echo date('Y-m-d'); ?>" 
                                   value="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="dropoffDate" class="form-label">Drop-off Date</label>
                            <input type="date" class="form-control" id="dropoffDate" name="dropoff_date" required 
                                   min="<?php echo date('Y-m-d'); ?>" 
                                   value="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                        </div>
                        <div class="cost-summary mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Price per day:</span>
                                <span>₹<?php echo number_format($car['price_per_day']); ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Number of days:</span>
                                <span id="numberOfDays">0</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <strong>Total Price:</strong>
                                <strong id="totalPrice">₹0</strong>
                            </div>
                        </div>
                        <?php if ($car['is_available']): ?>
                            <button type="submit" class="btn btn-primary w-100">Book Now</button>
                        <?php else: ?>
                            <button type="button" class="btn btn-secondary w-100" disabled>Currently Unavailable</button>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
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
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="../app.js"></script>
    <script>
        // Initialize Swiper with autoplay
        const swiper = new Swiper('.swiper', {
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            loop: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
        });

        // Calculate cost function
        function calculateCost() {
            const pickupDate = new Date(document.getElementById('pickupDate').value);
            const dropoffDate = new Date(document.getElementById('dropoffDate').value);
            const pricePerDay = <?php echo $car['price_per_day']; ?>;
            
            // Calculate number of days
            const diffTime = Math.abs(dropoffDate - pickupDate);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
            
            // Update display
            document.getElementById('numberOfDays').textContent = diffDays;
            document.getElementById('totalPrice').textContent = `₹${(diffDays * pricePerDay).toLocaleString()}`;
        }

        // Add event listeners for real-time cost calculation
        document.getElementById('pickupDate').addEventListener('change', calculateCost);
        document.getElementById('dropoffDate').addEventListener('change', calculateCost);

        // Calculate cost on page load
        document.addEventListener('DOMContentLoaded', calculateCost);
    </script>
</body>
</html> 