<?php
include("../config/conn.php");
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$data=mysqli_fetch_assoc(mysqli_query($con,"
    select * from berita where id=$id
    "));
?>

<form action="" method="post">
    Judul = <input type="text" name="judul" id="" value="<?= $data['judul'] ?>"><br>
    Isi = <textarea name="isi" id=""> <?= $data['isi'] ?> </textarea><br>
    <button name="update">update</button>
</form>

<?php
if(isset($_POST['update'])){
    $judul = mysqli_real_escape_string($con, $_POST['judul']);
    $isi = mysqli_real_escape_string($con, $_POST['isi']);

    mysqli_query($con,"
    update berita set 
    judul='$judul',
    isi='$isi'
    where id=$id
    ");
    header("Location: berita.php");
    exit;
}
?>