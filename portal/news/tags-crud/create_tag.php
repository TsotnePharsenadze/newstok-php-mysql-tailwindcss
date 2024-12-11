<?php
include('../../../db/db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$ref = !isset($_SESSION["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : $_SESSION["HTTP_REFERER"];
$_SESSION["HTTP_REFERER"] = $ref;
$err;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];


    $sql = "INSERT INTO tags (name) 
            VALUES ('$name')";

    if ($conn->query($sql) === TRUE) {
        if (strpos($ref, "?")) {
            $ref .= "&msg=Tag Created Successfully";
        } else {
            $ref .= "?msg=Tag Created Successfully";
        }
        unset($_SESSION["HTTP_REFERER"]);
        header("Location: ../index.php?msg=Tag Created Successfully");
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
    <title>Newstok - Create Tag</title>
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
            <h2 class="text-xl font-bold mb-4">Create Tag</h2>
            <form action="create_tag.php" method="POST">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" id="name" class="w-full p-2 border rounded-md" required>
                </div>

                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Create</button>
            </form>
        </div>
        <p class="text-center text-red-400 mt-2 mb-2"><?php if (!empty($err))
            echo $err; ?></p>
    </div>
</body>

</html>