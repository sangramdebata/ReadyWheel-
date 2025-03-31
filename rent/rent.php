<?php
// Database connection
$host = 'localhost';
$dbname = 'readywheel_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch all vehicles with their images
    $stmt = $pdo->query("SELECT * FROM listed_vehicles");
    $vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get images for each vehicle
    foreach ($vehicles as &$vehicle) {
        $stmt = $pdo->prepare("SELECT image_path FROM vehicle_images WHERE vehicle_id = ?");
        $stmt->execute([$vehicle['id']]);
        $images = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $vehicle['images'] = $images;
    }

    // Get unique values for filters
    $brands = array_unique(array_column($vehicles, 'brand'));
    $fuelTypes = array_unique(array_column($vehicles, 'fuel_type'));
    $colors = array_unique(array_column($vehicles, 'color'));
    $categories = array_unique(array_column($vehicles, 'category'));
    $models = array_unique(array_column($vehicles, 'model'));

} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReadyWheel - Vehicle Rentals</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
</head>
<body>
    <!-- header -->
     <header>
        <header>
            <!-- Navigation Header -->
            <!-- Navigation Header containing the logo and menu button -->
            <nav class="nav_bar">
              <div class="nav__header">
                <!-- Logo Section: Links to the homepage with different logo images for light/dark mode -->
                <div class="nav__logo">
                  <!-- Logo Header -->
                  <a href="#" class="logo">
                    <img src="../assets/logo-white.png" alt="logo" class="logo-white" />
                    <!-- Text Logo displaying the platform's name -->
                    <span>ReadyWheel</span>
                  </a>
                </div>
                <!-- Menu button for responsive navigation (appears on smaller screens) -->
                <div class="nav__menu__btn" id="menu-btn">
                  <!-- Menu icon (menu bar) for mobile navigation -->
                  <i class="ri-menu-line"></i>
                </div>
              </div>
               <!-- Navigation Links -->
              <ul class="nav__links" id="nav-links">
                <li><a href="../index.php">Home</a></li>
                <li><a href="../About/about.php">About</a></li>
                <li><a href="../why-choose-us/choose.php">Why Choose Us</a></li>
                <li><a href="rent.php">Rent</a></li>
                <li><a href="http://127.0.0.1:5500/login.php?">Register</a></li>
              </ul>
              <!-- Login Button -->
              <div class="nav__btns">
                <button id="login-btn" class="btn">Login</button>
              </div>
            </nav>
      
            <!-- Login Pop up Start-->
      
                <!-- Login Popup -->
                <div class="overlay" id="login-popup">
                  <div class="popup">
                      <span class="close-btn" id="close-login">&times;</span>
                      <h2>Welcome Back</h2>
                      <form method="post" id="log_form">
                          <label for="login-username">Username</label>
                          <input type="text" id="login-username" name="login-username" placeholder="Enter your username" required>
                          
                          <label for="login-password">Password</label>
                          <div class="password-container">
                              <input type="password" id="login-password" name="login-password" placeholder="Enter your password" required>
                              <span class="toggle-password" data-target="login-password">&#128065;</span>
                          </div>
      
                          <button type="submit">Login</button>
                      </form>
                      <div class="switch-link">
                          Don't have an account? <a href="#" id="show-register">Register here</a>
                      </div>
                  </div>
              </div>
      
              <!-- Registration Popup -->
              <div class="overlay" id="register-popup">
                  <div class="popup">
                      <span class="close-btn" id="close-register">&times;</span>
                      <h2>Create Account</h2>
                      <form method="post" id="reg_form">
                          <label for="reg-name">Full Name</label>
                          <input type="text" id="reg-name" name="FName" placeholder="Enter your full name" required>
      
                          <label for="reg-mobile">Mobile Number</label>
                          <input type="tel" id="reg-mobile" name="mob" placeholder="Enter your mobile number" required>
      
                          <label for="reg-email">Email</label>
                          <input type="email" id="reg-email" name="mail" placeholder="Enter your email" required>
      
                          <label for="reg-password">Password</label>
                          <div class="password-container">
                              <input type="password" id="reg-password" placeholder="Create a password" required>
                              <span class="toggle-password" data-target="reg-password">&#128065;</span>
                          </div>
      
                          <label for="reg-confirm-password">Confirm Password</label>
                          <div class="password-container">
                              <input type="password" id="reg-confirm-password" placeholder="Confirm your password" required>
                              <span class="toggle-password" data-target="reg-confirm-password">&#128065;</span>
                          </div>
      
                          <button type="submit">Register</button>
                      </form>
                      <div class="switch-link">
                          Already have an account? <a href="#" id="show-login">Login here</a>
                      </div>
                  </div>
              </div>
      
            <!-- Login Pop up end -->
     </header>
   
<main>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar Filter -->
            <div class="col-lg-3 filter-sidebar py-4">
                <div class="filter-header">
                    <h2 class="fw-bold">FILTER</h2>
                    <p class="text-muted">Search your vehicle</p>
                </div>

                <div class="search-box mb-4">
                    <div class="input-group">
                        <input type="text" class="form-control" id="search-input" placeholder="Search here...">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                    </div>
                </div>

                <div class="filter-section mb-3">
                    <select class="form-select" id="category-filter">
                        <option value="all">All Categories</option>
                        <?php foreach($categories as $category): ?>
                            <option value="<?php echo htmlspecialchars($category); ?>"><?php echo htmlspecialchars($category); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="filter-section mb-3">
                    <select class="form-select" id="brand-filter">
                        <option value="all">All Brands</option>
                        <?php foreach($brands as $brand): ?>
                            <option value="<?php echo htmlspecialchars($brand); ?>"><?php echo htmlspecialchars($brand); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="filter-section mb-3">
                    <h5>Model</h5>
                    <div class="model-buttons d-flex flex-wrap gap-2">
                        <?php foreach($models as $model): ?>
                            <button class="btn model-btn" data-model="<?php echo htmlspecialchars($model); ?>"><?php echo htmlspecialchars($model); ?></button>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="filter-section mb-3">
                    <h5>Price Range</h5>
                    <select class="form-select" id="price-filter">
                        <option value="all">All Prices</option>
                        <option value="under-5l">Under 5 Lakhs</option>
                        <option value="5l-10l">5-10 Lakhs</option>
                        <option value="10l-20l">10-20 Lakhs</option>
                        <option value="over-20l">Over 20 Lakhs</option>
                    </select>
                </div>

                <div class="filter-section mb-3">
                    <select class="form-select" id="fuel-filter">
                        <option value="all">All Fuel Types</option>
                        <?php foreach($fuelTypes as $fuelType): ?>
                            <option value="<?php echo htmlspecialchars($fuelType); ?>"><?php echo htmlspecialchars($fuelType); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="filter-section mb-3">
                    <select class="form-select" id="transmission-filter">
                        <option value="all">All Transmissions</option>
                        <option value="Automatic">Automatic</option>
                        <option value="Manual">Manual</option>
                    </select>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9 main-content py-4">
                <div class="content-header d-flex justify-content-between align-items-center mb-4">
                    <p class="mb-0" id="results-count">Loading vehicles...</p>
                </div>

                <div class="row car-grid" id="car-grid">
                    <!-- Vehicle cards will be dynamically inserted here -->
                </div>
            </div>
        </div>
    </div>
    
</main>
<footer class="footer">
      <!-- Footer Container -->
      <div class="section__container footer__container">
        <!-- Footer Column: Logo and Introduction -->
        <div class="footer__col">
          <!-- Footer Logo -->
          <div class="footer__logo">
            <a href="#" class="logo">
              <!-- Footer image -->
              <img src="../assets/logo-white.png" alt="logo" />
              <span>Car Rental</span>
            </a>
          </div>
          <!-- Service Description -->
          <p>
            We're here to provide you with the best vehicles and a seamless
            rental experience. Stay connected for updates, special offers, and
            more. Drive with confidence!
          </p>
          <!-- Social Media Links -->
          <ul class="footer__socials">
            <!-- Social Media Links List -->
            <li>
              <!-- Facebook link with icon -->
              <a href="#"><i class="ri-facebook-fill"></i></a>
            </li>
            <li>
              <!-- Twitter link with icon -->
              <a href="#"><i class="ri-twitter-fill"></i></a>
            </li>
            <li>
              <!-- LinkedIn link with icon -->
              <a href="#"><i class="ri-linkedin-fill"></i></a>
            </li>
            <li>
              <!-- Instagram link with icon -->
              <a href="#"><i class="ri-instagram-line"></i></a>
            </li>
            <li>
               <!-- YouTube link with icon -->
              <a href="#"><i class="ri-youtube-fill"></i></a>
            </li>
          </ul>
        </div>
        <div class="footer__col">
          <h4>Our Services</h4>
          <!-- Footer Column: Our Services -->
          <ul class="footer__links">
            <li><a href="#home">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#deals">Rental Deals</a></li>
            <li><a href="#choose">Why Choose Us</a></li>
            <li><a href="#client">Testimonials</a></li>
          </ul>
        </div>
        <!-- Footer Column: Vehicles Brand -->
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
        <!-- Footer Column: Contact Information -->
        <div class="footer__col">
          <h4>Contact</h4>
          <!-- List of Contact Details -->
          <ul class="footer__links">
             <!-- Contact Number -->
            <li>
              <a href="#">
                <span><i class="ri-phone-fill"></i></span> +91 9998887775
              </a>
            </li>
            <!-- Physical Address -->
            <li>
              <a href="#">
                <span><i class="ri-map-pin-fill"></i></span> Angul,odisha,India
              </a>
            </li>
            <!-- Email Address -->
            <li>
              <a href="#">
                <span><i class="ri-mail-fill"></i></span> info@readywheel
              </a>
            </li>
          </ul>
        </div>
      </div>
      <div class="footer__bar">
        <!-- Footer copyright text with our website name  -->
        Copyright © 2025 Readywheel. All rights reserved.
        <br>Made with 	&#10084;
      </div>
    </footer>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Initialize PHP data for JavaScript -->
    <script>
        // Initialize the vehicles data from PHP
        const initialVehicles = <?php echo json_encode($vehicles, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
        console.log('Loaded vehicles:', initialVehicles); // Debug log
    </script>

    <!-- Custom JS -->
    <script src="script.js"></script>
    <script src="../app.js"></script>
</body>
</html> 