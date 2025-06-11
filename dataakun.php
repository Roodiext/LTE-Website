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
    <title>AdminLTE 4 | Data Akun</title>
    
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

        .role-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .role-admin {
            background-color: #dc3545;
            color: white;
        }

        .role-pengguna {
            background-color: #28a745;
            color: white;
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
                                <li class="breadcrumb-item active" aria-current="page">Data Akun</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="app-content">
                <div class="container-fluid">
                    <div class="row">
                        <h2>Data Akun</h2>

                        <?php 
                        include("koneksi_akun.php"); 
                        
                        // Query untuk mengambil data akun
                        $query = "SELECT * FROM akun ORDER BY id DESC";
                        $result = mysqli_query($koneksi, $query);
                        $data_akun = mysqli_fetch_all($result, MYSQLI_ASSOC);
                        ?>
                        
                        <!-- Container untuk button tambah data dengan layout yang diperbaiki -->
                        <div class="button-container">
                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                                <a href="tambahakun.php" class="btn-add">
                                    <i class="bi bi-plus-circle"></i> Tambah Akun
                                </a>
                            <?php else: ?>
                                <button class="btn-add" onclick="aksesDitolak()" style="background: #6c757d; cursor: not-allowed;">
                                    <i class="bi bi-plus-circle"></i> Tambah Akun
                                </button>
                            <?php endif; ?>
                        </div>

                        <table id="tabelAkun">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Tanggal Dibuat</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                foreach ($data_akun as $row) {
                                    $role_class = $row['role'] == 'admin' ? 'role-admin' : 'role-pengguna';
                                    $tanggal_dibuat = isset($row['created_at']) ? date('d-m-Y H:i', strtotime($row['created_at'])) : '-';
                                ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td>
                                        <span class="role-badge <?php echo $role_class; ?>">
                                            <?php echo htmlspecialchars(ucfirst($row['role'])); ?>
                                        </span>
                                    </td>
                                    <td><?php echo $tanggal_dibuat; ?></td>
                                    <td>
                                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                                            <a href="editakun.php?id=<?= $row['id'] ?>"
                                                class="btn btn-success"
                                                style="background-color: #198754; color: white; border: none;">
                                                Edit
                                            </a>

                                            <a href="#" onclick="confirmHapus('<?= $row['id'] ?>')"
                                                class="btn btn-danger"
                                                style="background-color: #dc3545; color: white; border: none;">
                                                Hapus
                                            </a>

                                        <?php else: ?>
                                            <a href="#" class="btn btn-warning" onclick="aksesDitolak()">Edit</a>
                                            <a href="#" class="btn btn-danger" onclick="aksesDitolak()">Hapus</a>
                                        <?php endif; ?>

                                    </td>
                                </tr>
                                <?php } ?>
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
            $('#tabelAkun').DataTable({
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
        function confirmHapus(id) {
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data akun ini akan hilang selamanya!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'hapus_akun.php?id=' + id;
                }
            });
        }
    </script>
</body>
</html>