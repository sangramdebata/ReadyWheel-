<?php
session_start();
require_once '../config.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// Get filter parameters
$availability = isset($_GET['availability']) ? $_GET['availability'] : '';
$transmission = isset($_GET['transmission']) ? $_GET['transmission'] : '';
$fuel_type = isset($_GET['fuel_type']) ? $_GET['fuel_type'] : '';
$price_range = isset($_GET['price_range']) ? $_GET['price_range'] : '';

// Build the base SQL query - Modified to show all cars
$sql = "SELECT v.*, u.fullname as owner_name, u.mobile as owner_mobile, u.email as owner_email,
        (SELECT photo_path FROM vehicle_photos WHERE vehicle_id = v.id LIMIT 1) as photo_path
        FROM vehicles v 
        JOIN users u ON v.owner_id = u.id";

// Add filter conditions
$params = [];
$types = "";

if ($availability !== '') {
    $sql .= " WHERE v.is_available = ?";
    $params[] = ($availability === 'available' ? 1 : 0);
    $types .= "i";
} else {
    $sql .= " WHERE 1=1"; // Always true condition to start WHERE clause
}

if ($transmission !== '') {
    $sql .= " AND v.transmission = ?";
    $params[] = $transmission;
    $types .= "s";
}

if ($fuel_type !== '') {
    $sql .= " AND v.fuel_type = ?";
    $params[] = $fuel_type;
    $types .= "s";
}

if ($price_range !== '') {
    switch ($price_range) {
        case '0-1000':
            $sql .= " AND v.price_per_day <= 1000";
            break;
        case '1000-2000':
            $sql .= " AND v.price_per_day BETWEEN 1000 AND 2000";
            break;
        case '2000-5000':
            $sql .= " AND v.price_per_day BETWEEN 2000 AND 5000";
            break;
        case '5000+':
            $sql .= " AND v.price_per_day > 5000";
            break;
    }
}

$sql .= " ORDER BY v.created_at DESC";

try {
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    
    $result = $stmt->get_result();
    $cars = $result->fetch_all(MYSQLI_ASSOC);
    
    // Get unique values for filters - Modified to get all values
    $filter_sql = "SELECT DISTINCT transmission, fuel_type FROM vehicles";
    $filter_stmt = $conn->prepare($filter_sql);
    $filter_stmt->execute();
    $filter_result = $filter_stmt->get_result();
    $transmissions = [];
    $fuel_types = [];
    
    while ($row = $filter_result->fetch_assoc()) {
        if (!in_array($row['transmission'], $transmissions)) {
            $transmissions[] = $row['transmission'];
        }
        if (!in_array($row['fuel_type'], $fuel_types)) {
            $fuel_types[] = $row['fuel_type'];
        }
    }
    
} catch (Exception $e) {
    error_log("Error in my_listed_cars.php: " . $e->getMessage());
    $cars = [];
    $transmissions = [];
    $fuel_types = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Listed Cars - ReadyWheel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="../assets/logo-white.png">
    <link rel="shortcut icon" href="favicon.ico">
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
            cursor: pointer;
            background: white;
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
            color: #333;
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
            z-index: 1;
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
        a.text-decoration-none {
            display: block;
        }
        a.text-decoration-none:hover {
            text-decoration: none;
        }
        .filter-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        .filter-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            align-items: end;
        }
        .filter-group {
            margin-bottom: 0;
        }
        .filter-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        .filter-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .owner-info {
            font-size: 0.85rem;
            color: #666;
            margin-top: 5px;
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
                        <a href="my_listed_cars.php">All Listed Cars</a>
                        <a href="#">Settings</a>
                        <a href="../logout.php" id="logout-btn">Logout</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main class="container py-5">
        <h1 class="mb-4">All Listed Cars</h1>
        
        <!-- Filter Section -->
        <div class="filter-section">
            <form action="" method="GET" class="filter-form">
                <div class="filter-group">
                    <label for="availability">Availability</label>
                    <select name="availability" id="availability" class="form-select">
                        <option value="">All</option>
                        <option value="available" <?php echo $availability === 'available' ? 'selected' : ''; ?>>Available</option>
                        <option value="unavailable" <?php echo $availability === 'unavailable' ? 'selected' : ''; ?>>Unavailable</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="transmission">Transmission</label>
                    <select name="transmission" id="transmission" class="form-select">
                        <option value="">All</option>
                        <?php foreach ($transmissions as $t): ?>
                            <option value="<?php echo htmlspecialchars($t); ?>" <?php echo $transmission === $t ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($t); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="fuel_type">Fuel Type</label>
                    <select name="fuel_type" id="fuel_type" class="form-select">
                        <option value="">All</option>
                        <?php foreach ($fuel_types as $f): ?>
                            <option value="<?php echo htmlspecialchars($f); ?>" <?php echo $fuel_type === $f ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($f); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="price_range">Price Range (₹ per day)</label>
                    <select name="price_range" id="price_range" class="form-select">
                        <option value="">All</option>
                        <option value="0-1000" <?php echo $price_range === '0-1000' ? 'selected' : ''; ?>>₹0 - ₹1,000</option>
                        <option value="1000-2000" <?php echo $price_range === '1000-2000' ? 'selected' : ''; ?>>₹1,000 - ₹2,000</option>
                        <option value="2000-5000" <?php echo $price_range === '2000-5000' ? 'selected' : ''; ?>>₹2,000 - ₹5,000</option>
                        <option value="5000+" <?php echo $price_range === '5000+' ? 'selected' : ''; ?>>₹5,000+</option>
                    </select>
                </div>
                
                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                    <a href="my_listed_cars.php" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
        
        <!-- Cars Grid -->
        <div class="row">
            <?php if (empty($cars)): ?>
                <div class="col-12">
                    <div class="alert alert-info">
                        No cars found matching your criteria.
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($cars as $car): ?>
                    <div class="col-md-6 col-lg-4">
                        <a href="../rent/car-details.php?id=<?php echo $car['id']; ?>" class="text-decoration-none">
                            <div class="car-card">
                                <?php if ($car['is_available']): ?>
                                    <span class="status-badge status-available">Available</span>
                                <?php else: ?>
                                    <span class="status-badge status-unavailable">Unavailable</span>
                                <?php endif; ?>
                                
                                <?php if (!empty($car['photo_path'])): ?>
                                    <img src="<?php echo htmlspecialchars($car['photo_path']); ?>" alt="<?php echo htmlspecialchars($car['car_name']); ?>" class="car-image">
                                <?php else: ?>
                                    <div class="no-image">
                                        <i class="fas fa-car fa-3x"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="car-details">
                                    <h3 class="car-title"><?php echo htmlspecialchars($car['car_name']); ?></h3>
                                    <div class="car-specs">
                                        <p><strong>Brand:</strong> <?php echo htmlspecialchars($car['brand']); ?></p>
                                        <p><strong>Year:</strong> <?php echo htmlspecialchars($car['year']); ?></p>
                                        <p><strong>Type:</strong> <?php echo htmlspecialchars($car['car_type']); ?></p>
                                        <p><strong>Location:</strong> <?php echo htmlspecialchars($car['location']); ?></p>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <div class="car-price">₹<?php echo number_format($car['price_per_day'], 2); ?> / day</div>
                                        <span class="btn btn-sm btn-outline-primary">View Details</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
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
            <div class="footer__col">
                <h4>Our Services</h4>
                <ul class="footer__links">
                    <li><a href="../index.php">Home</a></li>
                    <li><a href="../About/about.php">About</a></li>
                    <li><a href="../rent/rent.php">Rental Deals</a></li>
                    <li><a href="../why-choose-us/choose.php">Why Choose Us</a></li>
                    <li><a href="#">Testimonials</a></li>
                </ul>
            </div>
            <div class="footer__col">
                <h4>Vehicles Brand</h4>
                <ul class="footer__links">
                    <li><a href="#">Tata Cars</a></li>
                    <li><a href="#">Mahindra cars</a></li>
                    <li><a href="#">Tata Cars</a></li>
                    <li><a href="#">Hero Bikes</a></li>
                    <li><a href="#">Honda Scooters</a></li>
                </ul>
            </div>
            <div class="footer__col">
                <h4>Contact</h4>
                <ul class="footer__links">
                    <li>
                        <a href="#">
                            <span><i class="ri-phone-fill"></i></span> +91 9998887775
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <span><i class="ri-map-pin-fill"></i></span> Angul, Odisha, India
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <span><i class="ri-mail-fill"></i></span> info@readywheel.com
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../app.js"></script>
</body>
</html> 