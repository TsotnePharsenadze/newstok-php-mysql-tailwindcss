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
$menu;
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM menu WHERE id = $id");
    $menu = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST["name"];
    $url = $_POST["url"][0] == "/" ? $_POST["url"] : "/" . $_POST["url"];
    $ord = intval($_POST["ord"]);
    $sts = intval($_POST["sts"]);
    $menuId = $_POST["id"];

    $stmt = $conn->query("UPDATE menu SET name='$name', url='$url', sts='$sts', ord='$ord'WHERE id=$menuId");

    if ($stmt == TRUE) {
        if (strpos($ref, "?")) {
            $ref .= "&msg=Menu item Edited Successfully";
        } else {
            $ref .= "?msg=Menu item Edited Successfully";
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
    <title>Newstok - Create Menu</title>
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
            <h2 class="text-xl font-bold mb-4">Create Menu Item</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" id="name" class="p-2 mr-2 border rounded-md w-full"
                        value="<?php echo $menu["name"] ?? ""; ?>" required>
                </div>
                <div class="mb-4">
                    <label for="url" class="block text-sm font-medium text-gray-700">Url</label>
                    <input type="text" name="url" id="url" class="p-2 mr-2 border rounded-md w-full"
                        value="<?php echo $menu["url"] ?? ""; ?>" required>
                </div>
                <div class="mb-4">
                    <label for="ord" class="block text-sm font-medium text-gray-700">Order</label>
                    <input type="number" name="ord" id="ord" class="p-2 mr-2 border rounded-md w-full" min="1"
                        value="<?php echo $menu["ord"] ?? ""; ?>" required>
                </div>
                <div class="mb-4">
                    <label for="sts" class="block text-sm font-medium text-gray-700">Status</label>
                    <div class="flex">
                        <div class="flex">
                            <input type="radio" name="sts" id="sts1" class="p-2 mr-2 border rounded-md" value="1" <?php echo $menu["sts"] == 1 ? "checked" : ""; ?>>
                            <label for="sts1"><span
                                    class="bg-blue-100 text-blue-800 font-medium me-2 px-2.5 py-0.5 rounded uppercase">Unlisted</span></label>
                        </div>
                        <div class="flex">
                            <input type="radio" name="sts" id="sts2" class="p-2 mr-2 border rounded-md" value="2" <?php echo $menu["sts"] == 2 ? "checked" : ""; ?>>
                            <label for="sts2"><span
                                    class="bg-green-100 text-green-800 font-medium me-2 px-2.5 py-0.5 rounded uppercase">Published</span></label>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="id" value="<?php echo $menu["id"] ?>">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Edit </button>
            </form>
        </div>
        <p class="text-center text-red-400 mt-2 mb-2"><?php if (!empty($err))
            echo $err; ?></p>
    </div>
</body>

</html>