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
<?php include '../components/header.php'; ?>
<body>
<form class="form-card" action="" method="post">
    <h2>Edit Post</h2>
    <label>Judul</label>
    <input type="text" name="judul" value="<?= htmlspecialchars($data['judul'], ENT_QUOTES, 'UTF-8') ?>" maxlength="250">
    <label>Isi</label>
    <textarea name="isi" maxlength="250"><?= htmlspecialchars($data['isi'], ENT_QUOTES, 'UTF-8') ?></textarea>
    <button name="update">update</button>
</form>
<?php include '../components/footer.php'; ?>
</body>