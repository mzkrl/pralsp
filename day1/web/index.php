<?php
include("config/conn.php");
?>
<?php include 'components/header.php'; ?>
<body>

<div class="feed-layout" style="max-width:1180px; margin:24px auto; padding:0 20px;">
    <!-- KOLOM KIRI: Feed -->
    <div class="feed-main">
        <h1 class="feed-title">Feeds</h1>
        <div class="feed-divider"></div>
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
        select berita.*, user.id as uid, user.username as user_username, user.gambar as user_gambar, user.gambar_type as user_gambar_type
        from berita
        left join user on berita.user_id = user.id" . $whereSql . " 
        order by berita.tanggal desc
        ");
        if (mysqli_num_rows($query) === 0) {                                //if else kalo kosong
        ?>
            <div class="empty-state">Tidak ada aktivitas terbaru.</div>   
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
                <div class="feed-item__header">
                    <div class="feed-item__avatar">
                        <?php if ($avatarSrc) { ?>
                            <img src="<?= $avatarSrc ?>" alt="<?= htmlspecialchars($uploaderName, ENT_QUOTES, 'UTF-8') ?>">
                        <?php } ?>
                    </div>
                    <span class="feed-item__username"><a href="view.php?id=<?= (int)$row['uid'] ?>"><?= htmlspecialchars($uploaderName, ENT_QUOTES, 'UTF-8') ?></a></span>
                </div>
                <a class="feed-item__link" href="detail.php?title=<?=rawurlencode(strtolower(str_replace(' ', '-', $row['judul']))) ?>&amp;id=<?= rawurlencode($row['id']) ?>">
                    <div class="feed-item__card">
                        <?php if ($imageSrc) { ?>
                            <img class="feed-item__image" src="<?= $imageSrc ?>" alt="<?= htmlspecialchars($row['judul'], ENT_QUOTES, 'UTF-8') ?>">
                        <?php } ?>
                        <h3 class="feed-item__title"><?= htmlspecialchars($row['judul'], ENT_QUOTES, 'UTF-8') ?></h3>
                        <p class="feed-item__excerpt"><?= htmlspecialchars(substr($row['isi'],0,100), ENT_QUOTES, 'UTF-8') ?></p>
                    </div>
                </a>
            </div>
        <?php
        }} ?>
    </div>

    <!-- KOLOM KANAN: Sidebar -->
    <aside class="feed-sidebar">
        <!-- <div class="sidebar-banner"></div> -->
        <form method="get" class="sidebar-filter">                                     <!-- untuk search/filter pake hashtag -->
            <input type="text" name="tag" placeholder="#hashtag" value="<?= htmlspecialchars(isset($_GET['tag']) ? trim($_GET['tag']) : '', ENT_QUOTES, 'UTF-8') ?>">
            <?php if (!empty($_GET['search'])) { ?>
                <input type="hidden" name="search" value="<?= htmlspecialchars(trim($_GET['search']), ENT_QUOTES, 'UTF-8') ?>">
            <?php } ?>
            <button type="submit">filter</button>
        </form>

        <!-- Daftar hashtag dari semua postingan -->
        <div class="sidebar-tags">
            <?php
            // Ambil semua hashtag unik dari isi dan judul berita
            $hashtagQuery = mysqli_query($con, "select isi, judul from berita order by tanggal desc");
            $allTags = array();
            while ($hrow = mysqli_fetch_assoc($hashtagQuery)) {
                preg_match_all('/#(\w+)/u', $hrow['isi'] . ' ' . $hrow['judul'], $matches);
                foreach ($matches[1] as $ht) {
                    $lower = strtolower($ht);
                    if (!isset($allTags[$lower])) {
                        $allTags[$lower] = $ht;
                    }
                }
            }
            foreach ($allTags as $tagKey => $tagDisplay) {
            ?>
                <div class="sidebar-tags__item">
                    <a href="index.php?tag=<?= rawurlencode($tagDisplay) ?>">#<?= htmlspecialchars($tagDisplay, ENT_QUOTES, 'UTF-8') ?></a>
                </div>
            <?php } ?>
        </div>
    </aside>
</div>

    <?php include 'components/footer.php'; ?>
</body>
