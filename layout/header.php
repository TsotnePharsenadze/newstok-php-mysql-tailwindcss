<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? "Newstok" ?></title>
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
                    <a class="<?php echo $_SERVER["PHP_SELF"] == $menuItem["url"] ? "underline hover:no-underline " : "hover:underline "; ?> text-nowrap"
                        href="<?php echo $menuItem["url"] ?>"><?php echo $menuItem["name"]; ?></a>
                <?php }
                ?>
                <div class="border flex items-center rounded-md">
                    <a href="<?php echo $buttonLogin ?? "./portal/login.php" ?>"
                        class="hover:bg-blue-400 p-2 border-r">Login</a>
                    <a href="<?php echo $buttonRegister ?? "./portal/register.php" ?>"
                        class="hover:bg-blue-400 p-2">Register</a>
                </div>
            </nav>
        </div>
    </header>