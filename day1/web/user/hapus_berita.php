<?php
session_start();
include("../config/conn.php");
if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$username = mysqli_real_escape_string($con, $_SESSION['username']);
mysqli_query($con,"
    delete from berita where id=$id and uploader='$username'
");

header("Location: upload.php");
?>