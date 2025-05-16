<?php
session_start();

if (empty($_SESSION['success'])) {
    header("Location: index.php");
    exit();
}

$message = $_SESSION['success'];
unset($_SESSION['success']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Application Complete</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="alert alert-success">
            <?= htmlspecialchars($message) ?>
        </div>
        <a href="index.php" class="btn btn-primary">Return Home</a>
    </div>
</body>
</html>