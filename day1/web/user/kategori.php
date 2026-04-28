<?php include '../config/conn.php'; ?>
<?php include '../components/header.php'; ?>
<body>
<div class="admin-page">
    <h2>kategori</h2>
    <a href="tambah_kategori.php">+ Tambah Kategori</a>
    <table class="data-table">
        <tr>
            <th>ID</th>
            <th>Nama Kategori</th>
            <th>Aksi</th>
        </tr>
        <?php 
        $query = mysqli_query($con, "SELECT * FROM kategori");
        while ($row = mysqli_fetch_assoc($query)) {
        ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['nama_kategori'], ENT_QUOTES, 'UTF-8') ?></td>
                <td>
                    <a href="edit_kategori.php?id=<?=$row['id']?>">edit</a>
                    <a href="hapus_kategori.php?id=<?=$row['id']?>">hapus</a>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>
<?php include '../components/footer.php'; ?>
</body>
