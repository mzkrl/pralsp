<?php
include("config/conn.php");

$id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;

$result = mysqli_query($con, "select * from berita where id=$id");
$data = $result ? mysqli_fetch_assoc($result) : null;
?>
<head>
    <link rel="stylesheet" href="assets/styles.css">
</head>


<h2 ><?= $data["judul"] ?></h2>
<p><?= $data["isi"] ?></p>
<a href="index.php">kembali</a>