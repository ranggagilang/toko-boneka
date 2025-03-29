<?php
// Konfigurasi database yang mendukung Railway
$host = getenv('MYSQLHOST') ?: 'localhost';
$user = getenv('MYSQLUSER') ?: 'root';
$pass = getenv('MYSQLPASSWORD') ?: '';
$db = getenv('MYSQLDATABASE') ?: 'railway'; // Ubah dari 'db_toko_boneka' ke 'railway'
$port = getenv('MYSQLPORT') ?: '3306';

$conn = mysqli_connect($host, $user, $pass, $db, $port);

if (!$conn) {
    die("koneksi gagal: " . mysqli_connect_error());
}
?>