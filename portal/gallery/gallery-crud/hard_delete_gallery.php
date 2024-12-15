<?php
include('../../../db/db.php');
session_start();

$ref = !isset($_SESSION["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : $_SESSION["HTTP_REFERER"];
$_SESSION["HTTP_REFERER"] = $ref;

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $conn->query("DELETE FROM gallery WHERE id = $id");
    if (strpos($ref, "?")) {
        $ref .= "&msg=Gallery Deleted Successfully";
    } else {
        $ref .= "?msg=Gallery Deleted Successfully";
    }
    header("Location: $ref");
} else if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    $err;
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    $author_id = $_SESSION["user_id"];

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $conn->query("DELETE FROM gallery WHERE sts='3' AND author_id='$author_id'");
            header("Location: deleted_gallery.php?msg=All Deleted Gallery Items Deleted Permanently");
        } else {
            $err = "Incorrect credentials.";
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
    <title>NewsTok - Login Verify</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <div class="min-h-screen w-full flex justify-center items-center">
        <div class="flex-col w-[400px]">
            <a href="deleted_news.php" class="text-blue-400 hover:underline mb-2">&lBarr; Go back</a>
            <div class="bg-white p-8 rounded-lg shadow-lg max-w-sm w-full mt-4">
                <h2 class="text-2xl font-bold text-center mb-6">Login</h2>
                <form action="" method="POST">
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
                        class="w-full p-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Login Verify</button>
                </form>
                <p class="text-center text-red-400 mt-2 mb-2"><?php if (!empty($err))
                    echo $err; ?></p>
                <hr />
            </div>
        </div>
    </div>

</body>

</html>