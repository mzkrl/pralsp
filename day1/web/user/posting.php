<?php
session_start();
include("../config/conn.php");
if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}

$error = '';
if(isset($_POST["simpan"])){
    $judul = mysqli_real_escape_string($con, $_POST["judul"]);
    $isi = mysqli_real_escape_string($con, $_POST["isi"]);
    $kategori = (int) $_POST["kategori"];
    $uploader = mysqli_real_escape_string($con, $_SESSION['username']);

    // Ambil user_id dari session username untuk relasi berita-user
    $userRow = mysqli_fetch_assoc(mysqli_query($con, "select id from user where username='$uploader'"));
    $userId = $userRow ? (int) $userRow['id'] : 0;
                                                                                            // biar ga kepannjangan
    if (strlen($isi) > 250) {
        $error = "Maksimum 250 karakter.";
    } else {                                                                                //untuk gambar, gambar disimpen jadi binary blob di db.
        $gambarSql = "NULL";
        $gambarTypeSql = "NULL";
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
            $gambarData = file_get_contents($_FILES['gambar']['tmp_name']);
            $gambarEsc = mysqli_real_escape_string($con, $gambarData);
            $gambarType = mysqli_real_escape_string($con, $_FILES['gambar']['type']);
            $gambarSql = "'" . $gambarEsc . "'";
            $gambarTypeSql = "'" . $gambarType . "'";
        }

        $fileSql = "NULL";
        $fileNameSql = "NULL";
        $fileTypeSql = "NULL";
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $fileData = file_get_contents($_FILES['file']['tmp_name']);
            $fileEsc = mysqli_real_escape_string($con, $fileData);
            $fileName = mysqli_real_escape_string($con, $_FILES['file']['name']);
            $fileType = mysqli_real_escape_string($con, $_FILES['file']['type']);
            $fileSql = "'" . $fileEsc . "'";
            $fileNameSql = "'" . $fileName . "'";
            $fileTypeSql = "'" . $fileType . "'";
        }
                                                                                            // simpan dengan user_id untuk relasi
        mysqli_query($con,"
        insert into berita (judul, isi, kategori_id, user_id, uploader, tanggal, gambar, gambar_type, file_blob, file_name, file_type)
        values('$judul', '$isi', $kategori, $userId, '$uploader', NOW(), $gambarSql, $gambarTypeSql, $fileSql, $fileNameSql, $fileTypeSql)
        ");
        header("Location: upload.php");
        exit;
    }
}
?>
<head>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<?php include '../components/header.php'; ?>
<form method="post" enctype="multipart/form-data">
    <?php if ($error) { ?>
        <div><?= $error ?></div>
    <?php } ?>
    Judul <input type="text" name="judul" maxlength="250"><br>
    Isi <textarea name="isi" maxlength="250"></textarea><br>

    <select name="kategori" >
        <?php
            $kat=mysqli_query($con,"
            select * from kategori
            ");

            while($k=mysqli_fetch_assoc($kat)){
                echo "<option value='{$k['id']}'>{$k['nama_kategori']}</option>";
            };
        ?>
    </select>
    Gambar <input type="file" name="gambar" accept="image/*"><br>
    File <input type="file" name="file"><br>
    <button name="simpan">simpan</button>
</form>
<?php include '../components/footer.php'; ?>
