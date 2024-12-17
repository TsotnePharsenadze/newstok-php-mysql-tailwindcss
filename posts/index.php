<?php
include("../db/db.php");

$news;
if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $result = $conn->query("SELECT * FROM news WHERE id='$id'");
    if ($result->num_rows > 0) {
        $news = $result->fetch_assoc();
    } else {
        header("location: ../index.php");
        exit();
    }
} else {
    header("location: ../index.php");
    exit();
}

$newsKeywords = $news["keywords"];
$keywordsArray = explode(',', $newsKeywords);

$keywordsArray = array_map('trim', $keywordsArray);
$keywordsArray = array_map(function ($keyword) use ($conn) {
    return "'" . $conn->real_escape_string($keyword) . "'";
}, $keywordsArray);

$keywordsCondition = implode(" OR ", array_map(function ($keyword) {
    return "FIND_IN_SET($keyword, keywords)";
}, $keywordsArray));

$sqlSimilarNews = "
    SELECT * 
    FROM news 
    WHERE sts=2 
    AND id != '$id'
    AND ($keywordsCondition) 
    ORDER BY createdAt DESC 
    LIMIT 3";

$resultSimilar = $conn->query($sqlSimilarNews);
$similarNews = [];

if ($resultSimilar->num_rows > 0) {
    while ($row = $resultSimilar->fetch_assoc()) {
        array_push($similarNews, $row);
    }
}

if (empty($similarNews)) {
    $tagIds = json_decode($news['tag_ids']);
    $firstTagId = $tagIds[0];

    $sqlTagBasedNews = "
        SELECT * 
        FROM news 
        WHERE FIND_IN_SET('$firstTagId', tag_ids) 
        AND sts=2 
        AND id != '$id' 
        ORDER BY createdAt DESC 
        LIMIT 3";

    $resultTagBased = $conn->query($sqlTagBasedNews);
    $similarNews = [];

    if ($resultTagBased->num_rows > 0) {
        while ($row = $resultTagBased->fetch_assoc()) {
            array_push($similarNews, $row);
        }
    }
}

// Layout Configuration
$newsTitle = $news["title"];
$title = "Newstok - $newsTitle";
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

?>

<?php include("../layout/header.php") ?>
<main class="container mx-auto px-4 py-8">
    <section class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-3xl font-bold mb-4"><?php echo $news["title"]; ?></h2>
        <p class="text-gray-600 text-sm mb-2">Published on: <b><?php echo $news["time"]; ?></b></p>
        <p class="text-gray-600 text-sm mb-6">Author:
            <b>
                <?php
                $author_id = $news["author_id"];
                $result = $conn->query("SELECT * FROM users WHERE id='$author_id'");
                if ($result->num_rows > 0) {
                    echo $result->fetch_assoc()["display_name"];
                }
                ?>
            </b>
        </p>
        <img src="<?php
        $image = $conn->query("SELECT file_path FROM gallery WHERE news_id='" . $news["id"] . "'");
        $imageSrc = $image->num_rows > 0 ? $image->fetch_assoc()["file_path"] : null;
        if ($imageSrc) {
            $filePathArray = explode("/", $imageSrc);
            $filePathArray[count($filePathArray) - 1] = trim($filePathArray[count($filePathArray) - 1]);
            $filePathArraySearch = array_search("gallery", $filePathArray);
            echo '/gallery/' . implode("/", array_slice($filePathArray, $filePathArraySearch + 1));
        } else {
            echo $news["thumbnail"];
        }
        ?>" class="w-full h-auto rounded-lg shadow-md mb-6" />
        <p class="text-gray-700 leading-relaxed">
            <?php
            echo $news["text"];
            ?>
        </p>
    </section>

    <h3 class="text-xl font-semibold mb-4 mt-8">Related Articles</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        <?php
        foreach ($similarNews as $row) {
            $image = $conn->query("SELECT file_path FROM gallery WHERE news_id='" . $row["id"] . "'");
            $imageSrc = $image->num_rows > 0 ? $image->fetch_assoc()["file_path"] : null;
            ?>
            <a href="?id=<?php echo $row["id"]; ?>" class="block bg-white rounded-lg shadow-md overflow-hidden">
                <img src="<?php
                if ($imageSrc) {
                    $filePathArray = explode("/", $imageSrc);
                    $filePathArray[count($filePathArray) - 1] = trim($filePathArray[count($filePathArray) - 1]);
                    $filePathArraySearch = array_search("gallery", $filePathArray);
                    echo '/gallery/' . implode("/", array_slice($filePathArray, $filePathArraySearch + 1));
                } else {
                    echo $row["thumbnail"];
                }
                ?>" alt="Related Article 3" class="w-full h-40 object-cover">
                <div class="p-4">
                    <h4 class="font-bold text-lg"><?php echo $row["title"] ?></h4>
                    <p class="text-gray-600 text-sm"><?php echo $row["description"] ?></p>
                </div>
            </a>
            <?php
        }
        ?>
    </div>
    </section>
</main>
<?php include("../layout/footer.php") ?>