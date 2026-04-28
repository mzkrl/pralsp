<?php
session_start();
include("../config/conn.php");
if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}
$username = mysqli_real_escape_string($con, $_SESSION['username']);
?>
<?php include '../components/header.php'; ?>
<body>
<div class="admin-page">
    <h2>kelola post</h2>
    <a href="posting.php">+ Posting Baru</a>
    <table class="data-table">
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
                    <td><?= htmlspecialchars($row['judul'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($row['nama_kategori'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <a href="edit_berita.php?id=<?= $row['id'] ?>">edit</a>
                        <a href="hapus_berita.php?id=<?= $row['id'] ?>">hapus</a>
                    </td>
                </tr>
            <?php } ?>
    </table>
</div>
<?php include '../components/footer.php'; ?>
</body>