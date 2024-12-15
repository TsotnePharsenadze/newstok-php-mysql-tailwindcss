<?php
include("db/db.php");

//Layout Configuration
$title = "Newstok";
$includeSlider = true;
//EndLayout Configuration

if (!isset($_SESSION["menu"])) {
    $result = $conn->query("SELECT * FROM menu ORDER BY ord ASC");
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
    <section class="mb-8">
        <h2 class="text-2xl font-bold mb-4">Featured Headlines</h2>
        <swiper-container class="mySwiper" navigation="true" pagination="true" keyboard="true" mousewheel="true"
            css-mode="true">
            <swiper-slide class="swiper-slide bg-white rounded-lg shadow-md">
                <img src="https://via.placeholder.com/800x400" alt="Featured News" class="w-full">
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-2">Featured News Headline 1</h3>
                    <p class="text-gray-700 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit...</p>
                    <a href="#" class="text-blue-600 font-semibold hover:underline">Read More</a>
                </div>
            </swiper-slide>
            <swiper-slide class="swiper-slide bg-white rounded-lg shadow-md">
                <img src="https://via.placeholder.com/800x400" alt="Featured News" class="w-full">
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-2">Featured News Headline 2</h3>
                    <p class="text-gray-700 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit...</p>
                    <a href="#" class="text-blue-600 font-semibold hover:underline">Read More</a>
                </div>
            </swiper-slide>
            <swiper-slide class="bg-white rounded-lg shadow-md">
                <img src="https://via.placeholder.com/800x400" alt="Featured News" class="w-full">
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-2">Featured News Headline 3</h3>
                    <p class="text-gray-700 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit...</p>
                    <a href="#" class="text-blue-600 font-semibold hover:underline">Read More</a>
                </div>
            </swiper-slide>
        </swiper-container>
    </section>
    <section>
        <h2 class="text-xl font-bold mb-4">Latest News</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <article class="bg-white rounded-lg shadow-md overflow-hidden">
                <img src="https://via.placeholder.com/400x200" alt="Article Image" class="w-full">
                <div class="p-4">
                    <h3 class="font-bold mb-2">Article Title</h3>
                    <p class="text-gray-700 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit...</p>
                    <a href="#" class="text-blue-600 font-semibold hover:underline">Read More</a>
                </div>
            </article>
            <article class="bg-white rounded-lg shadow-md overflow-hidden">
                <img src="https://via.placeholder.com/400x200" alt="Article Image" class="w-full">
                <div class="p-4">
                    <h3 class="font-bold mb-2">Article Title</h3>
                    <p class="text-gray-700 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit...</p>
                    <a href="#" class="text-blue-600 font-semibold hover:underline">Read More</a>
                </div>
            </article>
            <article class="bg-white rounded-lg shadow-md overflow-hidden">
                <img src="https://via.placeholder.com/400x200" alt="Article Image" class="w-full">
                <div class="p-4">
                    <h3 class="font-bold mb-2">Article Title</h3>
                    <p class="text-gray-700 mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit...</p>
                    <a href="#" class="text-blue-600 font-semibold hover:underline">Read More</a>
                </div>
            </article>
        </div>
    </section>
</main>

<?php
include("layout/footer.php");
?>