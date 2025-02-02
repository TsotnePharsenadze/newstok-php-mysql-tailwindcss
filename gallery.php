<?php
include("db/db.php");

// Layout Configuration
$title = "Newstok - Gallery";
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

$newsData = [];
$result = $conn->query("SELECT id, title, thumbnail, createdAt FROM news WHERE sts=2 ORDER BY createdAt DESC");
// echo "<pre>";
// var_dump($result->fetch_all());
// echo "</pre>";
while ($row = $result->fetch_assoc()) {
    $monthYear = date('F Y', strtotime($row['createdAt']));

    if (!isset($newsData[$monthYear])) {
        $newsData[$monthYear] = [];
    }

    $newsData[$monthYear][] = $row;
}

?>

<?php
include("./layout/header.php");
?>

<main class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-4">Gallery</h2>

    <?php
    foreach ($newsData as $monthYear => $articles) {
        ?>
        <section>
            <h3 class='text-xl font-semibold mb-2'><?php echo $monthYear; ?></h3>
            <div class='grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 mb-8'>
                <?php
                foreach ($articles as $article) {
                    $articleUrl = "?id=" . $article['id'];

                    $image = $conn->query("SELECT file_path FROM gallery WHERE news_id='" . $article["id"] . "'");
                    $imageSrc = $image->num_rows > 0 ? $image->fetch_assoc()["file_path"] : null;

                    ?>
                    <a href="../posts/index.php<?php echo $articleUrl; ?>" class="block">
                        <img src="<?php
                        if ($imageSrc) {
                            $filePathArray = explode("/", $imageSrc);
                            $filePathArray[count($filePathArray) - 1] = trim($filePathArray[count($filePathArray) - 1]);
                            $filePathArraySearch = array_search("gallery", $filePathArray);
                            echo '/gallery/' . implode("/", array_slice($filePathArray, $filePathArraySearch + 1));
                        } else {
                            echo $article["thumbnail"];
                        }
                        ?>" alt="<?php echo $article['title']; ?>"
                            class="w-full h-40 object-cover rounded-lg shadow-md">
                    </a>
                    <?php
                }
                ?>

            </div>
        </section>
    <?php }
    ?>
</main>

<?php
include("layout/footer.php");
?>