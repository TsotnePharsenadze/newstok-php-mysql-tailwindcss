<?php
session_start();
unset($_SESSION["HTTP_REFERER"]);
include('../../db/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$author_id = $_SESSION['user_id'];

$limit = isset($_GET["pageSizeGallery"]) ? (int) $_GET["pageSizeGallery"] : 5;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$searchGallery = isset($_GET["searchGallery"]) ? $_GET["searchGallery"] : "";

$sort_column = isset($_GET['sort']) ? $_GET['sort'] : 'createdAt';
$sort_order = isset($_GET['order']) ? $_GET['order'] === 'desc' ? 'DESC' : 'ASC' : "DESC";

$sortable_columns = ['file_path', 'file_size', 'sts', 'time', 'createdAt', 'updatedAt', "delete_date", "recovery_date"];
if (!in_array($sort_column, $sortable_columns)) {
    $sort_column = 'createdAt';
}

$query = "SELECT * FROM gallery WHERE sts <> '3' AND (
          file_path LIKE '%$searchGallery%' 
          OR file_size LIKE '%$searchGallery%' ";

if ($searchGallery == "unlisted" or $searchGallery == "published") {
    $searchGallerySts = $searchGallery == "unlisted" ? 1 : 2;
    $query .= " OR sts LIKE '%$searchGallerySts%'";
}

$query .= " OR time LIKE '%$searchGallery%' 
OR createdAt LIKE '%$searchGallery%' 
OR updatedAt LIKE '%$searchGallery%' 
OR delete_date LIKE '%$searchGallery%' 
OR recovery_date LIKE '%$searchGallery%'
) AND author_id='$author_id' ORDER BY $sort_column $sort_order LIMIT $limit OFFSET $offset";

$result = $conn->query($query);

$total_query = "SELECT COUNT(*) as total FROM gallery WHERE sts <> '3' AND (
    file_path LIKE '%$searchGallery%' 
    OR file_size LIKE '%$searchGallery%' ";

if ($searchGallery == "unlisted" or $searchGallery == "published") {
    $searchGallerySts = $searchGallery == "unlisted" ? 1 : 2;
    $total_query .= " OR sts LIKE '%$searchGallerySts%'";
}

$total_query .= " OR time LIKE '%$searchGallery%' 
OR createdAt LIKE '%$searchGallery%' 
OR updatedAt LIKE '%$searchGallery%' 
OR delete_date LIKE '%$searchGallery%' 
OR recovery_date LIKE '%$searchGallery%'
) AND author_id='$author_id' ORDER BY $sort_column $sort_order LIMIT $limit OFFSET $offset";

$total_result = $conn->query($total_query);

$total_row = $total_result->fetch_assoc();
$total_records = isset($total_row['total']) ? $total_row["total"] : 1;
$total_pages = ceil($total_records / $limit);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Newstoks - Gallery index</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css"
        integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function clearMsg() {
            document.querySelector(".msg").style.display = "none";
            const url = new URL(window.location.href);
            url.searchParams.delete("msg");
            window.history.replaceState(null, "", url);
        }

        function sortTable(column, order) {
            const url = new URL(window.location.href);

            url.searchParams.set('sort', column);
            url.searchParams.set('order', order);

            window.location.href = url.toString();
        }

        function pageParam(type = 0) {
            const url = new URL(window.location.href);

            const currentPage = parseInt(url.searchParams.get("page")) || 1;
            url.searchParams.set("page", type ? currentPage + 1 : currentPage - 1);

            window.location.href = url.toString();
        }

        function handlePageSizeSubmit(e) {
            let index = e.target.value;

            const url = new URL(window.location.href);

            url.searchParams.set("pageSizeGallery", index);

            window.location.href = url.toString();
        }

        function searchQuery() {
            const url = new URL(window.location.href);

            let value = document.querySelector("input[name='searchGallery']").value;
            url.searchParams.set("searchGallery", value);

            window.location.href = url.toString();
        }

        function openModal(src) {
            document.querySelector("#imageModal").style.display = "flex";
            document.getElementById('modalImage').src = src;
            document.getElementById('imageModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('imageModal').style.display = "none";
        }
    </script>
    <style>
        #button {
            background: rgba(0, 0, 0);
            padding: 10px 15px;
            border-radius: 9px;
            border: none;
            cursor: pointer;
        }

        #imageModal {
            display: none;
        }
    </style>
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
                    <h2 class="text-2xl font-bold">Gallery List 📰</h2>
                    <select name="pageSizeGallery" onchange="handlePageSizeSubmit(event, 1)"
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
                    <input type="search" name="searchGallery" class="p-2 border-black border-[2px] rounded-md w-[300px]"
                        placeholder="Search for Gallery" value="<?php echo $searchGallery; ?>" />
                    <button onclick="searchQuery()"
                        class="border-[2px] border-black bg-blue-500 text-white p-[0.5rem_0.9rem_0.5rem_0.9rem] ml-2 rounded-full hover:opacity-80 cursor-pointer">
                        <i class="fa-solid fa-search"></i></button>
                </div>
                <div class="flex gap-2">
                    <a href="gallery-crud/deleted_gallery.php"
                        class="bg-rose-500 text-white px-4 py-2 rounded-md">Deleted
                        Gallery
                        Records</a>
                    <a href="gallery-crud/create_gallery.php" class="bg-blue-500 text-white px-4 py-2 rounded-md">Create
                        Gallery</a>
                </div>
            </div>
            <div class="bg-white shadow-md rounded-lg overflow-x-scroll">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="w-full">
                            <th class="border p-4 text-left">
                                <div class="flex justify-between items-center">
                                    Preview
                                </div>
                            </th>
                            <th class="border p-4 text-left">
                                <div class="flex justify-between items-center">
                                    <a href="javascript:void(0)"
                                        onClick="sortTable('file_path', '<?php echo $sort_order === 'ASC' ? 'desc' : 'asc'; ?>')"
                                        class="text-blue-500 w-full">
                                        File Path
                                    </a>
                                    <?php if ($sort_column == "file_path") {
                                        echo $sort_order === "ASC"
                                            ? "<span class='text-xl font-bold text-blue-500'>&uarr;</span>"
                                            : "<span class='text-xl font-bold text-blue-500'>&darr;</span>";
                                    } ?>
                                </div>
                            </th>
                            <th class="border p-4 text-left">
                                <div class="flex justify-between items-center">
                                    <a href="javascript:void(0)"
                                        onClick="sortTable('file_size', '<?php echo $sort_order === 'ASC' ? 'desc' : 'asc'; ?>')"
                                        class="text-blue-500 w-full">
                                        File Size
                                    </a>
                                    <?php if ($sort_column == "file_size") {
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
                                <td class="border p-4">
                                    <div class="relative">
                                        <img src="<?php
                                        $filePathArray = explode("/", $row['file_path']);
                                        $filePathArray[count($filePathArray) - 1] = trim($filePathArray[count($filePathArray) - 1]);
                                        $filePathArraySearch = array_search("gallery", $filePathArray);
                                        echo '/gallery/' . implode("/", array_slice($filePathArray, $filePathArraySearch + 1));
                                        ?>" class="w-[250px] h-[250px] object-contain cursor-pointer"
                                            onclick="openModal(this.src)" />
                                    </div>

                                    <div id="imageModal"
                                        class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50">
                                        <div class="relative">
                                            <button id="button" onclick="closeModal()"
                                                class="absolute top-2 right-2 text-white text-xl">X</button>
                                            <img id="modalImage" src=""
                                                class="max-w-[700px] max-h-[700px] object-contain" />
                                        </div>
                                    </div>
                                </td>
                                <td class="border p-4 whitespace-nowrap overflow-x-auto max-w-md">
                                    <div class="overflow-x-auto">
                                        <?php
                                        $filePathArray = explode("/", $row['file_path']);
                                        $filePathArray[count($filePathArray) - 1] = trim($filePathArray[count($filePathArray) - 1]);
                                        $filePathArraySearch = array_search("gallery", $filePathArray);
                                        echo implode("/", array_slice($filePathArray, $filePathArraySearch));
                                        ?>
                                    </div>
                                </td>
                                <td class="border p-4"><?php echo round(((int) $row['file_size']) / 1024, 1); ?> KB</td>
                                <td class="border p-4">
                                    <span
                                        class="<?php echo $row['sts'] == 1 ? "bg-blue-100 text-blue-800" : "bg-green-100 text-green-800"; ?> font-medium me-2 px-2.5 py-0.5 rounded uppercase"><?php echo $row['sts'] == 1 ? "unlisted" : "published"; ?></span>
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
                                    <a href="gallery-crud/edit_Gallery.php?id=<?php echo $row['id']; ?>"
                                        class="text-blue-500 hover:underline">Edit</a> |
                                    <a href="gallery-crud/delete_gallery.php?id=<?php echo $row['id']; ?>"
                                        class="text-red-500 hover:underline">Delete</a>
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
        let searchGalleryInput = document.querySelector("input[name='searchGallery']");
        searchGalleryInput.addEventListener("input", () => {
            if (searchGalleryInput.value === "") {
                searchQuery();
            }
        })
    </script>
</body>

</html>