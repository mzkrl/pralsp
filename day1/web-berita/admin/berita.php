<?php include("../config/conn.php");?>
<head>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<h2>data berita</h2>
<a href="tambah_berita.php">tambah berita</a>
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
        join kategori on berita.kategori_id = kategori.id");

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