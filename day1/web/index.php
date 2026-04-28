<?php
include("config/conn.php");
?>
<?php include 'components/header.php'; ?>
<body>
    
<main class="feed-page">
    <div class="feed-divider"></div>
    <h1 class="feed-title">Feeds</h1>
    <?php 
    $query = mysqli_query($con,"
    select * from berita
    ");
    if (mysqli_num_rows($query) === 0) {                                //if else kalo kosong
    ?>
        <div>Tidak ada aktivitas terbaru.</div>   
    <?php
    } else {
    while ($row = mysqli_fetch_assoc($query)) {                         //while untuk looping data
        $imageSrc = '';
        if (!empty($row['gambar'])) {
            $imageSrc = 'data:image/jpeg;base64,' . base64_encode($row['gambar']);
        }
        ?>
        <div class="feed-item"> <span></span>
            <div class="feed-avatar"></div>
            <a class="feed-card-link" href="detail.php?title=<?=rawurlencode(strtolower(str_replace(' ', '-', $row['judul']))) ?>&amp;id=<?= rawurlencode($row['id']) ?>">
                <div class="feed-card">
                    <?php if ($imageSrc) { ?>
                        <img class="feed-post-image" src="<?= $imageSrc ?>" alt="<?= htmlspecialchars($row['judul'], ENT_QUOTES, 'UTF-8') ?>">
                    <?php } ?>
                    <h3 class="feed-post-title"><?= $row['judul'] ?></h3>
                    <p class="feed-post-excerpt"><?= substr($row['isi'],0,100) ?>...</p>
                </div>
            </a>
        </div>
    <?php
    }} ?>
</main>

    <?php include 'components/footer.php'; ?>
</body>
