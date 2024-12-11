<?php
session_start();
$type = $_GET["type"];
if (!empty($type)) {
    $_SESSION["auth_type"] = ($type == "login") ? "register" : "login";
    $backUrl = $_SERVER["HTTP_REFERER"] ?? "/portal/index.php";
    header("Location: $backUrl");
    exit();
} else {
    $backUrl = $_SERVER["HTTP_REFERER"] ?? "/portal/index.php";
    header("Location: $backUrl");
    exit();
}
