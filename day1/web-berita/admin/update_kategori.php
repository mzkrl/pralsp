<?php
include("../config/conn.php");

$id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;
$data = mysqli_query($con,
            "select * from kategori where id = $id");
$d = mysqli_fetch_assoc($data);

?>

<form action="" method="post">
    <input type="text" name="nama" value="<?= $d['nama_kategori'] ?>">
    <button name="update">update</button>
</form>

<?php
    if (isset($_POST['update'])) {
        $nama = mysqli_real_escape_string($con, $_POST['nama']);

        mysqli_query($con,"
        update kategori set nama_kategori='$nama' where id=$id
        ");

        header("Location: kategori.php");
        exit;
    }
?>