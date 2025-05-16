<?php
require 'config.php';
session_start();

// Initialize variables
$errors = [];
$formData = [
    'email' => '',
    'password' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $formData = [
        'email' => trim($_POST['email']),
        'password' => $_POST['password']
    ];

    // Validate data
    if (empty($formData['email'])) $errors[] = "Email is required";
    if (empty($formData['password'])) $errors[] = "Password is required";

    if (empty($errors)) {
        // Check if user exists
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
        $stmt->execute([$formData['email']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            // Verify password (plain text comparison for development)
            if ($formData['password'] === $user['password']) {
                // Set session and redirect
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header("Location: index.php");
                exit();
            } else {
                $errors[] = "Invalid email or password";
            }
        } else {
            $errors[] = "Invalid email or password";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-card {
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border: none;
        }
        .card-body {
            padding: 2.5rem;
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
        .input-group-text {
            border-radius: 8px 0 0 8px !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card login-card">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <img src="logo.png" alt="Company Logo" height="50" class="mb-3">
                            <h3 class="mb-2">Welcome Back</h3>
                            <p class="text-muted">Sign in to your account</p>
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

                        <form method="POST" action="login.php" id="loginForm">
                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?= htmlspecialchars($formData['email']) ?>" 
                                           required placeholder="your@email.com">
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
                                <div class="text-end mt-2">
                                    <a href="forgot-password.php" class="text-decoration-none">Forgot password?</a>
                                </div>
                            </div>

                            <!-- Remember Me Checkbox -->
                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                <label class="form-check-label" for="remember">
                                    Remember me
                                </label>
                            </div>
                            
                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary w-100 py-2">Sign In</button>
                            
                            <!-- Register Link -->
                            <div class="text-center mt-3">
                                <p class="text-muted mb-0">Don't have an account? <a href="register.php" class="text-decoration-none fw-semibold">Register</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form validation
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            let isValid = true;

            if (!email.value) {
                email.classList.add('is-invalid');
                isValid = false;
            } else {
                email.classList.remove('is-invalid');
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
        document.getElementById('email').addEventListener('input', function() {
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