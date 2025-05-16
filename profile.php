<?php
require_once 'config.php';


$isGuest = isset($_SESSION['guest']) && !isLoggedIn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $isGuest ? "Guest Profile" : "User Profile"; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .profile-card {
            max-width: 600px;
            margin: 30px auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .profile-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="profile-card">
            <div class="profile-header">
                <img src="<?php echo $isGuest ? 'default-avatar.jpg' : 'logo.png'; ?>" 
                     alt="Profile Image" class="profile-avatar">
                <h1><?php? "Guest User" :['email']; ?></h1>
                <p class="text-muted"><?php echo $isGuest ? "Guest Account" : "Member Account"; ?></p>
            </div>
            
            <?php if ($isGuest): ?>
                <div class="alert alert-info">
                    <h4>Guest Access</h4>
                    <p>You're viewing this profile as a guest. Some features may be limited.</p>
                    <a href="login.php" class="btn btn-primary">Sign In to Access Full Profile</a>
                    <a href="register.php" class="btn btn-outline-secondary ms-2">Create Account</a>
                </div>

            <?php else: ?>
                <div class="profile-details">
                    <h3>Account Details</h3>
                    <ul class="list-group mb-4">
                        <li class="list-group-item"><strong>Email:</strong></li>
                        <li class="list-group-item"><strong>Member Since:</strong> </li>
                        <li class="list-group-item"><strong>Last Login:</strong></li>
                    </ul>
                    
                    <h3>Profile Actions</h3>
                    <div class="d-grid gap-2">
                        <a href="edit-profile.php" class="btn btn-outline-primary">Edit Profile</a>
                        <a href="change-password.php" class="btn btn-outline-secondary">Change Password</a>
                        <a href="index.php" class="btn btn-danger mt-3">Back to Homepage</a>
                    </div>
                </div>
            <?php endif; ?>
            
            <div class="mt-4">
                <h4>Public Content</h4>
                <p>This content is visible to all users, whether logged in or not.</p>
                <!-- Add your public content here -->
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
