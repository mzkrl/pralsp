<?php
include("config/conn.php");
?>
<?php include 'components/header.php'; ?>
<body>
    
<main class="feed-page">
    <div class="feed-divider"></div>
    <h1 class="feed-title">Feeds</h1>
    <?php 
    $tag = isset($_GET['tag']) ? trim($_GET['tag']) : '';
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $conditions = array();

    if ($tag !== '') {
        $tagSafe = mysqli_real_escape_string($con, ltrim($tag, '#'));
        $conditions[] = "(berita.judul like '%#$tagSafe%' or berita.isi like '%#$tagSafe%' or exists (select 1 from komentar k where k.berita_id = berita.id and k.isi like '%#$tagSafe%'))";
    }
    if ($search !== '') {
        $searchSafe = mysqli_real_escape_string($con, $search);
        $conditions[] = "(berita.judul like '%$searchSafe%' or berita.isi like '%$searchSafe%')";
    }

    $whereSql = '';
    if (!empty($conditions)) {
        $whereSql = " where " . implode(" and ", $conditions);
    }

    // JOIN dengan tabel user untuk mendapatkan foto profil dan username uploader
    $query = mysqli_query($con,"
    select berita.*, user.username as user_username, user.gambar as user_gambar, user.gambar_type as user_gambar_type
    from berita
    left join user on berita.user_id = user.id" . $whereSql . " 
    order by berita.tanggal desc
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

        // Foto profil uploader
        $avatarSrc = '';
        if (!empty($row['user_gambar'])) {
            $avatarMime = !empty($row['user_gambar_type']) ? $row['user_gambar_type'] : 'image/jpeg';
            $avatarSrc = 'data:' . $avatarMime . ';base64,' . base64_encode($row['user_gambar']);
        }

        // Nama uploader: prioritaskan dari relasi user, fallback ke kolom uploader
        $uploaderName = !empty($row['user_username']) ? $row['user_username'] : $row['uploader'];
        ?>
        <div class="feed-item">
            <div class="feed-avatar-wrapper">
                <?php if ($avatarSrc) { ?>
                    <img class="feed-avatar-img" src="<?= $avatarSrc ?>" alt="<?= htmlspecialchars($uploaderName, ENT_QUOTES, 'UTF-8') ?>">
                <?php } else { ?>
                    <div class="feed-avatar"></div>
                <?php } ?>
                <span class="feed-username"><?= htmlspecialchars($uploaderName, ENT_QUOTES, 'UTF-8') ?></span>
            </div>
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
</main>

    <?php include 'components/footer.php'; ?>
</body>
