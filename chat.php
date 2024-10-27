<?php
session_start();

if (!isset($_SESSION["token"])) {
    Header("Location: index.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>.chat</title>
</head>

<body>
    <form method="POST" action="logout.php">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        <button>Logout</button>
    </form>

    <?php

    if (isset($_SESSION["logout_error"]))
        echo $_SESSION["logout_error"];
    unset($_SESSION["logout_error"]);
    ?>
</body>

</html>