<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'jobapplication');

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function db_connect() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }
    return $conn;
}

/**
 * Verify login credentials
 */
function verify_login($login, $password) {
    $conn = db_connect();
    
    // Modified query to match your actual columns
    $stmt = $conn->prepare("SELECT id, email, username, password FROM users WHERE email = ? OR username = ? LIMIT 1");
    $stmt->bind_param("ss", $login, $login);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            unset($user['password']); // Never store password in session
            return $user;
        }
    }
    return false;
}

/**
 * Login user
 */
function login_user($user) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['username'] = $user['username'] ?? ''; // Use username if available
    session_regenerate_id(true);
}

/**
 * Check if user is logged in
 */
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

/**
 * Get current user email
 */
function get_user_email() {
    return $_SESSION['email'] ?? null;
}

/**
 * Get current username or email as display name
 */
function get_user_name() {
    return $_SESSION['username'] ?? $_SESSION['email'] ?? 'Guest';
}