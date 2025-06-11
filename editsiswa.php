<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Cek role
if ($_SESSION['role'] !== 'admin') {
    // Output halaman HTML minimal agar SweetAlert bisa tampil
    echo '
    <!DOCTYPE html>
    <html>
    <head>
        <title>Akses Ditolak</title>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body>
        <script>
            Swal.fire({
                icon: "error",
                title: "Akses Ditolak!",
                text: "Halaman ini hanya bisa diakses oleh admin!",
                showConfirmButton: true,
                confirmButtonColor: "#d33"
            }).then(() => {
                window.location.href = "index.php";
            });
        </script>
    </body>
    </html>';
    exit();
}
?>

<!doctype html>
<html lang="en">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>AdminLTE 4 | Edit Data Siswa</title>
    <!--begin::Primary Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="title" content="AdminLTE 4 | Edit Data Siswa" />
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
    <!--begin::SweetAlert2-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!--end::SweetAlert2-->
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
                  <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                  <li class="breadcrumb-item"><a href="datasiswa.php">Data Siswa</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Edit</li>
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

<?php
include("koneksi_siswa.php");
$db = new SiswaDatabase();

// Variabel untuk menyimpan pesan alert
$alert_type = '';
$alert_message = '';
$redirect_url = '';

if (!isset($_GET['nisn'])) {
    $alert_type = 'error';
    $alert_message = 'NISN tidak ditemukan!';
    $redirect_url = 'datasiswa.php';
} else {
    $nisn = $_GET['nisn'];
    $query = "SELECT * FROM siswa WHERE nisn = ?";
    $stmt = $db->koneksi->prepare($query);
    $stmt->bind_param("i", $nisn);
    $stmt->execute();
    $result = $stmt->get_result();
    $siswa = $result->fetch_assoc();

    if (!$siswa) {
        $alert_type = 'error';
        $alert_message = 'Data siswa tidak ditemukan!';
        $redirect_url = 'datasiswa.php';
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nama = $_POST['nama'];
        $jeniskelamin = $_POST['jeniskelamin'];
        $kodejurusan = $_POST['kodejurusan'];
        $kelas = $_POST['kelas'];
        $alamat = $_POST['alamat'];
        $agama = $_POST['agama'];
        $nohp = $_POST['nohp'];

        $query = "UPDATE siswa SET nama=?, jeniskelamin=?, kodejurusan=?, kelas=?, alamat=?, agama=?, nohp=? WHERE nisn=?";
        $stmt = $db->koneksi->prepare($query);
        $stmt->bind_param("ssissisi", $nama, $jeniskelamin, $kodejurusan, $kelas, $alamat, $agama, $nohp, $nisn);

        if ($stmt->execute()) {
            $alert_type = 'success';
            $alert_message = 'Data siswa berhasil diperbarui!';
            $redirect_url = 'datasiswa.php';
        } else {
            $alert_type = 'error';
            $alert_message = 'Gagal memperbarui data siswa!';
            $redirect_url = '';
        }
    }
}
?>

<style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background-color: #f4f6f8;
        margin: 0;
        justify-content: center;
    }

    .container {
        background: #fff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        max-width: 1000px;
        width: 100%;
    }

    h2 {
        text-align: center;
        color: #2c3e50;
        margin-bottom: 30px;
    }

    form {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        border-top: 1px solid #eee;
        padding-top: 20px;
    }

    .form-group {
        flex: 1 1 45%;
        display: flex;
        flex-direction: column;
    }

    label {
        font-weight: bold;
        margin-bottom: 6px;
    }

    input, select {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 14px;
        transition: border-color 0.3s ease;
    }

    input:focus, select:focus {
        border-color: #3498db;
        outline: none;
        box-shadow: 0 0 5px rgba(52, 152, 219, 0.3);
    }

    .form-actions {
        flex: 1 1 100%;
        display: flex;
        justify-content: space-between;
        margin-top: 30px;
        border-top: 1px solid #eee;
        padding-top: 20px;
    }

    button {
        flex: 1;
        padding: 12px;
        background-color: #3498db;
        color: white;
        border: none;
        border-radius: 6px;
        font-weight: bold;
        cursor: pointer;
        margin-right: 10px;
        transition: all 0.3s ease;
    }

    button:hover {
        background-color: #2980b9;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
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
        margin-left: 10px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-cancel:hover {
        background-color: #c0392b;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        color: white;
        text-decoration: none;
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

    /* Loading animation */
    .loading {
        pointer-events: none;
        opacity: 0.6;
    }

    .loading button {
        position: relative;
    }

    .loading button:after {
        content: '';
        position: absolute;
        width: 16px;
        height: 16px;
        margin: auto;
        border: 2px solid transparent;
        border-top-color: #ffffff;
        border-radius: 50%;
        animation: spin 1s ease infinite;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<?php if ($alert_type && $alert_message): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    <?php if ($alert_type == 'success'): ?>
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
            popup: 'animate__animated animate__fadeInDown'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutUp'
        }
    }).then((result) => {
        <?php if ($redirect_url): ?>
        window.location.href = '<?php echo $redirect_url; ?>';
        <?php endif; ?>
    });
    <?php elseif ($alert_type == 'error'): ?>
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: '<?php echo $alert_message; ?>',
        showConfirmButton: true,
        confirmButtonText: 'OK',
        confirmButtonColor: '#dc3545',
        showClass: {
            popup: 'animate__animated animate__shakeX'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutUp'
        }
    }).then((result) => {
        <?php if ($redirect_url): ?>
        window.location.href = '<?php echo $redirect_url; ?>';
        <?php endif; ?>
    });
    <?php endif; ?>
});
</script>
<?php endif; ?>

<?php if (isset($siswa) && $siswa): ?>
<div class="container">
    <h2>Edit Data Siswa</h2>
    <form method="POST" action="" id="editForm">
        <div class="form-group">
            <label>NISN:</label>
            <input type="number" name="nisn" value="<?php echo htmlspecialchars($siswa['nisn']); ?>" readonly>
        </div>

        <div class="form-group">
            <label>Nama:</label>
            <input type="text" name="nama" value="<?php echo htmlspecialchars($siswa['nama']); ?>" required>
        </div>

        <div class="form-group">
            <label>Jenis Kelamin:</label>
            <select name="jeniskelamin" required>
                <option value="L" <?php echo ($siswa['jeniskelamin'] == 'L') ? 'selected' : ''; ?>>Laki-laki</option>
                <option value="P" <?php echo ($siswa['jeniskelamin'] == 'P') ? 'selected' : ''; ?>>Perempuan</option>
            </select>
        </div>

        <div class="form-group">
            <label>Jurusan:</label>
            <select name="kodejurusan" required>
                <?php
                $result = $db->koneksi->query("SELECT * FROM jurusan");
                while ($row = $result->fetch_assoc()) {
                    $selected = ($row['kodejurusan'] == $siswa['kodejurusan']) ? 'selected' : '';
                    echo "<option value='".$row['kodejurusan']."' $selected>".$row['namajurusan']."</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label>Kelas:</label>
            <select name="kelas" required>
                <option value="X" <?php echo ($siswa['kelas'] == 'X') ? 'selected' : ''; ?>>X</option>
                <option value="XI" <?php echo ($siswa['kelas'] == 'XI') ? 'selected' : ''; ?>>XI</option>
                <option value="XII" <?php echo ($siswa['kelas'] == 'XII') ? 'selected' : ''; ?>>XII</option>
            </select>
        </div>

        <div class="form-group">
            <label>Alamat:</label>
            <input type="text" name="alamat" value="<?php echo htmlspecialchars($siswa['alamat']); ?>" required>
        </div>

        <div class="form-group">
            <label>Agama:</label>
            <select name="agama" required>
                <?php
                $result = $db->koneksi->query("SELECT * FROM agama");
                while ($row = $result->fetch_assoc()) {
                    $selected = ($row['idAgama'] == $siswa['agama']) ? 'selected' : '';
                    echo "<option value='".$row['idAgama']."' $selected>".$row['namaAgama']."</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label>No HP:</label>
            <input type="text" name="nohp" value="<?php echo htmlspecialchars($siswa['nohp']); ?>" required>
        </div>

        <div class="form-actions">
            <button type="submit" id="submitBtn">
                <span id="btnText">Simpan</span>
            </button>
            <a href="datasiswa.php" class="btn-cancel" id="cancelBtn">Batal</a>
        </div>
    </form>
</div>

<script>
// Form submission dengan konfirmasi SweetAlert2
document.getElementById('editForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    Swal.fire({
        title: 'Konfirmasi',
        text: 'Apakah Anda yakin ingin menyimpan perubahan data siswa?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3498db',
        cancelButtonColor: '#e74c3c',
        confirmButtonText: 'Ya, Simpan!',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        showClass: {
            popup: 'animate__animated animate__zoomIn'
        },
        hideClass: {
            popup: 'animate__animated animate__zoomOut'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading state
            const form = document.getElementById('editForm');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const cancelBtn = document.getElementById('cancelBtn');
            
            form.classList.add('loading');
            submitBtn.disabled = true;
            cancelBtn.style.pointerEvents = 'none';
            btnText.textContent = 'Menyimpan...';
            
            // Submit form
            this.submit();
        }
    });
});

// Cancel button confirmation
document.getElementById('cancelBtn').addEventListener('click', function(e) {
    e.preventDefault();
    
    Swal.fire({
        title: 'Batalkan Edit?',
        text: 'Perubahan yang belum disimpan akan hilang!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e74c3c',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Batalkan',
        cancelButtonText: 'Tetap di sini',
        reverseButtons: true,
        showClass: {
            popup: 'animate__animated animate__pulse'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'datasiswa.php';
        }
    });
});
</script>
<?php endif; ?>
            
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
    <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
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