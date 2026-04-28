<?php
/**
 * View Profile - Halaman untuk melihat profil user lain
 * Akses: /view.php?id=<user_id>
 */
include("config/conn.php");

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Ambil data user berdasarkan id
$userResult = mysqli_query($con, "select * from user where id=$id");
$userData = $userResult ? mysqli_fetch_assoc($userResult) : null;
?>
<?php include 'components/header.php'; ?>
<body>
<div class="detail-page">
    <?php if (!$userData) { ?>
        <div class="empty-state">User tidak ditemukan.</div>
    <?php } else { ?>
        <?php
        // Foto profil user
        $profileImg = '';
        if (!empty($userData['gambar'])) {
            $mime = !empty($userData['gambar_type']) ? $userData['gambar_type'] : 'image/jpeg';
            $profileImg = 'data:' . $mime . ';base64,' . base64_encode($userData['gambar']);
        }
        ?>
        <!-- Profil user -->
        <div class="profile-card">
            <div class="profile-card__avatar">
                <?php if ($profileImg) { ?>
                    <img src="<?= $profileImg ?>" alt="<?= htmlspecialchars($userData['username'], ENT_QUOTES, 'UTF-8') ?>">
                <?php } ?>
            </div>
            <h2 class="profile-card__name"><?= htmlspecialchars($userData['username'], ENT_QUOTES, 'UTF-8') ?></h2>
            <?php if (!empty($userData['bio'])) { ?>
                <p class="profile-card__bio"><?= htmlspecialchars($userData['bio'], ENT_QUOTES, 'UTF-8') ?></p>
            <?php } else { ?>
                <p class="profile-card__bio" style="font-style:italic;">Belum ada bio.</p>
            <?php } ?>
        </div>

        <!-- Postingan user -->
        <h3 class="comment-section__title">Postingan oleh <?= htmlspecialchars($userData['username'], ENT_QUOTES, 'UTF-8') ?></h3>
        <?php
        $postsQuery = mysqli_query($con, "
            select * from berita where user_id=$id order by tanggal desc
        ");
        if (mysqli_num_rows($postsQuery) === 0) {
        ?>
            <div class="empty-state">Belum ada postingan.</div>
        <?php } else {
            while ($row = mysqli_fetch_assoc($postsQuery)) {
                $postImg = '';
                if (!empty($row['gambar'])) {
                    $postMime = !empty($row['gambar_type']) ? $row['gambar_type'] : 'image/jpeg';
                    $postImg = 'data:' . $postMime . ';base64,' . base64_encode($row['gambar']);
                }
        ?>
            <div class="feed-item">
                <a class="feed-item__link" href="detail.php?title=<?=rawurlencode(strtolower(str_replace(' ', '-', $row['judul']))) ?>&amp;id=<?= rawurlencode($row['id']) ?>">
                    <div class="feed-item__card">
                        <?php if ($postImg) { ?>
                            <img class="feed-item__image" src="<?= $postImg ?>" alt="<?= htmlspecialchars($row['judul'], ENT_QUOTES, 'UTF-8') ?>">
                        <?php } ?>
                        <h3 class="feed-item__title"><?= htmlspecialchars($row['judul'], ENT_QUOTES, 'UTF-8') ?></h3>
                        <p class="feed-item__excerpt"><?= htmlspecialchars(substr($row['isi'],0,100), ENT_QUOTES, 'UTF-8') ?></p>
                    </div>
                </a>
            </div>
        <?php }} ?>
    <?php } ?>

    <a class="back-link" href="index.php">← kembali</a>
</div>
<?php include 'components/footer.php'; ?>
</body>
