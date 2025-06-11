<?php
include("koneksi_agama.php");
$db = new AgamaDatabase();

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
            window.location.href = 'index.php';
        });
    </script>";
    exit();
}

$alert_message = '';
$alert_type = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $namaAgama = trim($_POST['namaAgama']);

    // Validasi input kosong
    if (empty($namaAgama)) {
        $alert_message = 'Nama agama tidak boleh kosong!';
        $alert_type = 'error';
    } else {
        // Validasi duplikat nama agama
        $check_query = "SELECT COUNT(*) as count FROM agama WHERE LOWER(namaAgama) = LOWER(?)";
        $check_stmt = $db->koneksi->prepare($check_query);
        $check_stmt->bind_param("s", $namaAgama);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        $check_row = $check_result->fetch_assoc();

        if ($check_row['count'] > 0) {
            $alert_message = 'Nama agama sudah ada dalam database!';
            $alert_type = 'error';
        } else {
            $query = "INSERT INTO agama (namaAgama) VALUES (?)";
            $stmt = $db->koneksi->prepare($query);
            $stmt->bind_param("s", $namaAgama);

            if ($stmt->execute()) {
                $alert_message = 'Data agama berhasil ditambahkan!';
                $alert_type = 'success';
            } else {
                $alert_message = 'Gagal menambahkan data agama!';
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
    <title>AdminLTE 4 | Tambah Agama</title>
    <!--begin::Primary Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="title" content="AdminLTE 4 | Tambah Agama" />
    <meta name="author" content="ColorlibHQ" />
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
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-weight: 600;
            margin-bottom: 8px;
            display: block;
            color: #555;
        }

        input[type="text"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }

        input[type="text"]:focus {
            border-color: #4A90E2;
            outline: none;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
        }

        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        button {
            flex: 1;
            padding: 12px;
            background-color: #4A90E2;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        button:hover {
            background-color: #357ABD;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(74, 144, 226, 0.3);
        }

        button:active {
            transform: translateY(0);
        }

        .btn-cancel {
            flex: 1;
            text-align: center;
            background-color: #e74c3c;
            color: white;
            padding: 12px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-cancel:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
            color: white;
            text-decoration: none;
        }

        .info-box {
            background-color: #e8f4fd;
            border: 1px solid #bee5eb;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            color: #0c5460;
            font-size: 14px;
            display: flex;
            align-items: center;
        }

        .info-box i {
            margin-right: 10px;
            font-size: 16px;
        }

        /* Loading animation */
        .loading {
            position: relative;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 1s linear infinite;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .loading.active::after {
            opacity: 1;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Custom SweetAlert2 styling */
        .swal2-popup {
            border-radius: 15px !important;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2) !important;
        }

        .swal2-title {
            font-size: 1.5rem !important;
            font-weight: 600 !important;
        }

        .swal2-content {
            font-size: 1rem !important;
        }

        .swal2-confirm {
            border-radius: 8px !important;
            padding: 10px 25px !important;
            font-weight: 600 !important;
        }

        /* Input enhancement */
        .input-container {
            position: relative;
        }

        .input-container input:focus + .input-focus-border,
        .input-container input:valid + .input-focus-border {
            transform: scaleX(1);
        }

        .input-focus-border {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2px;
            background-color: #4A90E2;
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        /* Character counter */
        .char-counter {
            font-size: 12px;
            color: #666;
            text-align: right;
            margin-top: 5px;
        }

        @media (max-width: 768px) {
            .container {
                margin: 20px;
                padding: 20px 25px;
            }

            .form-actions {
                flex-direction: column;
            }

            button, .btn-cancel {
                margin: 5px 0;
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
                                <li class="breadcrumb-item active" aria-current="page">Tambah Agama</li>
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
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="container">
                                <h2><i class="bi bi-plus-circle me-2"></i>Tambah Data Agama</h2>
                                
                                <div class="info-box">
                                    <i class="bi bi-info-circle"></i>
                                    <div>
                                        <strong>Info:</strong> Pastikan nama agama yang dimasukkan belum ada dalam database untuk menghindari duplikasi data.
                                    </div>
                                </div>
                                
                                <form method="POST" action="" id="formTambahAgama">
                                    <div class="form-group">
                                        <label for="namaAgama">
                                            <i class="bi bi-bookmarks me-2"></i>Nama Agama:
                                        </label>
                                        <div class="input-container">
                                            <input type="text" 
                                                   name="namaAgama" 
                                                   id="namaAgama" 
                                                   required 
                                                   maxlength="50"
                                                   placeholder="Masukkan nama agama..."
                                                   autocomplete="off">
                                            <div class="input-focus-border"></div>
                                        </div>
                                        <div class="char-counter">
                                            <span id="charCount">0</span>/50 karakter
                                        </div>
                                    </div>

                                    <div class="form-actions">
                                        <button type="submit" id="btnSimpan">
                                            <i class="bi bi-check-circle me-2"></i>
                                            <span id="btnText">Simpan</span>
                                        </button>
                                        <a href="dataagama.php" class="btn-cancel" id="btnBatal">
                                            <i class="bi bi-x-circle me-2"></i>Batal
                                        </a>
                                    </div>
                                </form>
                            </div>
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
                    title: 'Berhasil!',
                    text: '<?php echo $alert_message; ?>',
                    showConfirmButton: true,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#28a745',
                    timer: 3000,
                    timerProgressBar: true,
                    showClass: {
                        popup: 'animate__animated animate__zoomIn'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__zoomOut'
                    },
                    backdrop: `
                        rgba(40,40,40,0.5)
                        center left
                        no-repeat
                    `
                }).then((result) => {
                    if (result.isConfirmed || result.dismiss === Swal.DismissReason.timer) {
                        window.location.href = 'dataagama.php';
                    }
                });
            <?php elseif ($alert_type === 'error'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: '<?php echo $alert_message; ?>',
                    showConfirmButton: true,
                    confirmButtonText: 'Coba Lagi',
                    confirmButtonColor: '#dc3545',
                    showClass: {
                        popup: 'animate__animated animate__shakeX'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
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
        // Character counter
        const namaAgamaInput = document.getElementById('namaAgama');
        const charCount = document.getElementById('charCount');

        namaAgamaInput.addEventListener('input', function() {
            const currentLength = this.value.length;
            charCount.textContent = currentLength;
            
            if (currentLength > 40) {
                charCount.style.color = '#e74c3c';
            } else if (currentLength > 30) {
                charCount.style.color = '#f39c12';
            } else {
                charCount.style.color = '#666';
            }
        });

        // Form submission handling with loading animation
        document.getElementById('formTambahAgama').addEventListener('submit', function(e) {
            const namaAgama = namaAgamaInput.value.trim();
            
            // Client-side validation
            if (namaAgama.length < 2) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Validasi Gagal',
                    text: 'Nama agama harus minimal 2 karakter!',
                    confirmButtonColor: '#ffc107',
                    showClass: {
                        popup: 'animate__animated animate__wobble'
                    }
                });
                return;
            }

            const btnSimpan = document.getElementById('btnSimpan');
            const btnText = document.getElementById('btnText');
            const btnBatal = document.getElementById('btnBatal');
            
            // Show loading state
            btnSimpan.classList.add('loading', 'active');
            btnText.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Menyimpan...';
            btnSimpan.disabled = true;
            btnBatal.style.pointerEvents = 'none';
            btnBatal.style.opacity = '0.6';
        });

        // Cancel confirmation with SweetAlert2
        document.getElementById('btnBatal').addEventListener('click', function(e) {
            e.preventDefault();
            
            const namaAgama = namaAgamaInput.value.trim();
            
            if (namaAgama.length > 0) {
                Swal.fire({
                    title: 'Yakin ingin membatalkan?',
                    text: "Data yang sudah diisi akan hilang!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Batalkan',
                    cancelButtonText: 'Tidak',
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'dataagama.php';
                    }
                });
            } else {
                window.location.href = 'dataagama.php';
            }
        });

        // Auto-format nama agama (capitalize first letter of each word)
        namaAgamaInput.addEventListener('blur', function(e) {
            const value = e.target.value.trim();
            if (value.length > 0) {
                const formatted = value.replace(/\w\S*/g, (txt) => 
                    txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase()
                );
                e.target.value = formatted;
            }
        });

        // Prevent special characters except spaces, hyphens, and apostrophes
        namaAgamaInput.addEventListener('keypress', function(e) {
            const char = String.fromCharCode(e.which);
            const allowedChars = /^[a-zA-Z\s\-']+$/;
            
            if (!allowedChars.test(char)) {
                e.preventDefault();
                
                // Show brief warning
                this.style.borderColor = '#e74c3c';
                setTimeout(() => {
                    this.style.borderColor = '#ddd';
                }, 1000);
            }
        });

        // Enhanced input focus effects
        namaAgamaInput.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });

        namaAgamaInput.addEventListener('blur', function() {
            this.parentElement.classList.remove('focused');
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