<?php
include("../config/conn.php");
$id = $_GET['id'];
mysqli_query($con,"delete from kategori where id=$id");
header("Location: kategori.php");
?>