<?php
// Include file koneksi dan dashboard data
require_once 'koneksi.php';

// Cek apakah user sudah login
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// Ambil data user dari session
$username = $_SESSION['username'];
$email = $_SESSION['email'];
$role = $_SESSION['role'];

// Hitung jumlah siswa
$query_siswa = "SELECT COUNT(*) as total FROM siswa";
$result_siswa = $siswaDB->koneksi->query($query_siswa);
$jumlah_siswa = $result_siswa->fetch_assoc()['total'];

// Hitung jumlah agama
$query_agama = "SELECT COUNT(*) as total FROM agama";
$result_agama = $agamaDB->koneksi->query($query_agama);
$jumlah_agama = $result_agama->fetch_assoc()['total'];

// Hitung jumlah jurusan
$query_jurusan = "SELECT COUNT(*) as total FROM jurusan";
$result_jurusan = $jurusanDB->koneksi->query($query_jurusan);
$jumlah_jurusan = $result_jurusan->fetch_assoc()['total'];

// Hitung jumlah akun (hanya untuk admin)
$jumlah_akun = 0;
if ($role == 'admin') {
    include("koneksi_akun.php");
    $query_akun = "SELECT COUNT(*) as total FROM akun";
    $result_akun = mysqli_query($koneksi, $query_akun);
    if ($result_akun) {
        $jumlah_akun = mysqli_fetch_assoc($result_akun)['total'];
    }
}

// Ambil data siswa per jurusan untuk grafik
$query_chart = "
    SELECT 
        j.namajurusan,
        COUNT(s.idsiswa) as jumlah_siswa
    FROM jurusan j
    LEFT JOIN siswa s ON j.kodejurusan = s.kodejurusan
    GROUP BY j.kodejurusan, j.namajurusan
    ORDER BY j.namajurusan
";
$result_chart = $siswaDB->koneksi->query($query_chart);

$chart_labels = [];
$chart_data = [];

while($row = $result_chart->fetch_assoc()) {
    $chart_labels[] = $row['namajurusan'];
    $chart_data[] = (int)$row['jumlah_siswa'];
}

// Convert ke JSON untuk JavaScript
$chart_labels_json = json_encode($chart_labels);
$chart_data_json = json_encode($chart_data);
?>
<!doctype html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>AdminLTE v4 | Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="title" content="AdminLTE v4 | Dashboard" />
    <meta name="author" content="ColorlibHQ" />
    
    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" 
          integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" />
    
    <!-- OverlayScrollbars -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css" 
          integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg=" crossorigin="anonymous" />
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" 
          integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI=" crossorigin="anonymous" />
    
    <!-- AdminLTE -->
    <link rel="stylesheet" href="dist/css/adminlte.css" />
    
    <!-- ApexCharts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css" 
          integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0=" crossorigin="anonymous" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" 
          integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" 
          crossorigin="anonymous" />

         
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    
    <style>
    /* Custom CSS untuk small-box */
    .small-box .icon {
        position: absolute;
        top: auto !important;
        bottom: 60px !important;
        right: 30px !important;
        left: auto !important;
        transition: all 0.3s linear;
        opacity: 0.5 !important;
        z-index: 0;
    }

    .small-box .icon > i {
        font-size: 65px !important;
        color: rgba(255, 255, 225, 0.7) !important;
    }

    .small-box .inner {
        position: relative;
        z-index: 1;
        padding: 20px 15px;
    }

    .small-box:hover .icon {
        opacity: 0.7 !important;
    }

    .small-box:hover .icon > i {
        transform: scale(1.1);
        transition: transform 0.3s ease;
    }

    .stats-row {
        margin-bottom: 30px;
    }

    .stats-row .col-lg-3,
    .stats-row .col-lg-4 {
        margin-bottom: 20px;
    }

    /* Responsive untuk 4 kolom admin */
    @media (max-width: 1200px) {
        .stats-row .col-lg-3 {
            flex: 0 0 50%;
            max-width: 50%;
        }
    }

    /* Responsive untuk 3 kolom pengguna */
    @media (max-width: 992px) {
        .stats-row .col-lg-4 {
            flex: 0 0 50%;
            max-width: 50%;
        }
        
        .stats-row .col-lg-4:nth-child(3) {
            flex: 0 0 100%;
            max-width: 100%;
            margin-top: 10px;
        }

        .stats-row .col-lg-3:nth-child(3),
        .stats-row .col-lg-3:nth-child(4) {
            flex: 0 0 100%;
            max-width: 100%;
        }
    }

    @media (max-width: 768px) {
        .small-box .icon > i {
            font-size: 45px !important;
        }
        
        .small-box .icon {
            bottom: 5px !important;
            right: 5px !important;
        }

        .stats-row .col-lg-3,
        .stats-row .col-lg-4 {
            flex: 0 0 100%;
            max-width: 100%;
            margin-bottom: 15px;
        }
    }

    /* Style untuk small-box */
    .small-box {
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
        position: relative;
    }

    .small-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 25px rgba(0,0,0,0.15);
    }

    .small-box .inner h3 {
        font-size: 2.5rem;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .small-box .inner p {
        font-size: 1.1rem;
        margin-bottom: 0;
        font-weight: 500;
    }

    .small-box-footer {
        background-color: rgba(0,0,0,0.1);
        color: rgba(255,255,255,0.8);
        transition: background-color 0.3s ease;
    }

    .small-box-footer:hover {
        background-color: rgba(0,0,0,0.2);
        color: white;
        text-decoration: none;
    }

    /* User profile */
    .user-profile-info {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .user-profile-role {
        background: rgba(255, 255, 255, 0.2);
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-block;
        margin-top: 5px;
    }
    </style>
  </head>
  
  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
      
      <?php include "header.php"; ?>
      
      <?php include "sidebar.php"; ?>
      
      <!-- Main Content -->
      <main class="app-main">
        <!-- Content Header -->
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Dashboard</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Content -->
        <div class="app-content">
          <div class="container-fluid">
            <!-- Statistics Cards -->
            <div class="row stats-row">
              <!-- Data Siswa -->
              <div class="<?php echo ($role == 'admin') ? 'col-lg-3 col-md-6 col-sm-12' : 'col-lg-4 col-md-6 col-sm-12'; ?>">
                <div class="small-box bg-primary">
                  <div class="inner">
                    <h3><?php echo $jumlah_siswa; ?></h3>
                    <p>Data Siswa</p>
                  </div>
                  <div class="icon">
                    <i class="fas fa-user-graduate"></i>
                  </div>
                  <a href="datasiswa.php" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                </div>
              </div>

              <!-- Data Agama -->
              <div class="<?php echo ($role == 'admin') ? 'col-lg-3 col-md-6 col-sm-12' : 'col-lg-4 col-md-6 col-sm-12'; ?>">
                <div class="small-box bg-success">
                  <div class="inner">
                    <h3><?php echo $jumlah_agama; ?></h3>
                    <p>Data Agama</p>
                  </div>
                  <div class="icon">
                    <i class="fas fa-book"></i>
                  </div>
                  <a href="dataagama.php" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                </div>
              </div>

              <!-- Data Jurusan -->
              <div class="<?php echo ($role == 'admin') ? 'col-lg-3 col-md-6 col-sm-12' : 'col-lg-4 col-md-6 col-sm-12'; ?>">
                <div class="small-box bg-warning">
                  <div class="inner">
                    <h3><?php echo $jumlah_jurusan; ?></h3>
                    <p>Data Jurusan</p>
                  </div>
                  <div class="icon">
                    <i class="fas fa-school"></i>
                  </div>
                  <a href="datajurusan.php" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                </div>
              </div>

              <!-- Data Akun (Hanya untuk Admin) -->
              <?php if ($role == 'admin'): ?>
              <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="small-box bg-info">
                  <div class="inner">
                    <h3><?php echo $jumlah_akun; ?></h3>
                    <p>Data Akun</p>
                  </div>
                  <div class="icon">
                    <i class="fas fa-users"></i>
                  </div>
                  <a href="dataakun.php" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                </div>
              </div>
              <?php endif; ?>
            </div>

            <!-- Charts Section -->
            <div class="row">
              <div class="col-lg-8">
                <div class="card mb-4">
                  <div class="card-header">
                    <h3 class="card-title">
                      <i class="fas fa-chart-bar me-2"></i>
                      Jumlah Siswa Per Jurusan
                    </h3>
                  </div>
                  <div class="card-body">
                    <div id="students-by-major-chart"></div>
                  </div>
                </div>
              </div>
              
              <div class="col-lg-4">
                <div class="card mb-4">
                  <div class="card-header">
                    <h3 class="card-title">
                      <i class="fas fa-chart-pie me-2"></i>
                      Distribusi Siswa
                    </h3>
                  </div>
                  <div class="card-body">
                    <div id="students-distribution-chart"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js" 
            integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" 
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" 
            integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="dist/js/adminlte.js"></script>

    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js" 
            integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8=" crossorigin="anonymous"></script>
    
    <!-- Chart Configuration -->
    <script>
      // Data dari PHP
      const chartLabels = <?php echo $chart_labels_json; ?>;
      const chartData = <?php echo $chart_data_json; ?>;
      
      // Warna yang berbeda untuk setiap jurusan
      const colors = ['#0d6efd', '#20c997', '#fd7e14', '#dc3545', '#6f42c1', '#198754', '#ffc107', '#6c757d'];

      // Bar Chart - Jumlah Siswa Per Jurusan
      const barChartOptions = {
        series: [{
          name: 'Jumlah Siswa',
          data: chartData
        }],
        chart: {
          type: 'bar',
          height: 350,
          toolbar: {
            show: true,
            tools: {
              download: true,
              selection: false,
              zoom: false,
              zoomin: false,
              zoomout: false,
              pan: false,
              reset: false
            }
          }
        },
        colors: colors,
        plotOptions: {
          bar: {
            borderRadius: 4,
            horizontal: false,
            columnWidth: '60%',
            dataLabels: {
              position: 'top'
            }
          }
        },
        dataLabels: {
          enabled: true,
          offsetY: -20,
          style: {
            fontSize: '12px',
            colors: ["#304758"]
          }
        },
        xaxis: {
          categories: chartLabels,
          labels: {
            style: {
              fontSize: '12px'
            }
          }
        },
        yaxis: {
          title: {
            text: 'Jumlah Siswa'
          }
        },
        grid: {
          borderColor: '#e7eef7',
          strokeDashArray: 5
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return val + " siswa"
            }
          }
        }
      };

      // Pie Chart - Distribusi Siswa
      const pieChartOptions = {
        series: chartData,
        chart: {
          type: 'pie',
          height: 350
        },
        labels: chartLabels,
        colors: colors,
        responsive: [{
          breakpoint: 480,
          options: {
            chart: {
              height: 300
            },
            legend: {
              position: 'bottom'
            }
          }
        }],
        tooltip: {
          y: {
            formatter: function (val) {
              return val + " siswa"
            }
          }
        },
        legend: {
          position: 'bottom',
          fontSize: '12px'
        }
      };

      // Render Charts
      const barChart = new ApexCharts(
        document.querySelector('#students-by-major-chart'),
        barChartOptions
      );
      barChart.render();

      const pieChart = new ApexCharts(
        document.querySelector('#students-distribution-chart'),
        pieChartOptions
      );
      pieChart.render();
    </script>
  </body>
</html>