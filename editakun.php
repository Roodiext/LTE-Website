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

// Ambil ID dari URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: dataakun.php");
    exit();
}

$id = $_GET['id'];

// Ambil data akun berdasarkan ID
$query = "SELECT * FROM akun WHERE id = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data_akun = mysqli_fetch_assoc($result);

if (!$data_akun) {
    header("Location: dataakun.php");
    exit();
}

// Proses form ketika di-submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];
    $password = trim($_POST['password']);
    
    // Validasi input
    $errors = [];
    
    if (empty($username)) {
        $errors[] = "Username tidak boleh kosong";
    }
    
    if (empty($email)) {
        $errors[] = "Email tidak boleh kosong";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format email tidak valid";
    }
    
    if (!in_array($role, ['admin', 'pengguna'])) {
        $errors[] = "Role tidak valid";
    }
    
    // Cek apakah username sudah digunakan oleh akun lain
    $check_username = "SELECT id FROM akun WHERE username = ? AND id != ?";
    $stmt_check = mysqli_prepare($koneksi, $check_username);
    mysqli_stmt_bind_param($stmt_check, "si", $username, $id);
    mysqli_stmt_execute($stmt_check);
    $result_check = mysqli_stmt_get_result($stmt_check);
    
    if (mysqli_num_rows($result_check) > 0) {
        $errors[] = "Username sudah digunakan";
    }
    
    // Cek apakah email sudah digunakan oleh akun lain
    $check_email = "SELECT id FROM akun WHERE email = ? AND id != ?";
    $stmt_check_email = mysqli_prepare($koneksi, $check_email);
    mysqli_stmt_bind_param($stmt_check_email, "si", $email, $id);
    mysqli_stmt_execute($stmt_check_email);
    $result_check_email = mysqli_stmt_get_result($stmt_check_email);
    
    if (mysqli_num_rows($result_check_email) > 0) {
        $errors[] = "Email sudah digunakan";
    }
    
    // Jika tidak ada error, lakukan update
    if (empty($errors)) {
        if (!empty($password)) {
            // Update dengan password baru
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $update_query = "UPDATE akun SET username = ?, email = ?, role = ?, password = ? WHERE id = ?";
            $stmt_update = mysqli_prepare($koneksi, $update_query);
            mysqli_stmt_bind_param($stmt_update, "ssssi", $username, $email, $role, $hashed_password, $id);
        } else {
            // Update tanpa mengubah password
            $update_query = "UPDATE akun SET username = ?, email = ?, role = ? WHERE id = ?";
            $stmt_update = mysqli_prepare($koneksi, $update_query);
            mysqli_stmt_bind_param($stmt_update, "sssi", $username, $email, $role, $id);
        }
        
        if (mysqli_stmt_execute($stmt_update)) {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Data akun berhasil diperbarui',
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        window.location.href = 'dataakun.php';
                    });
                });
            </script>";
        } else {
            $errors[] = "Gagal memperbarui data akun";
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdminLTE 4 | Edit Akun</title>
    
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
        
        .password-note {
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
                                <li class="breadcrumb-item active" aria-current="page">Edit Akun</li>
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
                                <h2 class="form-title">Edit Akun</h2>
                                
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
                                
                                <form method="POST" action="">
                                    <div class="form-group">
                                        <label for="username">Username:</label>
                                        <input type="text" id="username" name="username" 
                                               value="<?php echo htmlspecialchars($data_akun['username']); ?>" 
                                               required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="email">Email:</label>
                                        <input type="email" id="email" name="email" 
                                               value="<?php echo htmlspecialchars($data_akun['email']); ?>" 
                                               required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="role">Role:</label>
                                        <select id="role" name="role" class="role-select" required>
                                            <option value="pengguna" <?php echo ($data_akun['role'] == 'pengguna') ? 'selected' : ''; ?>>
                                                Pengguna
                                            </option>
                                            <option value="admin" <?php echo ($data_akun['role'] == 'admin') ? 'selected' : ''; ?>>
                                                Admin
                                            </option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="password">Password Baru (Opsional):</label>
                                        <input type="password" id="password" name="password" 
                                               placeholder="Kosongkan jika tidak ingin mengubah password">
                                        <div class="password-note">
                                            * Kosongkan jika tidak ingin mengubah password
                                        </div>
                                    </div>
                                    
                                    <div class="button-group">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-check-circle"></i> Simpan Perubahan
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