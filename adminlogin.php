<?php
require 'config.php';
session_start();

// Initialize variables
$errors = [];
$formData = [
    'username' => '',
    'password' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $formData = [
        'username' => trim($_POST['username']),
        'password' => $_POST['password']
    ];

    // Validate data
    if (empty($formData['username'])) $errors[] = "Username is required";
    if (empty($formData['password'])) $errors[] = "Password is required";

    if (empty($errors)) {
        // Check admin credentials
        if ($formData['username'] === 'admin' && $formData['password'] === 'admin123') {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['username'] = 'admin';
            header("Location: dashboard.php");
            exit();
        } else {
            $errors[] = "Invalid username or password";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .admin-login-container {
            width: 100%;
            max-width: 500px;
            padding: 15px;
            margin: auto;
        }
        .admin-login-card {
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border: none;
        }
        .card-body {
            padding: 2rem;
        }
        .form-control {
            height: 45px;
            border-radius: 8px;
        }
        .btn-primary {
            height: 45px;
            border-radius: 8px;
            font-weight: 600;
        }
        .admin-header {
            border-bottom: 2px solid #dc3545;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        @media (max-width: 576px) {
            .card-body {
                padding: 1.5rem;
            }
            .admin-login-container {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="admin-login-container">
        <div class="card admin-login-card">
            <div class="card-body">
                <div class="text-center mb-4 admin-header">
                    <img src="logo.png" alt="Company Logo" height="50" class="mb-3">
                    <h3 class="mb-2 text-danger">Admin Dashboard</h3>
                    <p class="text-muted">Restricted access - authorized personnel only</p>
                </div>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger alert-dismissible fade show mb-4">
                        <ul class="mb-0 ps-3">
                            <?php foreach ($errors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form method="POST" id="adminLoginForm">
                    <!-- Username -->
                    <div class="mb-3">
                        <label for="username" class="form-label">Admin Username</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user-shield"></i></span>
                            <input type="text" class="form-control" id="username" name="username" 
                                   value="<?= htmlspecialchars($formData['username']) ?>" 
                                   required placeholder="Enter admin username">
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control" id="password" name="password" 
                                   required placeholder="••••••••">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-danger w-100 py-2 mt-3">
                        <i class="fas fa-sign-in-alt me-2"></i> Login
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form validation
        document.getElementById('adminLoginForm').addEventListener('submit', function(e) {
            const username = document.getElementById('username');
            const password = document.getElementById('password');
            let isValid = true;

            if (!username.value) {
                username.classList.add('is-invalid');
                isValid = false;
            } else {
                username.classList.remove('is-invalid');
            }

            if (!password.value) {
                password.classList.add('is-invalid');
                isValid = false;
            } else {
                password.classList.remove('is-invalid');
            }

            if (!isValid) {
                e.preventDefault();
            }
        });

        // Remove invalid class when user starts typing
        document.getElementById('username').addEventListener('input', function() {
            if (this.value) {
                this.classList.remove('is-invalid');
            }
        });

        document.getElementById('password').addEventListener('input', function() {
            if (this.value) {
                this.classList.remove('is-invalid');
            }
        });
    </script>
</body>
</html>