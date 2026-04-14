<?php
include("../config/conn.php");
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
mysqli_query($con,"delete from kategori where id=$id");
header("Location: kategori.php");
?>