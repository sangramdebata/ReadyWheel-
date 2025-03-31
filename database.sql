-- Create the database
CREATE DATABASE IF NOT EXISTS readywheel_db;
USE readywheel_db;

-- Drop existing table if exists
DROP TABLE IF EXISTS vehicle_images;
DROP TABLE IF EXISTS payments;
DROP TABLE IF EXISTS bookings;
DROP TABLE IF EXISTS vehicle_photos;
DROP TABLE IF EXISTS vehicles;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS listed_vehicles;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    mobile VARCHAR(15) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create vehicles table
CREATE TABLE IF NOT EXISTS vehicles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    owner_id INT NOT NULL,
    car_name VARCHAR(100) NOT NULL,
    brand VARCHAR(50) NOT NULL,
    year INT NOT NULL,
    car_type VARCHAR(50) NOT NULL,
    fuel_type VARCHAR(20) NOT NULL,
    specifications TEXT NOT NULL,
    seating_capacity INT NOT NULL,
    transmission VARCHAR(20) NOT NULL,
    price_per_day DECIMAL(10,2) NOT NULL,
    location VARCHAR(255) NOT NULL,
    is_available BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (owner_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create vehicle_photos table
CREATE TABLE IF NOT EXISTS vehicle_photos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vehicle_id INT NOT NULL,
    photo_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE CASCADE
);

-- Create bookings table
CREATE TABLE IF NOT EXISTS bookings (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    vehicle_id INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    booking_status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create payments table
CREATE TABLE IF NOT EXISTS payments (
    payment_id INT PRIMARY KEY AUTO_INCREMENT,
    booking_id INT NOT NULL,
    user_id INT NOT NULL,
    vehicle_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    transaction_id VARCHAR(100),
    reference_number VARCHAR(50),
    payment_method VARCHAR(50) NOT NULL,
    payment_gateway VARCHAR(50),
    payment_status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
    gateway_response TEXT,
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    receipt_number VARCHAR(50),
    FOREIGN KEY (booking_id) REFERENCES bookings(booking_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create listed_vehicles table
CREATE TABLE IF NOT EXISTS listed_vehicles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    transmission VARCHAR(20) NOT NULL,
    fuel_type VARCHAR(20) NOT NULL,
    passenger_capacity VARCHAR(20) NOT NULL,
    main_image VARCHAR(255) NOT NULL,
    brand VARCHAR(50) NOT NULL,
    vehicle_class VARCHAR(50) NOT NULL,
    model VARCHAR(50) NOT NULL,
    color VARCHAR(30) NOT NULL,
    year INT NOT NULL,
    mileage INT NOT NULL,
    engine VARCHAR(20) NOT NULL,
    drive_type VARCHAR(20) NOT NULL,
    body_type VARCHAR(30) NOT NULL,
    seats INT NOT NULL,
    doors INT,
    luggage_capacity DECIMAL(10,2),
    description TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create vehicle_images table
CREATE TABLE IF NOT EXISTS vehicle_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vehicle_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (vehicle_id) REFERENCES listed_vehicles(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample data into listed_vehicles
INSERT INTO listed_vehicles (name, category, price, transmission, fuel_type, passenger_capacity, main_image, brand, vehicle_class, model, color, year, mileage, engine, drive_type, body_type, seats, doors, luggage_capacity, description) VALUES
('Maruti-Suzuki Swift', 'Family Car', 807000, 'Manual', 'Petrol', '4 Persons', '../assets/swift1.jpg', 'Maruti_Suzuki', 'Car', 'Family Car', 'Blue', 2020, 200, '3000cc', '4WD', 'Coupe', 4, 4, 150, 'Maruti Suzuki Swift – Stylish & Reliable!Rent the fuel-efficient Swift for a smooth, comfortable drive. Perfect for city trips or getaways, it offers great mileage, spacious interiors, and a sporty design.Book now for an effortless ride! 🚗✨'),
('TATA Tiago', 'Family Car', 1040000, 'Automatic', 'Electric', '5 Persons', '../assets/tiago3.jpg', 'Tata', 'Car', 'Family Car', 'Sky Blue', 2021, 150, '200cc', 'RWD', 'Coupe', 5, 4, 242, 'Tata Tiago – Compact & Efficient!Rent the Tata Tiago for a smooth, fuel-efficient drive. With a stylish design, spacious interiors, and great performance, it''s perfect for city commutes and road trips.Book now for a hassle-free ride! 🚗✨'),
('Mahendra Scorpio', 'Family Car', 1440000, 'Manual', 'Diesel', '7 Persons', '../assets/scorpio2.jpg', 'Mahendra', 'Car', 'Family Car', 'Green', 2022, 100, '5200cc', 'AWD', 'Coupe', 7, 5, 460, 'Mahindra Scorpio – Power Meets Comfort!Rent the rugged and powerful Mahindra Scorpio for an adventurous and comfortable ride. With its bold design, spacious cabin, and powerful engine, it''s perfect for city drives, off-road adventures, and long trips. Enjoy top-notch safety, modern features, and a smooth driving experience.Book now and drive with confidence! 🚙🔥'),
('Activa 5G', 'Two_wheeler', 97000, 'Automatic', 'Petrol', '2 Persons', '../assets/activa5g-4.jpg', 'Honda', 'Two_wheeler', 'CITY BIKE', 'Blue', 2023, 40, '109.19cc', 'AWD', 'Sedan', 2, NULL, 5.3, 'Honda Activa 5G – Smooth & Reliable Ride!Rent the Honda Activa 5G for a hassle-free and fuel-efficient commute. With a stylish design, comfortable seating, and smooth performance, it''s perfect for city rides. Enjoy superior mileage, easy handling, and trusted reliability.Book now for a smooth journey! 🛵✨'),
('Audi Q5', 'Sports Car', 6699000, 'Automatic', 'Diesel', '4 Persons', '../assets/AudiQ5-3.jpg', 'Audi', 'Car', 'Luxury', 'White', 2022, 120, '3800cc', 'RWD', 'Coupe', 5, 4, 232, 'Audi Q5 – Luxury & Performance Combined!Rent the Audi Q5 for a premium driving experience with powerful performance, elegant design, and advanced features. With a spacious, luxurious interior and top-tier safety, it''s perfect for business trips, vacations, or city drives.Book now and drive in style! 🚘🔥'),
('Hero Splendor+', 'Bike', 93571, 'Manual', 'Petrol', '2 Persons', '../assets/splendor2.png', 'Hero', 'Bike', 'CITY BIKE', 'Black', 2021, 50, '97.2cc', '4WD', 'SUV', 2, NULL, NULL, 'Hero Splendor+ – Reliable & Fuel-Efficient Ride!Rent the Hero Splendor+ for a smooth, budget-friendly commute. Known for its excellent mileage, comfortable seating, and hassle-free performance, it''s perfect for city rides and daily travel. Enjoy a dependable and economical journey every time!Book now for a smooth ride! 🏍️✨'),
('Hundai Creta', 'Sports Car', 1322000, 'Automatic', 'Diesel', '4 Persons', '../assets/creta-2.jpg', 'Hyundai', 'Car', 'SUV', 'Silver', 2022, 150, '2500cc', 'FWD', 'SUV', 5, 4, 433, 'Hyundai Creta – Modern & Versatile!Rent the Hyundai Creta for a perfect blend of style and practicality. With its modern design, comfortable interiors, and advanced features, it''s ideal for both city drives and weekend getaways. Enjoy a smooth, powerful performance with excellent fuel efficiency.Book now for a premium driving experience! 🚗✨');

-- Insert sample data into vehicle_images
INSERT INTO vehicle_images (vehicle_id, image_path) VALUES
(1, '../assets/swift1.jpg'),
(1, '../assets/swift2.jpg'),
(1, '../assets/swift3.jpg'),
(1, '../assets/swift4.jpg'),
(2, '../assets/tiago1.jpg'),
(2, '../assets/tiago2.jpg'),
(2, '../assets/tiago3.jpg'),
(2, '../assets/tiago4.jpg'),
(3, '../assets/scorpio1.jpg'),
(3, '../assets/scorpio2.jpg'),
(3, '../assets/scorpio3.jpg'),
(3, '../assets/scorpio4.jpg'),
(4, '../assets/activa5g-1.jpg'),
(4, '../assets/activa5g-2.jpg'),
(4, '../assets/activa5g-3.jpg'),
(4, '../assets/activa5g-4.jpg'),
(5, '../assets/AudiQ5-1.jpg'),
(5, '../assets/AudiQ5-2.jpg'),
(5, '../assets/AudiQ5-3.jpg'),
(5, '../assets/AudiQ5-4.jpg'),
(6, '../assets/slpendor1.png'),
(6, '../assets/splendor2.png'),
(6, '../assets/splendor3.png'),
(6, '../assets/splendor4.png'),
(7, '../assets/creta-1.jpg'),
(7, '../assets/creta-2.jpg'); 