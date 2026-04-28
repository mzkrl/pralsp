<?php
include("config/conn.php");

$type = isset($_GET['type']) ? $_GET['type'] : '';
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($type === 'berita') {
    $row = mysqli_fetch_assoc(mysqli_query($con, "select file_blob, file_type, file_name from berita where id=$id"));
} elseif ($type === 'komentar') {
    $row = mysqli_fetch_assoc(mysqli_query($con, "select file_blob, file_type, file_name from komentar where id=$id"));
} else {
    exit;
}

if (!$row || empty($row['file_blob'])) {
    exit;
}

$fileType = $row['file_type'] ? $row['file_type'] : 'application/octet-stream';
$fileName = $row['file_name'] ? $row['file_name'] : 'file';

header('Content-Type: ' . $fileType);
header('Content-Disposition: attachment; filename="' . $fileName . '"');

echo $row['file_blob'];
?>