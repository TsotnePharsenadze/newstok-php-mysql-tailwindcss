<?php
if (!isset($noFooter)) { ?>
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
                    <?php
                    foreach ($_SESSION["menu"] as $menuItem) { ?>
                        <li>
                            <a class="<?php echo $_SERVER["PHP_SELF"] == $menuItem["url"] ? "underline hover:no-underline " : "hover:underline "; ?> text-nowrap"
                                href="<?php echo $menuItem["url"] ?>"><?php echo $menuItem["name"]; ?></a>
                        </li>
                    <?php }
                    ?>
                </ul>
            </div>

            <div>
                <h3 class="text-lg font-bold mb-2">Contact Us</h3>
                <p class="text-gray-400">Email: support@NewsTok.com</p>
                <p class="text-gray-400">Phone: +1 234 567 890</p>
                <p class="text-gray-400">Address: 123 News Street, Media City</p>
                <br />
                <a href="contact.php" class="bg-gray-800 text-white border px-4 py-2 rounded-md hover:opacity-95">Contact
                    Us</a>
            </div>
        </div>

        <div class="mt-8 text-center text-gray-500">
            <p>&copy; 2024 Newstok. All rights reserved.</p>
        </div>
    </footer>
<?php }
?>

<?php if (isset($includeSlider)) {
    echo '<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-element-bundle.min.js"></script>';
}
?>
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