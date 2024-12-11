<?php
session_start();
include('../../../db/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$ref = !isset($_SESSION["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : $_SESSION["HTTP_REFERER"];
$_SESSION["HTTP_REFERER"] = $ref;

$err;
$tag;

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM tags WHERE id = $id");
    $tag = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST["name"];
    $id = $_POST['id'];

    $sql = "UPDATE tags SET name='$name' WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        if (strpos($ref, "?")) {
            $ref .= "&msg=Tag Edited Successfully";
        } else {
            $ref .= "?msg=Tag Edited Successfully";
        }
        unset($_SESSION["HTTP_REFERER"]);
        header("Location: ../index.php?msg=Tag Updated Successfully");
    } else {
        $err = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Newstok - Edit Tag</title>
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
            <h2 class="text-xl font-bold mb-4">Edit Tag</h2>
            <form action="edit_tag.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $tag['id']; ?>" />
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" id="name" class="w-full p-2 border rounded-md"
                        value="<?php echo $tag["name"] ?? ""; ?>" required>
                </div>

                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Edit</button>
            </form>
        </div>
        <p class="text-center text-red-400 mt-2 mb-2"><?php if (!empty($err))
            echo $err; ?></p>
    </div>
</body>

</html>