<?php
class Auth {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }

    // Hash password
    public function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    // Verify password
    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }

    // Start session
    public function startSession() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Login user
    public function login($user) {
        $this->startSession();
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->full_name;
        $_SESSION['user_type'] = $user->user_type;
        $_SESSION['logged_in'] = true;
    }

    // Logout user
    public function logout() {
        $this->startSession();
        session_unset();
        session_destroy();
    }

    // Check if user is logged in
    public function isLoggedIn() {
        $this->startSession();
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    // Get current user
    public function getCurrentUser() {
        $this->startSession();
        if ($this->isLoggedIn()) {
            return [
                'id' => $_SESSION['user_id'],
                'email' => $_SESSION['user_email'],
                'name' => $_SESSION['user_name'],
                'type' => $_SESSION['user_type']
            ];
        }
        return null;
    }

    // Redirect if not logged in
    public function requireLogin($redirect_url = 'login.php') {
        if (!$this->isLoggedIn()) {
            header("Location: " . $redirect_url);
            exit();
        }
    }

    // Redirect if logged in
    public function redirectIfLoggedIn($redirect_url = 'index.php') {
        if ($this->isLoggedIn()) {
            header("Location: " . $redirect_url);
            exit();
        }
    }
}
    ?>