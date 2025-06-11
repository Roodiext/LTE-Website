<?php
include("koneksi_agama.php");
$db = new AgamaDatabase();

session_start();

// Set header untuk JSON response
header('Content-Type: application/json');

// Cek apakah session valid
if (!isset($_SESSION['username'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Session tidak valid',
        'redirect' => 'login.php'
    ]);
    exit();
}

// Cek apakah user adalah admin
if ($_SESSION['role'] !== 'admin') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Akses ditolak! Halaman ini hanya bisa diakses oleh admin.',
        'redirect' => 'index.php'
    ]);
    exit();
}

// Proses penghapusan data
if (isset($_GET['idAgama'])) {
    $idAgama = $_GET['idAgama'];
    
    // Validasi ID Agama tidak kosong
    if (empty($idAgama)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'ID Agama tidak boleh kosong!'
        ]);
        exit();
    }
    
    // Eksekusi penghapusan
    if ($db->hapus_agama($idAgama)) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Data agama berhasil dihapus!'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Gagal menghapus data agama! Data mungkin sedang digunakan di tabel lain.'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'ID Agama tidak ditemukan!'
    ]);
}
?>