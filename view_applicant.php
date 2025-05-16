<?php
// Start the session
session_start();

// Check if user is logged in, otherwise redirect to login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: adminlogin.php');
    exit;
}

// Load both database access methods
require_once 'config.php';           // Provides $conn (raw PDO)
require_once 'db_connection.php';    // Provides Database class

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid application ID");
}

$id = intval($_GET['id']);

// Helper function to safely output values
function safeOutput($array, $key) {
    return isset($array[$key]) ? htmlspecialchars($array[$key]) : '';
}

// Get applicant from applicants table using raw $conn from config.php
try {
    $appQuery = "SELECT * FROM applicants WHERE id = :id";
    $appStmt = $conn->prepare($appQuery); // $conn from config.php
    $appStmt->execute([':id' => $id]);
    $application = $appStmt->fetch(PDO::FETCH_ASSOC);

    if (!$application) {
        die("Application not found");
    }
} catch (PDOException $e) {
    error_log("Error querying applicants: " . $e->getMessage());
    die("Database error occurred");
}

// Get job application from job_applications table using db_connection.php
try {
    $conn2 = Database::getInstance()->getConnection(); // New connection from class
    $applicationsQuery = "SELECT * FROM job_applications WHERE id = :id";
    $applicationsStmt = $conn2->prepare($applicationsQuery);
    $applicationsStmt->execute([':id' => $id]);
    $job_applications = $applicationsStmt->fetch(PDO::FETCH_ASSOC);

    if (!$job_applications) {
        die("Job Applications not found");
    }
} catch (PDOException $e) {
    error_log("Error querying job applications: " . $e->getMessage());
    die("Database error occurred");
}

// Get job application from job_applications table using db_connection.php
try {
    $conn2 = Database::getInstance()->getConnection(); // New connection from class
    $documentsQuery = "SELECT * FROM application_documents WHERE application_id = :application_id";
    $documentsStmt = $conn2->prepare($documentsQuery);
    $documentsStmt->execute([':application_id' => $id]);
    $document_applications = $documentsStmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$job_applications) {
        die("Job Applications not found");
    }
} catch (PDOException $e) {
    error_log("Error querying job applications: " . $e->getMessage());
    die("Database error occurred");
}

?>


<!DOCTYPE html>
<html>
<head>
    <title>Application Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php 
            // Try to include sidebar, but don't fail if it doesn't exist
            if (file_exists('sidebar.php')) {
                include 'sidebar.php';
            } elseif (file_exists('includes/sidebar.php')) {
                include 'includes/sidebar.php';
            }
            ?>
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Application Details</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="dashboard.php" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Dashboard
                        </a>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5>Basic Information</h5>
                            </div>
                            <div class="card-body">
                                <p><strong>Name:</strong> <?= safeOutput($application, 'full_name') ?></p>
                                <p><strong>Email:</strong> <?= safeOutput($application, 'email') ?></p>
                                <p><strong>Phone:</strong> <?= safeOutput($application, 'contact_number') ?></p>
                                <p><strong>Job Category:</strong> <?= ucfirst(safeOutput($application, 'job_category')) ?></p>
                                <p><strong>Position Applied:</strong> <?= safeOutput($application, 'job_position') ?></p>
                            </div>
                        </div>
                        
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5>Educational Background</h5>
                            </div>
                            <div class="card-body">
                                <p><strong>Elementary:</strong> <?= safeOutput($job_applications, 'elementary_school') ?></p>
                                <p><strong>High School:</strong> <?= safeOutput($job_applications, 'high_school') ?></p>
                                <p><strong>Senior High:</strong> <?= safeOutput($job_applications, 'senior_high') ?></p>
                                <p><strong>College/University:</strong> <?= safeOutput($job_applications, 'college') ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5>Work Experience</h5>
                            </div>
                            <div class="card-body">
                                <h6>Primary Experience</h6>
                                <p><strong>Company:</strong> <?= safeOutput($job_applications, 'company1') ?></p>
                                <p><strong>Position:</strong> <?= safeOutput($job_applications, 'position1') ?></p>
                                <p><strong>Duration:</strong> <?= safeOutput($job_applications, 'duration1') ?> years</p>
                                
                                <?php if (isset($job_applications['company2']) && !empty($job_applications['company2'])): ?>
                                <hr>
                                <h6>Secondary Experience</h6>
                                <p><strong>Company:</strong> <?= safeOutput($job_applications, 'company2') ?></p>
                                <p><strong>Position:</strong> <?= safeOutput($job_applications, 'position2') ?></p>
                                <p><strong>Duration:</strong> <?= safeOutput($job_applications, 'duration2') ?> years</p>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header">
                                <h5>Application Documents</h5>
                            </div>
                            <div class="card-body">
                                <div class="list-group">
                                    <?php 
                                    // Your PHP code should use fetchAll() instead of fetch():
                                    // $documentsStmt->execute([':application_id' => $id]);
                                    // $document_applications = $documentsStmt->fetchAll(PDO::FETCH_ASSOC);
                                    
                                    if (isset($document_applications) && is_array($document_applications) && count($document_applications) > 0): 
                                        foreach ($document_applications as $doc):
                                    ?>
                                    <a href="download_document.php?id=<?= safeOutput($doc, 'id') ?>" class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1"><?= ucfirst(str_replace('_', ' ', safeOutput($doc, 'document_type') ?? 'Document')) ?></h6>
                                            <small><?= isset($doc['file_size']) ? round($doc['file_size'] / 1024) : '?' ?> KB</small>
                                        </div>
                                        <p class="mb-1"><?= safeOutput($doc, 'file_name') ?></p>
                                        <small class="text-muted"><?= safeOutput($doc, 'file_type') ?? 'Unknown type' ?></small>
                                    </a>
                                    <?php 
                                        endforeach;
                                    else: 
                                    ?>
                                    <div class="text-center p-3">
                                        <p class="text-muted">No documents available</p>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>Application Status</h5>
                            </div>
                            <div class="card-body">
                                <form action="update_status.php" method="POST">
                                    <input type="hidden" name="application_id" value="<?= $application['id'] ?? '' ?>">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <select class="form-select" name="status" required>
                                                <option value="pending" <?= isset($application['status']) && $application['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                                <option value="reviewed" <?= isset($application['status']) && $application['status'] === 'reviewed' ? 'selected' : '' ?>>Reviewed</option>
                                                <option value="hired" <?= isset($application['status']) && $application['status'] === 'hired' ? 'selected' : '' ?>>Hired</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <button type="submit" class="btn btn-primary">Update Status</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>