<?php
include "./db/db.php";
include "./helpers/icons.php";
$result2 = $conn->query("SELECT DISTINCT category from furniture");
$category = $_GET["category"];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>.furniture</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="p-1 md:p-6 max-w-[1000px] mx-auto w-full">
    <header class="flex flex-col">
        <nav>
            <ul class="flex justify-between">
                <li class="flex items-center gap-2 cursor-pointer">
                    <button class="flex flex-col justify-center items-center space-y-[2px]">
                        <span
                            class="bg-gray-500 block transition-all duration-400 ease-out h-0.5 w-3 rounded-sm"></span>
                        <span
                            class="bg-gray-500 block transition-all duration-400 ease-out h-0.5 w-3 rounded-sm"></span>
                        <span
                            class="bg-gray-500 block transition-all duration-400 ease-out h-0.5 w-3 rounded-sm"></span>
                    </button>
                    <span class="text-gray-500 font-semibold uppercase text-xs">Catalog</span>
                </li>
                <li class="font-black text-2xl">.furniture</li>
                <li class="flex space-x-4 items-center">
                    <div>
                        <span class="font-semibold text-sm">202</span> <span>&#183;</span>
                        <span class="font-semibold text-sm">555</span> <span>&#183;</span>
                        <span class="font-semibold text-sm">9255</span>
                    </div>
                    <i class="fa fa-search"></i>
                </li>
            </ul>
        </nav>
        <div class="bg-gray-100 sm:px-6 flex justify-center mt-4">
            <?php
            if ($result2->num_rows > 0) {
                while ($row = $result2->fetch_assoc()) { ?>
                    <div class="flex flex-col justify-center items-center p-2 opacity-40 hover:opacity-80 hover:bg-white w-[150px] cursor-pointer <?php if ($category == null) {
                        $category = strtolower($row["category"]);
                        echo "opacity-80 bg-white";
                    } else {
                        if ($category == strtolower($row["category"])) {
                            echo "opacity-80 bg-white";
                        }
                    }
                    ?>" onclick="changeCategory('<?php echo strtolower($row['category']); ?>')">
                        <img class="h-14 w-14 mt-4" src=<?php echo $icon[$row["category"]]; ?> />
                        <h1 class="uppercase font-bold text-xs mt-4 leading-6"><?php echo $row["category"] ?></h1>
                    </div>
                <?php }
            } ?>
        </div>
        <div class="mt-6 mb-6 border-b-[.45rem] border-gray-100 p-6">
            <div class="flex justify-center items-center">
                <div class="flex flex-1 space-x-4 items-center px-2">
                    <div>
                        <input type="checkbox" name="type" id="chairs" value="chairs" />
                        <label for="chairs" class="text-gray-700">Chairs</label>
                    </div>
                    <div>
                        <input type="checkbox" name="type" id="ottomans" value="ottomans" />
                        <label for="ottomans" class="text-gray-700">Ottomans</label>
                    </div>
                </div>

                <div class="flex flex-1 border-l-2 border-r-2 px-2 border-black/50">
                    <select name="colors"
                        class="w-full text-gray-700 p-2 rounded focus:outline-none focus:ring-2 focus:ring-gray-400">
                        <option value="anycolors">Any Colors</option>
                        <option value="red">Red</option>
                        <option value="blue">Blue</option>
                        <option value="green">Green</option>
                    </select>
                </div>

                <div class="flex flex-1 border-r-2 border-black/50 px-2">
                    <select name="designer"
                        class="w-full text-gray-700 p-2 rounded focus:outline-none focus:ring-2 focus:ring-gray-400">
                        <option value="anydesigner">Any Designer</option>
                        <option value="red">Red</option>
                        <option value="blue">Blue</option>
                        <option value="green">Green</option>
                    </select>
                </div>

                <div class="flex flex-1 gap-2 px-2 items-center">
                    <span class="text-gray-700">Price</span>
                    <input type="range"
                        class="h-2 bg-gray-300 rounded-lg appearance-none cursor-pointer accent-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-400" />
                    <span class="text-gray-700">$9500</span>
                </div>
            </div>
        </div>

    </header>
    <main>
        <header class="flex justify-between">
            <h1 class="text-3xl font-black uppercase"><?php echo ucfirst($category); ?></h1>
            <div>
                <span>Sort by:</span>
                <span class="font-black cursor-pointer hover:opacity-80">Name</span>
                <span>&#183;</span>
                <span class="cursor-pointer hover:opacity-80">Popularity</span>
                <span>&#183;</span>
                <span class="cursor-pointer hover:opacity-80">Price</span>
            </div>
        </header>
        <div class="grid mt-4 gap-4 grid-cols-1 mb-6 border-gray-100 sm:grid-cols-2 md:grid-cols-4 p-4">
            <?php
            $stmt = $conn->prepare("SELECT * FROM furniture WHERE category = ?");
            $stmt->bind_param("s", $category);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) { ?>
                    <div
                        class="flex flex-col items-center hover:scale-105 shadow-sm cursor-pointer hover:shadow-md transition-all pb-4">
                        <div class="w-full h-48 bg-gray-400"></div>
                        <p class="mt-2"><?php echo $row['name'] ?></p>
                        <p class="text-lg font-bold">$<?php echo $row['price'] ?></p>
                    </div>
                <?php }
            } else { ?>
                <h1 class="col-span-4 text-center">No products found</h1>
            <?php } ?>
        </div>

        <footer class=" border-t-[.45rem] p-4 flex justify-between pb-[4rem]">
            <h2 class="cursor-pointer hover:underline">Show more chairs</h2>
            <div>
                <span class="text-lg text-gray-400">&larr;</span>
                <span class="text-lg font-bold">3</span>
                <span class="text-lg text-gray-400">&rarr;</span>
            </div>
        </footer>
    </main>
    <footer class="bg-gray-100 pt-14 pb-8 flex space-y-2 flex-col justify-center items-center">
        <div>
            <ul class="flex gap-4 uppercase font-semibold">
                <li class="cursor-pointer hover:underline">catalog</li>
                <li class="cursor-pointer hover:underline">designers</li>
                <li class="cursor-pointer hover:underline">blog</li>
                <li class="cursor-pointer hover:underline">inspiration</li>
                <li class="cursor-pointer hover:underline">about us</li>
                <li class="cursor-pointer hover:underline">contact</li>
            </ul>
        </div>
        <p>&copy; 2024 .furniture All rights reserved</p>
    </footer>
    <script src="./scripts/main.js"></script>
</body>

</html>