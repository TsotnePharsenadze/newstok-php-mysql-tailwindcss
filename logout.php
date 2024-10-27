<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST["csrf_token"] == $_SESSION["csrf_token"]) {
        unset($_SESSION["token"]);
        header("Location: index.php");
        exit;
    } else {
        $_SESSION["logout_error"] = "Incorrect csrf_token";
        Header("Location: chat.php");
        exit;
    }
} else {
    $_SESSION["logout_error"] = "Incorrect request method for logout";
    Header("Location: chat.php");
    exit;
}