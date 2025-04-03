<?php
session_start();
require_once '../config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the logged-in user's ID
    $owner_id = $_SESSION['user_id'];
    
    // Sanitize and validate input data
    $car_name = mysqli_real_escape_string($conn, $_POST['carName']);
    $brand = mysqli_real_escape_string($conn, $_POST['carBrand']);
    $year = (int)$_POST['carYear'];
    $car_type = mysqli_real_escape_string($conn, $_POST['carType']);
    $fuel_type = mysqli_real_escape_string($conn, $_POST['fuelType']);
    $specifications = mysqli_real_escape_string($conn, $_POST['carSpecs']);
    $seating_capacity = (int)$_POST['seatingCapacity'];
    $transmission = mysqli_real_escape_string($conn, $_POST['transmission']);
    $price_per_day = (float)$_POST['carPrice'];
    $location = mysqli_real_escape_string($conn, $_POST['carLocation']);
    $is_available = isset($_POST['availableNow']) ? 1 : 0;

    // Handle file uploads
    $upload_dir = '../uploads/vehicles/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $photo_paths = [];
    if (isset($_FILES['carPhotos'])) {
        $file_count = count($_FILES['carPhotos']['name']);
        
        for ($i = 0; $i < $file_count; $i++) {
            if ($_FILES['carPhotos']['error'][$i] === UPLOAD_ERR_OK) {
                $file_name = uniqid() . '_' . $_FILES['carPhotos']['name'][$i];
                $target_path = $upload_dir . $file_name;
                
                if (move_uploaded_file($_FILES['carPhotos']['tmp_name'][$i], $target_path)) {
                    $photo_paths[] = $target_path;
                }
            }
        }
    }

    // Insert data into database
    $sql = "INSERT INTO vehicles (
        owner_id, car_name, brand, year, car_type, fuel_type, 
        specifications, seating_capacity, transmission, price_per_day, 
        location, is_available
    ) VALUES (
        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
    )";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "issssssisiss",
        $owner_id, $car_name, $brand, $year, $car_type, $fuel_type,
        $specifications, $seating_capacity, $transmission, $price_per_day,
        $location, $is_available
    );

    if ($stmt->execute()) {
        $vehicle_id = $conn->insert_id;
        
        // If we have photos, store them in a separate table
        if (!empty($photo_paths)) {
            $photo_sql = "INSERT INTO vehicle_photos (vehicle_id, photo_path) VALUES (?, ?)";
            $photo_stmt = $conn->prepare($photo_sql);
            
            foreach ($photo_paths as $path) {
                $photo_stmt->bind_param("is", $vehicle_id, $path);
                $photo_stmt->execute();
            }
        }

        $_SESSION['success_message'] = "Car listed successfully!";
        header('Location: owner.php');
        exit();
    } else {
        $_SESSION['error_message'] = "Error listing car: " . $conn->error;
        header('Location: owner.php');
        exit();
    }
}
?> 