<?php
include('../../../db/db.php');
session_start();

$ref = !isset($_SESSION["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : $_SESSION["HTTP_REFERER"];
$_SESSION["HTTP_REFERER"] = $ref;

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}

$author_id = $_SESSION["user_id"];

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $conn->query("UPDATE gallery SET sts='1', recovery_date=current_timestamp() WHERE id = $id AND author_id='$author_id'");
    if (strpos($ref, "?")) {
        $ref .= "&msg=Gallery Recovered Successfully";
    } else {
        $ref .= "?msg=Gallery Recovered Successfully";
    }
    header("Location: $ref");
}
?>