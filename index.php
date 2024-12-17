<?php
include("db/db.php");
function deduplicateNews($nestedArray)
{
    $uniqueIds = [];
    $result = [];

    foreach ($nestedArray as $key => $newsGroup) {
        $result[$key] = array_filter($newsGroup, function ($news) use (&$uniqueIds) {
            if (in_array($news['id'], $uniqueIds)) {
                return false;
            }
            $uniqueIds[] = $news['id'];
            return true;
        });
    }

    return $result;
}
//Layout Configuration
$title = "Newstok";
$includeSlider = true;
//EndLayout Configuration

if (!isset($_SESSION["menu"])) {
    $result = $conn->query("SELECT * FROM menu WHERE sts=2 ORDER BY ord ASC");
    $menuArray = [];
    while ($row = $result->fetch_assoc()) {
        array_push($menuArray, $row);
    }
    $_SESSION["menu"] = $menuArray;
}

$resultLatest = $conn->query("SELECT * FROM news WHERE sts=2 ORDER BY createdAt DESC");

$query = "SELECT id, tag_ids FROM news WHERE sts=2 ORDER BY createdAt DESC LIMIT 20";
$result = $conn->query($query);

$tagCounts = [];
$newsIdsByTag = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tagsArray = json_decode($row["tag_ids"], true);
        if ($tagsArray) {
            foreach ($tagsArray as $tagId) {
                if (!isset($tagCounts[$tagId])) {
                    $tagCounts[$tagId] = 0;
                }
                $tagCounts[$tagId]++;

                if (!isset($newsIdsByTag[$tagId])) {
                    $newsIdsByTag[$tagId] = [];
                }
                $newsIdsByTag[$tagId][] = $row['id'];
            }
        }
    }
}

arsort($tagCounts);
$topTags = array_slice(array_keys($tagCounts), 0, 5);

$tagNames = [];

foreach ($topTags as $tagId) {
    $tagsQuery = "SELECT name FROM tags WHERE id = '$tagId' AND sts = 2";
    $tagsResult = $conn->query($tagsQuery);

    if ($tagsResult->num_rows > 0) {
        $tagRow = $tagsResult->fetch_assoc();
        $tagNames[$tagId] = $tagRow['name'];
    } else {
        $tagNames[$tagId] = "Unknown";
    }
}


$newsForTopTags = [];
if (!empty($topTags)) {
    $newsQuery = "SELECT * FROM news WHERE sts=2 AND JSON_CONTAINS(tag_ids, ?) ORDER BY createdAt DESC";
    $stmt = $conn->prepare($newsQuery);

    foreach ($topTags as $tagId) {
        $tagJson = json_encode((string) $tagId);
        $stmt->bind_param('s', $tagJson);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($newsRow = $result->fetch_assoc()) {
            $newsForTopTags[$tagId][] = $newsRow;
        }
    }
}
$newsForTopTags = deduplicateNews($newsForTopTags);
// echo "<pre>";
// var_dump($newsForTopTags);
// echo "</pre>";
?>
<?php
include("./layout/header.php");
?>
<main class="container mx-auto px-4 py-8">
    <section class="mb-8">
        <h2 class="text-2xl font-bold mb-4">Featured Headlines</h2>
        <swiper-container class="mySwiper" navigation="true" pagination="true" keyboard="true" mousewheel="true"
            css-mode="true">

            <?php
            if ($resultLatest->num_rows > 0) {
                $i = 0;
                while ($row = $resultLatest->fetch_assoc() and $i < 6) {
                    $i++;
                    $image = $conn->query("SELECT file_path FROM gallery WHERE news_id='" . $row["id"] . "'");
                    $imageSrc = $image->num_rows > 0 ? $image->fetch_assoc()["file_path"] : null;
                    ?>
                    <swiper-slide class="bg-white rounded-lg shadow-md">
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
                        <div class="p-6">
                            <h3 class="text-xl font-bold mb-2"><?php echo $row["title"] ?></h3>
                            <p class="text-gray-700 mb-4"><?php echo $row["description"] ?></p>
                            <a href="posts?id=<?php echo $row["id"] ?>" class="text-blue-600 font-semibold hover:underline">Read
                                More</a>
                        </div>
                    </swiper-slide>
                    <?php
                }
            }
            ?>
        </swiper-container>
    </section>
    <section>
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold mb-4">Latest News</h2>
            <a href="#" class="text-blue-500 hover:underline">&#8608; See more</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php
            if ($resultLatest->num_rows > 0) {
                $i = 0;
                while ($row = $resultLatest->fetch_assoc() and $i < 6) {
                    $i++;
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
            }
            ?>
        </div>
    </section>
    <br />
    <hr /><br />
    <?php if (!empty($newsForTopTags)): ?>
        <?php foreach ($newsForTopTags as $tagId => $newsArray): ?>
            <section>
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold mb-2"><?php echo htmlspecialchars($tagNames[$tagId] ?? "Unknown"); ?></h3>
                    <?php
                    if (isset($newsArray)) {
                        if (count($newsArray) > 3) { ?>
                            <a href="<?php echo "categories/index.php?id=$tagId" ?>" class="text-blue-500 hover:underline">&#8608; See
                                more
                            </a>
                        <?php }
                    }
                    ?>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php
                    $i = 0;
                    foreach ($newsArray as $news):
                        if ($i >= 3)
                            break; 
                        ?>
                        <article class="bg-white rounded-lg shadow-md overflow-hidden">
                            <img src="<?php echo htmlspecialchars($news['thumbnail'] ?? 'https://via.placeholder.com/800x400'); ?>"
                                class="w-full" />
                            <div class="p-4">
                                <h3 class="font-bold mb-2"><?php echo htmlspecialchars($news["title"]); ?></h3>
                                <p class="text-gray-700 mb-4"><?php echo htmlspecialchars($news["description"]); ?></p>
                                <a href="posts?id=<?php echo htmlspecialchars($news["id"]); ?>"
                                    class="text-blue-600 font-semibold hover:underline">Read More</a>
                            </div>
                        </article>
                        <?php
                        $i++;
                    endforeach;
                    ?>
                </div>

            </section>
            <br />
            <hr /><br />
        <?php endforeach; ?>
    <?php endif; ?>
</main>

<?php
include("layout/footer.php");
?>