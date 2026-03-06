<?php
class User {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function findByUsername($username) {
        $sql = "SELECT users.*, roles.name AS role
                FROM users 
                JOIN roles ON users.role_id = roles.id
                WHERE username = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$username]);
        return $stmt->fetch();
    }

    public function create($username, $email, $password, $role_id) {
        $sql = "INSERT INTO users(username, email, password, role_id)
                VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $username,
            $email,
            password_hash($password, PASSWORD_DEFAULT),
            $role_id
        ]);
    }
}
