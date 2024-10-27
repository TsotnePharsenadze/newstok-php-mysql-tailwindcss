<?php
include("./db.php");
session_start();

if ($_SESSION["token"]) {
    Header("Location: chat.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (
        isset($_POST["email"]) &&
        isset($_POST["password"]) &&
        isset($_POST["csrf_token"]) &&
        $_POST["csrf_token"] == $_SESSION["csrf_token"]
    ) {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $_POST["email"]);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if ($user) {
                if (password_verify($_POST["password"], $user["password"])) {
                    $token = bin2hex(random_bytes(32));
                    $_SESSION["token"] = $token;

                    $sql = "UPDATE users SET token = ? WHERE email = ?";
                    $stmt2 = $conn->prepare($sql);
                    $stmt2->bind_param("ss", $token, $_POST["email"]);

                    if ($stmt2->execute()) {
                        header("Location: chat.php");
                        exit;
                    }
                } else {
                    $_SESSION["auth_error"] = "Invalid credentials";
                    header("Location: index.php");
                    exit;
                }
            } else {
                $_SESSION["auth_error"] = "Invalid credentials";
                header("Location: index.php");
                exit;
            }
        }
    } else {
        if (empty($_POST["email"])) {
            $_SESSION["auth_email_error"] = "email field cannot be empty";
        }
        if (empty($_POST["password"])) {
            $_SESSION["auth_password_error"] = "Password field cannot be empty";
        }
        if (empty($_POST["csrf_token"])) {
            $_SESSION["auth_error"] = "CSRF token is missing";
        } elseif ($_POST["csrf_token"] != $_SESSION["csrf_token"]) {
            $_SESSION["auth_error"] = "Invalid CSRF token";
        }
        header("Location: index.php");
        exit;
    }
} else {
    $_SESSION["auth_error"] = "Invalid request method";
    header("Location: index.php");
    exit;
}
