<?php
// Start the session
session_start();

// Check if user is logged in, otherwise redirect to login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: adminlogin.php');
    exit;
}

// Use the database connection
require_once 'config.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['application_id']) || !isset($_POST['status'])) {
        die("Missing required parameters");
    }
    
    $applicationId = intval($_POST['application_id']);
    $status = $_POST['status'];
    
    // Validate status
    $allowedStatuses = ['pending', 'reviewed', 'accepted', 'rejected'];
    if (!in_array($status, $allowedStatuses)) {
        die("Invalid status provided");
    }
    
    try {
        // Update the status in the applicants table
        $query = "UPDATE applicants SET status = :status WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->execute([
            ':status' => $status,
            ':id' => $applicationId
        ]);
        
        // Redirect back to the applicant view page
        header("Location: view_applicant.php?id=$applicationId&updated=1");
        exit;
    } catch (PDOException $e) {
        die("Error updating status: " . $e->getMessage());
    }
}
else {
    // Redirect if accessed directly without POST
    header("Location: dashboard.php");
    exit;
}