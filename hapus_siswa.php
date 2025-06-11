<?php
include("koneksi_siswa.php");
$db = new SiswaDatabase();

session_start();

// Set header untuk JSON response
header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Session tidak valid',
        'redirect' => 'login.php'
    ]);
    exit();
}

if ($_SESSION['role'] !== 'admin') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Akses ditolak! Halaman ini hanya bisa diakses oleh admin.',
        'redirect' => 'index.php'
    ]);
    exit();
}

if (isset($_GET['nisn'])) {
    $nisn = $_GET['nisn'];
    if ($db->hapus_siswa($nisn)) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Data siswa berhasil dihapus!'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Gagal menghapus data siswa!'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'NISN tidak ditemukan!'
    ]);
}
?>