<?php include '../config/conn.php'; ?>
<?php include '../components/header.php'; ?>
<body>
<form class="form-card" method="post">
    <h2>Tambah Kategori</h2>
    <input type="text" name="nama" placeholder="Nama kategori">
    <button name="simpan">simpan</button>
</form>

<?php
if (isset($_POST['simpan'])){
    $nama = mysqli_real_escape_string($con, $_POST['nama']);
    mysqli_query($con, "insert into kategori (nama_kategori) values ('$nama')");
    header("Location: kategori.php");
}
?>
<?php include '../components/footer.php'; ?>
</body>
