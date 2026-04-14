<form method="post">
    <input type="text" name="nama" id="">
    <button name="simpan">simpan</button>
</form>

<?php include '../config/conn.php';
if (isset($_POST['simpan'])){
    $nama = mysqli_real_escape_string($con, $_POST['nama']);
    mysqli_query(
        $con, 
        "insert into kategori (nama_kategori) values ('$nama')");
        header("Location: kategori.php");
}
?>