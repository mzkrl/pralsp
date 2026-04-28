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
    $tagSql = " and isi like '%#$tagSafe%'";
}

$errorComment = '';
if (isset($_POST['comment_submit']) && isset($_SESSION['login'])) {
    $isi = mysqli_real_escape_string($con, $_POST['isi']);
    if (strlen($isi) > 250) {
        $errorComment = "Maksimum 250 karakter.";
    } else {
        $username = mysqli_real_escape_string($con, $_SESSION['username']);

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

        mysqli_query($con,"
        insert into komentar (berita_id, username, isi, tanggal, gambar, gambar_type, file_blob, file_name, file_type)
        values($id, '$username', '$isi', NOW(), $gambarSql, $gambarTypeSql, $fileSql, $fileNameSql, $fileTypeSql)
        ");
        header("Location: detail.php?id=$id");
        exit;
    }
}

$result = mysqli_query($con, "select * from berita where id=$id");
$data = $result ? mysqli_fetch_assoc($result) : null;
?>
<head>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<?php include 'components/header.php'; ?>
<body>
    <?php if (!$data) { ?>
        <div>Post tidak ditemukan.</div>
    <?php } else { ?>
        <?php
        $imageSrc = '';
        if (!empty($data['gambar'])) {
            $mime = !empty($data['gambar_type']) ? $data['gambar_type'] : 'image/jpeg';
            $imageSrc = 'data:' . $mime . ';base64,' . base64_encode($data['gambar']);
        }
        ?>
        <h2><?= htmlspecialchars($data["judul"], ENT_QUOTES, 'UTF-8') ?></h2>
        <?php if ($imageSrc) { ?>
            <img class="feed-post-image" src="<?= $imageSrc ?>" alt="<?= htmlspecialchars($data['judul'], ENT_QUOTES, 'UTF-8') ?>">
        <?php } ?>
        <p><?= htmlspecialchars($data["isi"], ENT_QUOTES, 'UTF-8') ?></p>
        <?php if (!empty($data['file_blob'])) { ?>
            <a class="file-link" href="download.php?type=berita&id=<?= $id ?>">Download file: <?= htmlspecialchars($data['file_name'] ?: 'file', ENT_QUOTES, 'UTF-8') ?></a>
        <?php } ?>
    <?php } ?>

    <form method="get" class="feed-filter">
        <input type="text" name="tag" placeholder="#hashtag" value="<?= htmlspecialchars($tag, ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" name="id" value="<?= $id ?>">
        <button type="submit">Filter komentar</button>
    </form>

    <h3>Komentar</h3>
    <?php
        $commentQuery = "select * from komentar where berita_id=$id" . $tagSql . " order by tanggal desc";
        $comments = mysqli_query($con, $commentQuery);
        while ($c = mysqli_fetch_assoc($comments)) {
            $commentImage = '';
            if (!empty($c['gambar'])) {
                $mime = !empty($c['gambar_type']) ? $c['gambar_type'] : 'image/jpeg';
                $commentImage = 'data:' . $mime . ';base64,' . base64_encode($c['gambar']);
            }
    ?>
        <div class="comment-item">
            <strong><?= htmlspecialchars($c['username'], ENT_QUOTES, 'UTF-8') ?></strong>
            <p><?= htmlspecialchars($c['isi'], ENT_QUOTES, 'UTF-8') ?></p>
            <?php if ($commentImage) { ?>
                <img class="feed-post-image" src="<?= $commentImage ?>" alt="Komentar image">
            <?php } ?>
            <?php if (!empty($c['file_blob'])) { ?>
                <a class="file-link" href="download.php?type=komentar&id=<?= $c['id'] ?>">Download file: <?= htmlspecialchars($c['file_name'] ?: 'file', ENT_QUOTES, 'UTF-8') ?></a>
            <?php } ?>
            <?php if (isset($_SESSION['username']) && $_SESSION['username'] === $c['username']) { ?>
                <div>
                    <a class="file-link" href="comment_edit.php?id=<?= $c['id'] ?>&post_id=<?= $id ?>">edit</a>
                    <a class="file-link" href="comment_delete.php?id=<?= $c['id'] ?>&post_id=<?= $id ?>">hapus</a>
                </div>
            <?php } ?>
        </div>
    <?php } ?>

    <?php if (isset($_SESSION['login'])) { ?>
        <form method="post" enctype="multipart/form-data">
            <?php if ($errorComment) { ?>
                <div><?= $errorComment ?></div>
            <?php } ?>
            Komentar <textarea name="isi" maxlength="250"></textarea><br>
            Gambar <input type="file" name="gambar" accept="image/*"><br>
            File <input type="file" name="file"><br>
            <button name="comment_submit">kirim</button>
        </form>
    <?php } else { ?>
        <a class="file-link" href="auth/login.php">Login untuk komentar</a>
    <?php } ?>

    <a class="file-link" href="index.php">kembali</a>
    <?php include 'components/footer.php'; ?>
</body>