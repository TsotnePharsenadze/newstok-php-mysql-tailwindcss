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
                <li class="flex items-center gap-2">
                    <button class="flex flex-col justify-center items-center space-y-[2px]">
                        <span
                            class="bg-gray-500 block transition-all duration-300 ease-out h-0.5 w-3 rounded-sm"></span>
                        <span
                            class="bg-gray-500 block transition-all duration-300 ease-out h-0.5 w-3 rounded-sm"></span>
                        <span
                            class="bg-gray-500 block transition-all duration-300 ease-out h-0.5 w-3 rounded-sm"></span>
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
            <div class="flex flex-col justify-center items-center p-2 bg-white w-[150px]">
                <img class="h-14 w-14 mt-4" src="./public/sharp-solid/bed.svg" />
                <h1 class="uppercase font-bold text-xs mt-4 leading-6">sleeping bed</h1>
            </div>
            <div class="flex flex-col justify-center items-center p-2 bg-white w-[150px]">
                <img class="h-14 w-14 mt-4" src="./public/sharp-solid/chair.svg" />
                <h1 class="uppercase font-bold text-xs mt-4 leading-6">chair</h1>
            </div>
            <div class="flex flex-col justify-center items-center p-2 bg-white w-[150px]">
                <img class="h-14 w-14 mt-4" src="./public/sharp-solid/chair-office.svg" />
                <h1 class="uppercase font-bold text-xs mt-4 leading-6">office chair</h1>
            </div>
            <div class="flex flex-col justify-center items-center p-2 bg-white w-[150px]">
                <img class="h-14 w-14 mt-4" src="./public/sharp-solid/table-picnic.svg" />
                <h1 class="uppercase font-bold text-xs mt-4 leading-6">table picnic</h1>
            </div>
            <div class="flex flex-col justify-center items-center p-2 bg-white w-[150px]">
                <img class="h-14 w-14 mt-4" src="./public/sharp-solid/kitchen-set.svg" />
                <h1 class="uppercase font-bold text-xs mt-4 leading-6">Kitchen set</h1>
            </div>
        </div>
        <div class="mt-6 mb-6 border-b-[.45rem] p-6">
            <div class="flex justify-center items-center">
                <div class="flex flex-1 space-x-4 items-center px-2">
                    <div>
                        <input type="checkbox" name="type" id="chairs" value="chairs" />
                        <label for="chairs">Chairs</label>
                    </div>
                    <div>
                        <input type="checkbox" name="type" id="ottomans" value="ottomans" />
                        <label for="chairs">Ottomans</label>
                    </div>
                </div>
                <div class="flex flex-1 border-l-2 border-r-2 px-2 border-black/50">
                    <select name="colors">
                        <option value="anycolors">Any Colors</option>
                        <option value="red">red</option>
                        <option value="blue">blue</option>
                        <option value="green">green</option>
                    </select>
                </div>
                <div class="flex flex-1 border-r-2 border-black/50 px-2">
                    <select name="designer">
                        <option value="anydesigner">Any Designer</option>
                        <option value="red">red</option>
                        <option value="blue">blue</option>
                        <option value="green">green</option>
                    </select>
                </div>
                <div class="flex flex-1 gap-2 px-2">
                    <span>Price</span>
                    <input type="range" />
                    <span>$9500</span>
                </div>
            </div>
        </div>
    </header>
    <main>
        <header class="flex justify-between">
            <h1 class="text-3xl font-black">LOUNGE CHAIRS</h1>
            <div>
                <span>Sort by:</span>
                <span class="font-black">Name</span>
                <span>&#129;</span>
                <span>Popularity</span>
                <span>&#129;</span>
                <span>Price</span>
            </div>
        </header>
        <div class="grid mt-4 gap-2 grid-cols-1 sm:grid-cols-2 md:grid-cols-4 bg-green-100">
            <div class="w-full col-span-1">
                <div style="width: 200px; height: 200px; background-color: gray"></div>
                <p>Lounge Chair</p>
                <p>$5.178</p>
            </div>
            <div>
                <div style="width: 200px; height: 200px; background-color: gray"></div>
                <p>Lounge Chair</p>
                <p>$5.178</p>
            </div>
            <div>
                <div style="width: 200px; height: 200px; background-color: gray"></div>
                <p>Lounge Chair</p>
                <p>$5.178</p>
            </div>
            <div>
                <div style="width: 200px; height: 200px; background-color: gray"></div>
                <p>Lounge Chair</p>
                <p>$5.178</p>
            </div>
            <div>
                <div style="width: 200px; height: 200px; background-color: gray"></div>
                <p>Lounge Chair</p>
                <p>$5.178</p>
            </div>
        </div>
        <footer>
            <h2>Show more chairs</h2>
            <div>
                <span>&larr;</span>
                <span>3</span>
                <span>&rarr;</span>
            </div>
        </footer>
    </main>
    <footer>
        <div>
            <ul>
                <li>catalog</li>
                <li>designers</li>
                <li>blog</li>
                <li>inspiration</li>
                <li>about us</li>
                <li>contact</li>
            </ul>
        </div>
        <p>&copy; 2024 .furniture All rights reserved</p>
    </footer>
</body>

</html>