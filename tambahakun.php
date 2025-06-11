<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// Cek apakah user adalah admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: dataakun.php");
    exit();
}

include("koneksi_akun.php");

// Proses form ketika di-submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $role = $_POST['role'];
    
    // Validasi input
    $errors = [];
    
    if (empty($username)) {
        $errors[] = "Username tidak boleh kosong";
    } elseif (strlen($username) < 3) {
        $errors[] = "Username minimal 3 karakter";
    }
    
    if (empty($email)) {
        $errors[] = "Email tidak boleh kosong";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format email tidak valid";
    }
    
    if (empty($password)) {
        $errors[] = "Password tidak boleh kosong";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password minimal 6 karakter";
    }
    
    if ($password !== $confirm_password) {
        $errors[] = "Konfirmasi password tidak cocok";
    }
    
    if (!in_array($role, ['admin', 'pengguna'])) {
        $errors[] = "Role tidak valid";
    }
    
    // Cek apakah username sudah digunakan
    $check_username = "SELECT id FROM akun WHERE username = ?";
    $stmt_check = mysqli_prepare($koneksi, $check_username);
    mysqli_stmt_bind_param($stmt_check, "s", $username);
    mysqli_stmt_execute($stmt_check);
    $result_check = mysqli_stmt_get_result($stmt_check);
    
    if (mysqli_num_rows($result_check) > 0) {
        $errors[] = "Username sudah digunakan";
    }
    
    // Cek apakah email sudah digunakan
    $check_email = "SELECT id FROM akun WHERE email = ?";
    $stmt_check_email = mysqli_prepare($koneksi, $check_email);
    mysqli_stmt_bind_param($stmt_check_email, "s", $email);
    mysqli_stmt_execute($stmt_check_email);
    $result_check_email = mysqli_stmt_get_result($stmt_check_email);
    
    if (mysqli_num_rows($result_check_email) > 0) {
        $errors[] = "Email sudah digunakan";
    }
    
    // Jika tidak ada error, lakukan insert
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $created_at = date('Y-m-d H:i:s');
        
        $insert_query = "INSERT INTO akun (username, email, password, role, created_at) VALUES (?, ?, ?, ?, ?)";
        $stmt_insert = mysqli_prepare($koneksi, $insert_query);
        mysqli_stmt_bind_param($stmt_insert, "sssss", $username, $email, $hashed_password, $role, $created_at);
        
        if (mysqli_stmt_execute($stmt_insert)) {
            // Log aktivitas penambahan akun
            $log_message = "Akun baru dengan username '{$username}' berhasil dibuat oleh admin {$_SESSION['username']}";
            error_log($log_message);
            
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Akun baru berhasil dibuat',
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        window.location.href = 'dataakun.php';
                    });
                });
            </script>";
        } else {
            $errors[] = "Gagal membuat akun baru";
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdminLTE 4 | Tambah Akun</title>
    
    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css">
    <!-- OverlayScrollbars -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="dist/css/adminlte.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
        }
        
        .form-container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        
        .form-title {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: bold;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }
        
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
            box-sizing: border-box;
        }
        
        .form-group input:focus,
        .form-group select:focus {
            border-color: #007BFF;
            outline: none;
        }
        
        .password-requirements {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        
        .button-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }
        
        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: all 0.3s;
            min-width: 120px;
        }
        
        .btn-primary {
            background-color: #007BFF;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #0056b3;
            color: white;
        }
        
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #545b62;
            color: white;
        }
        
        .error-messages {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }
        
        .error-messages ul {
            margin: 0;
            padding-left: 20px;
        }
        
        .role-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 12px center;
            background-repeat: no-repeat;
            background-size: 16px;
            padding-right: 40px;
        }
        
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #007BFF;
            text-decoration: none;
            font-weight: bold;
        }
        
        .back-link:hover {
            color: #0056b3;
        }
        
        .back-link i {
            margin-right: 5px;
        }
        
        .required {
            color: #dc3545;
        }
        
        .form-info {
            background-color: #d1ecf1;
            color: #0c5460;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #bee5eb;
        }
        
        .form-info i {
            margin-right: 8px;
        }
    </style>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <!-- Header -->
        <?php include "header.php"; ?>
        <?php include "sidebar.php"; ?>

        <!-- Main Content -->
        <main class="app-main">
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6"><h3 class="mb-0"></h3></div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item"><a href="dataakun.php">Data Akun</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Tambah Akun</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="app-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <a href="dataakun.php" class="back-link">
                                <i class="bi bi-arrow-left"></i> Kembali ke Data Akun
                            </a>
                            
                            <div class="form-container">
                                <h2 class="form-title">Tambah Akun Baru</h2>
                                
                                <div class="form-info">
                                    <i class="bi bi-info-circle"></i>
                                    <strong>Informasi:</strong> Akun yang dibuat akan tercatat dengan tanggal dan waktu pembuatan secara otomatis.
                                </div>
                                
                                <?php if (!empty($errors)): ?>
                                    <div class="error-messages">
                                        <strong>Terjadi kesalahan:</strong>
                                        <ul>
                                            <?php foreach ($errors as $error): ?>
                                                <li><?php echo htmlspecialchars($error); ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                                
                                <form method="POST" action="" id="formTambahAkun">
                                    <div class="form-group">
                                        <label for="username">Username <span class="required">*</span></label>
                                        <input type="text" id="username" name="username" 
                                               value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" 
                                               required minlength="3" placeholder="Masukkan username">
                                        <div class="password-requirements">* Minimal 3 karakter</div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="email">Email <span class="required">*</span></label>
                                        <input type="email" id="email" name="email" 
                                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                                               required placeholder="Masukkan email">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="role">Role <span class="required">*</span></label>
                                        <select id="role" name="role" class="role-select" required>
                                            <option value="">Pilih Role</option>
                                            <option value="pengguna" <?php echo (isset($_POST['role']) && $_POST['role'] == 'pengguna') ? 'selected' : ''; ?>>
                                                Pengguna
                                            </option>
                                            <option value="admin" <?php echo (isset($_POST['role']) && $_POST['role'] == 'admin') ? 'selected' : ''; ?>>
                                                Admin
                                            </option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="password">Password <span class="required">*</span></label>
                                        <input type="password" id="password" name="password" 
                                               required minlength="6" placeholder="Masukkan password">
                                        <div class="password-requirements">* Minimal 6 karakter</div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="confirm_password">Konfirmasi Password <span class="required">*</span></label>
                                        <input type="password" id="confirm_password" name="confirm_password" 
                                               required placeholder="Ulangi password">
                                    </div>
                                    
                                    <div class="button-group">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-check-circle"></i> Buat Akun
                                        </button>
                                        <a href="dataakun.php" class="btn btn-secondary">
                                            <i class="bi bi-x-circle"></i> Batal
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="app-footer">
            <div class="float-end d-none d-sm-inline">Anything you want</div>
            <strong>Copyright &copy; 2014-2024&nbsp;<a href="https://adminlte.io" class="text-decoration-none">AdminLTE.io</a>.</strong>
            All rights reserved.
        </footer>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script src="dist/js/adminlte.js"></script>

    <script>
        // Validasi konfirmasi password
        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            
            if (password !== confirmPassword) {
                this.setCustomValidity('Password tidak cocok');
            } else {
                this.setCustomValidity('');
            }
        });
        
        // Validasi form sebelum submit
        document.getElementById('formTambahAkun').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Konfirmasi password tidak cocok!',
                });
                return false;
            }
        });

        // OverlayScrollbars Configure
        const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
        const Default = {
            scrollbarTheme: 'os-theme-light',
            scrollbarAutoHide: 'leave',
            scrollbarClickScroll: true,
        };
        document.addEventListener('DOMContentLoaded', function () {
            const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
            if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined') {
                OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                    scrollbars: {
                        theme: Default.scrollbarTheme,
                        autoHide: Default.scrollbarAutoHide,
                        clickScroll: Default.scrollbarClickScroll,
                    },
                });
            }
        });
    </script>
</body>
</html>