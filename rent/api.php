<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Database connection
$host = 'localhost';
$dbname = 'readywheel_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo json_encode(['error' => 'Connection failed: ' . $e->getMessage()]);
    exit;
}

// Get all vehicles with their images
function getVehicles() {
    global $pdo;
    
    try {
        // Get all vehicles
        $stmt = $pdo->query("SELECT * FROM listed_vehicles");
        $vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get images for each vehicle
        foreach ($vehicles as &$vehicle) {
            $stmt = $pdo->prepare("SELECT image_path FROM vehicle_images WHERE vehicle_id = ?");
            $stmt->execute([$vehicle['id']]);
            $images = $stmt->fetchAll(PDO::FETCH_COLUMN);
            $vehicle['images'] = $images;
        }
        
        return $vehicles;
    } catch(PDOException $e) {
        return ['error' => $e->getMessage()];
    }
}

// Get vehicle by ID with images
function getVehicleById($id) {
    global $pdo;
    
    try {
        // Get vehicle details
        $stmt = $pdo->prepare("SELECT * FROM listed_vehicles WHERE id = ?");
        $stmt->execute([$id]);
        $vehicle = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$vehicle) {
            return ['error' => 'Vehicle not found'];
        }
        
        // Get vehicle images
        $stmt = $pdo->prepare("SELECT image_path FROM vehicle_images WHERE vehicle_id = ?");
        $stmt->execute([$id]);
        $images = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $vehicle['images'] = $images;
        
        return $vehicle;
    } catch(PDOException $e) {
        return ['error' => $e->getMessage()];
    }
}

// Handle API requests
$request = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

switch ($request) {
    case '/rent/api.php/vehicles':
        if ($method === 'GET') {
            echo json_encode(getVehicles());
        }
        break;
        
    case (preg_match('/^\/rent\/api\.php\/vehicles\/(\d+)$/', $request, $matches) ? true : false):
        if ($method === 'GET') {
            $id = $matches[1];
            echo json_encode(getVehicleById($id));
        }
        break;
        
    default:
        http_response_code(404);
        echo json_encode(['error' => 'Not found']);
        break;
} 