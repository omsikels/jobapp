<?php
// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database credentials
$host = 'localhost';
$dbname = 'jobapplication';
$username = 'root'; // Default for XAMPP
$password = '';     // Default for XAMPP (empty)

try {
    // Create PDO connection
    $conn = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4", 
        $username, 
        $password, 
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_PERSISTENT => false // Better for most cases
        ]
    );
    
    // Optional: Set timezone for database connection
    $conn->exec("SET time_zone = '+00:00'");
    
} catch (PDOException $e) {
    // Log the error securely (don't expose to users in production)
    error_log('Database connection failed: ' . $e->getMessage());
    
    // Display user-friendly message
    die('Could not connect to the database. Please try again later.');
}