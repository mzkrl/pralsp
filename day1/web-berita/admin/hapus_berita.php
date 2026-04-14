<?php
include("../config/conn.php");

$id= $_GET['id'];
mysqli_query($con,"
    delete from berita where id=$id
");

header("Location: berita.php");
?>