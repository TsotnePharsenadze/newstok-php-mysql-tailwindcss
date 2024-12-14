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
    <title>Gallery</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css"
        integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
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
                    <a class="<?php echo $_SERVER["PHP_SELF"] == $menuItem["url"] ? "underline hover:no-underline " : "hover:underline "; ?> text-nowrap"
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

    <footer class="bg-gray-800 text-white py-8">
        <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <h3 class="text-lg font-bold mb-2">About Us</h3>
                <p class="text-gray-400">NewsToday is your go-to source for the latest news and photo galleries.
                    Discover our curated images from around the world.</p>
            </div>

            <div>
                <h3 class="text-lg font-bold mb-2">Quick Links</h3>
                <ul class="space-y-2">
                    <li><a href="index.html" class="hover:underline">Home</a></li>
                    <li><a href="gallery.html" class="hover:underline">Gallery</a></li>
                    <li><a href="contact.html" class="hover:underline">Contact</a></li>
                    <li><a href="about.html" class="hover:underline">About Us</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-bold mb-2">Contact Us</h3>
                <p class="text-gray-400">Email: gallery@newstoday.com</p>
                <p class="text-gray-400">Phone: +1 234 567 890</p>
                <p class="text-gray-400">Address: 123 Gallery Street, Art City</p>
            </div>
        </div>

        <div class="mt-8 text-center text-gray-500">
            <p>&copy; 2024 NewsToday. All rights reserved.</p>
        </div>
    </footer>

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