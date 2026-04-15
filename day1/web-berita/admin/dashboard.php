<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}
?>
<head>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<h2>dashboard</h2>
<a href="berita.php">berita</a>
<a href="kategori.php">kategori</a>
<a href="../auth/logout.php">logout</a>
    <?php include 'components/footer.html'; ?>
