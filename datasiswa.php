<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// Ambil data user dari session
$username = $_SESSION['username'];
$email = $_SESSION['email'];
$role = $_SESSION['role'];
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdminLTE 4 | Data Siswa</title>
    
    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css">
    <!-- OverlayScrollbars -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="dist/css/adminlte.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            margin: 0;
        }
        h2 {
            color: #333;
        }
        table {
            width: 100%;
            max-width: 1000px;
            margin: 20px auto;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            overflow: hidden;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #007BFF;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .btn {
            padding: 6px 12px;
            text-decoration: none;
            font-size: 14px;
            border-radius: 5px;
            margin: 5px;
            border: none;
            cursor: pointer;
            transition: 0.3s;
        }
        .btn-edit {
            background: #28a745;
            color: white;
            width: 80%;
        }
        .btn-edit:hover {
            background: #218838;
        }
        .btn-delete {
            background: #dc3545;
            color: white;
            width: 80%;
        }
        .btn-delete:hover {
            background: #c82333;
        }
        
        /* Perbaikan styling untuk button tambah data */
        .button-container {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            max-width: 1000px;
            margin: 0 auto;
            margin-bottom: 20px;
            padding: 0 10px;
        }
        
        .btn-add {
            display: inline-block;
            padding: 10px 20px;
            background: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: .3s ease-in-out;
            font-size: 14px;
            border: none;
            cursor: pointer;
        }
        .btn-add:hover {
            background: #0056b3;
            color: white;
            text-decoration: none;
        }
        
        .btn-add:disabled {
            background: #6c757d;
            cursor: not-allowed;
        }

        .dataTables_wrapper {
            max-width: 1000px;
            margin: 0 auto;
            text-align: left;
            font-size: 14px;
        }

        .dataTables_length label,
        .dataTables_filter label {
            font-weight: bold;
            color: #333;
        }

        .dataTables_length select,
        .dataTables_filter input {
            padding: 6px 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-left: 8px;
            margin-bottom: 10px;
        }

        .dataTables_paginate {
            margin-top: 15px;
            text-align: center;
        }

        .dataTables_paginate .paginate_button {
            background-color: #007BFF;
            color: white !important;
            border: none;
            padding: 6px 12px;
            margin: 0 2px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .dataTables_paginate .paginate_button:hover {
            background-color: #0056b3;
        }

        .dataTables_paginate .paginate_button.current {
            background-color: #0056b3 !important;
            font-weight: bold;
        }

        .dataTables_info {
            margin-top: 10px;
            color: #666;
        }
    </style>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <!-- Header -->
        

        <!-- Sidebar -->
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
                                <li class="breadcrumb-item active" aria-current="page">Data Siswa</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="app-content">
                <div class="container-fluid">
                    <div class="row">
                        <h2>Data Siswa</h2>

                        <?php 
                        include("koneksi_siswa.php"); 
                        $db = new SiswaDatabase();
                        $data_siswa = $db->tampil_data_siswa();  
                        ?>
                        
                        <!-- Container untuk button tambah data dengan layout yang diperbaiki -->
                        <div class="button-container">
                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                                <a href="tambahsiswa.php" class="btn-add">
                                    <i class="bi bi-plus-circle"></i> Tambah Data
                                </a>
                            <?php else: ?>
                                <button class="btn-add" onclick="aksesDitolak()" style="background: #6c757d; cursor: not-allowed;">
                                    <i class="bi bi-plus-circle"></i> Tambah Data
                                </button>
                            <?php endif; ?>
                        </div>

                        <table id="tabelSiswa">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NISN</th>
                                    <th>Nama</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Jurusan</th>
                                    <th>Kelas</th>
                                    <th>Alamat</th>
                                    <th>Agama</th>
                                    <th>No HP</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                foreach ($data_siswa as $row) {
                                    $jeniskelamin = $row['jeniskelamin'] == 'L' ? 'Laki-laki' : 'Perempuan';
                                    echo "<tr>";
                                    echo "<td>" . $no++ . "</td>";
                                    echo "<td>" . $row['nisn'] . "</td>";
                                    echo "<td>" . $row['nama'] . "</td>";
                                    echo "<td>" . $jeniskelamin . "</td>";
                                    echo "<td>" . $row['namajurusan'] . "</td>";
                                    echo "<td>" . $row['kelas'] . "</td>";
                                    echo "<td>" . $row['alamat'] . "</td>";
                                    echo "<td>" . $row['namaAgama'] . "</td>";
                                    echo "<td>" . $row['nohp'] . "</td>";
                                    echo "<td>";
                                    
                                    if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
                                        // PERBAIKAN: Gunakan 'nisn' konsisten di semua tempat
                                        echo '<a href="editsiswa.php?nisn=' . $row['nisn'] . '" class="btn btn-success" style="background-color: #198754; color: white; border: none; text-decoration: none; margin: 2px;">Edit</a>';
                                        echo '<a href="#" onclick="confirmHapus(\'' . $row['nisn'] . '\')" class="btn btn-danger" style="background-color: #dc3545; color: white; border: none; text-decoration: none; margin: 2px;">Hapus</a>';
                                    } else {
                                        echo '<button class="btn" onclick="aksesDitolak()" style="background-color: #6c757d; color: white; border: none; cursor: not-allowed; margin: 2px;">Edit</button>';
                                        echo '<button class="btn" onclick="aksesDitolak()" style="background-color: #6c757d; color: white; border: none; cursor: not-allowed; margin: 2px;">Hapus</button>';
                                    }
                                    
                                    echo "</td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
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
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#tabelSiswa').DataTable({
                "lengthMenu": [5, 10, 25, 50, 100]
            });
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function aksesDitolak() {
            Swal.fire({
                icon: 'error',
                title: 'Akses Ditolak!',
                text: 'Fitur ini hanya untuk admin!',
                showConfirmButton: false,
                timer: 2000
            });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
       // Ganti fungsi confirmHapus yang sudah ada dengan fungsi ini:

function confirmHapus(nisn) {
    Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: "Data ini akan hilang selamanya!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Tampilkan loading
            Swal.fire({
                title: 'Menghapus...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // AJAX request menggunakan fetch
            fetch(`hapus_siswa.php?nisn=${nisn}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message,
                            confirmButtonColor: '#28a745',
                            showConfirmButton: true
                        }).then(() => {
                            // Reload halaman untuk refresh data
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: data.message,
                            confirmButtonColor: '#dc3545'
                        });
                        
                        // Jika ada redirect (seperti session expired)
                        if (data.redirect) {
                            setTimeout(() => {
                                window.location.href = data.redirect;
                            }, 2000);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Terjadi kesalahan pada server atau koneksi',
                        confirmButtonColor: '#dc3545'
                    });
                });
        }
    });
}
    </script>
</body>
</html>