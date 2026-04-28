<?php
session_start();
include("../config/conn.php");
$username = mysqli_real_escape_string($con, $_POST['username']);
$password = mysqli_real_escape_string($con, md5($_POST['password']));


$query = mysqli_query($con,"
    insert into user(username, password) values ('$username', '$password')
");
header("Location: login.php");
?>