<?php
$host = "localhost";       // Ganti kalau Athaa pakai server beda
$user = "root";            // Default user XAMPP
$pass = "";                // Default password XAMPP (biasanya kosong)
$db   = "sekolah";         // Ganti sesuai nama database Athaa

$koneksi = mysqli_connect($host, $user, $pass, $db);

// Cek koneksi
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
