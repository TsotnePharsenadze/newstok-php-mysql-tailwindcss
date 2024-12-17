<?php
session_start();

//Layout Configuration
$title = "Newstok - Register";
$noFooter = true;
$buttonLogin = "login.php";
$buttonRegister = "register.php";
//EndLayout Configuration


include('../db/db.php');
if (!isset($_SESSION["menu"])) {
    $result = $conn->query("SELECT * FROM menu WHERE sts=2 ORDER BY ord ASC");
    $menuArray = [];
    while ($row = $result->fetch_assoc()) {
        array_push($menuArray, $row);
    }
    $_SESSION["menu"] = $menuArray;
}

if (isset($_SESSION["user_id"])) {
    header("Location: dashboard.php");
    exit();
}

$err;
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $display_name = $_POST['display_name'];
    $email = $_POST['email'];

    $sql = "INSERT INTO users (username, password, type, display_name, email) 
            VALUES ('$username', '$password', 'editor', '$display_name', '$email')";

    if ($conn->query($sql) === TRUE) {
        header("Location: login.php");
    } else {
        $err = $conn->error;
    }
}
?>

<?php
include('../layout/header.php');
?>

<div class="min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-sm w-full">
        <h2 class="text-2xl font-bold text-center mb-6">Register</h2>
        <form action="register.php" method="POST">
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" name="username" id="username" class="w-full p-2 border border-gray-300 rounded-md"
                    required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password"
                    class="w-full p-2 border border-gray-300 rounded-md" required>
            </div>
            <div class="mb-4">
                <label for="display_name" class="block text-sm font-medium text-gray-700">Display Name</label>
                <input type="text" name="display_name" id="display_name"
                    class="w-full p-2 border border-gray-300 rounded-md" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" class="w-full p-2 border border-gray-300 rounded-md"
                    required>
            </div>
            <button type="submit" name="register"
                class="w-full p-2 bg-gray-800 text-white rounded-md hover:bg-gray-600">Register</button>

        </form>
        <p class="text-center text-red-400 mt-2 mb-2"><?php if (!empty($err))
            echo $err; ?></p>
        <hr />
        <p class="mt-2">
            Already have an account?
            <a href="login.php" class="text-blue-600 hover:underline">Login here</a>
        </p>
    </div>
</div>

<?php
include('../layout/footer.php');
?>