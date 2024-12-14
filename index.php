<?php
include("db/db.php");
if (!isset($_SESSION["menu"])) {
    $result = $conn->query("SELECT * FROM menu ORDER BY ord ASC");
    $menuArray = [];
    while ($row = $result->fetch_assoc()) {
        array_push($menuArray, $row);
    }
    $_SESSION["menu"] = $menuArray;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Website</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css"
        integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="global.css" />
</head>

<body class="bg-gray-100">
    <header class="bg-blue-600 text-white py-4 shadow-md">
        <div class="container mx-auto px-4 flex flex-col sm:flex-row items-center justify-between items-center">
            <div class="flex justify-between items-center w-full">
                <a class="text-2xl font-bold" href="/index.php">NewsTok</a>
                <button id="menu-button" class="sm:hidden text-white text-2xl focus:outline-none">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>
            <nav id="menu" class="hidden sm:flex items-center space-x-4 sm:space-y-0 sm:flex-row flex-col">
                <?php
                foreach ($_SESSION["menu"] as $menuItem) { ?>
                    <a class="<?php echo $_SERVER["PHP_SELF"] == $menuItem["url"] ?  "underline hover:no-underline " :  "hover:underline "; ?> text-nowrap"
                        href="<?php echo $menuItem["url"] ?>"><?php echo $menuItem["name"]; ?></a>
                <?php }
                ?>
                <div class="border flex items-center rounded-md">
                    <a href="portal/login.php" class="hover:bg-blue-400 p-2 border-r">Login</a>
                    <a href="portal/register.php" class="hover:bg-blue-400 p-2">Register</a>
                </div>
            </nav>
        </div>
    </header>

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

    <footer class="bg-gray-800 text-white py-8">
        <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <h3 class="text-lg font-bold mb-2">About Us</h3>
                <p class="text-gray-400">Newstok is your go-to source for the latest news from around the world. Stay
                    informed with our in-depth articles and updates.</p>
            </div>

            <div>
                <h3 class="text-lg font-bold mb-2">Quick Links</h3>
                <ul class="space-y-2">
                    <li><a href="#" class="hover:underline">Home (News)</a></li>
                    <li><a href="#" class="hover:underline">Gallery</a></li>
                    <li><a href="#" class="hover:underline">Contact Us</a></li>
                    <li><a href="#" class="hover:underline">About Us</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-lg font-bold mb-2">Contact Us</h3>
                <p class="text-gray-400">Email: support@NewsTok.com</p>
                <p class="text-gray-400">Phone: +1 234 567 890</p>
                <p class="text-gray-400">Address: 123 News Street, Media City</p>
                <br />
                <a href="contact.php" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:opacity-95">Contact
                    Us</a>
            </div>
        </div>

        <div class="mt-8 text-center text-gray-500">
            <p>&copy; 2024 Newstok. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-element-bundle.min.js"></script>
    <script>
        const menuButton = document.getElementById('menu-button');
        const menu = document.getElementById('menu');
        menuButton.addEventListener('click', () => {
            menu.classList.toggle('hidden');
            if (menu.classList.contains("hidden")) {
                menu.classList.remove("mt-2", "w-full", "flex", "items-baseline", "gap-2", "border", "p-2", "flex-col");
            } else {
                menu.classList.add("mt-2", "w-full", "flex", "items-baseline", "gap-2", "border", "p-2", "flex-col");
            }
        });
        window.addEventListener('resize', () => {
            if (window.innerWidth > 640) {
                if (!menu.classList.contains("hidden")) {
                    menu.classList.toggle('hidden');
                    menu.classList.remove("mt-2", "w-full", "flex", "items-baseline", "gap-2", "border", "p-2", "flex-col");
                }
            };
        });
    </script>
</body>

</html>