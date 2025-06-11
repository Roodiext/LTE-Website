<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// Cek apakah user adalah admin
if ($_SESSION['role'] !== 'admin') {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Akses Ditolak!',
                text: 'Hanya admin yang dapat menghapus akun!',
                showConfirmButton: false,
                timer: 3000
            }).then(() => {
                window.location.href = 'dataakun.php';
            });
        });
    </script>";
    include('header_minimal.php'); // Include minimal header untuk SweetAlert
    exit();
}

include("koneksi_akun.php");

// Ambil ID dari URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'ID akun tidak valid!',
                showConfirmButton: false,
                timer: 2000
            }).then(() => {
                window.location.href = 'dataakun.php';
            });
        });
    </script>";
    include('header_minimal.php');
    exit();
}

$id = $_GET['id'];

// Cek apakah akun yang akan dihapus ada
$check_query = "SELECT * FROM akun WHERE id = ?";
$stmt_check = mysqli_prepare($koneksi, $check_query);
mysqli_stmt_bind_param($stmt_check, "i", $id);
mysqli_stmt_execute($stmt_check);
$result_check = mysqli_stmt_get_result($stmt_check);
$akun_data = mysqli_fetch_assoc($result_check);

if (!$akun_data) {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Akun tidak ditemukan!',
                showConfirmButton: false,
                timer: 2000
            }).then(() => {
                window.location.href = 'dataakun.php';
            });
        });
    </script>";
    include('header_minimal.php');
    exit();
}

// Cek apakah user mencoba menghapus akun sendiri
if ($id == $_SESSION['user_id']) {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan!',
                text: 'Anda tidak dapat menghapus akun sendiri!',
                showConfirmButton: false,
                timer: 3000
            }).then(() => {
                window.location.href = 'dataakun.php';
            });
        });
    </script>";
    include('header_minimal.php');
    exit();
}

// Cek apakah ini akun admin terakhir
if ($akun_data['role'] == 'admin') {
    $count_admin_query = "SELECT COUNT(*) as total_admin FROM akun WHERE role = 'admin'";
    $result_count = mysqli_query($koneksi, $count_admin_query);
    $count_data = mysqli_fetch_assoc($result_count);
    
    if ($count_data['total_admin'] <= 1) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tidak Dapat Menghapus!',
                    text: 'Tidak dapat menghapus admin terakhir! Sistem harus memiliki minimal satu admin.',
                    showConfirmButton: false,
                    timer: 4000
                }).then(() => {
                    window.location.href = 'dataakun.php';
                });
            });
        </script>";
        include('header_minimal.php');
        exit();
    }
}

// Proses penghapusan akun
$delete_query = "DELETE FROM akun WHERE id = ?";
$stmt_delete = mysqli_prepare($koneksi, $delete_query);
mysqli_stmt_bind_param($stmt_delete, "i", $id);

if (mysqli_stmt_execute($stmt_delete)) {
    // BAGIAN BARU: Reset dan reorganisasi ID
    
    // 1. Ambil semua data yang tersisa dan urutkan berdasarkan created_at atau kriteria lain
    $select_remaining = "SELECT * FROM akun ORDER BY created_at ASC";
    $result_remaining = mysqli_query($koneksi, $select_remaining);
    $remaining_accounts = mysqli_fetch_all($result_remaining, MYSQLI_ASSOC);
    
    // 2. Update setiap record dengan ID berurutan mulai dari 1
    $new_id = 1;
    foreach ($remaining_accounts as $account) {
        $update_id_query = "UPDATE akun SET id = ? WHERE id = ?";
        $stmt_update = mysqli_prepare($koneksi, $update_id_query);
        mysqli_stmt_bind_param($stmt_update, "ii", $new_id, $account['id']);
        mysqli_stmt_execute($stmt_update);
        $new_id++;
    }
    
    // 3. Reset AUTO_INCREMENT ke nilai berikutnya
    $reset_auto_increment = "ALTER TABLE akun AUTO_INCREMENT = $new_id";
    mysqli_query($koneksi, $reset_auto_increment);
    
    // Log aktivitas penghapusan (opsional)
    $log_message = "Akun dengan username '{$akun_data['username']}' berhasil dihapus oleh admin {$_SESSION['username']} dan ID telah direorganisasi";
    error_log($log_message);
    
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Akun berhasil dihapus dan ID telah diperbarui!',
                showConfirmButton: false,
                timer: 2000
            }).then(() => {
                window.location.href = 'dataakun.php';
            });
        });
    </script>";
} else {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Gagal menghapus akun. Silakan coba lagi.',
                showConfirmButton: false,
                timer: 3000
            }).then(() => {
                window.location.href = 'dataakun.php';
            });
        });
    </script>";
}

mysqli_stmt_close($stmt_delete);
mysqli_close($koneksi);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdminLTE 4 | Hapus Akun</title>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Bootstrap CSS untuk styling minimal -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        
        .processing-container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 500px;
        }
        
        .spinner-border {
            width: 3rem;
            height: 3rem;
            color: #007BFF;
        }
        
        .processing-text {
            margin-top: 20px;
            color: #666;
            font-size: 18px;
        }
        
        .redirect-info {
            margin-top: 15px;
            color: #999;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="processing-container">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div class="processing-text">
            Memproses penghapusan akun dan reorganisasi ID...
        </div>
        <div class="redirect-info">
            Mohon tunggu, Anda akan diarahkan kembali ke halaman data akun.
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>