<?php
include('../db/db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Newstok - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css"
        integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col items-center justify-center">
        <p>Welcome <b><?php echo $_SESSION["display_name"]; ?></b> (<?php echo ucfirst($_SESSION["type"]); ?>)</p>
        <h1 class="mb-4">Dashboard of Newstok | <a href="logout.php"
                class="uppercase text-red-400 hover:underline font-bold">Logout</a></h1>
        <?php if ($_SESSION["user_sts"] == 2) { ?>
            <?php if ($_SESSION["user_type"] == "admin") { ?>
                <div class="bg-white p-8 rounded-lg shadow-lg max-w-sm w-full">
                    <main class="grid grid-cols-2 gap-2">
                        <a href="news"
                            class="w-[150px] h-[100px] bg-lime-600 text-white text-center pt-[23%] hover:opacity-80 relative">News<i
                                class="fa-solid fa-newspaper absolute -bottom-[10px] -right-[10px] text-6xl opacity-60"></i></a>
                        <a href="gallery"
                            class="w-[150px] h-[100px] bg-blue-600 text-white text-center pt-[23%] hover:opacity-80 relative">Gallery<i
                                class="fa-solid fa-image absolute -bottom-[10px] -right-[10px] text-6xl opacity-60"></i></a>
                        <a href="menu"
                            class="w-[150px] h-[100px] bg-emerald-600 text-white text-center pt-[23%] hover:opacity-80 relative">Menu<i
                                class="fa-solid fa-question absolute -bottom-[10px] -right-[10px] text-6xl opacity-60"></i></a>
                        <a href="contact"
                            class="w-[150px] h-[100px] bg-green-600 text-white text-center pt-[23%] hover:opacity-80 relative">Contact<i
                                class="fa-solid fa-fill absolute -bottom-[10px] -right-[10px] text-6xl opacity-60"></i></a>
                        <a href="users"
                            class="col-span-2 h-[100px] bg-rose-600 text-white text-center pt-[13%] hover:opacity-80 relative">Users<i
                                class="fa-solid fa-user absolute -bottom-[10px] -right-[10px] text-6xl opacity-60"></i></a>
                    </main>
                </div>
            <?php } else { ?>
                <div class="bg-white p-8 rounded-lg shadow-lg max-w-sm w-full">
                    <main class="grid grid-cols-2 gap-2">
                        <a href="news"
                            class="w-[150px] h-[100px] bg-lime-600 text-white text-center pt-[23%] hover:opacity-80 relative">News<i
                                class="fa-solid fa-newspaper absolute -bottom-[10px] -right-[10px] text-6xl opacity-60"></i></a>
                        <a href="gallery"
                            class="w-[150px] h-[100px] bg-blue-600 text-white text-center pt-[23%] hover:opacity-80 relative">Gallery<i
                                class="fa-solid fa-image absolute -bottom-[10px] -right-[10px] text-6xl opacity-60"></i></a>
                    </main>
                </div>
            <?php } ?>
        <?php } else { ?>
            <div class="bg-white p-8 rounded-lg shadow-lg max-w-sm w-full">

                <main class="grid grid-cols-2 gap-2">
                    <a href="#"
                        class="col-span-2 h-[100px] bg-rose-600 text-white text-center pt-[3%] p-4 hover:opacity-80 relative">
                        <span class="break-words">
                            Please wait before your account is approved by Administrator
                        </span>
                        <i class="fa-solid fa-user absolute -bottom-[10px] -right-[10px] text-6xl opacity-60"></i>
                    </a>
                </main>
            </div>
        <?php } ?>
    </div>
</body>

</html>