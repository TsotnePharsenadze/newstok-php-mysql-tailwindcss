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
    $conn->query("UPDATE tags SET sts='3' WHERE id = $id");
    if (strpos($ref, "?")) {
        $ref .= "&msg=Tag Deleted Successfully";
    } else {
        $ref .= "?msg=Tag Deleted Successfully";
    }
    header("Location: $ref");
}
?>