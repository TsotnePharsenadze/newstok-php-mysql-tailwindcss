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
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST["email"];
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $type = $_POST["type"];
    $sts = (int) $_POST["sts"];
    $display_name = $_POST["display_name"];

    $stmt = $conn->prepare("INSERT INTO users (email, username, password, type, sts, display_name) 
                                    VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssis", $email, $username, $password, $type, $sts, $display_name);

    if ($stmt->execute()) {
        if (strpos($ref, "?")) {
            $ref .= "&msg=User Created Successfully";
        } else {
            $ref .= "?msg=User Created Successfully";
        }
        unset($_SESSION["HTTP_REFERER"]);
        header("Location: $ref");
        exit();
    } else {
        $err = "Database Error: " . $stmt->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Newstok - Create User</title>
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
            <h2 class="text-xl font-bold mb-4">Create User Item</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="text" name="email" id="email" class="p-2 mr-2 border rounded-md w-full" required>
                </div>
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" name="username" id="username" class="p-2 mr-2 border rounded-md w-full" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="password" class="p-2 mr-2 border rounded-md w-full"
                        required>
                </div>
                <div class="mb-4">
                    <label for="display_name" class="block text-sm font-medium text-gray-700">Display Name</label>
                    <input type="text" name="display_name" id="display_name" class="p-2 mr-2 border rounded-md w-full"
                        required>
                </div>
                <div class="mb-4">
                    <label for="sts" class="block text-sm font-medium text-gray-700">Status</label>
                    <div class="flex">
                        <div class="flex">
                            <input type="radio" name="sts" id="sts1" class="p-2 mr-2 border rounded-md" value="1"
                                checked>
                            <label for="sts1"><span
                                    class="bg-blue-100 text-blue-800 font-medium me-2 px-2.5 py-0.5 rounded uppercase">Pending</span></label>
                        </div>
                        <div class="flex">
                            <input type="radio" name="sts" id="sts2" class="p-2 mr-2 border rounded-md" value="2">
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
                                checked>
                            <label for="type1"><span
                                    class="bg-blue-100 text-blue-800 font-medium me-2 px-2.5 py-0.5 rounded uppercase">Editor</span></label>
                        </div>
                        <div class="flex">
                            <input type="radio" name="type" id="type2" class="p-2 mr-2 border rounded-md" value="admin">
                            <label for="type2"><span
                                    class="bg-green-100 text-green-800 font-medium me-2 px-2.5 py-0.5 rounded uppercase">Admin</span></label>
                        </div>
                    </div>
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Create</button>
            </form>
        </div>
        <p class="text-center text-red-400 mt-2 mb-2"><?php if (!empty($err))
            echo $err; ?></p>
    </div>
</body>

</html>