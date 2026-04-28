<?php
include("config/conn.php");
?>
<?php require("components/header.html"); ?>
<body>
    
<h1 class="">web berita</h1>
    <?php 
    $query = mysqli_query($con,"
    select * from berita
    ");

    while ($row = mysqli_fetch_assoc($query)) {
        ?>
        <div class="cards-container">
            <h3 class="flex"><?= $row['judul'] ?></h3>
            <p><?= substr($row['isi'],0,100) ?>...</p>
            <a href="detail.php?title=<?=rawurlencode(strtolower(str_replace(' ', '-', $row['judul']))) ?>&amp;id=<?= rawurlencode($row['id']) ?>">baca</a>
            <hr>
        </div>
        
    <?php
    } ?>
    <?php include 'components/footer.html'; ?>
</body>
