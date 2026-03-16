<?php

class ApiIntegrationTest {
    private $baseUrl = "http://localhost:8000";

    public function run() {
        echo "Starting Exhaustive CRUD Integration Tests...\n";
        
        $this->testUserCRUD();
        $this->testRoomCRUD();
        $this->testServiceCRUD();
        $this->testBookingAndInvoiceFlow();
        
        echo "\nIntegration Tests Completed.\n";
    }

    private function testUserCRUD() {
        echo "\n[User CRUD Test]\n";
        // Create
        echo "Creating user... ";
        $userData = [
            "username" => "testuser_" . time(),
            "password" => "testpass",
            "full_name" => "Test User",
            "email" => "test@example.com",
            "phone" => "0123456789",
            "role" => "customer"
        ];
        $res = $this->post("/api/users", $userData);
        echo ($res['code'] == 200) ? "PASSED\n" : "FAILED\n";

        // Update (Mocking ID for simplicity in this script, usually you'd get it from a list)
        echo "Updating user... ";
        $updateData = ["id" => 4, "full_name" => "Updated Name", "email" => "upd@ex.com", "phone" => "111", "role" => "customer"];
        $res = $this->put("/api/users", $updateData);
        echo ($res['code'] == 200) ? "PASSED\n" : "FAILED\n";

        // Delete
        echo "Deleting user... ";
        $res = $this->delete("/api/users", ["id" => 4]);
        echo ($res['code'] == 200) ? "PASSED\n" : "FAILED\n";
    }

    private function testRoomCRUD() {
        echo "\n[Room CRUD Test]\n";
        echo "Creating room... ";
        $roomData = ["room_number" => "999", "room_type_id" => 1, "status" => "available"];
        $res = $this->post("/api/rooms", $roomData);
        echo ($res['code'] == 200) ? "PASSED\n" : "FAILED\n";

        echo "Updating room... ";
        $res = $this->put("/api/rooms", ["id" => 6, "room_number" => "999-U", "room_type_id" => 1, "status" => "maintenance"]);
        echo ($res['code'] == 200) ? "PASSED\n" : "FAILED\n";

        echo "Deleting room... ";
        $res = $this->delete("/api/rooms", ["id" => 6]);
        echo ($res['code'] == 200) ? "PASSED\n" : "FAILED\n";
    }

    private function testServiceCRUD() {
        echo "\n[Service CRUD Test]\n";
        echo "Creating service... ";
        $serviceData = ["service_name" => "Test Service", "price" => 50000, "description" => "Test Desc"];
        $res = $this->post("/api/services", $serviceData);
        echo ($res['code'] == 200) ? "PASSED\n" : "FAILED\n";

        echo "Updating service... ";
        $res = $this->put("/api/services", ["id" => 1, "service_name" => "Updated Service", "price" => 60000, "description" => "Upd"]);
        echo ($res['code'] == 200) ? "PASSED\n" : "FAILED\n";
    }

    private function testBookingAndInvoiceFlow() {
        echo "\n[Booking & Invoice Flow Test]\n";
        echo "Creating booking... ";
        $bookingData = [
            "user_id" => 1,
            "room_id" => 1,
            "check_in_date" => date('Y-m-d'),
            "check_out_date" => date('Y-m-d', strtotime('+1 day')),
            "total_price" => 1200000,
            "status" => "confirmed"
        ];
        $res = $this->post("/api/bookings", $bookingData);
        $bookingId = $res['data']['booking_id'] ?? null;
        echo ($bookingId) ? "PASSED (ID: $bookingId)\n" : "FAILED\n";

        if ($bookingId) {
            echo "Generating invoice... ";
            $res = $this->post("/api/invoices", ["booking_id" => $bookingId]);
            $invoiceId = $res['data']['invoice_id'] ?? null;
            echo ($invoiceId) ? "PASSED (ID: $invoiceId)\n" : "FAILED\n";

            if ($invoiceId) {
                echo "Updating invoice status to paid... ";
                $res = $this->put("/api/invoices/status", ["id" => $invoiceId, "status" => "paid"]);
                echo ($res['code'] == 200) ? "PASSED\n" : "FAILED\n";
            }
        }
    }

    private function post($endpoint, $data) {
        return $this->request('POST', $endpoint, $data);
    }

    private function put($endpoint, $data) {
        return $this->request('PUT', $endpoint, $data);
    }

    private function delete($endpoint, $data) {
        return $this->request('DELETE', $endpoint, $data);
    }

    private function request($method, $endpoint, $data = null) {
        $ch = curl_init($this->baseUrl . $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        }
        $result = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return ['code' => $code, 'data' => json_decode($result, true)];
    }
}

if (php_sapi_name() === 'cli' && basename(__FILE__) == basename($_SERVER['PHP_SELF'])) {
    (new ApiIntegrationTest())->run();
}
