-- Create the database
CREATE DATABASE IF NOT EXISTS readywheel_db;
USE readywheel_db;

-- Drop existing table if exists
DROP TABLE IF EXISTS vehicle_photos;
DROP TABLE IF EXISTS vehicles;
DROP TABLE IF EXISTS users;

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