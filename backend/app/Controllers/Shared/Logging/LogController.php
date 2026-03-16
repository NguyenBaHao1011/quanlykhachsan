<?php
/**
 * SHARED - LOGGING SERVICE (All Actors / Frontend)
 * Actor: All
 * Service: Frontend log ingestion
 */

namespace App\Controllers\Shared\Logging;

use App\Core\Logger;

class LogController {

    public function store() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);
        $level   = strtoupper($data['level'] ?? 'INFO');
        $message = $data['message'] ?? '';
        Logger::log("[FRONTEND] $message", $level);
        echo json_encode(["message" => "Log received"]);
    }
}
