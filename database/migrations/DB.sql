CREATE DATABASE IF NOT EXISTS hotelmanager CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE hotelmanager;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    email VARCHAR(100),
    phone VARCHAR(20),
    role ENUM('admin', 'receptionist', 'accountant', 'customer') NOT NULL,
    avatar_url VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS room_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type_name VARCHAR(50) NOT NULL,
    price_per_night DECIMAL(10, 2) NOT NULL,
    description TEXT,
    capacity INT NOT NULL,
    thumbnail_url VARCHAR(500)
);

CREATE TABLE IF NOT EXISTS amenities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    icon VARCHAR(50) DEFAULT 'fa-check',
    category VARCHAR(50) DEFAULT 'general'
);

CREATE TABLE IF NOT EXISTS rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_number VARCHAR(10) NOT NULL UNIQUE,
    room_type_id INT,
    status ENUM('available', 'occupied', 'dirty', 'maintenance') DEFAULT 'available',
    description TEXT,
    max_guests INT DEFAULT 2,
    image_url VARCHAR(500) COMMENT 'Primary thumbnail image',
    floor INT DEFAULT 1,
    FOREIGN KEY (room_type_id) REFERENCES room_types(id)
);

CREATE TABLE IF NOT EXISTS room_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_id INT NOT NULL,
    image_url VARCHAR(500) NOT NULL,
    caption VARCHAR(200),
    image_type ENUM('thumbnail', 'bedroom', 'bathroom', 'view', 'living', 'other') DEFAULT 'bedroom',
    sort_order INT DEFAULT 0,
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS room_amenities (
    room_id INT NOT NULL,
    amenity_id INT NOT NULL,
    PRIMARY KEY (room_id, amenity_id),
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE,
    FOREIGN KEY (amenity_id) REFERENCES amenities(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    room_id INT,
    check_in_date DATE NOT NULL,
    check_out_date DATE NOT NULL,
    total_price DECIMAL(10, 2),
    status ENUM('pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled') DEFAULT 'pending',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (room_id) REFERENCES rooms(id)
);

CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    service_name VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    description TEXT,
    icon VARCHAR(50) DEFAULT 'fa-concierge-bell',
    is_active BOOLEAN DEFAULT TRUE
);

CREATE TABLE IF NOT EXISTS service_usage (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT,
    service_id INT,
    quantity INT DEFAULT 1,
    unit_price DECIMAL(10,2),
    usage_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(id),
    FOREIGN KEY (service_id) REFERENCES services(id)
);

CREATE TABLE IF NOT EXISTS invoices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT,
    invoice_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    room_amount DECIMAL(10, 2) DEFAULT 0,
    service_amount DECIMAL(10, 2) DEFAULT 0,
    total_amount DECIMAL(10, 2),
    payment_status ENUM('unpaid', 'paid') DEFAULT 'unpaid',
    payment_method ENUM('cash', 'card', 'transfer') DEFAULT 'cash',
    notes TEXT,
    FOREIGN KEY (booking_id) REFERENCES bookings(id)
);
