<?php
session_start();
include("../config/conn.php");
if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}
$username = mysqli_real_escape_string($con, $_SESSION['username']);
?>
<head>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<?php include '../components/header.php'; ?>
<h2>kelola post</h2>
<a href="posting.php">Posting</a>
<table border="1" cellpadding="10" cellspacing="0">
    <tr>
        <th>no</th>
        <th>judul</th>
        <th>kategori</th>
        <th>aksi</th>
    </tr>

    <?php
        $no = 1;
        $query = mysqli_query($con,"
        select berita.id, berita.judul, kategori.nama_kategori from berita
        join kategori on berita.kategori_id = kategori.id
        where berita.uploader = '$username'");

        while ($row = mysqli_fetch_assoc($query)) {
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $row['judul'] ?></td>
                <td><?= $row['nama_kategori'] ?></td>

                <td>
                    <a href="edit_berita.php?id=<?= $row['id'] ?>">edit</a>
                    <a href="hapus_berita.php?id=<?= $row['id'] ?>">hapus</a>
                </td>
            </tr>
        <?php } 
    ?>
</table>
<?php include '../components/footer.php'; ?>