<?php
include("config/conn.php");
?>

<h1>web berita</h1>
<?php 
$query = mysqli_query($con,"
select * from berita
");

while ($row = mysqli_fetch_assoc($query)) {
    ?>
    <h3><?= $row['judul'] ?></h3>
    <p><?= substr($row['isi'],0,100) ?>...</p>
    <a href="detail.php?title=<?= rawurlencode(str_replace(' ', '-', $row['judul'])) ?>&amp;id=<?= rawurlencode($row['id']) ?>">baca</a>
    <hr>
<?php
} ?>