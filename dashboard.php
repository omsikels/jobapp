<?php
// Start the session
session_start();

// Check if user is logged in, otherwise redirect to login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: adminlogin.php');
    exit;
}

// Database connection
require_once 'config.php';

// Initialize variables for counts
$totalApplicants = 0;
$pendingCount = 0;
$teachingCount = 0;
$nonTeachingCount = 0;
$acceptedCount = 0;
$rejectedCount = 0;

// Function to log errors
function logError($message, $sql = '', $params = []) {
    $logFile = 'error_log.txt';
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[{$timestamp}] ERROR: {$message}\n";
    
    if (!empty($sql)) {
        $logMessage .= "SQL: {$sql}\n";
    }
    
    if (!empty($params)) {
        $logMessage .= "Params: " . print_r($params, true) . "\n";
    }
    
    $logMessage .= "----------------------------------------\n";
    
    // Write to log file
    error_log($logMessage, 3, $logFile);
    
    // Also return the error message for display
    return "An error occurred. Check error_log.txt for details.";
}

// Initialize applicants array
$applicants = [];

try {
    // Get counts for summary boxes
    $countQuery = "SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
        SUM(CASE WHEN job_category = 'Teaching' THEN 1 ELSE 0 END) as teaching,
        SUM(CASE WHEN job_category = 'Non-Teaching' THEN 1 ELSE 0 END) as non_teaching,
        SUM(CASE WHEN status = 'accepted' THEN 1 ELSE 0 END) as accepted,
        SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected
        FROM applicants";
    
    $countStmt = $conn->query($countQuery);
    $countData = $countStmt->fetch(PDO::FETCH_ASSOC);
    
    $totalApplicants = $countData['total'] ?? 0;
    $pendingCount = $countData['pending'] ?? 0;
    $teachingCount = $countData['teaching'] ?? 0; 
    $nonTeachingCount = $countData['non_teaching'] ?? 0;
    $acceptedCount = $countData['accepted'] ?? 0;
    $rejectedCount = $countData['rejected'] ?? 0;

    // Fetch data for the line graph (applications by date)
    try {
        $graphQuery = "SELECT 
            DATE(application_date) as application_date,
            COUNT(*) as total_applications,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN status = 'accepted' THEN 1 ELSE 0 END) as accepted,
            SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected
            FROM applicants
            GROUP BY DATE(application_date)
            ORDER BY DATE(application_date) DESC
            LIMIT 30"; // Last 30 days
            
        $graphStmt = $conn->query($graphQuery);
        $graphData = $graphStmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Prepare data for Chart.js
        $labels = [];
        $totalData = [];
        $pendingData = [];
        $acceptedData = [];
        $rejectedData = [];
        
        // Reverse to show chronological order
        $graphData = array_reverse($graphData);
        
        foreach ($graphData as $row) {
            $labels[] = date('M j', strtotime($row['application_date']));
            $totalData[] = $row['total_applications'];
            $pendingData[] = $row['pending'];
            $acceptedData[] = $row['accepted'];
            $rejectedData[] = $row['rejected'];
        }
            
    } catch (PDOException $e) {
        $graphError = logError("Failed to load graph data: " . $e->getMessage(), $graphQuery);
    }
    
    // Fetch all applications with basic ordering
    $query = "SELECT id, full_name, email, job_category, job_position, application_date, status FROM applicants ORDER BY application_date DESC";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $applicants = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    $error = logError("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
        }
        .dashboard-header {
            background-color: #0d6efd;
            color: white;
            padding: 20px 0;
            margin-bottom: 30px;
        }
        .summary-card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            margin-bottom: 20px;
            border: none;
        }
        .summary-card:hover {
            transform: translateY(-5px);
        }
        .card-teaching {
            border-left: 5px solid #20c997;
        }
        .card-non-teaching {
            border-left: 5px solid #6f42c1;
        }
        .card-pending {
            border-left: 5px solid #fd7e14;
        }
        .card-accepted {
            border-left: 5px solid #198754;
        }
        .card-rejected {
            border-left: 5px solid #dc3545;
        }
        .applications-table {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow-x: auto;
            padding: 20px;
        }
        .table th {
            background-color: #f8f9fa;
            position: sticky;
            top: 0;
        }
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        .badge-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .badge-accepted {
            background-color: #d4edda;
            color: #155724;
        }
        .badge-rejected {
            background-color: #f8d7da;
            color: #721c24;
        }
        .action-btn {
            padding: 5px 10px;
            font-size: 0.8rem;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .card-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .chart-container {
            min-height: 300px;
        }
        @media (max-width: 768px) {
            .summary-card {
                margin-bottom: 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="dashboard-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">
                    <i class="bi bi-speedometer2"></i> Admin Dashboard
                </h1>
                <a href="logout.php" class="btn btn-light">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </div>
    </header>

    <div class="container">
        <!-- Summary Cards -->
        <div class="row">
            <div class="col-md-4 col-lg-2">
                <div class="card summary-card">
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2 text-muted">Total Applicants</h6>
                        <h3 class="card-title"><?= $totalApplicants ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-2">
                <div class="card summary-card card-pending">
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2 text-muted">Pending</h6>
                        <h3 class="card-title"><?= $pendingCount ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-2">
                <div class="card summary-card card-teaching">
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2 text-muted">Teaching</h6>
                        <h3 class="card-title"><?= $teachingCount ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-2">
                <div class="card summary-card card-non-teaching">
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2 text-muted">Non-Teaching</h6>
                        <h3 class="card-title"><?= $nonTeachingCount ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-2">
                <div class="card summary-card card-accepted">
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2 text-muted">Accepted</h6>
                        <h3 class="card-title"><?= $acceptedCount ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-2">
                <div class="card summary-card card-rejected">
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2 text-muted">Rejected</h6>
                        <h3 class="card-title"><?= $rejectedCount ?></h3>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Trend Graph Section -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="bi bi-graph-up"></i> Application Trends (Last 30 Days)
                </h5>
                <?php if (isset($graphError)): ?>
                    <div class="alert alert-warning"><?= $graphError ?></div>
                <?php else: ?>
                    <div class="chart-container" style="position: relative; height:300px;">
                        <canvas id="trendChart"></canvas>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Applications Table -->
        <div class="applications-table">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mb-0">Applications</h3>
                <div>
                    <a href="export.php" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-download"></i> Export
                    </a>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Category</th>
                            <th>Position</th>
                            <th>Status</th>
                            <th>Date Applied</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($applicants)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-4">No applicants found</td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($applicants as $applicant): ?>
                            <tr>
                                <td><?= htmlspecialchars($applicant['id']) ?></td>
                                <td><?= htmlspecialchars($applicant['full_name']) ?></td>
                                <td><?= htmlspecialchars($applicant['email']) ?></td>
                                <td><?= htmlspecialchars($applicant['job_category']) ?></td>
                                <td><?= htmlspecialchars($applicant['job_position'] ?? 'N/A') ?></td>
                                <td>
                                    <span class="status-badge badge-<?= strtolower($applicant['status'] ?? 'pending') ?>">
                                        <?= ucfirst(htmlspecialchars($applicant['status'] ?? 'pending')) ?>
                                    </span>
                                </td>
                                <td><?= date('M d, Y', strtotime($applicant['application_date'])) ?></td>
                                <td>
                                    <a href="view_applicant.php?id=<?= $applicant['id'] ?>" class="btn btn-sm btn-primary action-btn">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Line Chart Configuration
            const ctx = document.getElementById('trendChart').getContext('2d');
            const trendChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: <?= json_encode($labels ?? []) ?>,
                    datasets: [
                        {
                            label: 'Total Applications',
                            data: <?= json_encode($totalData ?? []) ?>,
                            borderColor: '#0d6efd',
                            backgroundColor: 'rgba(13, 110, 253, 0.1)',
                            tension: 0.3,
                            fill: true
                        },
                        {
                            label: 'Pending',
                            data: <?= json_encode($pendingData ?? []) ?>,
                            borderColor: '#fd7e14',
                            backgroundColor: 'rgba(253, 126, 20, 0.1)',
                            tension: 0.3,
                            fill: false
                        },
                        {
                            label: 'Accepted',
                            data: <?= json_encode($acceptedData ?? []) ?>,
                            borderColor: '#198754',
                            backgroundColor: 'rgba(25, 135, 84, 0.1)',
                            tension: 0.3,
                            fill: false
                        },
                        {
                            label: 'Rejected',
                            data: <?= json_encode($rejectedData ?? []) ?>,
                            borderColor: '#dc3545',
                            backgroundColor: 'rgba(220, 53, 69, 0.1)',
                            tension: 0.3,
                            fill: false
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    },
                    interaction: {
                        mode: 'nearest',
                        axis: 'x',
                        intersect: false
                    }
                }
            });
        });
    </script>
</body>
</html>