<?php
session_start();
include("config/conn.php");
if (!isset($_SESSION['login'])) {
    header("Location: auth/login.php");
    exit;
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$postId = isset($_GET['post_id']) ? (int) $_GET['post_id'] : 0;
$username = mysqli_real_escape_string($con, $_SESSION['username']);

mysqli_query($con, "delete from komentar where id=$id and username='$username'");

header("Location: detail.php?id=$postId");
?>