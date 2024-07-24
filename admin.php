<?php
include 'db_config.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'];

if ($role == 'admin') {
    header("Location: admin.php");
} else {
    header("Location: user.php");
}
?>
