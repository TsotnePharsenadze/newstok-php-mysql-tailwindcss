<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
} else {
    $_SESSION = array();
    header("Location: login.php");
    exit();
}