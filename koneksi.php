<?php
$host = "localhost";
$user = "root";
$pass = ""; // sesuaikan password database MySQL Anda
$db   = "db_keuangan";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi Database Gagal: " . mysqli_connect_error());
}
?>