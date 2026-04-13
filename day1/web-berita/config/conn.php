<?php

$user="user";
$host="127.0.0.1";
$password="";
$d="berita";

    $con = new mysqli($host,$user,$password,$d);
if ($con->connect_error) {
    die("die". $con->connect_error);
}
?>