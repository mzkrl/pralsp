<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("config/conn.php");

$id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;
$tag = isset($_GET['tag']) ? trim($_GET['tag']) : '';
$tagSql = '';
if ($tag !== '') {
    $tagSafe = mysqli_real_escape_string($con, ltrim($tag, '#'));
    $tagSql = " and komentar.isi like '%#$tagSafe%'";
}

$errorComment = '';
if (isset($_POST['comment_submit']) && isset($_SESSION['login'])) {
    $isi = mysqli_real_escape_string($con, $_POST['isi']);
    if (strlen($isi) > 250) {
        $errorComment = "Maksimum 250 karakter.";
    } else {
        $username = mysqli_real_escape_string($con, $_SESSION['username']);

        // Ambil user_id dari session username untuk relasi komentar-user
        $userRow = mysqli_fetch_assoc(mysqli_query($con, "select id from user where username='$username'"));
        $userId = $userRow ? (int) $userRow['id'] : 0;

        $gambarSql = "NULL";
        $gambarTypeSql = "NULL";
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
            $gambarData = file_get_contents($_FILES['gambar']['tmp_name']);
            $gambarEsc = mysqli_real_escape_string($con, $gambarData);
            $gambarType = mysqli_real_escape_string($con, $_FILES['gambar']['type']);
            $gambarSql = "'" . $gambarEsc . "'";
            $gambarTypeSql = "'" . $gambarType . "'";
        }

        $fileSql = "NULL";
        $fileNameSql = "NULL";
        $fileTypeSql = "NULL";
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $fileData = file_get_contents($_FILES['file']['tmp_name']);
            $fileEsc = mysqli_real_escape_string($con, $fileData);
            $fileName = mysqli_real_escape_string($con, $_FILES['file']['name']);
            $fileType = mysqli_real_escape_string($con, $_FILES['file']['type']);
            $fileSql = "'" . $fileEsc . "'";
            $fileNameSql = "'" . $fileName . "'";
            $fileTypeSql = "'" . $fileType . "'";
        }

        // Insert komentar dengan user_id untuk relasi
        mysqli_query($con,"
        insert into komentar (berita_id, user_id, username, isi, tanggal, gambar, gambar_type, file_blob, file_name, file_type)
        values($id, $userId, '$username', '$isi', NOW(), $gambarSql, $gambarTypeSql, $fileSql, $fileNameSql, $fileTypeSql)
        ");
        header("Location: detail.php?id=$id");
        exit;
    }
}

// JOIN dengan user untuk mendapatkan foto profil pemilik postingan
$result = mysqli_query($con, "
    select berita.*, user.username as user_username, user.gambar as user_gambar, user.gambar_type as user_gambar_type
    from berita
    left join user on berita.user_id = user.id
    where berita.id=$id
");
$data = $result ? mysqli_fetch_assoc($result) : null;
?>
<?php include 'components/header.php'; ?>
<body>
<div class="detail-page">
    <?php if (!$data) { ?>
        <div class="empty-state">Post tidak ditemukan.</div>
    <?php } else { ?>
        <?php
        $imageSrc = '';
        if (!empty($data['gambar'])) {
            $mime = !empty($data['gambar_type']) ? $data['gambar_type'] : 'image/jpeg';
            $imageSrc = 'data:' . $mime . ';base64,' . base64_encode($data['gambar']);
        }

        // Foto profil pemilik postingan
        $postAvatarSrc = '';
        if (!empty($data['user_gambar'])) {
            $postAvatarMime = !empty($data['user_gambar_type']) ? $data['user_gambar_type'] : 'image/jpeg';
            $postAvatarSrc = 'data:' . $postAvatarMime . ';base64,' . base64_encode($data['user_gambar']);
        }
        $postAuthor = !empty($data['user_username']) ? $data['user_username'] : $data['uploader'];
        ?>
        <!-- Info pemilik postingan -->
        <div class="detail-author">
            <div class="detail-author__avatar">
                <?php if ($postAvatarSrc) { ?>
                    <img src="<?= $postAvatarSrc ?>" alt="<?= htmlspecialchars($postAuthor, ENT_QUOTES, 'UTF-8') ?>">
                <?php } ?>
            </div>
            <div class="detail-author__meta">
                <span class="detail-author__name"><?= htmlspecialchars($postAuthor, ENT_QUOTES, 'UTF-8') ?></span>
                <span class="detail-author__date"><?= htmlspecialchars($data['tanggal'], ENT_QUOTES, 'UTF-8') ?></span>
            </div>
        </div>

        <h2 class="detail-post__title"><?= htmlspecialchars($data["judul"], ENT_QUOTES, 'UTF-8') ?></h2>
        <?php if ($imageSrc) { ?>
            <img class="detail-post__image" src="<?= $imageSrc ?>" alt="<?= htmlspecialchars($data['judul'], ENT_QUOTES, 'UTF-8') ?>">
        <?php } ?>
        <div class="detail-post__content">
            <p><?= htmlspecialchars($data["isi"], ENT_QUOTES, 'UTF-8') ?></p>
        </div>
        <?php if (!empty($data['file_blob'])) { ?>
            <a class="detail-file-link" href="download.php?type=berita&id=<?= $id ?>">Download file: <?= htmlspecialchars($data['file_name'] ?: 'file', ENT_QUOTES, 'UTF-8') ?></a>
        <?php } ?>
    <?php } ?>

    <form method="get" class="detail-filter">
        <input type="text" name="tag" placeholder="#hashtag" value="<?= htmlspecialchars($tag, ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" name="id" value="<?= $id ?>">
        <button type="submit">Filter komentar</button>
    </form>

    <h3 class="comment-section__title">Komentar</h3>
    <?php
        // JOIN komentar dengan user untuk foto profil komentator
        $commentQuery = "
            select komentar.*, user.gambar as user_gambar, user.gambar_type as user_gambar_type
            from komentar
            left join user on komentar.user_id = user.id
            where komentar.berita_id=$id" . $tagSql . " 
            order by komentar.tanggal desc
        ";
        $comments = mysqli_query($con, $commentQuery);
        while ($c = mysqli_fetch_assoc($comments)) {
            $commentImage = '';
            if (!empty($c['gambar'])) {
                $mime = !empty($c['gambar_type']) ? $c['gambar_type'] : 'image/jpeg';
                $commentImage = 'data:' . $mime . ';base64,' . base64_encode($c['gambar']);
            }

            // Foto profil komentator
            $commentAvatarSrc = '';
            if (!empty($c['user_gambar'])) {
                $commentAvatarMime = !empty($c['user_gambar_type']) ? $c['user_gambar_type'] : 'image/jpeg';
                $commentAvatarSrc = 'data:' . $commentAvatarMime . ';base64,' . base64_encode($c['user_gambar']);
            }
    ?>
        <div class="comment-item">
            <div class="comment-item__header">
                <div class="comment-item__avatar">
                    <?php if ($commentAvatarSrc) { ?>
                        <img src="<?= $commentAvatarSrc ?>" alt="<?= htmlspecialchars($c['username'], ENT_QUOTES, 'UTF-8') ?>">
                    <?php } ?>
                </div>
                <span class="comment-item__name"><?= htmlspecialchars($c['username'], ENT_QUOTES, 'UTF-8') ?></span>
            </div>
            <p><?= htmlspecialchars($c['isi'], ENT_QUOTES, 'UTF-8') ?></p>
            <?php if ($commentImage) { ?>
                <img class="comment-item__image" src="<?= $commentImage ?>" alt="Komentar image">
            <?php } ?>
            <?php if (!empty($c['file_blob'])) { ?>
                <a class="detail-file-link" href="download.php?type=komentar&id=<?= $c['id'] ?>">Download: <?= htmlspecialchars($c['file_name'] ?: 'file', ENT_QUOTES, 'UTF-8') ?></a>
            <?php } ?>
            <?php if (isset($_SESSION['username']) && $_SESSION['username'] === $c['username']) { ?>
                <div class="comment-item__actions">
                    <a href="comment_edit.php?id=<?= $c['id'] ?>&post_id=<?= $id ?>">edit</a>
                    <a href="comment_delete.php?id=<?= $c['id'] ?>&post_id=<?= $id ?>">hapus</a>
                </div>
            <?php } ?>
        </div>
    <?php } ?>

    <?php if (isset($_SESSION['login'])) { ?>
        <form method="post" enctype="multipart/form-data" class="comment-form">
            <?php if ($errorComment) { ?>
                <div class="alert-error"><?= $errorComment ?></div>
            <?php } ?>
            <label>Komentar</label>
            <textarea name="isi" maxlength="250"></textarea>
            <label>Gambar</label>
            <input type="file" name="gambar" accept="image/*">
            <label>File</label>
            <input type="file" name="file">
            <button name="comment_submit">kirim</button>
        </form>
    <?php } else { ?>
        <a class="login-prompt" href="auth/login.php">Login untuk komentar</a>
    <?php } ?>

    <a class="back-link" href="index.php">← kembali</a>
</div>

    <?php include 'components/footer.php'; ?>
</body>