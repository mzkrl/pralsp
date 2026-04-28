<?php
include("config/conn.php");

$id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;

$result = mysqli_query($con, "select gambar from user where id=$id");
$data = $result ? mysqli_fetch_assoc($result) : null;
$imageSrc = 'data:image/jpeg;base64,' . base64_encode($data['gambar']);
?>
<img src="<?= $imageSrc ?>" alt="Foto profil">
<a href="index.php">kembali</a>