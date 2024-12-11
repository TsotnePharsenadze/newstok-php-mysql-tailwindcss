<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: portal/login.php");
    exit();
} else {
    header("Location: portal/dashboard.php");
    exit();
}