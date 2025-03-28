<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Why Choose Us | Premium Car Rentals</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css"
      rel="stylesheet"
    />
    <link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"
    />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
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
            <li><a href="../rent/rent.php">Rent</a></li>
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
                  <form method="get" id="log_form">
                      <label for="login-username">Username</label>
                      <input type="text" id="login-username" placeholder="Enter your username" required>
                      
                      <label for="login-password">Password</label>
                      <div class="password-container">
                          <input type="password" id="login-password" placeholder="Enter your password" required>
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
    

    <!-- Why Choose Us Section -->
    <div class="spacer">
    <section id="benefits" class="py-5">
        <div class="container">
            <div class="row mb-5">
                <div class="col-12 text-center" data-aos="fade-up">
                    <h2 class="section-title">Why Choose <span class="text-primary">Us</span></h2>
                    <p class="section-subtitle">Discover the advantages that set us apart</p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="benefit-card">
                        <div class="icon-wrapper">
                            <i class="fas fa-car"></i>
                        </div>
                        <h3>Premium Fleet</h3>
                        <p>Our fleet consists of the latest models, meticulously maintained to ensure your safety and comfort on every journey.</p>
                    </div>
                </div>
                
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="benefit-card">
                        <div class="icon-wrapper">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <h3>Transparent Pricing</h3>
                        <p>No hidden fees or surprises. Our pricing is clear and competitive, with all-inclusive packages available.</p>
                    </div>
                </div>
                
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="benefit-card">
                        <div class="icon-wrapper">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h3>24/7 Support</h3>
                        <p>Our dedicated customer service team is available around the clock to assist you with any queries or concerns.</p>
                    </div>
                </div>
                
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="benefit-card">
                        <div class="icon-wrapper">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <h3>Convenient Locations</h3>
                        <p>Multiple pickup and drop-off points across the city, including airport and hotel services.</p>
                    </div>
                </div>
                
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="500">
                    <div class="benefit-card">
                        <div class="icon-wrapper">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <h3>Quick Booking</h3>
                        <p>Our streamlined booking process ensures you can reserve your vehicle in minutes, with instant confirmation.</p>
                    </div>
                </div>
                
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="600">
                    <div class="benefit-card">
                        <div class="icon-wrapper">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3>Comprehensive Insurance</h3>
                        <p>Drive with peace of mind knowing you're covered by our comprehensive insurance packages.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </div>

    <!-- First in City Section -->
    <section class="first-in-city py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <img src="assests/car1" alt="First Car Rental in City" class="img-fluid rounded shadow">
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="first-content p-4">
                        <span class="badge bg-primary mb-3">PIONEERING SERVICE</span>
                        <h2>The First Car Rental Service in the City</h2>
                        <p>Since our establishment, we've been leading the way in providing exceptional car rental services to our community. As the first car rental company in the city, we've set the standard for quality, reliability, and customer satisfaction.</p>
                        <p>Our pioneering spirit continues to drive us forward as we constantly innovate and improve our services to meet your evolving needs.</p>
                        <div class="d-flex mt-4">
                            <div class="me-4">
                                <h3 class="counter">15+</h3>
                                <p>Years of Experience</p>
                            </div>
                            <div class="me-4">
                                <h3 class="counter">10,000+</h3>
                                <p>Happy Customers</p>
                            </div>
                            <div>
                                <h3 class="counter">500+</h3>
                                <p>Premium Vehicles</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Vision Section -->
    <section class="vision-section py-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5" data-aos="fade-up">
                    <h2 class="section-title">Our <span class="text-primary">Vision</span></h2>
                    <p class="section-subtitle">Driving towards a better future</p>
                </div>
            </div>
            
            <div class="row align-items-center">
                <div class="col-lg-6 order-lg-2" data-aos="fade-left">
                    <img src="assests/our vision.jpg" alt="Our Vision" class="img-fluid rounded shadow">
                </div>
                <div class="col-lg-6 order-lg-1" data-aos="fade-right">
                    <div class="vision-content">
                        <div class="vision-item mb-4">
                            <h3><i class="fas fa-check-circle text-primary me-2"></i> Customer-Centric Approach</h3>
                            <p>We aim to exceed customer expectations by providing personalized services tailored to individual needs, ensuring every journey is memorable for the right reasons.</p>
                        </div>
                        
                        <div class="vision-item mb-4">
                            <h3><i class="fas fa-check-circle text-primary me-2"></i> Sustainable Growth</h3>
                            <p>We're committed to sustainable practices and continuous improvement, striving to make a positive impact on both our community and the environment.</p>
                        </div>
                        
                        <div class="vision-item mb-4">
                            <h3><i class="fas fa-check-circle text-primary me-2"></i> Innovation & Technology</h3>
                            <p>We embrace cutting-edge technology to enhance our services, from advanced booking systems to real-time vehicle tracking and maintenance.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials-section py-5">
        <div class="container">
            <div class="row mb-5">
                <div class="col-12 text-center" data-aos="fade-up">
                    <h2 class="section-title">What Our <span class="text-primary">Customers</span> Say</h2>
                    <p class="section-subtitle">Real experiences from real people</p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="testimonial-card">
                        <div class="testimonial-content">
                            <i class="fas fa-quote-left"></i>
                            <p>"The service was exceptional! From booking to return, everything was smooth and professional. I'll definitely be using ReadyWheel again."</p>
                        </div>
                        <div class="testimonial-author">
                            <img src="../assets/user1.jpg" alt="User 1">
                            <div>
                                <h4>Chaitanya Behera</h4>
                                <p>Business Traveler</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="testimonial-card">
                        <div class="testimonial-content">
                            <i class="fas fa-quote-left"></i>
                            <p>"The variety of vehicles and competitive pricing make ReadyWheel my go-to choice for car rentals. Their customer support is outstanding!"</p>
                        </div>
                        <div class="testimonial-author">
                            <img src="assests/testimonials/user2.jpg" alt="User 2">
                            <div>
                                <h4>Shreyaa Sahu</h4>
                                <p>Family Vacationer</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="testimonial-card">
                        <div class="testimonial-content">
                            <i class="fas fa-quote-left"></i>
                            <p>"I've rented from ReadyWheel multiple times, and they never disappoint. The vehicles are always in perfect condition."</p>
                        </div>
                        <div class="testimonial-author">
                            <img src="assests/testimonials/user3.jpg" alt="User 3">
                            <div>
                                <h4>Priyanka Sanam</h4>
                                <p>Regular Customer</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="section__container footer__container">
            <!-- Footer Column: Logo and Introduction -->
            <div class="footer__col">
                <!-- Footer Logo -->
                <div class="footer__logo">
                    <a href="#" class="logo">
                        <!-- Footer image -->
                        <img src="../assets/logo-white.png" alt="logo" />
                        <span>ReadyWheel</span>
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
                    <li><a href="../why-choose-us/choose.php">Why Choose Us</a></li>
                    <li><a href="../rent/rent.php">Rent</a></li>
                    <li><a href="../Profiles/owner.php">List Your Car</a></li>
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AOS Animation Library -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <!-- Custom JS -->
    <script src="script.js"></script>
    <script src="../app.js"></script>
</body>
</html> 