<?php
include("../config/conn.php");

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
mysqli_query($con,"
    delete from berita where id=$id
");

header("Location: berita.php");
?>