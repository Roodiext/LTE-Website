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
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>AdminLTE 4 | Data Jurusan</title>
    <!--begin::Primary Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="title" content="AdminLTE 4 | Data Jurusan" />
    <meta name="author" content="ColorlibHQ" />
    <meta
      name="description"
      content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS."
    />
    <meta
      name="keywords"
      content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard"
    />
    <!--end::Primary Meta Tags-->
    <!--begin::Fonts-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
      integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
      crossorigin="anonymous"
    />
    <!--end::Fonts-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css"
      integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg="
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(OverlayScrollbars)-->
    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
      integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI="
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(Bootstrap Icons)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="dist/css/adminlte.css" />
    <!--end::Required Plugin(AdminLTE)-->
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
  </head>
  <!--end::Head-->
  <!--begin::Body-->
  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
      <!--begin::Header-->
      
      <!--end::Header-->
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
                  <li class="breadcrumb-item active" aria-current="page">Data Jurusan</li>
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
            
       
              
               
                  <!-- /.card-header -->
                  <h2>Data Jurusan</h2>

                  <?php 
            include("koneksi_jurusan.php"); 
            $db = new JurusanDatabase();
            $data_jurusan = $db->tampil_data_jurusan();  
            ?>
            <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        box-sizing: border-box;
    }

    h2 {
        color: #333;
        text-align: center;
        margin-bottom: 30px;
    }

    .table-container {
        max-width: 1000px;
        margin: auto;
        padding: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        overflow: hidden;
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
        border: none;
        cursor: pointer;
        transition: 0.3s;
        margin: 2px;
    }

    .btn-edit {
        background: #28a745;
        color: white;
    }

    .btn-edit:hover {
        background: #218838;
    }

    .btn-delete {
        background: #dc3545;
        color: white;
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

<div class="table-container">
    <!-- Container untuk button tambah data dengan layout yang diperbaiki -->
    <div class="button-container">
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
            <a href="tambahjurusan.php" class="btn-add">
                <i class="bi bi-plus-circle"></i> Tambah Data
            </a>
        <?php else: ?>
            <button class="btn-add" onclick="aksesDitolak()" style="background: #6c757d; cursor: not-allowed;">
                <i class="bi bi-plus-circle"></i> Tambah Data
            </button>
        <?php endif; ?>
    </div>

    <table id="tabelJurusan">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Jurusan</th>
                <th>Nama Jurusan</th>
                <th>Opsi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            foreach ($data_jurusan as $row) {
            ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo htmlspecialchars($row['kodejurusan']); ?></td>
                <td><?php echo htmlspecialchars($row['namajurusan']); ?></td>
                <td>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                        <a href="editjurusan.php?kodejurusan=<?php echo urlencode($row['kodejurusan']); ?>" class="btn btn-edit">
                            <i class="bi bi-pencil-square"></i> Edit
                        </a>
                        <button onclick="confirmHapus('<?php echo $row['kodejurusan']; ?>')" class="btn btn-delete">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    <?php else: ?>
                        <button class="btn" onclick="aksesDitolak()" style="background-color: #6c757d; color: white; border: none; cursor: not-allowed; margin: 2px;">
                            <i class="bi bi-pencil-square"></i> Edit
                        </button>
                        <button class="btn" onclick="aksesDitolak()" style="background-color: #6c757d; color: white; border: none; cursor: not-allowed; margin: 2px;">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    <?php endif; ?>
                </td>
            </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>


    

    

    
                  <!-- /.card-body -->
                <!-- /.card -->
              </p>
              <!-- /.col -->
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
    <!--begin::Script-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script
      src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
      integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ="
      crossorigin="anonymous"
    ></script>
    <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
      integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
    <script src="dist/js/adminlte.js"></script>
    <!--end::Required Plugin(AdminLTE)-->
    
    <!-- jQuery and DataTables -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

    <!-- DataTable Initialization -->
    <script>
        $(document).ready(function() {
            $('#tabelJurusan').DataTable({
                "lengthMenu": [5, 10, 25, 50, 100]
            });
        });
    </script>

    <!-- SweetAlert Functions -->
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

        function confirmHapus(kodejurusan) {
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data jurusan ini akan hilang selamanya!",
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
                    fetch(`hapus_jurusan.php?kodejurusan=${kodejurusan}`)
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
    <!--end::Script-->
  </body>
  <!--end::Body-->
</html>