<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
} else {
    header("Location: ../index.php");
    exit();
}

if ($_SESSION["user_type"] == "editor") {
    header("Location: ../../dashboard.php");
    exit();
}