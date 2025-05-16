<?php
require_once 'db_connection.php';

// Set error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log incoming data for debugging
$debug_log = 'application_debug.log';
file_put_contents($debug_log, "---- New Request at " . date('Y-m-d H:i:s') . " ----\n", FILE_APPEND);
file_put_contents($debug_log, "POST Data: " . print_r($_POST, true) . "\n", FILE_APPEND);
file_put_contents($debug_log, "FILES Data: " . print_r($_FILES, true) . "\n", FILE_APPEND);

// Set upload directory with proper permissions
$uploadDir = 'uploads/job_applications/';
if (!file_exists($uploadDir)) {
    if (!mkdir($uploadDir, 0755, true)) {
        file_put_contents($debug_log, "Failed to create upload directory\n", FILE_APPEND);
        die("Failed to create upload directory");
    }
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        file_put_contents($debug_log, "Processing POST request\n", FILE_APPEND);
        
        // Check if form data exists
        if (empty($_POST)) {
            file_put_contents($debug_log, "ERROR: No POST data received\n", FILE_APPEND);
            throw new Exception("No form data received. Please try again.");
        }
        
        // Validate and sanitize input data
        $requiredFields = [
            'full_name', 'email', 'contact_number', 'job_category', 'job_position',
            'elementary_school', 'high_school', 'senior_high', 'college',
            'company1', 'position1', 'duration1'
        ];

        foreach ($requiredFields as $field) {
            if (!isset($_POST[$field]) || trim($_POST[$field]) === '') {
                file_put_contents($debug_log, "ERROR: Required field '$field' is missing or empty\n", FILE_APPEND);
                throw new Exception("Required field '$field' is missing or empty");
            }
        }

        // Initialize database connection
        $db = Database::getInstance();
        $db->beginTransaction();
        
        file_put_contents($debug_log, "Database connection established, transaction started\n", FILE_APPEND);

        // Basic Information
        $full_name = htmlspecialchars(trim($_POST['full_name']));
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {   
            throw new Exception("Invalid email address");
        }
        
        $contact_number = preg_replace('/[^0-9]/', '', $_POST['contact_number']);
        if (strlen($contact_number) < 10) {
            throw new Exception("Invalid contact number");
        }
        
        $job_category = $_POST['job_category'];
        if (!in_array($job_category, ['teaching', 'non-teaching'])) {
            throw new Exception("Invalid job category");
        }
            
        $job_position = htmlspecialchars(trim($_POST['job_position']));

        // Educational Background
        $elementary = htmlspecialchars(trim($_POST['elementary_school']));
        $high_school = htmlspecialchars(trim($_POST['high_school']));
        $senior_high = htmlspecialchars(trim($_POST['senior_high']));
        $college = htmlspecialchars(trim($_POST['college']));

        // Work Experience
        $company1 = htmlspecialchars(trim($_POST['company1']));
        $position1 = htmlspecialchars(trim($_POST['position1']));
        $duration1 = (int)$_POST['duration1'];
        $company2 = isset($_POST['company2']) ? htmlspecialchars(trim($_POST['company2'])) : null;
        $position2 = isset($_POST['position2']) ? htmlspecialchars(trim($_POST['position2'])) : null;
        $duration2 = isset($_POST['duration2']) ? (int)$_POST['duration2'] : null;

        // FIRST: Insert into job_applications table (same as original code)
        $sql = "INSERT INTO job_applications (
            full_name, email, contact_number, job_category, job_position,
            elementary_school, high_school, senior_high, college,
            company1, position1, duration1, company2, position2, duration2,
            status
        ) VALUES (
            ?, ?, ?, ?, ?,
            ?, ?, ?, ?,
            ?, ?, ?, ?, ?, ?,
            'pending'
        )";
        
        file_put_contents($debug_log, "Preparing SQL statement for job_applications\n", FILE_APPEND);
        file_put_contents($debug_log, "SQL: $sql\n", FILE_APPEND);
        
        // Using PDO directly for better error handling
        try {
            $stmt = $db->getConnection()->prepare($sql);
            $stmt->execute([
                $full_name, $email, $contact_number, $job_category, $job_position,
                $elementary, $high_school, $senior_high, $college,
                $company1, $position1, $duration1, $company2, $position2, $duration2
            ]);
            
            $application_id = $db->getConnection()->lastInsertId();
            file_put_contents($debug_log, "Application inserted with ID: $application_id\n", FILE_APPEND);
            
            if (!$application_id) {
                throw new Exception("Failed to get application ID after insert");
            }
        } catch (PDOException $e) {
            file_put_contents($debug_log, "Database error: " . $e->getMessage() . "\n", FILE_APPEND);
            throw new Exception("Database error: " . $e->getMessage());
        }
        
        // SECOND: Also insert into applicants table 
        $applicant_sql = "INSERT INTO applicants (
            full_name, email, contact_number, job_category, job_position, status
        ) VALUES (
            ?, ?, ?, ?, ?, 'pending'
        )";
        
        file_put_contents($debug_log, "Preparing SQL statement for applicants table\n", FILE_APPEND);
        file_put_contents($debug_log, "SQL: $applicant_sql\n", FILE_APPEND);
        
        try {
            $stmt = $db->getConnection()->prepare($applicant_sql);
            $stmt->execute([
                $full_name, $email, $contact_number, $job_category, $job_position
            ]);
            
            $applicant_id = $db->getConnection()->lastInsertId();
            file_put_contents($debug_log, "Applicant inserted with ID: $applicant_id\n", FILE_APPEND);
            
            if (!$applicant_id) {
                throw new Exception("Failed to get applicant ID after insert");
            }
        } catch (PDOException $e) {
            file_put_contents($debug_log, "Database error on applicants table: " . $e->getMessage() . "\n", FILE_APPEND);
            throw new Exception("Database error: " . $e->getMessage());
        }

        // Process file uploads
        $documentTypes = [
            'resume' => [
                'required' => true, 
                'types' => ['application/pdf'],
                'max_size' => 5 * 1024 * 1024 // 5MB
            ],
            'cover_letter' => [
                'required' => true, 
                'types' => ['application/pdf'],
                'max_size' => 5 * 1024 * 1024
            ],
            'terms_of_reference' => [
                'required' => true, 
                'types' => ['application/pdf'],
                'max_size' => 5 * 1024 * 1024
            ],
            'eligibility' => [
                'required' => true, 
                'types' => ['application/pdf', 'image/jpeg', 'image/png'],
                'max_size' => 5 * 1024 * 1024
            ]
        ];

        foreach ($documentTypes as $type => $requirements) {
            file_put_contents($debug_log, "Processing document: $type\n", FILE_APPEND);
            
            if ($requirements['required'] && (!isset($_FILES[$type]) || $_FILES[$type]['error'] !== UPLOAD_ERR_OK)) {
                file_put_contents($debug_log, "Required file $type is missing or has errors\n", FILE_APPEND);
                throw new Exception("$type is required and must be a valid file");
            }

            if (isset($_FILES[$type]) && $_FILES[$type]['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES[$type];
                
                // Validate file upload
                if ($file['error'] !== UPLOAD_ERR_OK) {
                    $upload_errors = [
                        UPLOAD_ERR_INI_SIZE => "File exceeds maximum upload size defined in php.ini",
                        UPLOAD_ERR_FORM_SIZE => "File exceeds maximum size specified in the form",
                        UPLOAD_ERR_PARTIAL => "File was only partially uploaded",
                        UPLOAD_ERR_NO_FILE => "No file was uploaded",
                        UPLOAD_ERR_NO_TMP_DIR => "Missing temporary folder",
                        UPLOAD_ERR_CANT_WRITE => "Failed to write file to disk",
                        UPLOAD_ERR_EXTENSION => "A PHP extension stopped the file upload"
                    ];
                    $error_message = isset($upload_errors[$file['error']]) ? 
                                     $upload_errors[$file['error']] : 
                                     "Unknown upload error";
                    file_put_contents($debug_log, "File upload error for $type: $error_message\n", FILE_APPEND);
                    throw new Exception("File upload error for $type: $error_message");
                }

                // Validate file type
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $detected_type = finfo_file($finfo, $file['tmp_name']);
                finfo_close($finfo);
                
                file_put_contents($debug_log, "File $type detected type: $detected_type\n", FILE_APPEND);

                if (!in_array($detected_type, $requirements['types'])) {
                    file_put_contents($debug_log, "Invalid file type for $type: $detected_type\n", FILE_APPEND);
                    throw new Exception("Invalid file type for $type. Only " . implode(', ', $requirements['types']) . " allowed.");
                }

                // Validate file size
                if ($file['size'] > $requirements['max_size']) {
                    $max_size_mb = $requirements['max_size'] / (1024 * 1024);
                    file_put_contents($debug_log, "File too large for $type: {$file['size']} bytes\n", FILE_APPEND);
                    throw new Exception("File too large for $type (max {$max_size_mb}MB)");
                }

                // Generate secure filename
                $file_ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $file_basename = bin2hex(random_bytes(8)); // Random name for security
                $new_filename = "app_{$application_id}_{$type}_{$file_basename}.{$file_ext}";
                $file_path = $uploadDir . $new_filename;
                
                file_put_contents($debug_log, "Generated filename: $new_filename\n", FILE_APPEND);

                // Move uploaded file
                if (!move_uploaded_file($file['tmp_name'], $file_path)) {
                    file_put_contents($debug_log, "Failed to move uploaded file from {$file['tmp_name']} to $file_path\n", FILE_APPEND);
                    throw new Exception("Failed to move uploaded file");
                }

                // Set proper permissions
                chmod($file_path, 0644);
                
                file_put_contents($debug_log, "File moved successfully to $file_path\n", FILE_APPEND);

                // Insert document record
                try {
                    $doc_sql = "INSERT INTO application_documents (
                        application_id, document_type, file_name, file_path, file_type, file_size
                    ) VALUES (?, ?, ?, ?, ?, ?)";
                    
                    $stmt = $db->getConnection()->prepare($doc_sql);
                    $stmt->execute([
                        $application_id, 
                        $type, 
                        $file['name'], 
                        $file_path, 
                        $detected_type, 
                        $file['size']
                    ]);
                    
                    file_put_contents($debug_log, "Document record inserted for $type\n", FILE_APPEND);
                } catch (PDOException $e) {
                    file_put_contents($debug_log, "Failed to insert document record: " . $e->getMessage() . "\n", FILE_APPEND);
                    throw new Exception("Failed to save document information: " . $e->getMessage());
                }
            }
        }

        $db->commit();
        file_put_contents($debug_log, "Transaction committed successfully\n", FILE_APPEND);
        
        // Send confirmation email (optional)
        // $this->sendConfirmationEmail($email, $full_name);
        
        // Redirect to success page
        file_put_contents($debug_log, "Processing complete, redirecting to success page\n", FILE_APPEND);
        header("Location: application_complete.php");
        exit();
        
    } catch (Exception $e) {
        if (isset($db)) {
            $db->rollback();
            file_put_contents($debug_log, "Transaction rolled back due to error\n", FILE_APPEND);
        }
        
        // Log the detailed error
        file_put_contents($debug_log, "ERROR: " . $e->getMessage() . "\n", FILE_APPEND);
        error_log("Application Error: " . $e->getMessage());
        
        // Display error message for debugging
        $errorMessage = "We encountered an error processing your application: " . $e->getMessage();
        file_put_contents($debug_log, "Displaying error to user: $errorMessage\n", FILE_APPEND);
        
        echo "<div style='max-width: 800px; margin: 50px auto; padding: 20px; background-color: #f8d7da; border: 1px solid #f5c6cb; border-radius: 5px;'>";
        echo "<h2>Application Error</h2>";
        echo "<p>$errorMessage</p>";
        echo "<p>Please go back and try again. If the problem persists, contact support.</p>";
        echo "<a href='javascript:history.back()' class='btn btn-primary'>Go Back</a>";
        echo "</div>";
        exit();
    }
} else {
    // Not a POST request
    file_put_contents($debug_log, "Not a POST request, redirecting to form page\n", FILE_APPEND);
    header("Location: simplified-form.html");
    exit();
}
?>