<?php
include("../db/db.php");

$category;
$tagId = isset($_GET["category"]) ? $_GET["category"] : null;
if (isset($tagId)) {
    $result = $conn->query("SELECT * FROM tags WHERE id=$tagId");
    $category = $result->fetch_assoc();
}

// Layout Configuration
$title = "Newstok - " . (isset($category) ? $category["name"] : "Category");
$includeSlider = true;
// End Layout Configuration

if (!isset($_SESSION["menu"])) {
    $result = $conn->query("SELECT * FROM menu WHERE sts=2 ORDER BY ord ASC");
    $menuArray = [];

    while ($row = $result->fetch_assoc()) {
        array_push($menuArray, $row);
    }

    $_SESSION["menu"] = $menuArray;
}

$result = $conn->query("SELECT * FROM tags WHERE sts='2'");
?>

<?php
include("../layout/header.php");
?>

<main class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-4"><?php echo $tagId ? "Category" : "Categories"; ?></h2>

    <?php
    if (!$tagId) {
        foreach ($result as $tag) { ?>
            <section class="mb-8">
                <div class="flex justify-between">
                    <h2 class="text-xl font-bold mb-4"><?php echo $tag["name"]; ?></h2>
                    <a href="/category/?category=<?php echo $tag["id"]; ?>" class="text-blue-500 hover:underline">&#8608; Show
                        only this</a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php
                    $jsonTagId = json_encode($tag["id"]);
                    $resultLatest = $conn->query("SELECT * FROM news WHERE sts=2 AND JSON_CONTAINS(tag_ids, '$jsonTagId') ORDER BY createdAt DESC");

                    if ($resultLatest->num_rows > 0) {
                        while ($row = $resultLatest->fetch_assoc()) {
                            $image = $conn->query("SELECT file_path FROM gallery WHERE news_id='" . $row["id"] . "'");
                            $imageSrc = $image->num_rows > 0 ? $image->fetch_assoc()["file_path"] : null;
                            ?>
                            <article class="bg-white rounded-lg shadow-md overflow-hidden">
                                <img src="<?php
                                if ($imageSrc) {
                                    $filePathArray = explode("/", $imageSrc);
                                    $filePathArray[count($filePathArray) - 1] = trim($filePathArray[count($filePathArray) - 1]);
                                    $filePathArraySearch = array_search("gallery", $filePathArray);
                                    echo '/gallery/' . implode("/", array_slice($filePathArray, $filePathArraySearch + 1));
                                } else {
                                    echo $row["thumbnail"];
                                }
                                ?>" class="w-full" />
                                <div class="p-4">
                                    <h3 class="font-bold mb-2"><?php echo $row["title"] ?></h3>
                                    <p class="text-gray-700 mb-4"><?php echo $row["description"] ?></p>
                                    <a href="posts?id=<?php echo $row["id"] ?>" class="text-blue-600 font-semibold hover:underline">Read
                                        More</a>
                                </div>
                            </article>
                            <?php
                        }
                    } else {
                        echo "<p class='text-center text-semibold'>No posts yet for this category</p>";
                    }
                    ?>
                </div>
            </section>
        <?php }
    } else { ?>
        <section class="mb-8">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-bold mb-4"><?php echo $category["name"] ?? "Unknown category"; ?></h2>
            </div>
            <div <?php
            if (isset($category)) {
                if ($category["name"]) {
                    echo 'class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"';
                }
            }
            ?>>
                <?php
                $jsonTagId = json_encode($tagId);
                $resultLatest = $conn->query("SELECT * FROM news WHERE sts=2 AND JSON_CONTAINS(tag_ids, '$jsonTagId') ORDER BY createdAt DESC");

                if ($resultLatest->num_rows > 0) {
                    while ($row = $resultLatest->fetch_assoc()) {
                        $image = $conn->query("SELECT file_path FROM gallery WHERE news_id='" . $row["id"] . "'");
                        $imageSrc = $image->num_rows > 0 ? $image->fetch_assoc()["file_path"] : null;
                        ?>
                        <article class="bg-white rounded-lg shadow-md overflow-hidden">
                            <img src="<?php
                            if ($imageSrc) {
                                $filePathArray = explode("/", $imageSrc);
                                $filePathArray[count($filePathArray) - 1] = trim($filePathArray[count($filePathArray) - 1]);
                                $filePathArraySearch = array_search("gallery", $filePathArray);
                                echo '/gallery/' . implode("/", array_slice($filePathArray, $filePathArraySearch + 1));
                            } else {
                                echo $row["thumbnail"];
                            }
                            ?>" class="w-full" />
                            <div class="p-4">
                                <h3 class="font-bold mb-2"><?php echo $row["title"] ?></h3>
                                <p class="text-gray-700 mb-4"><?php echo $row["description"] ?></p>
                                <a href="posts?id=<?php echo $row["id"] ?>" class="text-blue-600 font-semibold hover:underline">Read
                                    More</a>
                            </div>
                        </article>
                        <?php
                    }
                } else {
                    if (isset($category)) {
                        if ($category["name"]) {
                            echo "<p class='text-center text-semibold'>No posts yet for this category</p>";
                        }
                    } else { ?>
                        <div class="w-full flex flex-col justify-center items-center mx-auto">
                            <p class='text-center text-semibold text-4xl font-semibold'>404 BRUH</p>
                            <a href="javascript:history.back()"
                                class="bg-gray-800 text-white border px-4 py-2 rounded-md hover:opacity-95 mt-4">&#8606; Go Back</a>
                        </div>
                    <?php }
                }
                ?>
            </div>
        </section>
    <?php }
    ?>
</main>

<?php
include("../layout/footer.php");
?>