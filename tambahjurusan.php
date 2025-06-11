<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['role'] !== 'admin') {
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Akses Ditolak!',
            text: 'Halaman ini hanya bisa diakses oleh admin.',
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        }).then(function() {
            window.location.href = 'index.php'; // Kembali ke halaman utama
        });
    </script>";
    exit();
}

include("koneksi_jurusan.php");
$db = new JurusanDatabase();

$alert_message = '';
$alert_type = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $namajurusan = trim($_POST['namajurusan']);

    // Validasi nama jurusan tidak kosong dan tidak duplikat
    if (empty($namajurusan)) {
        $alert_message = 'Nama jurusan tidak boleh kosong!';
        $alert_type = 'error';
    } else {
        // Cek duplikat nama jurusan
        $check_query = "SELECT COUNT(*) as count FROM jurusan WHERE LOWER(namajurusan) = LOWER(?)";
        $check_stmt = $db->koneksi->prepare($check_query);
        $check_stmt->bind_param("s", $namajurusan);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        $check_row = $check_result->fetch_assoc();

        if ($check_row['count'] > 0) {
            $alert_message = 'Nama jurusan sudah ada dalam database!';
            $alert_type = 'error';
        } else {
            $query = "INSERT INTO jurusan (namajurusan) VALUES (?)";
            $stmt = $db->koneksi->prepare($query);
            $stmt->bind_param("s", $namajurusan);

            if ($stmt->execute()) {
                $alert_message = 'Data jurusan berhasil ditambahkan!';
                $alert_type = 'success';
            } else {
                $alert_message = 'Gagal menambahkan data jurusan!';
                $alert_type = 'error';
            }
            $stmt->close();
        }
        $check_stmt->close();
    }
}
?>

<!doctype html>
<html lang="en">
<!--begin::Head-->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>AdminLTE 4 | Tambah Jurusan</title>
    <!--begin::Primary Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="title" content="AdminLTE 4 | Tambah Jurusan" />
    <meta name="author" content="ColorlibHQ" />
    <meta name="description" content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS." />
    <meta name="keywords" content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard" />
    <!--end::Primary Meta Tags-->
    
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" />
    <!--end::Fonts-->
    
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css" integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg=" crossorigin="anonymous" />
    <!--end::Third Party Plugin(OverlayScrollbars)-->
    
    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI=" crossorigin="anonymous" />
    <!--end::Third Party Plugin(Bootstrap Icons)-->
    
    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="dist/css/adminlte.css" />
    <!--end::Required Plugin(AdminLTE)-->
    
    <!--begin::Animate.css for SweetAlert2 animations-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <!--end::Animate.css-->
    
    <!--begin::SweetAlert2-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!--end::SweetAlert2-->
    
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
        }

        .container {
            background: #fff;
            padding: 40px 50px;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            max-width: 600px;
            width: 100%;
            margin: 0 auto;
            position: relative;
            overflow: hidden;
        }

        .container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #4A90E2, #357ABD, #2E86AB);
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 35px;
            font-weight: 600;
            font-size: 2rem;
            position: relative;
        }

        h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background: linear-gradient(90deg, #4A90E2, #357ABD);
            border-radius: 2px;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        label {
            font-weight: 600;
            margin-bottom: 8px;
            color: #34495e;
            display: block;
            font-size: 15px;
            transition: color 0.3s ease;
        }

        input[type="text"] {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e0e6ed;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
            background-color: #fafbfc;
            box-sizing: border-box;
        }

        input[type="text"]:focus {
            border-color: #4A90E2;
            background-color: #fff;
            outline: none;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(74, 144, 226, 0.2);
        }

        input[type="text"]:focus + label {
            color: #4A90E2;
        }

        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 35px;
        }

        button {
            flex: 1;
            padding: 15px 25px;
            background: linear-gradient(135deg, #4A90E2, #357ABD);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        button:hover::before {
            left: 100%;
        }

        button:hover {
            background: linear-gradient(135deg, #357ABD, #2E86AB);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(74, 144, 226, 0.4);
        }

        button:active {
            transform: translateY(-1px);
        }

        .btn-cancel {
            flex: 1;
            text-align: center;
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            padding: 15px 25px;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            position: relative;
            overflow: hidden;
        }

        .btn-cancel::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn-cancel:hover::before {
            left: 100%;
        }

        .btn-cancel:hover {
            background: linear-gradient(135deg, #c0392b, #a93226);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(231, 76, 60, 0.4);
            color: white;
            text-decoration: none;
        }

        .btn-cancel:active {
            transform: translateY(-1px);
        }

        .info-box {
            background: linear-gradient(135deg, #e3f2fd, #bbdefb);
            border-left: 4px solid #2196f3;
            border-radius: 8px;
            padding: 15px 20px;
            margin-bottom: 25px;
            color: #1565c0;
            font-size: 14px;
            position: relative;
            overflow: hidden;
        }

        .info-box::before {
            content: '💡';
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
            opacity: 0.7;
        }

        .info-box strong {
            color: #0d47a1;
        }

        /* Loading animation */
        .loading {
            position: relative;
            pointer-events: none;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: #ffffff;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Input validation styles */
        .input-error {
            border-color: #e74c3c !important;
            background-color: #fdf2f2 !important;
        }

        .input-success {
            border-color: #27ae60 !important;
            background-color: #f0f9f4 !important;
        }

        /* Custom SweetAlert2 styling */
        .swal2-popup {
            border-radius: 20px !important;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.3) !important;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
        }

        .swal2-title {
            font-size: 1.6rem !important;
            font-weight: 600 !important;
            color: #2c3e50 !important;
        }

        .swal2-content {
            font-size: 1.1rem !important;
            color: #34495e !important;
        }

        .swal2-confirm {
            border-radius: 10px !important;
            padding: 12px 30px !important;
            font-weight: 600 !important;
            font-size: 14px !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
        }

        .swal2-cancel {
            border-radius: 10px !important;
            padding: 12px 30px !important;
            font-weight: 600 !important;
            font-size: 14px !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
        }

        @media (max-width: 768px) {
            .container {
                margin: 20px;
                padding: 30px 25px;
                max-width: none;
                width: auto;
            }

            .form-actions {
                flex-direction: column;
            }

            button, .btn-cancel {
                margin: 5px 0;
            }

            h2 {
                font-size: 1.6rem;
            }
        }

        /* Smooth page transition */
        .app-content {
            animation: fadeInUp 0.5s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<!--end::Head-->

<!--begin::Body-->
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
        <!--begin::Header-->
        <?php include "header.php"; ?>
        <?php include "sidebar.php"; ?>
        
        <!--begin::App Main-->
        <main class="app-main">
            <!--begin::App Content Header-->
            <div class="app-content-header">
                <!--begin::Container-->
                <div class="container-fluid">
                    <!--begin::Row-->
                    <div class="row">
                        <div class="col-sm-6"><h3 class="mb-0"></h3></div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Tambah Jurusan</li>
                            </ol>
                        </div>
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Container-->
            </div>
            <!--end::App Content Header-->
            
            <!--begin::App Content-->
            <div class="app-content">
                <!--begin::Container-->
                <div class="container-fluid">
                    <!--begin::Row-->
                    <div class="row">
                        <div class="container">
                            <h2>Tambah Data Jurusan</h2>
                            
                            <div class="info-box">
                                <strong>Info:</strong> Masukkan nama jurusan yang baru. Pastikan nama jurusan belum ada dalam database untuk menghindari duplikasi data.
                            </div>
                            
                            <form method="POST" action="" id="formTambahJurusan">
                                <div class="form-group">
                                    <label for="namajurusan">Nama Jurusan:</label>
                                    <input type="text" name="namajurusan" id="namajurusan" required maxlength="100" placeholder="Contoh: Teknik Informatika">
                                    <small style="color: #6c757d; margin-top: 5px; display: block;">Maksimal 100 karakter</small>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" id="btnSimpan">
                                        <span id="btnText">💾 Simpan</span>
                                    </button>
                                    <a href="datajurusan.php" class="btn-cancel" id="btnBatal">❌ Batal</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Container-->
            </div>
            <!--end::App Content-->
        </main>
        <!--end::App Main-->
        
        <!--begin::Footer-->
        <footer class="app-footer">
            <!--begin::To the end-->
            <div class="float-end d-none d-sm-inline">Anything you want</div>
            <!--end::To the end-->
            <!--begin::Copyright-->
            <strong>
                Copyright &copy; 2014-2024&nbsp;
                <a href="https://adminlte.io" class="text-decoration-none">AdminLTE.io</a>.
            </strong>
            All rights reserved.
            <!--end::Copyright-->
        </footer>
        <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->

    <?php if (!empty($alert_message)): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($alert_type === 'success'): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil! 🎉',
                    text: '<?php echo $alert_message; ?>',
                    showConfirmButton: true,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#28a745',
                    timer: 3000,
                    timerProgressBar: true,
                    showClass: {
                        popup: 'animate__animated animate__bounceIn'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__bounceOut'
                    },
                    backdrop: `
                        rgba(40,167,69,0.2)
                        center left
                        no-repeat
                    `
                }).then((result) => {
                    if (result.isConfirmed || result.dismiss === Swal.DismissReason.timer) {
                        window.location.href = 'datajurusan.php';
                    }
                });
            <?php elseif ($alert_type === 'error'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops... 😞',
                    text: '<?php echo $alert_message; ?>',
                    showConfirmButton: true,
                    confirmButtonText: 'Coba Lagi',
                    confirmButtonColor: '#dc3545',
                    showClass: {
                        popup: 'animate__animated animate__shakeX'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    },
                    backdrop: `
                        rgba(220,53,69,0.2)
                        center left
                        no-repeat
                    `
                });
            <?php endif; ?>
        });
    </script>
    <?php endif; ?>

    <!--begin::Script-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js" integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ=" crossorigin="anonymous"></script>
    <!--end::Third Party Plugin(OverlayScrollbars)-->
    
    <!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)-->
    
    <!--begin::Required Plugin(Bootstrap 5)-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <!--end::Required Plugin(Bootstrap 5)-->
    
    <!--begin::Required Plugin(AdminLTE)-->
    <script src="dist/js/adminlte.js"></script>
    <!--end::Required Plugin(AdminLTE)-->

    <script>
        // Form submission handling with loading animation
        document.getElementById('formTambahJurusan').addEventListener('submit', function(e) {
            const btnSimpan = document.getElementById('btnSimpan');
            const btnText = document.getElementById('btnText');
            const btnBatal = document.getElementById('btnBatal');
            const input = document.getElementById('namajurusan');
            
            // Validate input
            if (!validateForm()) {
                e.preventDefault();
                return false;
            }
            
            // Show loading state
            btnSimpan.classList.add('loading');
            btnText.innerHTML = '⏳ Menyimpan...';
            btnSimpan.disabled = true;
            btnBatal.style.pointerEvents = 'none';
            btnBatal.style.opacity = '0.6';
            input.disabled = true;
        });

        // Form validation with visual feedback
        function validateForm() {
            const namajurusan = document.getElementById('namajurusan').value.trim();
            const input = document.getElementById('namajurusan');

            // Remove previous validation classes
            input.classList.remove('input-error', 'input-success');

            if (!namajurusan || namajurusan.length < 2) {
                input.classList.add('input-error');
                Swal.fire({
                    icon: 'warning',
                    title: 'Validasi Gagal ⚠️',
                    text: 'Nama jurusan harus minimal 2 karakter dan tidak boleh kosong!',
                    confirmButtonColor: '#ffc107',
                    confirmButtonText: 'OK',
                    showClass: {
                        popup: 'animate__animated animate__wobble'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
                });
                input.focus();
                return false;
            }

            if (namajurusan.length > 100) {
                input.classList.add('input-error');
                Swal.fire({
                    icon: 'warning',
                    title: 'Validasi Gagal ⚠️',
                    text: 'Nama jurusan tidak boleh lebih dari 100 karakter!',
                    confirmButtonColor: '#ffc107',
                    confirmButtonText: 'OK',
                    showClass: {
                        popup: 'animate__animated animate__wobble'
                    }
                });
                input.focus();
                return false;
            }

            input.classList.add('input-success');
            return true;
        }

        // Cancel confirmation with enhanced animation
        document.getElementById('btnBatal').addEventListener('click', function(e) {
            e.preventDefault();
            
            const input = document.getElementById('namajurusan').value.trim();
            const hasData = input.length > 0;
            
            if (hasData) {
                Swal.fire({
                    title: 'Yakin ingin membatalkan? 🤔',
                    text: "Data yang sudah diisi akan hilang!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '✅ Ya, Batalkan',
                    cancelButtonText: '❌ Tidak',
                    showClass: {
                        popup: 'animate__animated animate__zoomIn'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__zoomOut'
                    },
                    backdrop: `
                        rgba(220,53,69,0.2)
                        center left
                        no-repeat
                    `
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Add leaving animation
                        document.body.style.animation = 'fadeOutUp 0.3s ease-out';
                        setTimeout(() => {
                            window.location.href = 'datajurusan.php';
                        }, 300);
                    }
                });
            } else {
                window.location.href = 'datajurusan.php';
            }
        });

        // Real-time input validation
        document.getElementById('namajurusan').addEventListener('input', function(e) {
            const input = e.target;
            const value = input.value.trim();
            
            // Remove validation classes first
            input.classList.remove('input-error', 'input-success');
            
            if (value.length > 0 && value.length >= 2 && value.length <= 100) {
                input.classList.add('input-success');
            } else if (value.length > 0) {
                input.classList.add('input-error');
            }
        });

        // Auto-format nama jurusan (capitalize first letter of each word)
        document.getElementById('namajurusan').addEventListener('blur', function(e) {
            const value = e.target.value.trim();
            if (value) {
                const formatted = value.replace(/\w\S*/g, (txt) => 
                    txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase()
                );
                e.target.value = formatted;
            }
        });

        // Character counter
        document.getElementById('namajurusan').addEventListener('input', function(e) {
            const maxLength = 100;
            const currentLength = e.target.value.length;
            const remaining = maxLength - currentLength;
            
            let small = e.target.nextElementSibling;
            if (remaining < 20) {
                small.style.color = remaining < 10 ? '#e74c3c' : '#f39c12';
                small.textContent = `${remaining} karakter tersisa`;
            } else {
                small.style.color = '#6c757d';
                small.textContent = 'Maksimal 100 karakter';
            }
        });

        // Add some entrance animation
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.querySelector('.container');
            container.style.opacity = '0';
            container.style.transform = 'translateY(30px)';
            
            setTimeout(() => {
                container.style.transition = 'all 0.5s ease-out';
                container.style.opacity = '1';
                container.style.transform = 'translateY(0)';
            }, 100);
        });
    </script>

    <!--begin::OverlayScrollbars Configure-->
    <script>
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
    <!--end::OverlayScrollbars Configure-->
    <!--end::Script-->
</body>
<!--end::Body-->
</html>