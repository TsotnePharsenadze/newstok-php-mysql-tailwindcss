<?php
include('../../../db/db.php');
session_start();

$ref = !isset($_SESSION["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : $_SESSION["HTTP_REFERER"];
$_SESSION["HTTP_REFERER"] = $ref;

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $conn->query("DELETE FROM news WHERE id = $id");
    if (strpos($ref, "?")) {
        $ref .= "&msg=News Deleted Successfully";
    } else {
        $ref .= "?msg=News Deleted Successfully";
    }
    header("Location: $ref");
}
?>