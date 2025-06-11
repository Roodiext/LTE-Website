<?php
include("koneksi_jurusan.php");
$db = new JurusanDatabase();

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

if (isset($_GET['kodejurusan'])) {
    $kodejurusan = $_GET['kodejurusan'];
    if ($db->hapus_jurusan($kodejurusan)) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Data jurusan berhasil dihapus!'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Gagal menghapus data jurusan!'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Kode jurusan tidak ditemukan!'
    ]);
}
?>