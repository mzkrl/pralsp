<?php
include("config/conn.php");
?>
<?php include 'components/header.php'; ?>
<body>
    
<main class="feed-page">
    <div class="feed-divider"></div>
    <h1 class="feed-title">Feeds</h1>
    <!-- <div style="justify-content: space-between; display: flex; align-items: center; margin-bottom: 20px;"> -->
    <?php 
    $tag = isset($_GET['tag']) ? trim($_GET['tag']) : '';
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $conditions = array();

    if ($tag !== '') {
        $tagSafe = mysqli_real_escape_string($con, ltrim($tag, '#'));
        $conditions[] = "(judul like '%#$tagSafe%' or isi like '%#$tagSafe%' or exists (select 1 from komentar k where k.berita_id = berita.id and k.isi like '%#$tagSafe%'))";
    }
    if ($search !== '') {
        $searchSafe = mysqli_real_escape_string($con, $search);
        $conditions[] = "(judul like '%$searchSafe%' or isi like '%$searchSafe%')";
    }

    $whereSql = '';
    if (!empty($conditions)) {
        $whereSql = " where " . implode(" and ", $conditions);
    }

    $query = mysqli_query($con,"
    select * from berita" . $whereSql . " 
    order by tanggal desc
    ");
    if (mysqli_num_rows($query) === 0) {                                //if else kalo kosong
    ?>
        <div>Tidak ada aktivitas terbaru.</div>   
    <?php
    } else {
    while ($row = mysqli_fetch_assoc($query)) {                         //while untuk looping data
        $imageSrc = '';
        if (!empty($row['gambar'])) {
            $mime = !empty($row['gambar_type']) ? $row['gambar_type'] : 'image/jpeg';
            $imageSrc = 'data:' . $mime . ';base64,' . base64_encode($row['gambar']);
        }
        ?>
        <div class="feed-item">
            <div class="feed-avatar"></div>
            <a class="feed-card-link" href="detail.php?title=<?=rawurlencode(strtolower(str_replace(' ', '-', $row['judul']))) ?>&amp;id=<?= rawurlencode($row['id']) ?>">
                <div class="feed-card">
                    <?php if ($imageSrc) { ?>
                        <img class="feed-post-image" src="<?= $imageSrc ?>" alt="<?= htmlspecialchars($row['judul'], ENT_QUOTES, 'UTF-8') ?>">
                    <?php } ?>
                    <h3 class="feed-post-title"><?= $row['judul'] ?></h3>
                    <p class="feed-post-excerpt"><?= substr($row['isi'],0,100) ?></p>
                </div>
            </a>
        </div>
    <?php
    }} ?>
    <form method="get" class="feed-filter" style="margin-left:16px;">                                     <!-- untuk search pake hastag-->                  
            <input type="text" name="tag" placeholder="#hashtag" value="<?= htmlspecialchars(isset($_GET['tag']) ? trim($_GET['tag']) : '', ENT_QUOTES, 'UTF-8') ?>">
            <?php if (!empty($_GET['search'])) { ?>
                <input type="hidden" name="search" value="<?= htmlspecialchars(trim($_GET['search']), ENT_QUOTES, 'UTF-8') ?>">
            <?php } ?>
            <button type="submit">Filter</button>
    </form>
    <!-- </div> -->
</main>

    <?php include 'components/footer.php'; ?>
</body>
