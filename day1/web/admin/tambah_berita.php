<?php include("../config/conn.php"); ?>
<head>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<form method="post">
    Judul = <input type="text" name="judul"><br>
    Isi = <textarea name="isi" ></textarea><br>

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

    <button name="simpan">simpan</button>
</form>

<?php
if(isset($_POST["simpan"])){
    $judul = mysqli_real_escape_string($con, $_POST["judul"]);
    $isi = mysqli_real_escape_string($con, $_POST["isi"]);
    $kategori = (int) $_POST["kategori"];

    mysqli_query($con,"
    insert into berita (judul, isi, kategori_id, tanggal)
    values('$judul', '$isi', $kategori, NOW())
    ");
    header("Location: berita.php");
    exit;
}
?>