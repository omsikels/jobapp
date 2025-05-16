<?php
require 'config.php';
session_start();

// Initialize variables
$errors = [];
$formData = [
    'username' => '',
    'full_name' => '',
    'email' => '',
    'password' => '',
    'confirm_password' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $formData = [
        'username' => trim($_POST['username']),
        'full_name' => trim($_POST['full_name']),
        'email' => trim($_POST['email']),
        'password' => $_POST['password'],
        'confirm_password' => $_POST['confirm_password']
    ];

    // Validate data
    if (empty($formData['username'])) $errors[] = "Username is required";
    if (empty($formData['full_name'])) $errors[] = "Full name is required";
    if (empty($formData['email'])) $errors[] = "Email is required";
    if (empty($formData['password'])) $errors[] = "Password is required";
    if ($formData['password'] !== $formData['confirm_password']) $errors[] = "Passwords don't match";

    if (empty($errors)) {
        // Check if user exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$formData['username'], $formData['email']]);
        
        if ($stmt->rowCount() > 0) {
            $errors[] = "Username or email already exists";
        } else {
            // Insert new user (with plain text password - for development only)
            $stmt = $conn->prepare("INSERT INTO users (username, email, full_name, password) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $formData['username'],
                $formData['email'],
                $formData['full_name'],
                $formData['password'] 
            ]);
            
            // Set session and redirect
            $_SESSION['user_id'] = $conn->lastInsertId();
            $_SESSION['username'] = $formData['username'];
            header("Location: index.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Job Application System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px 0;
        }
        .register-card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: none;
        }
        .card-body {
            padding: 2.5rem;
        }
        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        .input-group-text {
            width: 42px;
            justify-content: center;
        }
        .password-strength {
            height: 5px;
            margin-top: 5px;
            background: #e9ecef;
            border-radius: 3px;
            overflow: hidden;
        }
        .password-strength-bar {
            height: 100%;
            width: 0%;
            transition: width 0.3s, background-color 0.3s;
        }
        .form-control {
            padding: 0.75rem 0.75rem;
            border-left: 0;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #ced4da;
        }
        .btn-primary {
            padding: 0.75rem;
            font-weight: 600;
            margin-top: 1rem;
        }
        .text-muted {
            font-size: 0.9rem;
        }
        .invalid-feedback {
            font-size: 0.8rem;
        }
        .terms-link {
            text-decoration: underline;
        }
        @media (max-width: 767.98px) {
            .card-body {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card register-card">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <img src="logo.png" alt="Company Logo" height="50" class="mb-3">
                            <h3 class="mb-2">Create Account</h3>
                            <p class="text-muted">Fill in your details to register</p>
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

                        <form method="POST" action="register.php" id="registrationForm" novalidate>
                            <!-- Username -->
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" id="username" name="username" required placeholder="johndoe">
                                </div>
                                <small class="text-muted">3-20 characters</small>
                            </div>

                            <!-- Full Name -->
                            <div class="mb-3">
                                <label for="full_name" class="form-label">Full Name</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                    <input type="text" class="form-control" id="full_name" name="full_name" placeholder="John Doe">
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="your@email.com">
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="password" name="password" 
                                           required placeholder="••••••••" minlength="8">
                                </div>
                                <div class="password-strength mt-2">
                                    <div class="password-strength-bar" id="passwordStrengthBar"></div>
                                </div>
                                <small class="text-muted">Minimum 8 characters with uppercase, lowercase, and number</small>
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="confirm_password" 
                                           name="confirm_password" required placeholder="••••••••">
                                </div>
                                <div class="invalid-feedback" id="passwordMatchFeedback">Passwords don't match</div>
                            </div>

                            <!-- Terms Checkbox -->
                            <div class="form-check mt-3 mb-4">
                                <input type="checkbox" class="form-check-input" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    I agree to the <a href="#" class="terms-link" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and Conditions</a>
                                </label>
                                <div class="invalid-feedback">You must agree to the terms</div>
                            </div>
                            
                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary w-100 py-2">Register</button>
                            
                            <!-- Login Link -->
                            <div class="text-center mt-3">
                                <p class="text-muted mb-0">Already have an account? <a href="login.php" class="text-decoration-none fw-semibold">Sign in</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Terms Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Terms and Conditions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Your terms and conditions content goes here...</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">I Understand</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Password strength indicator
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('passwordStrengthBar');
            let strength = 0;
            
            // Length check
            if (password.length >= 8) strength += 1;
            // Lowercase check
            if (password.match(/[a-z]/)) strength += 1;
            // Uppercase check
            if (password.match(/[A-Z]/)) strength += 1;
            // Number check
            if (password.match(/[0-9]/)) strength += 1;
            // Special char check
            if (password.match(/[^a-zA-Z0-9]/)) strength += 1;
            
            const width = (strength / 5) * 100;
            strengthBar.style.width = width + '%';
            
            // Color coding
            if (strength < 2) {
                strengthBar.style.backgroundColor = '#dc3545'; // Red
            } else if (strength < 4) {
                strengthBar.style.backgroundColor = '#fd7e14'; // Orange
            } else {
                strengthBar.style.backgroundColor = '#28a745'; // Green
            }
        });
        
        // Password match validation
        function validatePasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const confirmField = document.getElementById('confirm_password');
            
            if (password && confirmPassword && password !== confirmPassword) {
                confirmField.classList.add('is-invalid');
                return false;
            } else {
                confirmField.classList.remove('is-invalid');
                return true;
            }
        }
        
        document.getElementById('confirm_password').addEventListener('input', validatePasswordMatch);
        document.getElementById('password').addEventListener('input', validatePasswordMatch);
        
        // Form validation
        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            // Validate password match
            if (!validatePasswordMatch()) {
                e.preventDefault();
                document.getElementById('passwordMatchFeedback').textContent = 'Passwords do not match';
            }
            
            // Validate terms checkbox
            const terms = document.getElementById('terms');
            if (!terms.checked) {
                e.preventDefault();
                terms.classList.add('is-invalid');
            }
        });
        
        // Terms checkbox validation
        document.getElementById('terms').addEventListener('change', function() {
            if (this.checked) {
                this.classList.remove('is-invalid');
            }
        });
    </script>
</body>
</html>