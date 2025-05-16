<?php
// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the Database class
require_once 'db_connection.php';
require_once 'auth_check.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid document ID");
}

$id = intval($_GET['id']);

try {
    // Get document info using Database class
    $conn = Database::getInstance()->getConnection();
    $query = "SELECT * FROM application_documents WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->execute([':id' => $id]);
    $document = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$document) {
        die("Document not found in database");
    }

    if (!file_exists($document['file_path'])) {
        error_log("File not found: " . $document['file_path']);
        die("Document file not found on server");
    }

    // Verify the admin has permission to view this document
    // (You might want to add additional checks here)

    // Send file to browser
    header('Content-Type: ' . $document['file_type']);
    header('Content-Disposition: attachment; filename="' . $document['file_name'] . '"');
    header('Content-Length: ' . filesize($document['file_path']));
    readfile($document['file_path']);
    exit();
} catch (PDOException $e) {
    error_log("Database error in download_document.php: " . $e->getMessage());
    die("Database error occurred");
} catch (Exception $e) {
    error_log("Error in download_document.php: " . $e->getMessage());
    die("Error occurred while processing the document");
}
?>