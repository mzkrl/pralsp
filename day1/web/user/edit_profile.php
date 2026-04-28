<?php
session_start();
include("../config/conn.php");
if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}

$username = mysqli_real_escape_string($con, $_SESSION['username']);
$user = mysqli_fetch_assoc(mysqli_query($con, "select * from user where username='$username'"));
$bio = $user ? $user['bio'] : '';
$imageSrc = '';
if (!empty($user['gambar'])) {
    $mime = !empty($user['gambar_type']) ? $user['gambar_type'] : 'image/jpeg';
    $imageSrc = 'data:' . $mime . ';base64,' . base64_encode($user['gambar']);
}

$success = '';
if (isset($_POST['save'])) {
    $bio = mysqli_real_escape_string($con, $_POST['bio']);
    if (strlen($bio) > 250) {
        $bio = substr($bio, 0, 250);
    }

    $setImage = '';
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) { //if kalo ada gambar, maka upload gambar data, gambarEsc. gambarType. terus set $setImage buat query update nanti
        $gambarData = file_get_contents($_FILES['gambar']['tmp_name']);
        $gambarEsc = mysqli_real_escape_string($con, $gambarData);
        $gambarType = mysqli_real_escape_string($con, $_FILES['gambar']['type']);
        $setImage = ", gambar='$gambarEsc', gambar_type='$gambarType'";
    }

    try {                                                                          //upload ke db
        mysqli_query($con, "update user set bio='$bio' $setImage where username='$username'");
        $success = "Profil tersimpan.";
    } catch (Exception $e) {
        $success = "Error: gambar terlalu besar.";
    }

}
?>
<?php include '../components/header.php'; ?>
<body>
    <form class="form-card" action="" method="post" enctype="multipart/form-data">
        <?php if ($success) { ?>
            <div class="alert-success"><?= $success ?></div>
        <?php } ?>
        <?php if ($imageSrc) { ?>
            <img class="form-card__profile-img" src="<?= $imageSrc ?>" alt="Foto profil">
        <?php } ?>
        <label>Bio</label>
        <textarea name="bio" maxlength="250"><?= htmlspecialchars($bio, ENT_QUOTES, 'UTF-8') ?></textarea>
        <label>Foto Profil</label>
        <input type="file" name="gambar" accept="image/*">
        <button name="save">simpan</button>
    </form> 

    <?php include '../components/footer.php'; ?>
</body>