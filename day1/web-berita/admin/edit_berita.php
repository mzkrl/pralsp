<?php
include("../config/conn.php");
$id=$_GET['id'];

$data=mysqli_fetch_assoc(mysqli_query($conn,"
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
    mysqli_query($conn,"
    update berita set 
    judul='$_POST[judul]',
    isi='$_POST[isi]'
    where id=$id
    ");
}
?>