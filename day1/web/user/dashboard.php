<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}
?>
<?php include '../components/header.php'; ?>
<body>
<div class="admin-page">
    <h2>dashboard</h2>
    <a href="upload.php">Kelola Post</a>
    <a href="kategori.php">Kategori</a>
    <a href="../auth/logout.php">Logout</a>
</div>
<?php include '../components/footer.php'; ?>
</body>
