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

$error = '';
if(isset($_POST['update'])){
    $judul = mysqli_real_escape_string($con, $_POST['judul']);
    $isi = mysqli_real_escape_string($con, $_POST['isi']);

    if (strlen($isi) > 250) {
        $error = "Maksimum 250 karakter.";
    } else {
        // Update gambar jika ada upload baru
        $setGambar = '';
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
            $gambarData = file_get_contents($_FILES['gambar']['tmp_name']);
            $gambarEsc = mysqli_real_escape_string($con, $gambarData);
            $gambarType = mysqli_real_escape_string($con, $_FILES['gambar']['type']);
            $setGambar = ", gambar='$gambarEsc', gambar_type='$gambarType'";
        }

        // Update file jika ada upload baru
        $setFile = '';
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $fileData = file_get_contents($_FILES['file']['tmp_name']);
            $fileEsc = mysqli_real_escape_string($con, $fileData);
            $fileName = mysqli_real_escape_string($con, $_FILES['file']['name']);
            $fileType = mysqli_real_escape_string($con, $_FILES['file']['type']);
            $setFile = ", file_blob='$fileEsc', file_name='$fileName', file_type='$fileType'";
        }

        // Hapus gambar jika diminta
        if (isset($_POST['hapus_gambar'])) {
            $setGambar = ", gambar=NULL, gambar_type=NULL";
        }

        // Hapus file jika diminta
        if (isset($_POST['hapus_file'])) {
            $setFile = ", file_blob=NULL, file_name=NULL, file_type=NULL";
        }

        mysqli_query($con,"
        update berita set 
        judul='$judul',
        isi='$isi'
        $setGambar
        $setFile
        where id=$id and uploader='$username'
        ");
        header("Location: upload.php");
        exit;
    }
}

// Re-fetch data setelah proses (untuk preview gambar/file terbaru)
$data = mysqli_fetch_assoc(mysqli_query($con,"select * from berita where id=$id and uploader='$username'"));

$imageSrc = '';
if (!empty($data['gambar'])) {
    $mime = !empty($data['gambar_type']) ? $data['gambar_type'] : 'image/jpeg';
    $imageSrc = 'data:' . $mime . ';base64,' . base64_encode($data['gambar']);
}
?>
<?php include '../components/header.php'; ?>
<body>
<form class="form-card" action="" method="post" enctype="multipart/form-data">
    <h2>Edit Post</h2>
    <?php if ($error) { ?>
        <div class="alert-error"><?= $error ?></div>
    <?php } ?>
    <label>Judul</label>
    <input type="text" name="judul" value="<?= htmlspecialchars($data['judul'], ENT_QUOTES, 'UTF-8') ?>" maxlength="250">
    <label>Isi</label>
    <textarea name="isi" maxlength="250"><?= htmlspecialchars($data['isi'], ENT_QUOTES, 'UTF-8') ?></textarea>

    <!-- Gambar -->
    <label>Gambar</label>
    <?php if ($imageSrc) { ?>
        <img src="<?= $imageSrc ?>" alt="Gambar post" style="max-width:100%; max-height:200px; border-radius:8px; object-fit:contain;">
        <label><input type="checkbox" name="hapus_gambar" value="1"> Hapus gambar</label>
    <?php } ?>
    <input type="file" name="gambar" accept="image/*">

    <!-- File -->
    <label>File</label>
    <?php if (!empty($data['file_name'])) { ?>
        <span style="font-size:0.85rem; color:var(--secondary-text);">File saat ini: <?= htmlspecialchars($data['file_name'], ENT_QUOTES, 'UTF-8') ?></span>
        <label><input type="checkbox" name="hapus_file" value="1"> Hapus file</label>
    <?php } ?>
    <input type="file" name="file">

    <button name="update">update</button>
</form>
<?php include '../components/footer.php'; ?>
</body>