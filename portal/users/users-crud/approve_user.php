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
    $conn->query("UPDATE users SET sts='2', updatedAt=current_timestamp() WHERE id = '$id'");
    if (strpos($ref, "?")) {
        $ref .= "&msg=User Status Updated Successfully";
    } else {
        $ref .= "?msg=User Status Updated Successfully";
    }
    header("Location: $ref");
}
?>