<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_donasi";
$port = 3306; // Port dari XAMPP Anda

// Pastikan $port ditambahkan sebagai parameter terakhir
$koneksi = mysqli_connect($host, $user, $pass, $db, $port);

if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>