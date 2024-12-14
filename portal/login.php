<?php
session_start();
include('../db/db.php');

if (isset($_SESSION["user_id"])) {
    header("Location: dashboard.php");
    exit();
}

$err;
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['user_type'] = $user['type'];
            $_SESSION["username"] = $user["username"];
            $_SESSION["display_name"] = $user["display_name"];
            header("Location: dashboard.php");
        } else {
            $err = "Incorrect password.";
        }
    } else {
        $err = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NewsTok - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <header class="bg-blue-600 text-white py-4 shadow-md">
        <div class="container mx-auto px-4 flex flex-col sm:flex-row items-center justify-between items-center">
            <div class="flex justify-between items-center w-full">
                <h1 class="text-2xl font-bold">NewsTok</h1>
                <button id="menu-button" class="sm:hidden text-white text-2xl focus:outline-none">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>
            <nav id="menu" class="hidden sm:flex items-center space-x-4 sm:space-y-0 sm:flex-row flex-col">
                <a href="#" class="hover:underline">Home</a>
                <a href="#" class="hover:underline">World</a>
                <a href="#" class="hover:underline">Politics</a>
                <a href="#" class="hover:underline">Technology</a>
                <a href="#" class="hover:underline">Sports</a>
                <div class="border flex items-center rounded-md">
                    <a href="portal/login.php" class="hover:bg-blue-400 p-2 border-r">Login</a>
                    <a href="portal/register.php" class="hover:bg-blue-400 p-2">Register</a>
                </div>
            </nav>
        </div>
    </header>
    <div class="h-[80vh] flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-sm w-full">
            <h2 class="text-2xl font-bold text-center mb-6">Login</h2>
            <form action="login.php" method="POST">
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" class="w-full p-2 border border-gray-300 rounded-md"
                        required>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="password"
                        class="w-full p-2 border border-gray-300 rounded-md" required>
                </div>
                <button type="submit" name="login"
                    class="w-full p-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Login</button>
            </form>
            <p class="text-center text-red-400 mt-2 mb-2"><?php if (!empty($err))
                echo $err; ?></p>
            <hr />
            <p class="mt-2">
                Don't have an account?
                <a href="register.php" class="text-blue-600 hover:underline">Register here</a>
            </p>
        </div>
    </div>

    <script>
        const menuButton = document.getElementById('menu-button');
        const menu = document.getElementById('menu');
        menuButton.addEventListener('click', () => {
            menu.classList.toggle('hidden');
            if (menu.classList.contains("hidden")) {
                menu.classList.remove("mt-2", "w-full", "flex", "items-baseline", "gap-2", "border", "p-2", "flex-col");
            } else {
                menu.classList.add("mt-2", "w-full", "flex", "items-baseline", "gap-2", "border", "p-2", "flex-col");
            }
        });
        window.addEventListener('resize', () => {
            if (window.innerWidth > 640) {
                if (!menu.classList.contains("hidden")) {
                    menu.classList.toggle('hidden');
                    menu.classList.remove("mt-2", "w-full", "flex", "items-baseline", "gap-2", "border", "p-2", "flex-col");
                }
            };
        });
    </script>

</html>