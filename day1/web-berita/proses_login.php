<?php
session_start();
include("../config/conn.php");
$username = $_POST["username"];
$password = md5($_POST["password"]);

$query = mysqli_query($con,"
    select * from user where username='$username' and password='$password'
");

$data = mysqli_fetch_assoc($query);

if($data) {
    $_SESSION['login'] = true;
    header("Location: ../admin/dashboard.php");
    exit;
}else{
    echo "login gagal";
}
?>