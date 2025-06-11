<?php
session_start();
include 'koneksi_akun.php';

if (isset($_POST['login'])) {
    // Mengambil data dari form
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = $_POST['password'];
    
    // Validasi input kosong (server-side, sebagai fallback jika client-side bypass)
    if (empty($email) || empty($password)) {
        header("Location: login.php?status=empty_fields&message=Email dan password harus diisi!");
        exit();
    }
    
    // Validasi panjang password (server-side, sebagai fallback jika client-side bypass)
    if (strlen($password) < 6) {
        header("Location: login.php?status=password_short&message=Password harus memiliki minimal 6 karakter.");
        exit();
    }

    // Query untuk mencari user berdasarkan email
    $query = "SELECT * FROM akun WHERE email = '$email'";
    $result = mysqli_query($koneksi, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            // Login berhasil, buat session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['logged_in'] = true;
            
            // Redirect ke login dengan parameter khusus untuk menampilkan sukses
            header("Location: login.php?success_login=true&username=" . urlencode($user['username']));
            exit();   
        } else {
            // Password salah
            header("Location: login.php?status=error&message=Email atau password salah! Silahkan coba kembali");
            exit();
        }
    } else {
        // Email tidak ditemukan (atau kombinasi email/password salah)
        header("Location: login.php?status=error&message=Email atau password salah! Silahkan coba kembali");
        exit();
    }
} else {
    // Jika tidak ada data POST, redirect ke login
    header("Location: login.php");
    exit();
}
?>