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
            $relative_path = $root_dir . "/gallery/$time/ $file_name";
            $stmt = $conn->prepare("INSERT INTO gallery (file_path, file_size, time, sts, author_id) 
                                    VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssii", $relative_path, $file_size, $time, $sts, $author_id);

            if ($stmt->execute()) {
                if (strpos($ref, "?")) {
                    $ref .= "&msg=Gallery item Created Successfully";
                } else {
                    $ref .= "?msg=Gallery item Created Successfully";
                }
                unset($_SESSION["HTTP_REFERER"]);
                header("Location: $ref");
                exit();
            } else {
                $err = "Database Error: " . $stmt->error;
            }
        } else {
            $err = "Failed to upload the file.";
        }
    } else {
        $err = "No file uploaded or an error occurred.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Newstok - Create Gallery</title>
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
            <h2 class="text-xl font-bold mb-4">Create Gallery Item</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <label for="file" class="block text-sm font-medium text-gray-700">File</label>
                    <input type="file" name="file" id="file" accept="image/*" required onchange="previewImage(event)" />
                    <div id="imagePreview" class="mt-4 hidden">
                        <p class="text-gray-600 text-sm">Preview:</p>
                        <img id="preview" src="#" alt="Image Preview" class="w-full h-auto rounded shadow-md" />
                    </div>
                </div>
                <div class="mb-4">
                    <label for="date" class="block text-sm font-medium text-gray-700">Date (<span
                            class="text-xs font-thin italic">For the date news is of</span>)</label>
                    <input type="date" name="date" id="date" class="w-full p-2 border rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="sts" class="block text-sm font-medium text-gray-700">Status</label>
                    <div class="flex">
                        <div class="flex">
                            <input type="radio" name="sts" id="sts1" class="p-2 mr-2 border rounded-md" value="1"
                                checked>
                            <label for="sts1"><span
                                    class="bg-blue-100 text-blue-800 font-medium me-2 px-2.5 py-0.5 rounded uppercase">Unlisted</span></label>
                        </div>
                        <div class="flex">
                            <input type="radio" name="sts" id="sts2" class="p-2 mr-2 border rounded-md" value="2">
                            <label for="sts2"><span
                                    class="bg-green-100 text-green-800 font-medium me-2 px-2.5 py-0.5 rounded uppercase">Published</span></label>
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