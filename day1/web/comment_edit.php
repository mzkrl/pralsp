<?php
session_start();
include("config/conn.php");
if (!isset($_SESSION['login'])) {
    header("Location: auth/login.php");
    exit;
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$postId = isset($_GET['post_id']) ? (int) $_GET['post_id'] : 0;
$username = mysqli_real_escape_string($con, $_SESSION['username']);

$comment = mysqli_fetch_assoc(mysqli_query($con, "select * from komentar where id=$id and username='$username'"));
if (!$comment) {
    echo "Komentar tidak ditemukan";
    exit;
}

$error = '';
if (isset($_POST['update'])) {
    $isi = mysqli_real_escape_string($con, $_POST['isi']);
    if (strlen($isi) > 250) {
        $error = "Maksimum 250 karakter.";
    } else {
        mysqli_query($con, "update komentar set isi='$isi' where id=$id and username='$username'");
        header("Location: detail.php?id=$postId");
        exit;
    }
}
?>
<head>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<?php include 'components/header.php'; ?>
<form method="post">
    <?php if ($error) { ?>
        <div><?= $error ?></div>
    <?php } ?>
    Komentar <textarea name="isi" maxlength="250"><?= htmlspecialchars($comment['isi'], ENT_QUOTES, 'UTF-8') ?></textarea><br>
    <button name="update">update</button>
</form>
<?php include 'components/footer.php'; ?>