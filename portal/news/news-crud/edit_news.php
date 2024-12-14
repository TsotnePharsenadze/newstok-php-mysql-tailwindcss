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
$news;
$tags = [];

$sql = "SELECT * FROM tags";
$tagsTotal = $conn->query($sql);

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM news WHERE id = $id AND author_id='$author_id'");
    $news = $result->fetch_assoc();
}

if (!empty($news["tag_ids"])) {
    $tag_ids = json_decode($news["tag_ids"]);
    foreach ($tag_ids as $tag) {
        $result = $conn->query("SELECT * FROM tags WHERE id=$tag");
        array_push($tags, $result->fetch_assoc());
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST["id"];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $text = $_POST['text'];
    $keywords = $_POST['keywords'];
    $tag_ids = $_POST['tag_ids'];
    $time = $_POST["date"];
    $sts = $_POST["sts"];
    $tagIdsToStr = implode(",", $tag_ids);
    $authorId = $_SESSION["user_id"];

    $sql = "UPDATE news SET title='$title', description='$description', text='$text', keywords='$keywords', time='$time', sts='$sts', tag_ids='$tagIdsToStr', updatedAt=current_timestamp() WHERE id='$id' AND author_id='$authorId'";

    if ($conn->query($sql) === TRUE) {
        if (strpos($ref, "?")) {
            $ref .= "&msg=News Edited Successfully";
        } else {
            $ref .= "?msg=News Edited Successfully";
        }
        header("Location: $ref");
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
    <title>Newstok - Edit News</title>
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
    </script>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
        <div class="bg-white p-6 rounded-lg shadow-md max-w-md mx-auto">
            <a href="javascript:goBack()" class="text-blue-400 hover:underline">&lBarr; Go back</a>
            <hr class="mt-2 mb-2" />
            <h2 class="text-xl font-bold mb-4">Edit News</h2>
            <form action="edit_news.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $news['id']; ?>" />
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" name="title" id="title" class="w-full p-2 border rounded-md"
                        value="<?php echo $news["title"] ?? ""; ?>" required>
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" class="w-full p-2 border rounded-md"
                        required><?php echo $news["description"] ?? ""; ?></textarea>
                </div>
                <div class="mb-4">
                    <label for="text" class="block text-sm font-medium text-gray-700">Text</label>
                    <textarea name="text" id="text" class="w-full p-2 border rounded-md"
                        required><?php echo $news["text"] ?? ""; ?></textarea>
                </div>
                <div class="mb-4">
                    <label for="keywords" class="block text-sm font-medium text-gray-700">Keywords</label>
                    <input type="text" name="keywords" id="keywords" class="w-full p-2 border rounded-md"
                        value="<?php echo $news["keywords"] ?? ""; ?>">
                </div>
                <div class="mb-4">
                    <label for="date" class="block text-sm font-medium text-gray-700">
                        Date
                        <span class="text-xs font-thin italic">(For the date of the news)</span>
                    </label>
                    <input type="date" name="date" id="date" class="w-full p-2 border rounded-md"
                        value="<?php echo date('Y-m-d', strtotime($news['time'])); ?>">
                </div>

                <div class="mb-4">
                    <label for="sts" class="block text-sm font-medium text-gray-700">Status</label>
                    <div class="flex">
                        <div class="flex">
                            <input type="radio" name="sts" id="sts1" class="p-2 mr-2 border rounded-md" value='1' <?php echo $news["sts"] == 1 ? "checked" : ""; ?>>
                            <label for="sts1"> <span
                                    class="bg-blue-100 text-blue-800 font-medium me-2 px-2.5 py-0.5 rounded uppercase">drafted</span></label>
                        </div>
                        <div class="flex">
                            <input type="radio" name="sts" id="sts2" class="p-2 mr-2 border rounded-md" value='2' <?php echo $news["sts"] == 2 ? "checked" : ""; ?>>
                            <label for="sts2"> <span
                                    class="bg-green-100 text-green-800 font-medium me-2 px-2.5 py-0.5 rounded uppercase">published</span></label>
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="tags" class="block text-sm font-medium text-gray-700">Tags</label>
                    <select id="tag-select" class="w-full p-2 border rounded-md">
                        <option value="">-- Select a Tag --</option>
                        <?php
                        while ($tag = $tagsTotal->fetch_assoc()) {
                            echo "<option value='" . $tag["id"] . "'>" . $tag["name"] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div id="selected-tags" class="mb-4 flex flex-wrap">
                    <?php
                    if ($tags) {
                        foreach ($tags as $tag) { ?>
                            <div class="tag" data-id="<?php echo $tag["id"]; ?>">
                                <?php echo $tag["name"]; ?>
                                <button type="button" onClick="removeTag(this)">x</button>
                            </div>
                        <?php }
                    }
                    ?>
                </div>
                <input type="hidden" name="tag_ids[]" id="tag-ids">

                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Edit</button>
            </form>
        </div>
        <p class="text-center text-red-400 mt-2 mb-2"><?php if (!empty($err))
            echo $err; ?></p>
    </div>
    <script>
        const tagSelect = document.getElementById('tag-select');
        const selectedTagsContainer = document.getElementById('selected-tags');
        const tagIdsInput = document.getElementById('tag-ids');

        tagSelect.addEventListener('change', function () {
            const selectedOption = tagSelect.options[tagSelect.selectedIndex];
            const tagId = selectedOption.value;
            const tagName = selectedOption.text;

            if (!tagId) return;

            const existingTags = Array.from(selectedTagsContainer.querySelectorAll('.tag'))
                .map(tag => tag.getAttribute('data-id'));
            if (existingTags.includes(tagId)) {
                return;
            }
            let tagElement;
            tagElement = document.createElement('div');
            tagElement.className = 'tag';
            tagElement.setAttribute('data-id', tagId);
            tagElement.innerHTML = `
        ${tagName}
        <button type="button" onclick="removeTag(this)">x</button>
    `;
            selectedTagsContainer.appendChild(tagElement);
            updateTagIds();
            tagSelect.value = '';
        });


        function removeTag(button) {
            const tagElement = button.parentElement;
            tagElement.remove();
            updateTagIds();
        }

        function updateTagIds() {
            const tagElements = selectedTagsContainer.querySelectorAll('.tag');
            const tagIds = new Set(Array.from(tagElements).map(tag => tag.getAttribute('data-id')));
            tagIdsInput.value = JSON.stringify([...tagIds]);
        }
    </script>
</body>

</html>