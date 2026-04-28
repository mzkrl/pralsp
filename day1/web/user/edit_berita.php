<?php
session_start();
include("../config/conn.php");
if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$username = mysqli_real_escape_string($con, $_SESSION['username']);

$data = mysqli_fetch_assoc(mysqli_query($con,"
    select * from berita where id=$id and uploader='$username'
    "));

if (!$data) {
    echo "data tidak ditemukan";
    exit;
}
?>
<head>
   <link rel="stylesheet" href="../assets/styles.css">
</head>
<form action="" method="post">
    Judul = <input type="text" name="judul" id="" value="<?= $data['judul'] ?>" maxlength="250"><br>
    Isi = <textarea name="isi" id="" maxlength="250"> <?= $data['isi'] ?> </textarea><br>
    <button name="update">update</button>
</form>

<?php
if(isset($_POST['update'])){
    $judul = mysqli_real_escape_string($con, $_POST['judul']);
    $isi = mysqli_real_escape_string($con, $_POST['isi']);

    if (strlen($isi) > 250) {
        echo "Maksimum 250 karakter.";
        exit;
    }

    mysqli_query($con,"
    update berita set 
    judul='$judul',
    isi='$isi'
    where id=$id and uploader='$username'
    ");
    header("Location: upload.php");
    exit;
}
?>