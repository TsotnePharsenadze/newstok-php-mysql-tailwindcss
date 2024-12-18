<?php
include('../../../db/db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}

$ref = $_SESSION["HTTP_REFERER"] ?? $_SERVER["HTTP_REFERER"];
$_SESSION["HTTP_REFERER"] = $ref;

$err = '';
$user;
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM users WHERE id = $id");
    $user = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST["email"];
    $username = $_POST["username"];
    $type = $_POST["type"];
    $sts = (int) $_POST["sts"];
    $display_name = $_POST["display_name"];
    $id = $_GET["id"];
    $stmt;
    if ($user["password"] == $_POST["password"]) {
        $stmt = $conn->query("UPDATE users SET username='$username', email='$email', sts='$sts', type='$type', display_name='$display_name' WHERE id='$id'");
    } else {
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
        $stmt = $conn->query("UPDATE users SET username='$username', email='$email', password='$password', sts='$sts', type='$type', display_name='$display_name' WHERE id='$id'");
    }

    if ($stmt == TRUE) {
        if (strpos($ref, "?")) {
            $ref .= "&msg=User Edited Successfully";
        } else {
            $ref .= "?msg=User Edited Successfully";
        }
        unset($_SESSION["HTTP_REFERER"]);
        header("Location: $ref");
        exit();
    } else {
        $err = "Database Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Newstok - Edit User</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function goBack() {
            window.location.href = <?php echo json_encode($ref); ?>;
        }
    </script>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
        <div class="bg-white p-6 rounded-lg shadow-md max-w-md mx-auto">
            <a href="javascript:goBack()" class="text-blue-400 hover:underline">&lBarr; Go back</a>
            <hr class="mt-2 mb-2" />
            <h2 class="text-xl font-bold mb-4">Edit User</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="text" name="email" id="email" class="p-2 mr-2 border rounded-md w-full" value="<?php
                    echo $news["email"] ?? "";
                    ?>" required>
                </div>
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" name="username" id="username" class="p-2 mr-2 border rounded-md w-full" value="<?php
                    echo $news["username"] ?? "";
                    ?>" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="password" class="p-2 mr-2 border rounded-md w-full"
                        value="<?php
                        echo $news["password"] ?? "";
                        ?>" required>
                </div>
                <div class="mb-4">
                    <label for="display_name" class="block text-sm font-medium text-gray-700">Display Name</label>
                    <input type="text" name="display_name" id="display_name" class="p-2 mr-2 border rounded-md w-full"
                        value="<?php
                        echo $news["display_name"] ?? "";
                        ?>" required>
                </div>
                <div class="mb-4">
                    <label for="sts" class="block text-sm font-medium text-gray-700">Status</label>
                    <div class="flex">
                        <div class="flex">
                            <input type="radio" name="sts" id="sts1" class="p-2 mr-2 border rounded-md" value="1" value="<?php
                            echo $news["sts"] == 1 ? "checked" : "";
                            ?>">
                            <label for="sts1"><span
                                    class="bg-blue-100 text-blue-800 font-medium me-2 px-2.5 py-0.5 rounded uppercase">Pending</span></label>
                        </div>
                        <div class="flex">
                            <input type="radio" name="sts" id="sts2" class="p-2 mr-2 border rounded-md" value="2" value="<?php
                            echo $news["sts"] == 2 ? "checked" : "";
                            ?>">
                            <label for="sts2"><span
                                    class="bg-green-100 text-green-800 font-medium me-2 px-2.5 py-0.5 rounded uppercase">Approved</span></label>
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="sts" class="block text-sm font-medium text-gray-700">Type</label>
                    <div class="flex">
                        <div class="flex">
                            <input type="radio" name="type" id="type1" class="p-2 mr-2 border rounded-md" value="editor"
                                value="<?php
                                echo $news["type"] == "editor" ? "checked" : "";
                                ?>">
                            <label for="type1"><span
                                    class="bg-blue-100 text-blue-800 font-medium me-2 px-2.5 py-0.5 rounded uppercase">Editor</span></label>
                        </div>
                        <div class="flex">
                            <input type="radio" name="type" id="type2" class="p-2 mr-2 border rounded-md" value="admin"
                                value="<?php
                                echo $news["type"] == "admin" ? "checked" : "";
                                ?>">
                            <label for="type2"><span
                                    class="bg-green-100 text-green-800 font-medium me-2 px-2.5 py-0.5 rounded uppercase">Admin</span></label>
                        </div>
                    </div>
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Edit</button>
            </form>
        </div>
        <p class="text-center text-red-400 mt-2 mb-2"><?php if (!empty($err))
            echo $err; ?></p>
    </div>
</body>

</html>