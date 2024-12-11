<?php
session_start();
unset($_SESSION["HTTP_REFERER"]);
include('../../db/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$limit = isset($_GET["pageSizeNews"]) ? (int) $_GET["pageSizeNews"] : 5;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$limitTag = isset($_GET["pageSizeTags"]) ? (int) $_GET["pageSizeTags"] : 5;
$pageTag = isset($_GET['pageTag']) ? (int) $_GET['pageTag'] : 1;
$offsetTag = ($pageTag - 1) * $limitTag;


$sort_column = isset($_GET['sort']) ? $_GET['sort'] : 'createdAt';
$sort_order = isset($_GET['order']) ? $_GET['order'] === 'desc' ? 'DESC' : 'ASC' : "DESC";
$sortTag_column = isset($_GET['sortTag']) ? $_GET['sortTag'] : 'createdAt';
$sortTag_order = isset($_GET['orderTag']) ? $_GET['orderTag'] === 'desc' ? 'DESC' : 'ASC' : "DESC";

$sortable_columns = ['title', 'description', 'sts', 'time', 'createdAt', 'updatedAt'];
if (!in_array($sort_column, $sortable_columns)) {
    $sort_column = 'createdAt';
}

$sortTagable_columns = ["id", "name", "createdAt", "updatedAt"];
if (!in_array($sortTag_column, $sortTagable_columns)) {
    $sort_column = 'createdAt';
}

$result = $conn->query("SELECT * FROM news WHERE sts <> '3' ORDER BY $sort_column $sort_order LIMIT $limit OFFSET $offset");

$total_result = $conn->query("SELECT COUNT(*) as total FROM news");
$total_row = $total_result->fetch_assoc();
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $limit);

$resultTags = $conn->query("SELECT * FROM tags WHERE sts <> '3' ORDER BY $sortTag_column $sortTag_order LIMIT $limitTag OFFSET $offsetTag");

$total_resultTags = $conn->query("SELECT COUNT(*) as total FROM tags WHERE sts <> '3'");
$total_rowTags = $total_resultTags->fetch_assoc();
$total_recordsTags = $total_rowTags['total'];
$total_pagesTags = ceil($total_recordsTags / $limitTag);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Newstoks - News index</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function clearMsg() {
            document.querySelector(".msg").style.display = "none";
            const url = new URL(window.location.href);
            url.searchParams.delete("msg");
            window.history.replaceState(null, "", url);
        }

        function sortTable(column, order, tag = false) {
            const url = new URL(window.location.href);
            if (tag) {
                url.searchParams.set('sortTag', column);
                url.searchParams.set('orderTag', order);
            } else {
                url.searchParams.set('sort', column);
                url.searchParams.set('order', order);
            }
            window.location.href = url.toString();
        }

        function pageParam(type = 0, off = 0) {
            const url = new URL(window.location.href);

            if (!off) {
                const currentPage = parseInt(url.searchParams.get("page")) || 1;
                url.searchParams.set("page", type ? currentPage + 1 : currentPage - 1);
            } else {
                const currentPageTag = parseInt(url.searchParams.get("pageTag")) || 1;
                url.searchParams.set("pageTag", type ? currentPageTag + 1 : currentPageTag - 1);
            }

            window.location.href = url.toString();
        }

        function handlePageSizeSubmit(e, type) {
            let index = e.target.value;

            const url = new URL(window.location.href);

            if (!type) {
                url.searchParams.set("pageSizeTags", index);
            } else {
                url.searchParams.set("pageSizeNews", index);
            }

            window.location.href = url.toString();
        }

    </script>
</head>

<body class="bg-gray-100 p-4">
    <div class="container mx-auto mt-10">
        <a href="../dashboard.php" class="text-blue-400 hover:underline">&lBarr; Go back</a>

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
                    <h2 class="text-2xl font-bold">News List 📰</h2>
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
                <div class="flex gap-2">
                    <a href="news-crud/deleted_news.php" class="bg-rose-500 text-white px-4 py-2 rounded-md">Deleted
                        News
                        Records</a>
                    <a href="news-crud/create_news.php" class="bg-blue-500 text-white px-4 py-2 rounded-md">Create
                        News</a>
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
                            <th class="border p-4 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td class=" border p-4"><?php echo $row['title']; ?></td>
                                <td class="border p-4"><?php echo $row['description']; ?></td>
                                <td class="border p-4">
                                    <span
                                        class="<?php echo $row['sts'] == 1 ? "bg-blue-100 text-blue-800" : "bg-green-100 text-green-800"; ?> font-medium me-2 px-2.5 py-0.5 rounded uppercase"><?php echo $row['sts'] == 1 ? "drafted" : "published"; ?></span>
                                </td>
                                <td class="border p-4">
                                    <?php echo explode(".", $row["time"])[0]; ?>
                                </td>
                                <td class="border p-4"><?php echo explode(".", $row['createdAt'])[0]; ?></td>
                                <td class="border p-4"><?php echo explode(".", $row['updatedAt'])[0]; ?></td>
                                <td class="border p-4">
                                    <a href="news-crud/edit_news.php?id=<?php echo $row['id']; ?>"
                                        class="text-blue-500">Edit</a> |
                                    <a href="news-crud/delete_news.php?id=<?php echo $row['id']; ?>"
                                        class="text-red-500">Delete</a>
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
        <hr class="mt-2 mb-2" />
        <div class="mt-6">
            <div class="flex justify-between items-center mb-6">
                <div class="flex gap-4 items-center flex-col sm:flex-row">
                    <h2 class="text-2xl font-bold">Tags List 🧷</h2>
                    <select name="pageSizeTags" onchange="handlePageSizeSubmit(event, 0)"
                        class="p-[0.3rem_0.7rem_0.3rem_0.7rem] text-lg border-[2px] border-black rounded-md font-semibold">
                        <option value="5" <?php echo $limitTag == 5 ? "selected" : ""; ?>>5 Records
                        </option>
                        <option value="10" <?php echo $limitTag == 10 ? "selected" : ""; ?>>10 Records
                        </option>
                        <option value="15" <?php echo $limitTag == 15 ? "selected" : ""; ?>>15 Records
                        </option>
                        <option value="35" <?php echo $limitTag == 35 ? "selected" : ""; ?>>35 Records
                        </option>
                        <option value="50" <?php echo $limitTag == 50 ? "selected" : ""; ?>>50 Records
                        </option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <a href="tags-crud/create_tag.php" class="bg-rose-500 text-white px-4 py-2 rounded-md">Deleted Tag
                        Records</a>
                    <a href="tags-crud/create_tag.php" class="bg-blue-500 text-white px-4 py-2 rounded-md">Create
                        Tag</a>
                </div>
            </div>
            <div class="bg-white shadow-md rounded-lg overflow-x-scroll">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="w-full">
                            <th class="border p-4 text-left">
                                <div class="flex justify-between items-center">
                                    <a href="javascript:void(0)"
                                        onClick="sortTable('id', '<?php echo $sortTag_order === 'ASC' ? 'desc' : 'asc'; ?>', 1)"
                                        class="text-blue-500 w-full">
                                        Id
                                    </a>
                                    <?php if ($sortTag_column == "id") {
                                        echo $sortTag_order === "ASC"
                                            ? "<span class='text-xl font-bold text-blue-500'>&uarr;</span>"
                                            : "<span class='text-xl font-bold text-blue-500'>&darr;</span>";
                                    } ?>
                                </div>
                            </th>
                            <th class="border p-4 text-left">
                                <div class="flex justify-between items-center">
                                    <a href="javascript:void(0)"
                                        onClick="sortTable('name', '<?php echo $sortTag_order === 'ASC' ? 'desc' : 'asc'; ?>', 1)"
                                        class="text-blue-500 w-full">
                                        Name
                                    </a>
                                    <?php if ($sortTag_column == "name") {
                                        echo $sortTag_order === "ASC"
                                            ? "<span class='text-xl font-bold text-blue-500'>&uarr;</span>"
                                            : "<span class='text-xl font-bold text-blue-500'>&darr;</span>";
                                    } ?>
                                </div>
                            </th>
                            <th class="border p-4 text-left">
                                <div class="flex justify-between items-center">
                                    <a href="javascript:void(0)"
                                        onClick="sortTable('createdAt', '<?php echo $sortTag_order === 'ASC' ? 'desc' : 'asc'; ?>', 1)"
                                        class="text-blue-500 w-full">
                                        Created At
                                    </a>
                                    <?php if ($sortTag_column == "createdAt") {
                                        echo $sortTag_order === "ASC"
                                            ? "<span class='text-xl font-bold text-blue-500'>&uarr;</span>"
                                            : "<span class='text-xl font-bold text-blue-500'>&darr;</span>";
                                    } ?>
                                </div>
                            </th>
                            <th class="border p-4 text-left">
                                <div class="flex justify-between items-center">
                                    <a href="javascript:void(0)"
                                        onClick="sortTable('updatedAt', '<?php echo $sortTag_order === 'ASC' ? 'desc' : 'asc'; ?>', 1)"
                                        class="text-blue-500 w-full">
                                        Updated At
                                    </a>
                                    <?php if ($sortTag_column == "updatedAt") {
                                        echo $sortTag_order === "ASC"
                                            ? "<span class='text-xl font-bold text-blue-500'>&uarr;</span>"
                                            : "<span class='text-xl font-bold text-blue-500'>&darr;</span>";
                                    } ?>
                                </div>
                            </th>
                            <th class="border p-4 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $resultTags->fetch_assoc()): ?>
                            <tr>
                                <td class=" border p-4"><?php echo $row['id']; ?></td>
                                <td class="border p-4"><?php echo $row['name']; ?></td>
                                <td class="border p-4"><?php echo explode(".", $row['createdAt'])[0]; ?></td>
                                <td class="border p-4"><?php echo explode(".", $row['updatedAt'])[0]; ?></td>
                                <td class="border p-4">
                                    <a href="tags-crud/edit_tag.php?id=<?php echo $row['id']; ?>"
                                        class="text-blue-500">Edit</a> |
                                    <a href="tags-crud/delete_tag.php?id=<?php echo $row['id']; ?>"
                                        class="text-red-500">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <nav class="flex justify-between items-center w-full mr-auto mt-2 text-xl mb-[40px]">
            <ul class="flex space-x-4 items-center">
                <?php if ($pageTag > 1): ?>
                    <li class="border-[2px] border-black/30 p-2 w-full hover:bg-gray-200 cursor-pointer"
                        onClick="pageParam(0, 1)">
                        <a href="javascript:void(0)" class="text-blue-500 hover:text-blue-700">Previous</a>
                    </li>
                <?php endif; ?>

                <?php if ($pageTag < $total_pagesTags): ?>
                    <li class="border-[2px] border-black/30 p-2 w-full hover:bg-gray-200 cursor-pointer"
                        onClick="pageParam(1, 1)">
                        <a href="javascript:void(0)" class="text-blue-500 hover:text-blue-700">Next</a>
                    </li>
                <?php endif; ?>

            </ul>
            <ul>
                <li> <?php
                echo "<span>Page: $pageTag</span>"; ?></li>
            </ul>
        </nav>
    </div>
</body>

</html>