<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>AdminLTE 4 | Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.min.css" crossorigin="anonymous">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Source Sans 3', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 100%;
            max-width: 420px;
        }

        .login-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
            text-align: center;
            margin-bottom: 10px;
        }

        .login-header h1 b {
            color: #667eea;
        }

        .login-subtitle {
            color: #666;
            text-align: center;
            margin-bottom: 30px;
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

        .input-icon {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 1.2rem;
        }

        .remember-signin-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #666;
            font-size: 0.95rem;
        }

        .btn-signin {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 1rem;
            font-weight: 600;
            padding: 12px 24px;
            transition: all 0.3s ease;
        }

        .btn-signin:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        .footer-links {
            text-align: center;
            margin-top: 25px;
        }

        .footer-links a {
            color: #667eea;
            text-decoration: none;
            font-size: 0.95rem;
            display: block;
            margin-bottom: 8px;
        }

        .footer-links a:hover {
            color: #764ba2;
            text-decoration: underline;
        }



        /* SweetAlert Custom Styling */
        .swal2-popup {
            border-radius: 20px !important;
            font-family: 'Source Sans 3', sans-serif !important;
        }

        .btn-swal-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
            border: none !important;
            border-radius: 12px !important;
            font-weight: 600 !important;
            padding: 14px 30px !important;
        }

        .btn-swal-error {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%) !important;
            border: none !important;
            border-radius: 12px !important;
            font-weight: 600 !important;
            padding: 14px 30px !important;
        }

        .btn-swal-warning {
            background: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%) !important;
            border: none !important;
            border-radius: 12px !important;
            font-weight: 600 !important;
            padding: 14px 30px !important;
            color: #212529 !important;
        }

        /* Shake animation for error */
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-8px); }
            20%, 40%, 60%, 80% { transform: translateX(8px); }
        }

        .animate__animated {
            animation-duration: 0.8s;
            animation-fill-mode: both;
        }

        .animate__shakeX {
            animation-name: shake;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-container {
                padding: 30px 20px;
                margin: 10px;
            }
            
            .login-header h1 {
                font-size: 2rem;
            }
            
            .remember-signin-row {
                flex-direction: column;
                gap: 15px;
                align-items: stretch;
            }
            
            .btn-signin {
                width: 100%;
            }
        }

        /* ===== STYLING SWEETALERT2 YANG MINIMAL ===== */
        
        /* Success animation yang smooth */
        .swal2-success-animate {
            animation: successPulse 0.75s ease-out;
        }

        @keyframes successPulse {
            0% {
                transform: scale(0.8);
                opacity: 0.5;
            }
            50% {
                transform: scale(1.1);
                opacity: 0.8;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* Timer progress bar styling */
        .swal2-timer-progress-bar {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
        }
    </style>
</head>

<body>
<div class="login-container">
        <div class="login-header">
            <h1><b>Admin</b>LTE</h1>
        </div>
        
        <p class="login-subtitle">Sign in to start your session</p>
        
        <form id="loginForm" action="proses_login.php" method="post">
            <div class="form-group">
                <input id="loginEmail" name="email" type="email" class="form-control" placeholder="Email" required>
                <i class="bi bi-envelope input-icon"></i>
            </div>
            
            <div class="form-group">
                <input id="loginPassword" name="password" type="password" class="form-control" placeholder="Password" required>
                <i class="bi bi-lock-fill input-icon"></i>
            </div>
            
            <div class="remember-signin-row">
                <div class="remember-me">
                    <input class="form-check-input" type="checkbox" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Remember Me</label>
                </div>
                <button type="submit" name="login" class="btn-signin">Sign In</button>
            </div>
        </form>
        
        <div class="footer-links">
            <a href="register.php">Register a new membership</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.all.min.js" crossorigin="anonymous"></script>

    <script>
        // Fungsi test untuk demo (hapus tombol test dari HTML)
        function testSuccessAlert() {
            showSuccessLogin('Test User');
        }

        // Fungsi untuk menampilkan success alert dengan konfigurasi SweetAlert2 yang benar
        function showSuccessLogin(username = 'User') {
            Swal.fire({
                icon: 'success',
                title: 'Login Berhasil!',
                text: `Selamat datang ${username}! Anda akan dialihkan ke dashboard.`,
                showConfirmButton: false,
                allowOutsideClick: false,
                timer: 3000,
                timerProgressBar: true,
                background: '#fff',
                color: '#333',
                iconColor: '#28a745',
                customClass: { 
                    popup: 'swal2-success-animate'
                },
                didOpen: () => {
                    // Pastikan ikon success terlihat dengan benar
                    const popup = Swal.getPopup();
                    const icon = popup.querySelector('.swal2-icon.swal2-success');
                    if (icon) {
                        icon.style.display = 'flex';
                        icon.style.alignItems = 'center';
                        icon.style.justifyContent = 'center';
                    }
                }
            });
        }

        // Check login status from URL parameters
        function checkLoginStatus() {
            const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get('status');
            const message = urlParams.get('message');
            const successLogin = urlParams.get('success_login');
            const username = urlParams.get('username');

            // Hapus parameter URL segera setelah dibaca
            const url = new URL(window.location);
            url.searchParams.delete('status');
            url.searchParams.delete('message');
            url.searchParams.delete('success_login');
            url.searchParams.delete('username');
            window.history.replaceState({}, '', url.toString());

            // Cek jika login baru saja berhasil
            if (successLogin === 'true') {
                // Tampilkan loading dulu
                Swal.fire({
                    title: 'Memproses Login...',
                    text: 'Memverifikasi kredensial Anda',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => Swal.showLoading()
                });

                // Setelah 1.5 detik, tampilkan sukses dengan konfigurasi yang benar
                setTimeout(() => {
                    showSuccessLogin(username || 'User');
                    // Redirect setelah alert selesai
                    setTimeout(() => {
                        window.location.href = 'index.php';
                    }, 3000);
                }, 1500);
                
                return; // Exit function early untuk success case
            }

            // Handle status lainnya (error, warning, dll)
            if (status === 'error') {
                Swal.fire({
                    icon: 'error',
                    title: 'Login Gagal!',
                    text: 'Email atau password salah! Silahkan coba kembali',
                    confirmButtonText: 'Coba Lagi',
                    allowOutsideClick: false,
                    customClass: { 
                        confirmButton: 'btn-swal-error',
                        popup: 'animate__animated animate__shakeX'
                    }
                }).then(() => {
                    document.getElementById('loginEmail').value = '';
                    document.getElementById('loginPassword').value = '';
                    document.getElementById('loginEmail').focus();
                });
            } else if (status === 'empty_fields') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Input Kosong!',
                    text: message || 'Email dan password harus diisi!',
                    customClass: { confirmButton: 'btn-swal-warning' }
                }).then(() => {
                    document.getElementById('loginEmail').focus();
                });
            } else if (status === 'password_short') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Password Terlalu Pendek!',
                    text: message || 'Password harus memiliki minimal 6 karakter.',
                    customClass: { confirmButton: 'btn-swal-warning' }
                }).then(() => {
                    document.getElementById('loginPassword').focus();
                });
            }
        }

        // Form validation saat submit
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const email = document.getElementById('loginEmail').value.trim();
            const password = document.getElementById('loginPassword').value;

            // Validasi Email
            if (!email) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Email Kosong!',
                    text: 'Silakan masukkan alamat email Anda.',
                    customClass: { confirmButton: 'btn-swal-warning' }
                }).then(() => document.getElementById('loginEmail').focus());
                return;
            }

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',  
                    title: 'Format Email Tidak Valid!',
                    text: 'Silakan masukkan alamat email yang valid.',
                    customClass: { confirmButton: 'btn-swal-warning' }
                }).then(() => document.getElementById('loginEmail').focus());
                return;
            }

            // Validasi Password
            if (!password.trim()) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Password Kosong!',
                    text: 'Silakan masukkan password Anda.',
                    customClass: { confirmButton: 'btn-swal-warning' }
                }).then(() => document.getElementById('loginPassword').focus());
                return;
            }

            if (password.length < 6) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Password Terlalu Pendek!',
                    text: 'Password harus memiliki minimal 6 karakter.',
                    customClass: { confirmButton: 'btn-swal-warning' }
                }).then(() => document.getElementById('loginPassword').focus());
                return;
            }

            // Tampilkan loading saat submit form
            Swal.fire({
                title: 'Memproses Login...',
                text: 'Memverifikasi kredensial Anda',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => Swal.showLoading()
            });
        });

        // Initialize
        document.addEventListener('DOMContentLoaded', checkLoginStatus);

        // Input focus effects
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.style.borderColor = '#667eea';
                this.style.boxShadow = '0 0 0 3px rgba(102, 126, 234, 0.1)';
            });

            input.addEventListener('blur', function() {
                this.style.borderColor = 'rgba(102, 126, 234, 0.2)';
                this.style.boxShadow = 'none';
            });
        });
    </script>
</body>
</html>