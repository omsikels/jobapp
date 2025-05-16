<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: jobapp/login.php");
    exit;
}
?>
<!-- Applications page HTML -->