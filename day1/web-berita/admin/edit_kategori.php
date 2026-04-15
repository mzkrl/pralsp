<?php
include("../config/conn.php");

$id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;
$data = mysqli_query($con,
            "select * from kategori where id = $id");
$d = mysqli_fetch_assoc($data);

if (isset($_POST['update'])) {
    $nama = mysqli_real_escape_string($con, $_POST['nama']);

    mysqli_query($con,"
    update kategori set nama_kategori='$nama' where id=$id
    ");

    header("Location: kategori.php");
    exit;
}
?>
<head>
    <link rel="stylesheet" href="../assets/styles.css">
    <title>Edit Kategori</title>
</head>

<form action="" method="post">
    <h2>Edit Kategori</h2>
    <input type="text" name="nama" value="<?= htmlspecialchars($d['nama_kategori']) ?>" required>
    <button name="update">Update</button>
</form>

<?php include '../components/footer.html'; ?>