<?php
include("config/conn.php");

$id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;

$data=mysqli_fetch_assoc(mysqli_query($con,"select * from berita where id=$id"));
?>

<h2 ><?= $data["judul"] ?></h2>
<p><?= $data["isi"] ?></p>
<a href="index.php">kembali</a>