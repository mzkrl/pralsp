<?php include '../config/conn.php';
?>
<head>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<h2>kategori</h2>
<a href="tambah_kategori.php">tambah kategori</a>

<table border="1" cellpadding="10" cellspacing="0">
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
            <td><?= $row['nama_kategori'] ?></td>
            <td><a href="hapus_kategori.php?id=<?=$row['id']?>">hapus</a> <a href="edit_kategori.php?id=<?=$row['id']?>">edit</a></td>
        </tr>
    <?php } ?>
</table>