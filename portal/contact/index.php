<?php
session_start();
unset($_SESSION["HTTP_REFERER"]);
include('../../db/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$author_id = $_SESSION['user_id'];

$limit = isset($_GET["pageSizeContact"]) ? (int) $_GET["pageSizeContact"] : 5;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$searchContact = isset($_GET["searchContact"]) ? $_GET["searchContact"] : "";

$sort_column = isset($_GET['sort']) ? $_GET['sort'] : 'createdAt';
$sort_order = isset($_GET['order']) ? $_GET['order'] === 'desc' ? 'DESC' : 'ASC' : "DESC";

$sortable_columns = ['id', 'name', 'email', 'message', 'sts', 'responded', 'createdAt', "delete_date", "recovery_date"];
if (!in_array($sort_column, $sortable_columns)) {
    $sort_column = 'createdAt';
}

$query = "SELECT * FROM contact WHERE sts <> '3' AND (
          id LIKE '%$searchContact%' 
          OR name LIKE '%$searchContact%' ";

if ($searchContact == "pending" or $searchContact == "responded") {
    $searchContactSts = $searchContact == "pending" ? 1 : 2;
    $query .= " OR sts LIKE '%$searchContactSts%'";
}

$query .= " OR email LIKE '%$searchContact%' 
OR message LIKE '%$searchContact%' 
OR createdAt LIKE '%$searchContact%' 
OR delete_date LIKE '%$searchContact%' 
OR recovery_date LIKE '%$searchContact%'
) ORDER BY $sort_column $sort_order LIMIT $limit OFFSET $offset";

$result = $conn->query($query);

$total_query = "SELECT COUNT(*) as total FROM contact WHERE sts <> '3' AND (
    id LIKE '%$searchContact%' 
    OR name LIKE '%$searchContact%' ";

if ($searchContact == "pending" or $searchContact == "responded") {
    $searchContactSts = $searchContact == "pending" ? 1 : 2;
    $total_query .= " OR sts LIKE '%$searchContactSts%'";
}

$total_query .= " OR email LIKE '%$searchContact%' 
OR message LIKE '%$searchContact%' 
OR createdAt LIKE '%$searchContact%' 
OR delete_date LIKE '%$searchContact%' 
OR recovery_date LIKE '%$searchContact%'
) ORDER BY $sort_column $sort_order LIMIT $limit OFFSET $offset";

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
    <title>Newstoks - Contact index</title>
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

            url.searchParams.set("pageSizeContact", index);

            window.location.href = url.toString();
        }

        function searchQuery() {
            const url = new URL(window.location.href);

            let value = document.querySelector("input[name='searchContact']").value;
            url.searchParams.set("searchContact", value);

            window.location.href = url.toString();
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
    <div id="viewContactModal"
        class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg w-96 p-6">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold">Contact Details</h2>
                <button class="text-red-500 text-2xl" onclick="closeModal()">&times;</button>
            </div>
            <div class="mt-4">
                <p><strong>Name:</strong> <span id="contactName"></span></p>
                <p><strong>Email:</strong> <span id="contactEmail"></span></p>
                <p><strong>Message:</strong> <span id="contactMessage"></span></p>
                <p><strong>Status:</strong> <span id="contactStatus"></span></p>
                <p><strong>Created At:</strong> <span id="contactCreatedAt"></span></p>
                <p class="hidden responded"><strong>Responded At:</strong> <span id="contactRespondedAt"></span></p>
            </div>
            <div class="mt-6 text-right">
                <button class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
                    onclick="closeModal()">Close</button>
            </div>
        </div>
    </div>

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
                    <h2 class="text-2xl font-bold">Contact List ☎</h2>
                    <select name="pageSizeContact" onchange="handlePageSizeSubmit(event, 1)"
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
                    <input type="search" name="searchContact" class="p-2 border-black border-[2px] rounded-md w-[300px]"
                        placeholder="Search for Contact" value="<?php echo $searchContact; ?>" />
                    <button onclick="searchQuery()"
                        class="border-[2px] border-black bg-blue-500 text-white p-[0.5rem_0.9rem_0.5rem_0.9rem] ml-2 rounded-full hover:opacity-80 cursor-pointer">
                        <i class="fa-solid fa-search"></i></button>
                </div>
                <div class="flex gap-2">
                    <a href="contact-crud/deleted_contact.php"
                        class="bg-rose-500 text-white px-4 py-2 rounded-md">Deleted
                        Contact
                        Records</a>
                </div>
            </div>
            <div class="bg-white shadow-md rounded-lg overflow-x-scroll">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="w-full">
                            <th class="border p-4 text-left">
                                <div class="flex justify-between items-center">
                                    <a href="javascript:void(0)"
                                        onClick="sortTable('id', '<?php echo $sort_order === 'ASC' ? 'desc' : 'asc'; ?>')"
                                        class="text-blue-500 w-full">
                                        Id
                                    </a>
                                    <?php if ($sort_column == "id") {
                                        echo $sort_order === "ASC"
                                            ? "<span class='text-xl font-bold text-blue-500'>&uarr;</span>"
                                            : "<span class='text-xl font-bold text-blue-500'>&darr;</span>";
                                    } ?>
                                </div>
                            </th>
                            <th class="border p-4 text-left">
                                <div class="flex justify-between items-center">
                                    <a href="javascript:void(0)"
                                        onClick="sortTable('name', '<?php echo $sort_order === 'ASC' ? 'desc' : 'asc'; ?>')"
                                        class="text-blue-500 w-full">
                                        Name
                                    </a>
                                    <?php if ($sort_column == "name") {
                                        echo $sort_order === "ASC"
                                            ? "<span class='text-xl font-bold text-blue-500'>&uarr;</span>"
                                            : "<span class='text-xl font-bold text-blue-500'>&darr;</span>";
                                    } ?>
                                </div>
                            </th>

                            <th class="border p-4 text-left">
                                <div class="flex justify-between items-center">
                                    <a href="javascript:void(0)"
                                        onClick="sortTable('email', '<?php echo $sort_order === 'ASC' ? 'desc' : 'asc'; ?>')"
                                        class="text-blue-500 w-full">
                                        Email
                                    </a>
                                    <?php if ($sort_column == "email") {
                                        echo $sort_order === "ASC"
                                            ? "<span class='text-xl font-bold text-blue-500'>&uarr;</span>"
                                            : "<span class='text-xl font-bold text-blue-500'>&darr;</span>";
                                    } ?>
                                </div>
                            </th>
                            <th class="border p-4 text-left">
                                <div class="flex justify-between items-center">
                                    <a href="javascript:void(0)"
                                        onClick="sortTable('message', '<?php echo $sort_order === 'ASC' ? 'desc' : 'asc'; ?>')"
                                        class="text-blue-500 w-full">
                                        Message
                                    </a>
                                    <?php if ($sort_column == "message") {
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
                                        onClick="sortTable('responded', '<?php echo $sort_order === 'ASC' ? 'desc' : 'asc'; ?>')"
                                        class="text-blue-500 w-full">
                                        Responded At
                                    </a>
                                    <?php if ($sort_column == "responded") {
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
                                <td class="border p-4"><?php echo $row["id"]; ?></td>
                                <td class="border p-4"><?php echo $row["name"]; ?></td>
                                <td class="border p-4"><?php echo $row["email"]; ?></td>
                                <td class="border p-4 overflow-x-auto max-w-[400px]"><?php echo $row["message"]; ?></td>
                                <td class="border p-4">
                                    <span
                                        class="<?php echo $row['sts'] == 1 ? "bg-blue-100 text-blue-800" : "bg-green-100 text-green-800"; ?> font-medium me-2 px-2.5 py-0.5 rounded uppercase"><?php echo $row['sts'] == 1 ? "Pending" : "Responded"; ?></span>
                                </td>
                                <td class="border p-4"><?php echo $row["responded"]; ?></td>


                                <td class="border p-4"><?php echo explode(".", $row['createdAt'])[0]; ?></td>
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
                                    <a href="contact-crud/responded_contact.php?id=<?php echo $row['id']; ?>"
                                        class="text-green-500 hover:underline">Responded</a> |
                                    <a href="javascript:void(0)" class="text-blue-500 hover:underline"
                                        onclick='openModal(<?php echo json_encode($row); ?>)'>View</a> |
                                    <a href="contact-crud/delete_contact.php?id=<?php echo $row['id']; ?>"
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
        let searchContactInput = document.querySelector("input[name='searchContact']");
        searchContactInput.addEventListener("input", () => {
            if (searchContactInput.value === "") {
                searchQuery();
            }
        })
    </script>
    <script>
        function openModal(contact) {
            document.getElementById('contactName').innerText = contact.name;
            document.getElementById('contactEmail').innerText = contact.email;
            document.getElementById('contactMessage').innerText = contact.message;
            document.getElementById('contactStatus').innerText = contact.sts == 1 ? 'Pending' : 'Responded';
            document.getElementById('contactCreatedAt').innerText = contact.createdAt;
            document.getElementById('contactRespondedAt').innerText = contact.responded;
            if (contact.responded) {
                document.querySelector(".responded").classList.remove("hidden");
            }

            document.getElementById('viewContactModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('viewContactModal').classList.add('hidden');
        }
    </script>
</body>

</html>