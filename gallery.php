<?php
include("db/db.php");

//Layout Configuration
$title = "Newstok - Gallery";
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
?>

<?php
include("./layout/header.php");
?>

<main class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-4">Gallery</h2>

    <section>
        <h3 class="text-xl font-semibold mb-2">December 2024</h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 mb-8">
            <a href="images/december/image1.jpg" target="_blank" class="block">
                <img src="https://via.placeholder.com/200" alt="Gallery Image 1"
                    class="w-full h-40 object-cover rounded-lg shadow-md">
            </a>
            <a href="images/december/image2.jpg" target="_blank" class="block">
                <img src="https://via.placeholder.com/200" alt="Gallery Image 2"
                    class="w-full h-40 object-cover rounded-lg shadow-md">
            </a>
            <a href="images/december/image3.jpg" target="_blank" class="block">
                <img src="https://via.placeholder.com/200" alt="Gallery Image 3"
                    class="w-full h-40 object-cover rounded-lg shadow-md">
            </a>
            <a href="images/december/image4.jpg" target="_blank" class="block">
                <img src="https://via.placeholder.com/200" alt="Gallery Image 4"
                    class="w-full h-40 object-cover rounded-lg shadow-md">
            </a>
        </div>
    </section>

    <section>
        <h3 class="text-xl font-semibold mb-2">November 2024</h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 mb-8">
            <a href="images/november/image1.jpg" target="_blank" class="block">
                <img src="https://via.placeholder.com/200" alt="Gallery Image 1"
                    class="w-full h-40 object-cover rounded-lg shadow-md">
            </a>
            <a href="images/november/image2.jpg" target="_blank" class="block">
                <img src="https://via.placeholder.com/200" alt="Gallery Image 2"
                    class="w-full h-40 object-cover rounded-lg shadow-md">
            </a>
            <a href="images/november/image3.jpg" target="_blank" class="block">
                <img src="https://via.placeholder.com/200" alt="Gallery Image 3"
                    class="w-full h-40 object-cover rounded-lg shadow-md">
            </a>
            <a href="images/november/image4.jpg" target="_blank" class="block">
                <img src="https://via.placeholder.com/200" alt="Gallery Image 4"
                    class="w-full h-40 object-cover rounded-lg shadow-md">
            </a>
        </div>
    </section>

    <section>
        <h3 class="text-xl font-semibold mb-2">October 2024</h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 mb-8">
            <a href="images/october/image1.jpg" target="_blank" class="block">
                <img src="https://via.placeholder.com/200" alt="Gallery Image 1"
                    class="w-full h-40 object-cover rounded-lg shadow-md">
            </a>
            <a href="images/october/image2.jpg" target="_blank" class="block">
                <img src="https://via.placeholder.com/200" alt="Gallery Image 2"
                    class="w-full h-40 object-cover rounded-lg shadow-md">
            </a>
            <a href="images/october/image3.jpg" target="_blank" class="block">
                <img src="https://via.placeholder.com/200" alt="Gallery Image 3"
                    class="w-full h-40 object-cover rounded-lg shadow-md">
            </a>
            <a href="images/october/image4.jpg" target="_blank" class="block">
                <img src="https://via.placeholder.com/200" alt="Gallery Image 4"
                    class="w-full h-40 object-cover rounded-lg shadow-md">
            </a>
        </div>
    </section>
</main>

<?php
include("layout/footer.php");
?>