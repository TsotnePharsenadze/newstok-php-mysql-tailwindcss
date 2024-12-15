<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Article</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>

<body class="bg-gray-100">
    <header class="bg-blue-600 text-white py-4 shadow-md">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold">NewsToday</h1>
            <button id="menu-button" class="sm:hidden text-white text-2xl focus:outline-none">
                <i class="fas fa-bars"></i>
            </button>
            <nav id="menu" class="hidden sm:flex space-x-4 sm:space-y-0 sm:flex-row flex-col">
                <a href="index.html" class="hover:underline">Home</a>
                <a href="gallery.html" class="hover:underline">Gallery</a>
                <a href="contact.html" class="hover:underline">Contact</a>
                <a href="about.html" class="hover:underline">About Us</a>
            </nav>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        <section class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-3xl font-bold mb-4">Headline of the News Article</h2>
            <p class="text-gray-600 text-sm mb-2">Published on: December 14, 2024</p>
            <p class="text-gray-600 text-sm mb-6">Author: John Doe</p>
            <img src="https://via.placeholder.com/800x400" alt="Article Image"
                class="w-full h-auto rounded-lg shadow-md mb-6">
            <p class="text-gray-700 leading-relaxed mb-4">
                This is the introductory paragraph of the news article. It provides a summary or key details to engage
                the reader. The main content of the article follows below.
            </p>
            <p class="text-gray-700 leading-relaxed mb-4">
                Here’s the detailed content of the news article. It can include multiple paragraphs, quotes, or
                sub-sections to thoroughly cover the topic. Make sure to present the information clearly and concisely.
            </p>
            <p class="text-gray-700 leading-relaxed">
                End the article with a concluding statement or call to action, encouraging readers to explore related
                content or share their thoughts on the topic.
            </p>
        </section>

        <section class="mt-8">
            <h3 class="text-xl font-semibold mb-4">Related Articles</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <a href="article1.html" class="block bg-white rounded-lg shadow-md overflow-hidden">
                    <img src="https://via.placeholder.com/300" alt="Related Article 1" class="w-full h-40 object-cover">
                    <div class="p-4">
                        <h4 class="font-bold text-lg">Related Article 1</h4>
                        <p class="text-gray-600 text-sm">A brief description of the related article.</p>
                    </div>
                </a>
                <a href="article2.html" class="block bg-white rounded-lg shadow-md overflow-hidden">
                    <img src="https://via.placeholder.com/300" alt="Related Article 2" class="w-full h-40 object-cover">
                    <div class="p-4">
                        <h4 class="font-bold text-lg">Related Article 2</h4>
                        <p class="text-gray-600 text-sm">A brief description of the related article.</p>
                    </div>
                </a>
                <a href="article3.html" class="block bg-white rounded-lg shadow-md overflow-hidden">
                    <img src="https://via.placeholder.com/300" alt="Related Article 3" class="w-full h-40 object-cover">
                    <div class="p-4">
                        <h4 class="font-bold text-lg">Related Article 3</h4>
                        <p class="text-gray-600 text-sm">A brief description of the related article.</p>
                    </div>
                </a>
            </div>
        </section>
    </main>

    <footer class="bg-gray-800 text-white py-8">
        <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <h3 class="text-lg font-bold mb-2">About Us</h3>
                <p class="text-gray-400">NewsToday brings you the latest updates, breaking news, and insightful stories
                    from around the world.</p>
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
                <p class="text-gray-400">Email: info@newstoday.com</p>
                <p class="text-gray-400">Phone: +1 234 567 890</p>
                <p class="text-gray-400">Address: 123 News Street, Media City</p>
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
        });
    </script>
</body>

</html>