-- ============================================================
-- Grand Luxury Hotel Management System
-- Database Initialization Script v2.0
-- Architecture: Microservice (Admin/Receptionist/Accountant/Customer)
-- ============================================================

CREATE DATABASE IF NOT EXISTS hotel_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE hotel_management;

-- ============================================================
-- USERS TABLE
-- ============================================================
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

-- ============================================================
-- ROOM TYPES (Loai phong)
-- ============================================================
CREATE TABLE IF NOT EXISTS room_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type_name VARCHAR(50) NOT NULL,
    price_per_night DECIMAL(10, 2) NOT NULL,
    description TEXT,
    capacity INT NOT NULL,
    thumbnail_url VARCHAR(500)
);

-- ============================================================
-- AMENITIES (Tien nghi)
-- ============================================================
CREATE TABLE IF NOT EXISTS amenities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    icon VARCHAR(50) DEFAULT 'fa-check',
    category VARCHAR(50) DEFAULT 'general'
);

-- ============================================================
-- ROOMS (Phong)
-- ============================================================
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

-- ============================================================
-- ROOM IMAGES (Nhieu anh cho moi phong)
-- ============================================================
CREATE TABLE IF NOT EXISTS room_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_id INT NOT NULL,
    image_url VARCHAR(500) NOT NULL,
    caption VARCHAR(200),
    image_type ENUM('thumbnail', 'bedroom', 'bathroom', 'view', 'living', 'other') DEFAULT 'bedroom',
    sort_order INT DEFAULT 0,
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE
);

-- ============================================================
-- ROOM AMENITIES (Many-to-Many)
-- ============================================================
CREATE TABLE IF NOT EXISTS room_amenities (
    room_id INT NOT NULL,
    amenity_id INT NOT NULL,
    PRIMARY KEY (room_id, amenity_id),
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE,
    FOREIGN KEY (amenity_id) REFERENCES amenities(id) ON DELETE CASCADE
);

-- ============================================================
-- BOOKINGS (Dat phong)
-- ============================================================
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

-- ============================================================
-- SERVICES (Dich vu khach san)
-- ============================================================
CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    service_name VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    description TEXT,
    icon VARCHAR(50) DEFAULT 'fa-concierge-bell',
    is_active BOOLEAN DEFAULT TRUE
);

-- ============================================================
-- SERVICE USAGE (Ghi nhan su dung dich vu)
-- ============================================================
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

-- ============================================================
-- INVOICES (Hoa don)
-- ============================================================
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

-- ============================================================
-- SEED DATA
-- ============================================================

-- *** USERS ***
-- Mac khau mac dinh: "password"
-- Bcrypt hash: $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi
INSERT INTO users (username, password, role, full_name, email, phone) VALUES
-- Tai khoan nhan vien
('admin',        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin',        'Nguyen Quan Tri',   'admin@luxuryhotel.vn',        '0901000001'),
('receptionist', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'receptionist', 'Tran Le Tan',       'receptionist@luxuryhotel.vn', '0901000002'),
('accountant',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'accountant',   'Le Ke Toan',        'accountant@luxuryhotel.vn',   '0901000003'),
-- Tai khoan khach hang
('khachhang01',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', 'Nguyen Van An',    'nguyenvanan@gmail.com',    '0912345601'),
('khachhang02',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', 'Tran Thi Binh',    'tranthivanh@gmail.com',    '0912345602'),
('khachhang03',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', 'Pham Manh Cuong',  'phammanh.cuong@gmail.com', '0912345603'),
('khachhang04',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', 'Hoang Thi Duyen',  'hoangduyen.hn@gmail.com',  '0912345604'),
('khachhang05',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', 'Le Quoc Dat',      'lequocdat99@gmail.com',    '0912345605'),
('khachhang06',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', 'Vu Hai Linh',      'vuhailinh.sg@gmail.com',   '0912345606'),
('khachhang07',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', 'Do Minh Quan',     'dominhquan88@gmail.com',   '0912345607'),
('khachhang08',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', 'Nguyen Thu Ha',    'nguyenthuha.kh@gmail.com', '0912345608'),
('khachhang09',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', 'Bui Thanh Tung',   'buithanhlung@gmail.com',   '0912345609'),
('khachhang10',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', 'Dinh Phuong Thao', 'dinhphuongthao@gmail.com', '0912345610');

-- *** ROOM TYPES ***
INSERT INTO room_types (type_name, price_per_night, capacity, description, thumbnail_url) VALUES
('Standard Single', 1200000, 1,
 'Phong don tieu chuan am cung va tien nghi, thich hop cho khach cong tac hoac du lich mot minh. Trang bi day du tien nghi hien dai voi thiet ke toi gian thanh lich.',
 '/uploads/rooms/standard_thumb.png'),
('Deluxe Double', 2800000, 2,
 'Phong doi cao cap rong rai voi tam nhin toan canh thanh pho. Khong gian sang trong, giuong king-size thoai mai, phong tam cao cap voi bon tam va voi sen rieng biet.',
 '/uploads/rooms/deluxe_thumb.png'),
('Suite', 5500000, 4,
 'Bo phong Suite xa hoa voi khong gian sinh hoat va phong ngu phan tach. Tam nhin panorama 360 do, san marble, decor nghe thuat cao cap, va dich vu tien nghi 5 sao tuyet voi.',
 '/uploads/rooms/suite_thumb.png');

-- *** AMENITIES ***
INSERT INTO amenities (name, icon, category) VALUES
('May lanh',           'fa-snowflake',      'comfort'),
('TV man hinh phang',  'fa-tv',             'entertainment'),
('Minibar',            'fa-wine-bottle',    'comfort'),
('WiFi mien phi',      'fa-wifi',           'connectivity'),
('Bua sang mien phi',  'fa-mug-hot',        'dining'),
('Phong tam rieng',    'fa-bath',           'bathroom'),
('Bon tam sua',        'fa-hot-tub',        'bathroom'),
('Ket sat dien tu',    'fa-lock',           'security'),
('Dich vu phong 24/7', 'fa-concierge-bell', 'service'),
('Tu lanh mini',       'fa-temperature-low','comfort'),
('May say toc',        'fa-wind',           'bathroom'),
('Ban cong rieng',     'fa-door-open',      'view'),
('View thanh pho',     'fa-city',           'view'),
('View be boi',        'fa-swimming-pool',  'view'),
('May pha ca phe',     'fa-coffee',         'comfort'),
('Ban lam viec',       'fa-desk',           'comfort'),
('Ghe massage',        'fa-chair',          'comfort'),
('Phong khach rieng',  'fa-couch',          'living');

-- *** ROOMS ***
INSERT INTO rooms (room_number, room_type_id, status, description, max_guests, image_url, floor) VALUES
-- Standard Rooms (Tang 1)
('101', 1, 'available',
 'Phong don tieu chuan tang 1 huong nhin ra vuon hoa. Thiet ke go sang mau tao cam giac am cung, thu thai. Dien tich 22m2.',
 1, '/uploads/rooms/standard_thumb.png', 1),
('102', 1, 'available',
 'Phong don tieu chuan tang 1 yen tinh, noi that hien dai. Anh sang tu nhien chan hoa qua cua so lon. Dien tich 22m2.',
 1, '/uploads/rooms/standard_thumb.png', 1),
('103', 1, 'occupied',
 'Phong don tieu chuan tang 1, goc phong thoang dang voi 2 cua so. Phu hop khach cong tac ngan ngay. Dien tich 24m2.',
 1, '/uploads/rooms/standard_thumb.png', 1),
-- Deluxe Rooms (Tang 2)
('201', 2, 'available',
 'Phong doi cao cap tang 2 voi tam nhin thanh pho tuyet dep. Giuong king-size, sofa thu gian, ban an sang rieng. Dien tich 38m2.',
 2, '/uploads/rooms/deluxe_thumb.png', 2),
('202', 2, 'occupied',
 'Phong doi cao cap tang 2, phong cach hien dai ket hop co dien. Huong nhin ra ho boi. Dien tich 38m2.',
 2, '/uploads/rooms/deluxe_thumb.png', 2),
('203', 2, 'maintenance',
 'Phong doi cao cap tang 2 dang duoc bao tri nang cap. Dien tich 38m2.',
 2, '/uploads/rooms/deluxe_thumb.png', 2),
-- Suite Rooms (Tang 3)
('301', 3, 'available',
 'Suite Cao Cap tang 3 voi khong gian tiep khach sang trong. Tam nhin panorama 360 do toan canh thanh pho. Bep mini, bon tam jacuzzi, ghe massage. Dien tich 75m2.',
 4, '/uploads/rooms/suite_thumb.png', 3),
('302', 3, 'available',
 'Suite Presidential tang 3, phong khach rieng biet, 2 phong ngu, huong nhin ra bai bien. Dich vu butler rieng. Dien tich 90m2.',
 4, '/uploads/rooms/suite_thumb.png', 3);

-- *** ROOM IMAGES ***
-- Room 101 (Standard)
INSERT INTO room_images (room_id, image_url, caption, image_type, sort_order) VALUES
(1, '/uploads/rooms/standard_thumb.png',    'Phong ngu chinh',      'thumbnail', 0),
(1, '/uploads/rooms/standard_bathroom.png', 'Phong tam tieu chuan', 'bathroom',  1);

-- Room 102 (Standard)
INSERT INTO room_images (room_id, image_url, caption, image_type, sort_order) VALUES
(2, '/uploads/rooms/standard_thumb.png',    'Phong ngu chinh',      'thumbnail', 0),
(2, '/uploads/rooms/standard_bathroom.png', 'Phong tam tieu chuan', 'bathroom',  1);

-- Room 103 (Standard)
INSERT INTO room_images (room_id, image_url, caption, image_type, sort_order) VALUES
(3, '/uploads/rooms/standard_thumb.png',    'Phong ngu chinh', 'thumbnail', 0),
(3, '/uploads/rooms/standard_bathroom.png', 'Phong tam',       'bathroom',  1);

-- Room 201 (Deluxe)
INSERT INTO room_images (room_id, image_url, caption, image_type, sort_order) VALUES
(4, '/uploads/rooms/deluxe_thumb.png',    'Phong ngu cao cap',  'thumbnail', 0),
(4, '/uploads/rooms/deluxe_bathroom.png', 'Phong tam cao cap',  'bathroom',  1),
(4, '/uploads/rooms/pool_area.png',       'Tam nhin be boi',    'view',      2);

-- Room 202 (Deluxe)
INSERT INTO room_images (room_id, image_url, caption, image_type, sort_order) VALUES
(5, '/uploads/rooms/deluxe_thumb.png',    'Phong ngu cao cap',      'thumbnail', 0),
(5, '/uploads/rooms/deluxe_bathroom.png', 'Phong tam voi bon tam',  'bathroom',  1),
(5, '/uploads/rooms/pool_area.png',       'View ho boi',             'view',      2);

-- Room 203 (Deluxe - maintenance)
INSERT INTO room_images (room_id, image_url, caption, image_type, sort_order) VALUES
(6, '/uploads/rooms/deluxe_thumb.png', 'Phong ngu cao cap', 'thumbnail', 0);

-- Room 301 (Suite)
INSERT INTO room_images (room_id, image_url, caption, image_type, sort_order) VALUES
(7, '/uploads/rooms/suite_thumb.png',    'Suite - Phong ngu',   'thumbnail', 0),
(7, '/uploads/rooms/suite_living.png',   'Suite - Phong khach', 'living',    1),
(7, '/uploads/rooms/deluxe_bathroom.png','Suite - Phong tam',   'bathroom',  2),
(7, '/uploads/rooms/pool_area.png',      'Tam nhin tu ban cong','view',      3);

-- Room 302 (Suite Presidential)
INSERT INTO room_images (room_id, image_url, caption, image_type, sort_order) VALUES
(8, '/uploads/rooms/suite_thumb.png',    'Presidential Suite',        'thumbnail', 0),
(8, '/uploads/rooms/suite_living.png',   'Phong khach sang trong',    'living',    1),
(8, '/uploads/rooms/deluxe_bathroom.png','Phong tam dai ly',          'bathroom',  2),
(8, '/uploads/rooms/pool_area.png',      'View be boi vo cuc',        'view',      3);

-- *** ROOM AMENITIES ***
-- Room 101, 102, 103 (Standard): May lanh, TV, WiFi, Phong tam, May say toc, Ket sat, Tu lanh, Ban lam viec
INSERT INTO room_amenities (room_id, amenity_id) VALUES
(1,1),(1,2),(1,4),(1,6),(1,8),(1,10),(1,11),(1,16),
(2,1),(2,2),(2,4),(2,6),(2,8),(2,10),(2,11),(2,16),
(3,1),(3,2),(3,4),(3,6),(3,8),(3,10),(3,11),(3,16);

-- Room 201, 202, 203 (Deluxe): Tat ca Standard + Minibar, Bua sang, Bon tam, Dich vu phong, View thanh pho, May ca phe
INSERT INTO room_amenities (room_id, amenity_id) VALUES
(4,1),(4,2),(4,3),(4,4),(4,5),(4,6),(4,7),(4,8),(4,9),(4,10),(4,11),(4,13),(4,15),(4,16),
(5,1),(5,2),(5,3),(5,4),(5,5),(5,6),(5,7),(5,8),(5,9),(5,10),(5,11),(5,13),(5,15),(5,16),
(6,1),(6,2),(6,3),(6,4),(6,5),(6,6),(6,7),(6,8),(6,9),(6,10),(6,11),(6,13),(6,15),(6,16);

-- Room 301, 302 (Suite): Tat ca tien nghi
INSERT INTO room_amenities (room_id, amenity_id) VALUES
(7,1),(7,2),(7,3),(7,4),(7,5),(7,6),(7,7),(7,8),(7,9),(7,10),(7,11),(7,12),(7,13),(7,14),(7,15),(7,16),(7,17),(7,18),
(8,1),(8,2),(8,3),(8,4),(8,5),(8,6),(8,7),(8,8),(8,9),(8,10),(8,11),(8,12),(8,13),(8,14),(8,15),(8,16),(8,17),(8,18);

-- *** SERVICES ***
INSERT INTO services (service_name, price, description, icon) VALUES
('Do an phong (Room Service)', 150000, 'Giao do an tan phong 24/7 tu nha hang khach san',  'fa-utensils'),
('Giat ui',                    80000,  'Dich vu giat, ui, say quan ao trong ngay',           'fa-tshirt'),
('Thue xe dua don',            500000, 'Xe hoi cao cap dua don san bay, diem du lich',       'fa-car'),
('Massage & Spa',              400000, 'Lieu trinh massage thu gian tai phong hoac spa',     'fa-spa'),
('Tham quan thanh pho',        300000, 'Tour tham quan thanh pho co huong dan vien',         'fa-map-marked-alt'),
('Thue xe dap',                 50000, 'Thue xe dap kham pha xung quanh khach san',          'fa-bicycle'),
('Ruou vang & Trai cay',       220000, 'Set ruou vang va trai cay chao mung trong phong',   'fa-wine-glass-alt'),
('Chup anh chuyen nghiep',     600000, 'Dich vu chup anh ky niem tai khach san',            'fa-camera');

-- *** SAMPLE BOOKINGS ***
INSERT INTO bookings (user_id, room_id, check_in_date, check_out_date, total_price, status, notes) VALUES
(4, 3, '2026-03-10', '2026-03-13', 3600000,  'checked_out', 'Khach yeu cau phong yen tinh'),
(5, 5, '2026-03-11', '2026-03-15', 11200000, 'checked_in',  'Ky niem ngay cuoi'),
(6, 7, '2026-03-12', '2026-03-14', 11000000, 'confirmed',   'VIP khach hang than thiet'),
(7, 1, '2026-03-13', '2026-03-15', 2400000,  'pending',     NULL),
(8, 4, '2026-03-14', '2026-03-17', 8400000,  'pending',     'Can giuong phu cho tre em'),
(9, 8, '2026-03-15', '2026-03-20', 27500000, 'confirmed',   'Khach nuoc ngoai, can phien dich');

-- *** SAMPLE SERVICE USAGE ***
INSERT INTO service_usage (booking_id, service_id, quantity, unit_price) VALUES
(1, 1, 2, 150000),
(1, 2, 1, 80000),
(2, 4, 1, 400000),
(2, 7, 1, 220000),
(3, 1, 3, 150000),
(3, 3, 2, 500000);

-- *** SAMPLE INVOICES ***
INSERT INTO invoices (booking_id, room_amount, service_amount, total_amount, payment_status, payment_method) VALUES
(1, 3600000,  380000,   3980000,  'paid',   'card'),
(2, 11200000, 620000,  11820000,  'paid',   'transfer'),
(3, 11000000, 1450000, 12450000,  'unpaid', 'cash');
