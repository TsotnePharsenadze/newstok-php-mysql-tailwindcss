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
    $conn->query("UPDATE contact SET sts='3' WHERE id = $id");
    if (strpos($ref, "?")) {
        $ref .= "&msg=Contact Deleted Successfully";
    } else {
        $ref .= "?msg=Contact Deleted Successfully";
    }
    header("Location: $ref");
}
?>