<?php
session_start();
include('../../../db/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}

$author_id = $_SESSION["user_id"];

$ref = !isset($_SESSION["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : $_SESSION["HTTP_REFERER"];
$_SESSION["HTTP_REFERER"] = $ref;

$err;
$gallery;

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM gallery WHERE id = $id AND author_id='$author_id'");
    $gallery = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sts = intval($_POST["sts"]);
    $time = $_POST['date'];
    $author_id = $_SESSION["user_id"];

    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $file = $_FILES['file'];
        $file_name = basename($file['name']);
        $file_size = $file['size'];
        $file_tmp = $file['tmp_name'];
        $root_dir = $_SERVER['DOCUMENT_ROOT'];
        $upload_dir = $root_dir . "/gallery/$time";

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_path = "$upload_dir/$file_name";
        if (move_uploaded_file($file_tmp, $file_path)) {
            $relative_path = "/gallery/$time/$file_name";
            $stmt = $conn->prepare("UPDATE gallery 
                SET file_path = ?, 
                    file_size = ?, 
                    time = ?, 
                    sts = ?, 
                    author_id = ?, 
                    updatedAt = CURRENT_TIMESTAMP() 
                WHERE id = ?");

            $stmt->bind_param("sssiii", $relative_path, $file_size, $time, $sts, $author_id, $id);
        } else {
            $err = "Failed to upload the file.";
        }
    } else {
        $stmt = $conn->prepare("UPDATE gallery 
            SET time = ?, 
                sts = ?, 
                author_id = ?, 
                updatedAt = CURRENT_TIMESTAMP() 
            WHERE id = ?");

        $stmt->bind_param("siii", $time, $sts, $author_id, $id);
    }

    if (isset($stmt) && $stmt->execute()) {
        if (strpos($ref, "?")) {
            $ref .= "&msg=Gallery item Updated Successfully";
        } else {
            $ref .= "?msg=Gallery item Updated Successfully";
        }
        unset($_SESSION["HTTP_REFERER"]);
        header("Location: $ref");
        exit();
    } else {
        $err = isset($stmt) ? "Database Error: " . $stmt->error : "Failed to process the request.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Newstok - Edit Gallery</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .tag {
            display: inline-flex;
            align-items: center;
            background-color: #f3f4f6;
            padding: 0.5rem;
            border-radius: 0.5rem;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .tag button {
            margin-left: 0.5rem;
            color: #ef4444;
        }
    </style>
    <script>
        function goBack() {
            window.location.href = <?php echo json_encode($ref); ?>;
        }

        function previewImage(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('preview');
            const imagePreviewContainer = document.getElementById('imagePreview');

            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    imagePreviewContainer.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                preview.src = "#";
                imagePreviewContainer.classList.add('hidden');
            }
        }
    </script>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
        <div class="bg-white p-6 rounded-lg shadow-md max-w-md mx-auto">
            <a href="javascript:goBack()" class="text-blue-400 hover:underline">&lBarr; Go back</a>
            <hr class="mt-2 mb-2" />
            <h2 class="text-xl font-bold mb-4">Edit Gallery Item</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <label for="file" class="block text-sm font-medium text-gray-700">File</label>
                    <input type="file" name="file" id="file" accept="image/*" onchange="previewImage(event)" />
                    <?php if (!empty($gallery['file_path'])): ?>
                        <div id="imagePreview" class="mt-4">
                            <p class="text-gray-600 text-sm">Current Image:</p>
                            <img id="preview" src="<?php
                            $filePathArray = explode("/", $gallery['file_path']);
                            $filePathArray[count($filePathArray) - 1] = trim($filePathArray[count($filePathArray) - 1]);
                            $filePathArraySearch = array_search("gallery", $filePathArray);
                            echo '/gallery/' . implode("/", array_slice($filePathArray, $filePathArraySearch + 1));
                            ?>" alt=" Current Image" class="w-full h-auto rounded shadow-md" />
                        </div>
                    <?php endif; ?>
                </div>
                <div class="mb-4">
                    <label for="date" class="block text-sm font-medium text-gray-700">Date (<span
                            class="text-xs font-thin italic">For the date news is of</span>)</label>
                    <input type="date" name="date" id="date" class="w-full p-2 border rounded-md" required
                        value="<?php echo !empty($gallery['time']) ? htmlspecialchars(explode(' ', $gallery['time'])[0]) : ''; ?>">
                </div>
                <div class="mb-4">
                    <label for="sts" class="block text-sm font-medium text-gray-700">Status</label>
                    <div class="flex">
                        <div class="flex items-center mr-4">
                            <input type="radio" name="sts" id="sts1" class="p-2 mr-2 border rounded-md" value="1" <?php echo isset($gallery['sts']) && $gallery['sts'] == 1 ? 'checked' : ''; ?>>
                            <label for="sts1"><span
                                    class="bg-blue-100 text-blue-800 font-medium me-2 px-2.5 py-0.5 rounded uppercase">Unlisted</span></label>
                        </div>
                        <div class="flex items-center">
                            <input type="radio" name="sts" id="sts2" class="p-2 mr-2 border rounded-md" value="2" <?php echo isset($gallery['sts']) && $gallery['sts'] == 2 ? 'checked' : ''; ?>>
                            <label for="sts2"><span
                                    class="bg-green-100 text-green-800 font-medium me-2 px-2.5 py-0.5 rounded uppercase">Published</span></label>
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