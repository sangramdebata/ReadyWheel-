CREATE TABLE IF NOT EXISTS `payments` (
    `payment_id` INT PRIMARY KEY AUTO_INCREMENT,
    `booking_id` INT NOT NULL,
    `user_id` INT NOT NULL,
    `car_id` INT NOT NULL,
    `amount` DECIMAL(10,2) NOT NULL,
    `transaction_id` VARCHAR(100),
    `reference_number` VARCHAR(50),
    `payment_method` VARCHAR(50) NOT NULL,
    `payment_gateway` VARCHAR(50),
    `payment_status` ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
    `gateway_response` TEXT,
    `payment_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `receipt_number` VARCHAR(50),
    FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`booking_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`),
    FOREIGN KEY (`car_id`) REFERENCES `cars`(`car_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4; 