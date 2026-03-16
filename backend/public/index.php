<?php

require_once '../app/Core/Router.php';
require_once '../app/Core/Database.php';

ini_set('display_errors', 0);
error_reporting(E_ALL);

// PSR-4 Autoloader - supports deep namespaces for microservice structure
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) return;

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

use App\Core\Router;
use App\Core\Logger;

$method = $_SERVER['REQUEST_METHOD'];
$uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

Logger::info("Incoming Request: $method $uri");

$router = new Router();

// =========================================================
// AUTH SERVICE (Shared - All Actors)
// =========================================================
$router->add('POST', '/api/auth/login',    'Auth\AuthController@login');
$router->add('POST', '/api/login',         'Auth\AuthController@login'); // backward compat

// =========================================================
// ADMIN SERVICES
// =========================================================

// Admin > UserManagement
$router->add('GET',    '/api/admin/users',  'Admin\UserManagement\AdminUserController@index');
$router->add('POST',   '/api/admin/users',  'Admin\UserManagement\AdminUserController@store');
$router->add('PUT',    '/api/admin/users',  'Admin\UserManagement\AdminUserController@update');
$router->add('DELETE', '/api/admin/users',  'Admin\UserManagement\AdminUserController@destroy');

// Admin > RoomManagement
$router->add('GET',    '/api/admin/rooms',         'Admin\RoomManagement\AdminRoomController@index');
$router->add('GET',    '/api/admin/rooms/detail',  'Admin\RoomManagement\AdminRoomController@show');
$router->add('POST',   '/api/admin/rooms',         'Admin\RoomManagement\AdminRoomController@store');
$router->add('POST',   '/api/admin/rooms/upload',  'Admin\RoomManagement\AdminRoomController@upload');
$router->add('PUT',    '/api/admin/rooms',         'Admin\RoomManagement\AdminRoomController@update');
$router->add('DELETE', '/api/admin/rooms',         'Admin\RoomManagement\AdminRoomController@destroy');
$router->add('GET',    '/api/admin/amenities',     'Admin\RoomManagement\AdminRoomController@amenities');

// Admin > BookingManagement
$router->add('GET',    '/api/admin/bookings',          'Admin\BookingManagement\AdminBookingController@index');
$router->add('PUT',    '/api/admin/bookings',          'Admin\BookingManagement\AdminBookingController@update');
$router->add('PUT',    '/api/admin/bookings/status',   'Admin\BookingManagement\AdminBookingController@updateStatus');
$router->add('DELETE', '/api/admin/bookings',          'Admin\BookingManagement\AdminBookingController@destroy');

// Admin > Dashboard
$router->add('GET', '/api/admin/stats', 'Admin\Dashboard\AdminDashboardController@getSummary');

// Admin > ServiceManagement
$router->add('GET',    '/api/admin/services',  'Admin\ServiceManagement\AdminServiceController@index');
$router->add('POST',   '/api/admin/services',  'Admin\ServiceManagement\AdminServiceController@store');
$router->add('PUT',    '/api/admin/services',  'Admin\ServiceManagement\AdminServiceController@update');
$router->add('DELETE', '/api/admin/services',  'Admin\ServiceManagement\AdminServiceController@destroy');

// =========================================================
// RECEPTIONIST SERVICES
// =========================================================

// Receptionist > RoomStatus
$router->add('GET',  '/api/receptionist/rooms',         'Receptionist\RoomStatus\ReceptionistRoomController@index');
$router->add('GET',  '/api/receptionist/rooms/detail',  'Receptionist\RoomStatus\ReceptionistRoomController@show');
$router->add('PUT',  '/api/receptionist/rooms/status',  'Receptionist\RoomStatus\ReceptionistRoomController@updateStatus');

// Receptionist > Booking
$router->add('GET',    '/api/receptionist/bookings',           'Receptionist\Booking\ReceptionistBookingController@index');
$router->add('POST',   '/api/receptionist/bookings',           'Receptionist\Booking\ReceptionistBookingController@store');
$router->add('PUT',    '/api/receptionist/bookings',           'Receptionist\Booking\ReceptionistBookingController@update');
$router->add('PUT',    '/api/receptionist/bookings/status',    'Receptionist\Booking\ReceptionistBookingController@updateStatus');
$router->add('PUT',    '/api/receptionist/bookings/checkin',   'Receptionist\Booking\ReceptionistBookingController@checkIn');
$router->add('PUT',    '/api/receptionist/bookings/checkout',  'Receptionist\Booking\ReceptionistBookingController@checkOut');
$router->add('DELETE', '/api/receptionist/bookings',           'Receptionist\Booking\ReceptionistBookingController@destroy');

// Receptionist > ServiceUsage
$router->add('GET',  '/api/receptionist/services',        'Receptionist\ServiceUsage\ReceptionistServiceController@index');
$router->add('POST', '/api/receptionist/services/usage',  'Receptionist\ServiceUsage\ReceptionistServiceController@addUsage');

// =========================================================
// ACCOUNTANT SERVICES
// =========================================================

// Accountant > Invoice
$router->add('GET',    '/api/accountant/invoices',          'Accountant\Invoice\AccountantInvoiceController@index');
$router->add('POST',   '/api/accountant/invoices',          'Accountant\Invoice\AccountantInvoiceController@create');
$router->add('PUT',    '/api/accountant/invoices/status',   'Accountant\Invoice\AccountantInvoiceController@updateStatus');
$router->add('DELETE', '/api/accountant/invoices',          'Accountant\Invoice\AccountantInvoiceController@destroy');

// Accountant > Revenue
$router->add('GET', '/api/accountant/revenue',         'Accountant\Revenue\AccountantRevenueController@getSummary');
$router->add('GET', '/api/accountant/service-report',  'Accountant\ServiceReport\AccountantServiceReportController@getSummary');

// =========================================================
// CUSTOMER SERVICES
// =========================================================

// Customer > Room
$router->add('GET', '/api/customer/rooms',        'Customer\Room\CustomerRoomController@index');
$router->add('GET', '/api/customer/rooms/detail', 'Customer\Room\CustomerRoomController@show');

// Customer > Booking
$router->add('POST',   '/api/customer/bookings',          'Customer\Booking\CustomerBookingController@store');
$router->add('GET',    '/api/customer/bookings/history',  'Customer\Booking\CustomerBookingController@history');
$router->add('DELETE', '/api/customer/bookings',          'Customer\Booking\CustomerBookingController@cancel');

// Customer > User (Profile)
$router->add('GET', '/api/users/profile', 'Customer\User\CustomerUserController@getProfile');
$router->add('PUT', '/api/users/profile', 'Customer\User\CustomerUserController@updateProfile');

// =========================================================
// SHARED SERVICES
// =========================================================
$router->add('POST', '/api/logs', 'Shared\Logging\LogController@store');

// =========================================================
// BACKWARD-COMPATIBLE LEGACY ROUTES
// (Keep working while frontends are updated to new namespace)
// =========================================================
$router->add('GET',    '/api/users',            'Admin\UserManagement\AdminUserController@index');
$router->add('POST',   '/api/users',            'Admin\UserManagement\AdminUserController@store');
$router->add('PUT',    '/api/users',            'Admin\UserManagement\AdminUserController@update');
$router->add('DELETE', '/api/users',            'Admin\UserManagement\AdminUserController@destroy');

$router->add('GET',    '/api/rooms',            'Admin\RoomManagement\AdminRoomController@index');
$router->add('GET',    '/api/rooms/detail',     'Admin\RoomManagement\AdminRoomController@show');
$router->add('GET',    '/api/amenities',        'Admin\RoomManagement\AdminRoomController@amenities');
$router->add('POST',   '/api/rooms',            'Admin\RoomManagement\AdminRoomController@store');
$router->add('POST',   '/api/rooms/upload',     'Admin\RoomManagement\AdminRoomController@upload');
$router->add('PUT',    '/api/rooms',            'Admin\RoomManagement\AdminRoomController@update');
$router->add('DELETE', '/api/rooms',            'Admin\RoomManagement\AdminRoomController@destroy');

$router->add('GET',    '/api/bookings',         'Admin\BookingManagement\AdminBookingController@index');
$router->add('POST',   '/api/bookings',         'Receptionist\Booking\ReceptionistBookingController@store');
$router->add('PUT',    '/api/bookings',         'Receptionist\Booking\ReceptionistBookingController@update');
$router->add('PUT',    '/api/bookings/status',  'Admin\BookingManagement\AdminBookingController@updateStatus');
$router->add('DELETE', '/api/bookings',         'Admin\BookingManagement\AdminBookingController@destroy');

$router->add('GET',    '/api/services',        'Admin\ServiceManagement\AdminServiceController@index');
$router->add('POST',   '/api/services',        'Admin\ServiceManagement\AdminServiceController@store');
$router->add('PUT',    '/api/services',        'Admin\ServiceManagement\AdminServiceController@update');
$router->add('DELETE', '/api/services',        'Admin\ServiceManagement\AdminServiceController@destroy');
$router->add('POST',   '/api/services/usage',  'Receptionist\ServiceUsage\ReceptionistServiceController@addUsage');

$router->add('GET',    '/api/invoices',         'Accountant\Invoice\AccountantInvoiceController@index');
$router->add('POST',   '/api/invoices',         'Accountant\Invoice\AccountantInvoiceController@create');
$router->add('PUT',    '/api/invoices/status',  'Accountant\Invoice\AccountantInvoiceController@updateStatus');
$router->add('DELETE', '/api/invoices',         'Accountant\Invoice\AccountantInvoiceController@destroy');

$router->add('GET', '/api/stats', 'Admin\Dashboard\AdminDashboardController@getSummary');

// Handle Request
try {
    $router->handle($method, $uri);
} catch (\Exception $e) {
    Logger::error("Global Exception: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["message" => "Internal Server Error", "debug" => $e->getMessage()]);
}
