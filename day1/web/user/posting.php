<?php include("../config/conn.php"); ?>
<head>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<?php include '../components/header.php'; ?>
<form method="post" enctype="multipart/form-data">
    Judul <input type="text" name="judul"><br>
    Isi <textarea name="isi" ></textarea><br>

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
    Gambar <input type="file" name="gambar"><br>
    <button name="simpan">simpan</button>
</form>

<?php
if(isset($_POST["simpan"])){
    $judul = mysqli_real_escape_string($con, $_POST["judul"]);
    $isi = mysqli_real_escape_string($con, $_POST["isi"]);
    $kategori = (int) $_POST["kategori"];
    $gambarSql = "NULL";
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $gambarData = file_get_contents($_FILES['gambar']['tmp_name']);
        $gambarEsc = mysqli_real_escape_string($con, $gambarData);
        $gambarSql = "'" . $gambarEsc . "'";
    }

    mysqli_query($con,"
    insert into berita (judul, isi, kategori_id, tanggal,gambar)
    values('$judul', '$isi', $kategori, NOW(), $gambarSql)
    ");
    header("Location: dashboard.php");
    exit;
}
?>
<?php include '../components/footer.php'; ?>
