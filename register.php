<?php
include 'koneksi_akun.php';

if (isset($_POST['submit'])) {
    // Menggunakan variabel $koneksi (sesuai dengan koneksi_akun.php)
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $email    = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password
    $role     = mysqli_real_escape_string($koneksi, $_POST['role']);

    // Cek apakah username atau email sudah ada
    $cek = mysqli_query($koneksi, "SELECT * FROM akun WHERE username='$username' OR email='$email'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Username atau email sudah terdaftar!',
                        confirmButtonColor: '#667eea',
                        background: 'rgba(255, 255, 255, 0.95)',
                        backdrop: 'rgba(0, 0, 0, 0.4)',
                        showClass: {
                            popup: 'animate__animated animate__zoomIn'
                        },
                        hideClass: {
                            popup: 'animate__animated animate__zoomOut'
                        }
                    });
                });
              </script>";
    } else {
        // Insert data baru
        $query = "INSERT INTO akun (username, email, password, role) VALUES ('$username', '$email', '$password', '$role')";
        if (mysqli_query($koneksi, $query)) {
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Akun berhasil ditambahkan!',
                            confirmButtonColor: '#667eea',
                            background: 'rgba(255, 255, 255, 0.95)',
                            backdrop: 'rgba(0, 0, 0, 0.4)',
                            showClass: {
                                popup: 'animate__animated animate__bounceIn'
                            },
                            hideClass: {
                                popup: 'animate__animated animate__fadeOut'
                            }
                        }).then(function() {
                            window.location.href = 'login.php';
                        });
                    });
                  </script>";
        } else {
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Registrasi gagal: " . mysqli_error($koneksi) . "',
                            confirmButtonColor: '#667eea',
                            background: 'rgba(255, 255, 255, 0.95)',
                            backdrop: 'rgba(0, 0, 0, 0.4)',
                            showClass: {
                                popup: 'animate__animated animate__shakeX'
                            },
                            hideClass: {
                                popup: 'animate__animated animate__fadeOut'
                            }
                        });
                    });
                  </script>";
        }
    }
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>AdminLTE 4 | Register Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="title" content="AdminLTE 4 | Register Page" />
    <meta name="author" content="ColorlibHQ" />
    <meta
      name="description"
      content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS."
    />
    <meta
      name="keywords"
      content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard"
    />
    
    <!-- Fonts -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
      integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
      crossorigin="anonymous"
    />
    
    <!-- Bootstrap Icons -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
      integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI="
      crossorigin="anonymous"
    />
    
    <!-- Bootstrap CSS -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
      crossorigin="anonymous"
    />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
    
    <!-- Animate.css for animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <script>
      function togglePassword() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        if (passwordInput.type === 'password') {
          passwordInput.type = 'text';
          eyeIcon.classList.remove('fa-eye-slash');
          eyeIcon.classList.add('fa-eye');
        } else {
          passwordInput.type = 'password';
          eyeIcon.classList.remove('fa-eye');
          eyeIcon.classList.add('fa-eye-slash');
        }
      }

      function toggleConfirmPassword() {
        const confirmInput = document.getElementById('confirmPassword');
        const eyeIcon = document.getElementById('eyeConfirmIcon');
        if (confirmInput.type === 'password') {
          confirmInput.type = 'text';
          eyeIcon.classList.remove('fa-eye-slash');
          eyeIcon.classList.add('fa-eye');
        } else {
          confirmInput.type = 'password';
          eyeIcon.classList.remove('fa-eye');
          eyeIcon.classList.add('fa-eye-slash');
        }
      }

      function checkPasswordStrength(password) {
        const helpText = document.getElementById('passwordHelp');
        if (password.length === 0) {
          helpText.innerHTML = '';
          helpText.style.color = '';
        } else if (password.length < 6) {
          helpText.innerHTML = 'Weak password 😢';
          helpText.style.color = 'red';
        } else if (!password.match(/[A-Z]/) || !password.match(/[0-9]/)) {
          helpText.innerHTML = 'Medium password 😐 (Tambah angka dan huruf kapital)';
          helpText.style.color = 'orange';
        } else {
          helpText.innerHTML = 'Strong password 💪';
          helpText.style.color = 'green';
        }
      }
    </script>
    
    <!-- Custom CSS -->
    <style>
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
      }

      body {
        font-family: 'Source Sans 3', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
      }

      .register-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        padding: 40px;
        width: 100%;
        max-width: 450px;
        border: 1px solid rgba(255, 255, 255, 0.2);
      }

      .register-header {
        text-align: center;
        margin-bottom: 30px;
      }

      .register-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 10px;
        text-decoration: none;
      }

      .register-header h1 b {
        color: #667eea;
      }

      .register-subtitle {
        color: #666;
        font-size: 1rem;
        margin-bottom: 30px;
        text-align: center;
      }

      .form-group {
        margin-bottom: 20px;
        position: relative;
      }

      .form-control {
        background: rgba(255, 255, 255, 0.8);
        border: 2px solid rgba(102, 126, 234, 0.2);
        border-radius: 12px;
        padding: 15px 50px 15px 20px;
        font-size: 1rem;
        transition: all 0.3s ease;
        width: 100%;
      }

      .form-control:focus {
        outline: none;
        border-color: #667eea;
        background: rgba(255, 255, 255, 1);
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
      }

      .form-control::placeholder {
        color: #999;
      }

      .input-icon {
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: #999;
        font-size: 1.2rem;
      }

      .form-check {
        margin: 20px 0;
        display: flex;
        align-items: flex-start;
        gap: 10px;
      }

      .form-check-input {
        margin-top: 4px;
        flex-shrink: 0;
      }

      .form-check-label {
        color: #666;
        font-size: 0.9rem;
        line-height: 1.4;
      }

      .form-check-label a {
        color: #667eea;
        text-decoration: none;
      }

      .form-check-label a:hover {
        text-decoration: underline;
      }

      .btn-register {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 12px;
        color: white;
        font-size: 1.1rem;
        font-weight: 600;
        padding: 15px;
        width: 100%;
        transition: all 0.3s ease;
        margin-top: 10px;
      }

      .btn-register:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
      }

      .divider {
        text-align: center;
        margin: 30px 0 20px;
        position: relative;
        color: #999;
      }

      .divider::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 1px;
        background: rgba(102, 126, 234, 0.2);
      }

      .divider span {
        background: rgba(255, 255, 255, 0.95);
        padding: 0 20px;
      }

      .social-buttons {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 25px;
      }

      .btn-social {
        border: 2px solid rgba(102, 126, 234, 0.2);
        border-radius: 12px;
        padding: 12px 20px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
      }

      .btn-facebook {
        background: rgba(24, 119, 242, 0.1);
        color: #1877f2;
      }

      .btn-facebook:hover {
        background: #1877f2;
        color: white;
        transform: translateY(-2px);
      }

      .btn-google {
        background: rgba(234, 67, 53, 0.1);
        color: #ea4335;
      }

      .btn-google:hover {
        background: #ea4335;
        color: white;
        transform: translateY(-2px);
      }

      .footer-links {
        text-align: center;
        margin-top: 25px;
      }

      .footer-links a {
        color: #667eea;
        text-decoration: none;
        font-size: 0.95rem;
        transition: color 0.3s ease;
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
      }

      .footer-links a:hover {
        color: #764ba2;
        text-decoration: underline;
      }

      .password-strength {
        margin-top: 8px;
        font-size: 0.85rem;
      }

      .strength-weak {
        color: #dc3545;
      }

      .strength-medium {
        color: #ffc107;
      }

      .strength-strong {
        color: #28a745;
      }

      .toggle-btn {
        height: 100%;
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        display: flex;
        align-items: center;
        justify-content: center;
      }

      /* Custom SweetAlert2 Styling */
      .swal2-popup {
        border-radius: 20px !important;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2) !important;
      }

      .swal2-title {
        font-family: 'Source Sans 3', sans-serif !important;
        font-weight: 700 !important;
      }

      .swal2-content {
        font-family: 'Source Sans 3', sans-serif !important;
      }

      .swal2-confirm {
        border-radius: 12px !important;
        padding: 12px 24px !important;
        font-weight: 600 !important;
      }

      @media (max-width: 480px) {
        .register-container {
          padding: 30px 20px;
          margin: 20px;
        }
        
        .register-header h1 {
          font-size: 2rem;
        }
        
        .form-control {
          padding: 12px 45px 12px 15px;
        }
        
        .input-icon {
          right: 15px;
          font-size: 1.1rem;
        }
      }
    </style>
  </head>
  
  <body>
    <div class="register-container">
      <div class="register-header">
        <h1><b>Admin</b>LTE</h1>
      </div>
      
      <p class="register-subtitle">Register a new membership</p>
      
      <!-- PERBAIKAN: Ganti action dan method -->
      <form action="" method="post">
        <div class="form-group">
          <!-- PERBAIKAN: Tambah name attribute -->
          <input id="registerFullname" name="username" type="text" class="form-control" placeholder="Full Name" required />
          <i class="bi bi-person-fill input-icon"></i>
        </div>
        
        <div class="form-group">
          <!-- PERBAIKAN: Tambah name attribute -->
          <input id="registerEmail" name="email" type="email" class="form-control" placeholder="Email" required />
          <i class="bi bi-envelope input-icon"></i>
        </div>

        <div class="input-group mb-3">
            <select class="form-control" name="role" required>
              <option value="" disabled selected>Role</option>
              <option value="admin">Admin</option>
              <option value="pengguna">Pengguna</option>
            </select>
        </div>
        
        <!-- PASSWORD -->
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="password" id="password" placeholder="Password" required onkeyup="checkPasswordStrength(this.value)">
          <div class="input-group-append">
            <button class="btn btn-outline-secondary toggle-btn" type="button" onclick="togglePassword()" tabindex="-1">
              <i class="fas fa-eye-slash" id="eyeIcon"></i>
            </button>
          </div>
        </div>
        <small id="passwordHelp" class="form-text text-muted"></small>

        <!-- RETYPE PASSWORD -->
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="confirm_password" id="confirmPassword" placeholder="Retype Password" required>
          <div class="input-group-append">
            <button class="btn btn-outline-secondary toggle-btn" type="button" onclick="toggleConfirmPassword()" tabindex="-1">
              <i class="fas fa-eye-slash" id="eyeConfirmIcon"></i>
            </button>
          </div>
        </div>
        
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="" id="agreeTerms" required />
          <label class="form-check-label" for="agreeTerms">
            I agree to the <a href="#" target="_blank">terms and conditions</a>
          </label>
        </div>
        
        <!-- PERBAIKAN: Tambah name="submit" -->
        <button type="submit" name="submit" class="btn-register">Register</button>
      </form>
      
      <div class="divider">
        <span>- OR -</span>
      </div>
      
      <div class="social-buttons">
        <a href="#" class="btn-social btn-facebook">
          <i class="bi bi-facebook"></i>
          Sign up using Facebook
        </a>
        <a href="#" class="btn-social btn-google">
          <i class="bi bi-google"></i>
          Sign up using Google+
        </a>
      </div>
      
      <div class="footer-links">
        <a href="login.php">I already have a membership</a>
      </div>
    </div>

    <!-- SweetAlert2 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

    <!-- Bootstrap JavaScript -->
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
      integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9XuaZUKyUvmyr+Q="
      crossorigin="anonymous"
    ></script>
    
    <!-- Custom JavaScript -->
    <script>
      // Form validation with SweetAlert2
      document.querySelector('form').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirmPassword').value;
        const agreeTerms = document.getElementById('agreeTerms').checked;
        
        if (password !== confirmPassword) {
          e.preventDefault();
          Swal.fire({
            icon: 'error',
            title: 'Password Tidak Cocok!',
            text: 'Password dan konfirmasi password harus sama.',
            confirmButtonColor: '#667eea',
            background: 'rgba(255, 255, 255, 0.95)',
            backdrop: 'rgba(0, 0, 0, 0.4)',
            showClass: {
              popup: 'animate__animated animate__shakeX'
            },
            hideClass: {
              popup: 'animate__animated animate__fadeOut'
            }
          });
          return;
        }
        
        if (!agreeTerms) {
          e.preventDefault();
          Swal.fire({
            icon: 'warning',
            title: 'Persetujuan Diperlukan!',
            text: 'Silakan setujui syarat dan ketentuan terlebih dahulu.',
            confirmButtonColor: '#667eea',
            background: 'rgba(255, 255, 255, 0.95)',
            backdrop: 'rgba(0, 0, 0, 0.4)',
            showClass: {
              popup: 'animate__animated animate__wobble'
            },
            hideClass: {
              popup: 'animate__animated animate__fadeOut'
            }
          });
          return;
        }
        
        if (password.length < 6) {
          e.preventDefault();
          Swal.fire({
            icon: 'error',
            title: 'Password Terlalu Pendek!',
            text: 'Password harus minimal 6 karakter.',
            confirmButtonColor: '#667eea',
            background: 'rgba(255, 255, 255, 0.95)',
            backdrop: 'rgba(0, 0, 0, 0.4)',
            showClass: {
              popup: 'animate__animated animate__shakeX'
            },
            hideClass: {
              popup: 'animate__animated animate__fadeOut'
            }
          });
          return;
        }
      });
    </script>
  </body>
</html>