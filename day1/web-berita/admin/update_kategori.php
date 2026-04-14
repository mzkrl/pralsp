<?php
include("../config/conn.php");

$id = $_GET["id"];
$data = mysqli_query($con,
            "select * from kategori where id = '$id'");
$d = mysqli_fetch_assoc($data);

?>

<form action="" method="post">
    <input type="text" name="nama" value="<?= $d['nama'] ?>">
    <button name="update">update</button>
</form>

<?php
    if (isset($_POST[''])) {
        
    }
?>