<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$pathParts = explode('/', trim($_SERVER['SCRIPT_NAME'], '/'));
$rootIndex = array_search('web', $pathParts);
$basePath = '';
if ($rootIndex !== false) {
    $basePath = '/' . implode('/', array_slice($pathParts, 0, $rootIndex + 1));
}

$user = null;
$imageSrc = '';
if (isset($_SESSION['username'])) {
    include_once __DIR__ . '/../config/conn.php';
    if (isset($con)) {
        $usernameSafe = mysqli_real_escape_string($con, $_SESSION['username']);
        $user = mysqli_fetch_assoc(mysqli_query($con, "select gambar, gambar_type from user where username='$usernameSafe'"));
        if (!empty($user['gambar'])) {
            $mime = !empty($user['gambar_type']) ? $user['gambar_type'] : 'image/jpeg';
            $imageSrc = 'data:' . $mime . ';base64,' . base64_encode($user['gambar']);
        }
    }
}
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>medsos</title>
    <link rel="stylesheet" href="<?= $basePath ?>/assets/styles.css">
</head>
    <header class="site-header">
        <div class="site-header__container">
            <a href="../index.php"><h2 class="site-header__logo">medsos lsp</h2></a>
            <!-- searchbar: redirect ke /index.php?search=$keyword -->
            <form class="site-header__search" method="get" action="<?= $basePath ?>/index.php">
                <input type="text" name="search" placeholder="Search" value="<?= htmlspecialchars(isset($_GET['search']) ? trim($_GET['search']) : '', ENT_QUOTES, 'UTF-8') ?>">
                <?php if (!empty($_GET['tag'])) { ?>
                    <input type="hidden" name="tag" value="<?= htmlspecialchars($_GET['tag'], ENT_QUOTES, 'UTF-8') ?>">
                <?php } ?>
                <button type="submit">🔍</button>
            </form>
            <div class="site-header__user">
                <span class="site-header__greeting">halo, <strong><?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'User'; ?></strong></span>
                <a href="<?= $basePath ?>/user/edit_profile.php" class="site-header__avatar">
                    <?php if ($imageSrc) { ?>
                    <img src="<?= $imageSrc ?>" alt="Foto profil">
                    <?php } ?>
                </a>
            </div>
        </div>
    </header>
