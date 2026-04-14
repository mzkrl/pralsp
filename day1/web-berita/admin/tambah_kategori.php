<form method="post">
    <input type="text" name="nama" id="">
    <button name="simpan">simpan</button>
</form>

<?php include '../config/conn.php';
if (isset($_POST['simpan'])){
    mysqli_query(
        $con, 
        "insert into kategori values ('', '$_POST[nama]');");
        header("Location: kategori.php");
}
?>