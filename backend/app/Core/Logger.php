<?php

namespace App\Core;

class Logger {
    private static $logFile = __DIR__ . '/../../logs/app.log';

    public static function log($message, $level = 'INFO') {
        $date = date('Y-m-d H:i:s');
        $context = self::captureContext();
        $logMessage = "[$date] [$level] $context $message" . PHP_EOL;
        
        $logDir = dirname(self::$logFile);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }

        file_put_contents(self::$logFile, $logMessage, FILE_APPEND);
    }

    private static function captureContext() {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $method = $_SERVER['REQUEST_METHOD'] ?? 'N/A';
        $uri = $_SERVER['REQUEST_URI'] ?? 'N/A';
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? 'N/A';
        return "[$ip] [$method] [$uri]";
    }

    public static function error($message) {
        self::log($message, 'ERROR');
    }

    public static function warn($message) {
        self::log($message, 'WARN');
    }

    public static function info($message) {
        self::log($message, 'INFO');
    }
}
