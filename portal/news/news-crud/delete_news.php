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
    $conn->query("UPDATE news SET sts='3', delete_date=current_timestamp() WHERE id = $id AND author_id='$author_id'");
    if (strpos($ref, "?")) {
        $ref .= "&msg=News Deleted Successfully";
    } else {
        $ref .= "?msg=News Deleted Successfully";
    }
    header("Location: $ref");
}
?>