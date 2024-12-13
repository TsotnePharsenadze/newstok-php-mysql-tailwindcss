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
    $conn->query("UPDATE tags SET sts='1', recovery_date=current_timestamp() WHERE id = $id");
    if (strpos($ref, "?")) {
        $ref .= "&msg=Tag Recovered Successfully";
    } else {
        $ref .= "?msg=Tag Recovered Successfully";
    }
    header("Location: $ref");
}
?>