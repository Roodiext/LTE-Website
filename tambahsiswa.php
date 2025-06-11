<?php 
include("koneksi_siswa.php");
$db = new SiswaDatabase();

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

// Ambil NISN terakhir berdasarkan data yang terakhir diinput (berdasarkan ID terbesar)
$query_nisn = "SELECT nisn FROM siswa ORDER BY idsiswa DESC LIMIT 1";
$result_nisn = $db->koneksi->query($query_nisn);
$row_nisn = $result_nisn->fetch_assoc();
$next_nisn = $row_nisn['nisn'] ? $row_nisn['nisn'] + 1 : 12345; // Default start dari 12345 jika belum ada data

$alert_message = '';
$alert_type = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nisn = $_POST['nisn'];
    $nama = $_POST['nama'];
    $jeniskelamin = $_POST['jeniskelamin'];
    $kodejurusan = $_POST['kodejurusan'];
    $kelas = $_POST['kelas'];
    $alamat = $_POST['alamat'];
    $agama = $_POST['agama'];
    $nohp = $_POST['nohp'];

    // Validasi NISN tidak duplikat
    $check_query = "SELECT COUNT(*) as count FROM siswa WHERE nisn = ?";
    $check_stmt = $db->koneksi->prepare($check_query);
    $check_stmt->bind_param("i", $nisn);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    $check_row = $check_result->fetch_assoc();

    if ($check_row['count'] > 0) {
        $alert_message = 'NISN sudah ada dalam database!';
        $alert_type = 'error';
    } else {
        $query = "INSERT INTO siswa (nisn, nama, jeniskelamin, kodejurusan, kelas, alamat, agama, nohp) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $db->koneksi->prepare($query);
        $stmt->bind_param("isssssss", $nisn, $nama, $jeniskelamin, $kodejurusan, $kelas, $alamat, $agama, $nohp);

        if ($stmt->execute()) {
            $alert_message = 'Data siswa berhasil ditambahkan!';
            $alert_type = 'success';
        } else {
            $alert_message = 'Gagal menambahkan data siswa!';
            $alert_type = 'error';
        }
    }
}
?>

<!doctype html>
<html lang="en">
<!--begin::Head-->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>AdminLTE 4 | Tambah Siswa</title>
    <!--begin::Primary Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="title" content="AdminLTE 4 | Tambah Siswa" />
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
            max-width: 1000px;
            width: 100%;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        form {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .form-group {
            flex: 1 1 45%;
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: 600;
            margin-bottom: 6px;
        }

        input, select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
            transition: border-color 0.3s;
        }

        input:focus, select:focus {
            border-color: #4A90E2;
            outline: none;
        }

        .form-actions {
            flex: 1 1 100%;
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        button {
            flex: 1;
            padding: 12px;
            background-color: #4A90E2;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-right: 10px;
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
            border-radius: 6px;
            font-weight: bold;
            transition: all 0.3s ease;
            margin-left: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-cancel:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
        }

        .nisn-info {
            background-color: #e8f4fd;
            border: 1px solid #bee5eb;
            border-radius: 6px;
            padding: 10px;
            margin-bottom: 20px;
            color: #0c5460;
            font-size: 14px;
        }

        .nisn-readonly {
            background-color: #f8f9fa;
            color: #6c757d;
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

        @media (max-width: 768px) {
            .form-group {
                flex: 1 1 100%;
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
                                <li class="breadcrumb-item active" aria-current="page">Tambah Siswa</li>
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
                            <h2>Tambah Data Siswa</h2>
                            
                            <div class="nisn-info">
                                <strong>Info:</strong> NISN akan otomatis diisi dengan nomor berikutnya (<?php echo $next_nisn; ?>). 
                                Anda dapat mengubahnya jika diperlukan.
                            </div>
                            
                            <form method="POST" action="" id="formTambahSiswa">
                                <div class="form-group">
                                    <label for="nisn">NISN:</label>
                                    <input type="number" name="nisn" id="nisn" value="<?php echo $next_nisn; ?>" required maxlength="10">
                                    <small style="color: #6c757d; margin-top: 5px;">NISN otomatis terisi, dapat diubah jika diperlukan</small>
                                </div>

                                <div class="form-group">
                                    <label for="nama">Nama:</label>
                                    <input type="text" name="nama" id="nama" required>
                                </div>

                                <div class="form-group">
                                    <label for="jeniskelamin">Jenis Kelamin:</label>
                                    <select name="jeniskelamin" id="jeniskelamin" required>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="kodejurusan">Jurusan:</label>
                                    <select name="kodejurusan" id="kodejurusan" required>
                                        <?php
                                        $result = $db->koneksi->query("SELECT * FROM jurusan");
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value='".$row['kodejurusan']."'>".$row['namajurusan']."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="kelas">Kelas:</label>
                                    <select name="kelas" id="kelas" required>
                                        <option value="X">X</option>
                                        <option value="XI">XI</option>
                                        <option value="XII">XII</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="alamat">Alamat:</label>
                                    <input type="text" name="alamat" id="alamat" required>
                                </div>

                                <div class="form-group">
                                    <label for="agama">Agama:</label>
                                    <select name="agama" id="agama" required>
                                        <?php
                                        $result = $db->koneksi->query("SELECT * FROM agama");
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value='".$row['idAgama']."'>".$row['namaAgama']."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="nohp">No HP:</label>
                                    <input type="text" name="nohp" id="nohp" required maxlength="14">
                                </div>

                                <div class="form-actions">
                                    <button type="submit" id="btnSimpan">
                                        <span id="btnText">Simpan</span>
                                    </button>
                                    <a href="datasiswa.php" class="btn-cancel" id="btnBatal">Batal</a>
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
                        window.location.href = 'datasiswa.php';
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
        // Form submission handling with loading animation
        document.getElementById('formTambahSiswa').addEventListener('submit', function(e) {
            const btnSimpan = document.getElementById('btnSimpan');
            const btnText = document.getElementById('btnText');
            const btnBatal = document.getElementById('btnBatal');
            
            // Show loading state
            btnSimpan.classList.add('loading', 'active');
            btnText.textContent = 'Menyimpan...';
            btnSimpan.disabled = true;
            btnBatal.style.pointerEvents = 'none';
            btnBatal.style.opacity = '0.6';
        });

        // Form validation with SweetAlert2
        function validateForm() {
            const nisn = document.getElementById('nisn').value;
            const nama = document.getElementById('nama').value.trim();
            const nohp = document.getElementById('nohp').value.trim();

            if (!nisn || nisn.length < 5) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Validasi Gagal',
                    text: 'NISN harus minimal 5 digit!',
                    confirmButtonColor: '#ffc107',
                    showClass: {
                        popup: 'animate__animated animate__wobble'
                    }
                });
                return false;
            }

            if (!nama || nama.length < 2) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Validasi Gagal',
                    text: 'Nama harus minimal 2 karakter!',
                    confirmButtonColor: '#ffc107',
                    showClass: {
                        popup: 'animate__animated animate__wobble'
                    }
                });
                return false;
            }

            if (!nohp || nohp.length < 10) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Validasi Gagal',
                    text: 'No HP harus minimal 10 digit!',
                    confirmButtonColor: '#ffc107',
                    showClass: {
                        popup: 'animate__animated animate__wobble'
                    }
                });
                return false;
            }

            return true;
        }

        // Cancel confirmation
        document.getElementById('btnBatal').addEventListener('click', function(e) {
            e.preventDefault();
            
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
                    window.location.href = 'datasiswa.php';
                }
            });
        });

        // Input formatting for phone number
        document.getElementById('nohp').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); // Remove non-digits
            if (value.length > 14) {
                value = value.substring(0, 14);
            }
            e.target.value = value;
        });

        // Auto-format nama (capitalize first letter of each word)
        document.getElementById('nama').addEventListener('blur', function(e) {
            const value = e.target.value;
            const formatted = value.replace(/\w\S*/g, (txt) => 
                txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase()
            );
            e.target.value = formatted;
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