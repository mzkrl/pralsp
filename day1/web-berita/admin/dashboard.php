<?php
session_start();
if (isset($_SESSION["login"]) ) {
    header("Location: ../auth/login.php");
}
?>

<h2>dashboard</h2>
<a href="berita.php">berita</a>
<a href="kategori.php">kategori</a>
<a href="../auth/logout.php">logout</a>