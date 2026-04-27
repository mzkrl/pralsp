<?php

$host = "127.0.0.1"; # getenv('DB_HOST');
$user = "root"; #getenv('DB_USER');
$password = "root" ;#getenv('DB_PASSWORD');
$d = "berita";#getenv('DB_NAME');

if ($host === false || $user === false || $d === false) {
    die("Database configuration is not set.");
}

if ($password === false) {
    $password = "";
}

$con = new mysqli($host, $user, $password, $d);
try {
    if ($con->connect_error) {
        throw new Exception("Connection failed: " . $con->connect_error);
    }
} catch (Exception $e) {
    die($e->getMessage());
}   
?>
