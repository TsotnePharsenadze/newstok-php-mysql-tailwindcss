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
    $conn->query("UPDATE news SET sts='1' WHERE id = $id");
    if (strpos($ref, "?")) {
        $ref .= "&msg=News Recovered Successfully";
    } else {
        $ref .= "?msg=News Recovered Successfully";
    }
    header("Location: $ref");
}
?>