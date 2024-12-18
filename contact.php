<?php
include("db/db.php");

session_start();

//Layout Configuration
$title = "Newstok - Contact Us";
$includeSlider = true;
//EndLayout Configuration

if (!isset($_SESSION["menu"])) {
    $result = $conn->query("SELECT * FROM menu WHERE sts=2 ORDER BY ord ASC");
    $menuArray = [];
    while ($row = $result->fetch_assoc()) {
        array_push($menuArray, $row);
    }
    $_SESSION["menu"] = $menuArray;
}
$err;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $message = $_POST["message"];
    $result = $conn->query("INSERT INTO contact (name, email, message) VALUES ('$name', '$email', '$message')");

    if ($result == TRUE) {
        $_SESSION["contact_sent"] = time();
    } else {
        $err = "Error " . $conn->error;
    }
}
?>

<?php
include("./layout/header.php");
?>
<div class="flex items-center justify-center py-24">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md min-h-[494px]">
        <h1 class="text-2xl font-black text-gray-800 mb-6 text-center">Contact Us</h1>
        <?php
        if (isset($_SESSION["contact_sent"])) { ?>
            <div
                class="msg bg-green-200 w-full flex p-2 border-l-[10px] border-l-green-400 rounded-md flex flex-col pr-6 pl-6 mb-2 mt-2 items-center justify-center mt-[30%]">
                <div class="flex justify-between mb-2 flex-col items-baseline gap-2">
                    <h1 class="font-bold text-2xl">Your message has been received!</h1>
                    <p class="text-lg font-semibold">Sent at:
                        <?php echo date("Y-m-d H:i:s", $_SESSION["contact_sent"]); ?>
                    </p>
                </div>
            </div>
        <?php } else { ?>
            <form action="" method="POST" class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" id="name" name="name" required
                        class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" required
                        class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                    <textarea id="message" name="message" rows="4" required
                        class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>

                <button type="submit"
                    class="w-full bg-gray-800 text-white font-semibold py-2 px-4 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Submit
                </button>
                <p class="text-center text-red-400 mt-2 mb-2"><?php if (!empty($err))
                    echo $err; ?></p>
            </form>
        <?php }

        ?>
    </div>
</div>

<?php
include("layout/footer.php");
?>