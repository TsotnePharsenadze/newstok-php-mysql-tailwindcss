<?php
session_start();
unset($_SESSION["HTTP_REFERER"]);
include('../../../db/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}

$author_id = $_SESSION["user_id"];

$limit = isset($_GET["pageSizeNews"]) ? (int) $_GET["pageSizeNews"] : 5;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$searchNews = isset($_GET["searchNews"]) ? $_GET["searchNews"] : "";

$sort_column = isset($_GET['sort']) ? $_GET['sort'] : 'createdAt';
$sort_order = isset($_GET['order']) ? $_GET['order'] === 'desc' ? 'DESC' : 'ASC' : "DESC";

$sortable_columns = ['title', 'description', 'sts', 'time', 'createdAt', 'updatedAt', "delete_date", "recovery_date"];
if (!in_array($sort_column, $sortable_columns)) {
    $sort_column = 'createdAt';
}

$query = "SELECT * FROM news WHERE sts = '3' AND (
    title LIKE '%$searchNews%' 
    OR description LIKE '%$searchNews%' OR time LIKE '%$searchNews%' 
OR createdAt LIKE '%$searchNews%' 
OR updatedAt LIKE '%$searchNews%' 
OR delete_date LIKE '%$searchNews%' 
OR recovery_date LIKE '%$searchNews%'
) AND author_id='$author_id' ORDER BY $sort_column $sort_order LIMIT $limit OFFSET $offset";

$result = $conn->query($query);

$total_query = "SELECT COUNT(*) as total FROM news WHERE sts = '3' AND (
title LIKE '%$searchNews%' 
OR description LIKE '%$searchNews%' OR time LIKE '%$searchNews%' 
OR createdAt LIKE '%$searchNews%' 
OR updatedAt LIKE '%$searchNews%' 
OR delete_date LIKE '%$searchNews%' 
OR recovery_date LIKE '%$searchNews%'
) AND author_id='$author_id' ORDER BY $sort_column $sort_order LIMIT $limit OFFSET $offset";

$total_result = $conn->query($total_query);

$total_row = $total_result->fetch_assoc();
$total_records = isset($total_row["total"]) ? $total_row['total'] : 1;
$total_pages = ceil($total_records / $limit);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Newstoks - Deleted News index</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css"
        integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script>
        function clearMsg() {
            document.querySelector(".msg").style.display = "none";
            const url = new URL(window.location.href);
            url.searchParams.delete("msg");
            window.history.replaceState(null, "", url);
        }

        function sortTable(column, order, tag = false) {
            const url = new URL(window.location.href);

            url.searchParams.set('sort', column);
            url.searchParams.set('order', order);

            window.location.href = url.toString();
        }

        function pageParam(type = 0, off = 0) {
            const url = new URL(window.location.href);

            const currentPage = parseInt(url.searchParams.get("page")) || 1;
            url.searchParams.set("page", type ? currentPage + 1 : currentPage - 1);

            window.location.href = url.toString();
        }

        function handlePageSizeSubmit(e, type) {
            let index = e.target.value;

            const url = new URL(window.location.href);

            url.searchParams.set("pageSizeNews", index);

            window.location.href = url.toString();
        }


        function searchQuery() {
            const url = new URL(window.location.href);

            let value = document.querySelector("input[name='searchNews']").value;
            url.searchParams.set("searchNews", value);

            window.location.href = url.toString();
        }
    </script>
</head>

<body class="bg-gray-100 p-4">
    <div class="container mx-auto mt-10">
        <a href="../index.php" class="text-blue-400 hover:underline">&lBarr; Go back</a>

        <?php
        if (!empty($_GET["msg"])) { ?>
            <div
                class="msg bg-green-200 w-full flex p-2 border-l-[10px] border-l-green-400 rounded-md flex flex-col pr-6 pl-6 mb-2 mt-2">
                <div class="flex justify-between mb-2">
                    <h1 class="font-bold text-2xl">Success</h1>
                    <span class="text-2xl cursor-pointer hover:opacity-80" onClick="clearMsg()">&times;</span>
                </div>
                <p><?php echo $_GET["msg"]; ?></p>
            </div>
        <?php }
        ?>
        <div>
            <div class="flex justify-between items-center mb-6">
                <div class="flex gap-4 items-centers flex-col sm:flex-row">
                    <h2 class="text-2xl font-bold">Deleted News List 📰</h2>
                    <select name="pageSizeNews" onchange="handlePageSizeSubmit(event, 1)"
                        class="p-[0.3rem_0.7rem_0.3rem_0.7rem] text-lg border-[2px] border-black rounded-md font-semibold">
                        <option value="5" <?php echo $limit == 5 ? "selected" : ""; ?>>5 Records
                        </option>
                        <option value="10" <?php echo $limit == 10 ? "selected" : ""; ?>>10 Records
                        </option>
                        <option value="15" <?php echo $limit == 15 ? "selected" : ""; ?>>15 Records
                        </option>
                        <option value="35" <?php echo $limit == 35 ? "selected" : ""; ?>>35 Records
                        </option>
                        <option value="50" <?php echo $limit == 50 ? "selected" : ""; ?>>50 Records
                        </option>
                    </select>
                </div>
                <div class="flex items-center">
                    <input type="search" name="searchNews" class="p-2 border-black border-[2px] rounded-md w-[300px]"
                        placeholder="Search for news" value="<?php echo $searchNews; ?>" />
                    <button onclick="searchQuery()"
                        class="border-[2px] border-black bg-blue-500 text-white p-[0.5rem_0.9rem_0.5rem_0.9rem] ml-2 rounded-full hover:opacity-80 cursor-pointer">
                        <i class="fa-solid fa-search"></i></button>
                </div>
                <div class="flex gap-2">
                    <a href="<?php if ($total_records > 0) {
                        echo "hard_delete_news.php";
                    } else {
                        echo "javascript:void(0)";
                    } ?>" class="bg-rose-500 text-white px-4 py-2 rounded-md <?php if ($total_records == 0) {
                         echo "opacity-80 cursor-not-allowed";
                     } ?>">Deleted
                        All
                        Records Permanently</a>
                </div>
            </div>
            <div class="bg-white shadow-md rounded-lg overflow-x-scroll">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="w-full">
                            <th class="border p-4 text-left">
                                <div class="flex justify-between items-center">
                                    <a href="javascript:void(0)"
                                        onClick="sortTable('title', '<?php echo $sort_order === 'ASC' ? 'desc' : 'asc'; ?>')"
                                        class="text-blue-500 w-full">
                                        Title
                                    </a>
                                    <?php if ($sort_column == "title") {
                                        echo $sort_order === "ASC"
                                            ? "<span class='text-xl font-bold text-blue-500'>&uarr;</span>"
                                            : "<span class='text-xl font-bold text-blue-500'>&darr;</span>";
                                    } ?>
                                </div>
                            </th>
                            <th class="border p-4 text-left">
                                <div class="flex justify-between items-center">
                                    <a href="javascript:void(0)"
                                        onClick="sortTable('description', '<?php echo $sort_order === 'ASC' ? 'desc' : 'asc'; ?>')"
                                        class="text-blue-500 w-full">
                                        Description
                                    </a>
                                    <?php if ($sort_column == "description") {
                                        echo $sort_order === "ASC"
                                            ? "<span class='text-xl font-bold text-blue-500'>&uarr;</span>"
                                            : "<span class='text-xl font-bold text-blue-500'>&darr;</span>";
                                    } ?>
                                </div>
                            </th>

                            <th class="border p-4 text-left">
                                <div class="flex justify-between items-center">
                                    <a href="javascript:void(0)"
                                        onClick="sortTable('sts', '<?php echo $sort_order === 'ASC' ? 'desc' : 'asc'; ?>')"
                                        class="text-blue-500 w-full">
                                        Status
                                    </a>
                                    <?php if ($sort_column == "sts") {
                                        echo $sort_order === "ASC"
                                            ? "<span class='text-xl font-bold text-blue-500'>&uarr;</span>"
                                            : "<span class='text-xl font-bold text-blue-500'>&darr;</span>";
                                    } ?>
                                </div>
                            </th>
                            <th class="border p-4 text-left">
                                <div class="flex justify-between items-center">
                                    <a href="javascript:void(0)"
                                        onClick="sortTable('time', '<?php echo $sort_order === 'ASC' ? 'desc' : 'asc'; ?>')"
                                        class="text-blue-500 w-full">
                                        Time
                                    </a>
                                    <?php if ($sort_column == "time") {
                                        echo $sort_order === "ASC"
                                            ? "<span class='text-xl font-bold text-blue-500'>&uarr;</span>"
                                            : "<span class='text-xl font-bold text-blue-500'>&darr;</span>";
                                    } ?>
                                </div>
                            </th>
                            <th class="border p-4 text-left">
                                <div class="flex justify-between items-center">
                                    <a href="javascript:void(0)"
                                        onClick="sortTable('createdAt', '<?php echo $sort_order === 'ASC' ? 'desc' : 'asc'; ?>')"
                                        class="text-blue-500 w-full">
                                        Created At
                                    </a>
                                    <?php if ($sort_column == "createdAt") {
                                        echo $sort_order === "ASC"
                                            ? "<span class='text-xl font-bold text-blue-500'>&uarr;</span>"
                                            : "<span class='text-xl font-bold text-blue-500'>&darr;</span>";
                                    } ?>
                                </div>
                            </th>
                            <th class="border p-4 text-left">
                                <div class="flex justify-between items-center">
                                    <a href="javascript:void(0)"
                                        onClick="sortTable('updatedAt', '<?php echo $sort_order === 'ASC' ? 'desc' : 'asc'; ?>')"
                                        class="text-blue-500 w-full">
                                        Updated At
                                    </a>
                                    <?php if ($sort_column == "updatedAt") {
                                        echo $sort_order === "ASC"
                                            ? "<span class='text-xl font-bold text-blue-500'>&uarr;</span>"
                                            : "<span class='text-xl font-bold text-blue-500'>&darr;</span>";
                                    } ?>
                                </div>
                            </th>
                            <th class="border p-4 text-left">
                                <div class="flex justify-between items-center">
                                    <a href="javascript:void(0)"
                                        onClick="sortTable('delete_date', '<?php echo $sort_order === 'ASC' ? 'desc' : 'asc'; ?>')"
                                        class="text-blue-500 w-full">
                                        Delete Date
                                    </a>
                                    <?php if ($sort_column == "delete_date") {
                                        echo $sort_order === "ASC"
                                            ? "<span class='text-xl font-bold text-blue-500'>&uarr;</span>"
                                            : "<span class='text-xl font-bold text-blue-500'>&darr;</span>";
                                    } ?>
                                </div>
                            </th>
                            <th class="border p-4 text-left">
                                <div class="flex justify-between items-center">
                                    <a href="javascript:void(0)"
                                        onClick="sortTable('recovery_date', '<?php echo $sort_order === 'ASC' ? 'desc' : 'asc'; ?>')"
                                        class="text-blue-500 w-full">
                                        Recovery Date
                                    </a>
                                    <?php if ($sort_column == "recovery_date") {
                                        echo $sort_order === "ASC"
                                            ? "<span class='text-xl font-bold text-blue-500'>&uarr;</span>"
                                            : "<span class='text-xl font-bold text-blue-500'>&darr;</span>";
                                    } ?>
                                </div>
                            </th>
                            <th class="border p-4 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td class=" border p-4  overflow-x-auto max-w-[280px]"><?php echo $row['title']; ?></td>
                                <td class="border p-4  overflow-x-auto max-w-[280px]"><?php echo $row['description']; ?>
                                </td>
                                <td class="border p-4">
                                    <span
                                        class="bg-rose-100 text-rose-800 font-medium me-2 px-2.5 py-0.5 rounded uppercase">deleted</span>
                                </td>
                                <td class="border p-4">
                                    <?php echo explode(".", $row["time"])[0]; ?>
                                </td>
                                <td class="border p-4"><?php echo explode(".", $row['createdAt'])[0]; ?></td>
                                <td class="border p-4"><?php echo explode(".", $row['updatedAt'])[0]; ?></td>
                                <td class="border p-4">
                                    <?php if ($row["delete_date"]) {
                                        echo explode(".", $row['delete_date'])[0];
                                    } else {
                                        echo "Null";
                                    } ?>
                                </td>
                                <td class="border p-4">
                                    <?php if ($row["recovery_date"]) {
                                        echo explode(".", $row['recovery_date'])[0];
                                    } else {
                                        echo "Null";
                                    } ?>
                                </td>
                                <td class="border p-4">
                                    <a href="recover_news.php?id=<?php echo $row['id']; ?>"
                                        class="text-green-500 hover:underline">Recover</a> |
                                    <a href="hard_delete_news.php?id=<?php echo $row['id']; ?>"
                                        class="text-red-500 hover:underline">Delete Permanently</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <nav class="flex justify-between items-center w-full mr-auto mt-2 text-xl">
            <ul class="flex space-x-4 items-center">
                <?php if ($page > 1): ?>
                    <li class="border-[2px] border-black/30 p-2 w-full hover:bg-gray-200 cursor-pointer"
                        onClick="pageParam()">
                        <a href="javascript:void(0)" class="text-blue-500 hover:text-blue-700">Previous</a>
                    </li>
                <?php endif; ?>

                <?php if ($page < $total_pages): ?>
                    <li class="border-[2px] border-black/30 p-2 w-full hover:bg-gray-200 cursor-pointer"
                        onClick="pageParam(1)">
                        <a href="javascript:void(0)" class="text-blue-500 hover:text-blue-700">Next</a>
                    </li>
                <?php endif; ?>

            </ul>
            <ul>
                <li> <?php
                echo "<span>Page: $page</span>"; ?></li>
            </ul>
        </nav>
    </div>
    <script>
        let searchNewsInput = document.querySelector("input[name='searchNews']");
        searchNewsInput.addEventListener("input", () => {
            if (searchNewsInput.value === "") {
                searchQuery();
            }
        })
    </script>
</body>

</html>