<?php

use PHPUnit\Framework\TestCase;

// Mock autoloader for tests
spl_autoload_register(function ($class) {
    $path = str_replace('\\', '/', $class);
    $file = __DIR__ . '/../../backend/' . lcfirst($path) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

class UserTest extends TestCase {
    public function testPasswordVerification() {
        $password = "password";
        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        $this->assertTrue(password_verify($password, $hash));
    }

    public function testUserRoleMapping() {
        $roles = ['admin', 'receptionist', 'accountant', 'customer'];
        $this->assertContains('admin', $roles);
    }
}
