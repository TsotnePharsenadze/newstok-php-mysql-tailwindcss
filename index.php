<?php
include "./db/db.php";
include "./helpers/icons.php";
$pageSize = 12;
$dataCount = null;
if (isset($_GET["page"]) && is_numeric($_GET["page"]) && $_GET["page"] > 0) {
    $page = (int) $_GET["page"];
} else {
    $page = 1;
}
$resultCategories = $conn->query("SELECT DISTINCT category from furniture");
$resultColors = $conn->query("SELECT DISTINCT color from furniture");
$resultBrands = $conn->query("SELECT DISTINCT brand from furniture");
$resultPrices = $conn->query("SELECT MIN(price) as min_price, MAX(price) as max_price from furniture");
$resultPricesFetch = $resultPrices->fetch_assoc();
$category = $_GET["category"] ?? null;
$colors = $_GET["colors"] ?? null;
$brand = $_GET["brand"] ?? null;
$price = $_GET["price"] ?? null;
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

<body class="p-1 md:p-6 max-w-[1000px] mx-auto w-full shadow-lg">
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
            if ($resultCategories->num_rows > 0) {
                while ($row = $resultCategories->fetch_assoc()) { ?>
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
            <?php
            $stmt = $conn->prepare("SELECT COUNT(*) as data_count FROM furniture WHERE category = ?");
            $stmt->bind_param("s", $category);
            $stmt->execute();
            $resultCount = $stmt->get_result();
            $dataCount = $resultCount->fetch_assoc()["data_count"];
            ?>
        </div>
        <div class="mt-6 mb-6 border-b-[.45rem] border-gray-100 p-6">
            <div class="flex justify-center items-center">
                <div class="flex flex-1 border-r-2 px-2 border-black/50">
                    <select name="colors"
                        class="w-full text-gray-700 p-2 rounded focus:outline-none focus:ring-2 focus:ring-gray-400">
                        <option value="anycolors">Any Colors</option>
                        <?php
                        if ($resultColors->num_rows > 0) {
                            while ($row = $resultColors->fetch_assoc()) { ?>
                                <option value="<?php echo $row["color"]; ?>"><?php echo ucfirst($row["color"]); ?></option>
                            <?php }
                        }
                        ?>
                    </select>
                </div>
                <div class="flex flex-1 border-r-2 border-black/50 px-2">
                    <select name="designer"
                        class="w-full text-gray-700 p-2 rounded focus:outline-none focus:ring-2 focus:ring-gray-400">
                        <option value="anydesigner">Any Designer</option>
                        <?php
                        if ($resultBrands->num_rows > 0) {
                            while ($row = $resultBrands->fetch_assoc()) { ?>
                                <option value="<?php echo $row["brand"]; ?>"><?php echo ucfirst($row["brand"]); ?></option>
                            <?php }
                        }
                        ?>
                    </select>
                </div>

                <div class="flex flex-1 gap-2 px-2 items-center">
                    <span class="text-gray-700">Price</span>
                    <input type="range" min="<?php echo $resultPricesFetch["min_price"]; ?>"
                        max="<?php echo $resultPricesFetch["max_price"]; ?>"
                        value="<?php echo $resultPricesFetch["max_price"]; ?>" oninput="updateValue(this.value)"
                        class="h-2 bg-gray-300 rounded-lg appearance-none cursor-pointer accent-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-400" />
                    <span id="priceRange" class="text-gray-700">$<?php echo $resultPricesFetch["max_price"]; ?></span>
                </div>
            </div>
            <button type="button" onClick="filter()" class="bg-gray-900 text-gray-100 w-full py-1.5 rounded-md mt-4 hover:opacity-90 cursor-pointer">Filter</button>
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
            $offset = ((int) $page - 1) * $pageSize;
            $stmt = $conn->prepare("SELECT * FROM furniture WHERE category = ? LIMIT ? OFFSET ?");
            $stmt->bind_param("sii", $category, $pageSize, $offset);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) { ?>
                    <div
                        class="flex flex-col items-center hover:scale-105 shadow-sm cursor-pointer hover:shadow-md transition-all pb-4">
                        <img src="./storage/public/chair.png" class="w-full h-48" />
                        <p class="mt-2"><?php echo $row['name'] ?></p>
                        <p class="text-lg font-bold">$<?php echo $row['price'] ?></p>
                    </div>
                <?php }
            } else { ?>
                <h1 class="col-span-4 text-center">No products found</h1>
            <?php } ?>
        </div>

        <footer class=" border-t-[.45rem] p-4 flex justify-between pb-[4rem]">

            <h2 class="w-full max-w-[200px] <?php if ($page <= 1) {
                echo "opacity-50 cursor-default";
            } else {
                echo "cursor-pointer hover:underline ";
            } ?>" <?php if ($page > 1) {
                 echo 'onClick="showMore(1)"';
             } ?>>Go back</h2>


            <div class="mx-auto">
                <span class="text-lg text-gray-400">&larr;</span>
                <span class="text-lg font-bold"><?php echo $page; ?></span>
                <span class="text-lg text-gray-400">&rarr;</span>
            </div>

            <h2 class="text-right w-full max-w-[200px] <?php if ($page >= ceil($dataCount / $pageSize)) {
                echo "opacity-50 cursor-default";
            } else {
                echo "cursor-pointer hover:underline  ";
            } ?>" <?php if ($page < ceil($dataCount / $pageSize)) {
                 echo 'onClick="showMore()"';
             } ?>>Show more</h2>
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