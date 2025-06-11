<?php
session_start();

// Hapus semua session data
$_SESSION = array();

// Hapus session cookie jika ada
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Hapus remember me cookie jika ada
if (isset($_COOKIE['remember_login'])) {
    setcookie('remember_login', '', time() - 3600, '/');
}

// Destroy session
session_destroy();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout - Berhasil</title>
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.10.1/sweetalert2.min.css">
    <!-- Animate.css untuk animasi tambahan -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .loading-spinner {
            display: none;
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="loading-spinner" id="loadingSpinner"></div>

    <!-- SweetAlert2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.10.1/sweetalert2.all.min.js"></script>
    
    <script>
        // Tampilkan loading spinner sebentar
        document.getElementById('loadingSpinner').style.display = 'block';
        
        // Delay untuk efek loading
        setTimeout(function() {
            document.getElementById('loadingSpinner').style.display = 'none';
            
            // Tampilkan SweetAlert2 dengan animasi yang menarik
            Swal.fire({
                title: '👋 Sampai Jumpa!',
                html: `
                    <div style="text-align: center;">
                        <p style="font-size: 16px; color: #666; margin-bottom: 20px;">
                            Anda telah berhasil logout dari sistem
                        </p>
                        <div style="background: #f8f9fa; padding: 15px; border-radius: 10px; margin: 10px 0;">
                            <i class="fas fa-check-circle" style="color: #28a745; font-size: 24px;"></i>
                            <p style="margin: 5px 0; color: #495057;">Session telah dihapus dengan aman</p>
                        </div>
                    </div>
                `,
                icon: 'success',
                showConfirmButton: true,
                confirmButtonText: '🚀 Kembali ke Login',
                confirmButtonColor: '#28a745',
                showCancelButton: true,
                cancelButtonText: '🏠 Halaman Utama',
                cancelButtonColor: '#6c757d',
                timer: 5000,
                timerProgressBar: true,
                allowOutsideClick: false,
                allowEscapeKey: false,
                background: '#fff',
                backdrop: `
                    rgba(0,0,0,0.4)
                    url("data:image/gif;base64,R0lGODlhEAAQAPIAAP///wAAAMLCwkJCQgAAAGJiYoKCgpKSkiH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCgAAACwAAAAAEAAQAAADMwi63P4wyklrE2MIOggZnAdOmGYJRbExwroUmcG2LmDEwnHQLVsYOd2mBzkYDAdKa+dIAAAh+QQJCgAAACwAAAAAEAAQAAADNAi63P5OjCEgG4QMu7DmikRxQlFUYDEZIGBMRVsaqHwctXXf7WEYB4Ag1xjihkMZsiUkKhIAIfkECQoAAAAsAAAAABAAEAAAAzYIujIjK8pByJDMlFYvBoVjHA70GU7xSUJhmKtwHPAKzLO9HMaoKwJZ7Rf8AYPDDzKpZBqfvwQAIfkECQoAAAAsAAAAABAAEAAAAzMIumIlK8oyhpHsnFZfhYumCYUhDAQxRIdhHBGqRoKw0R8DYlJd8z0fMDgsGo/IpHI5TAAAIfkECQoAAAAsAAAAABAAEAAAAzIIunInK0rnZBTwGPNMgQwmdsNgXGJUlIWEuR5oWUIpz8pAEAMe6TwfwyYsGo/IpFKSAAAh+QQJCgAAACwAAAAAEAAQAAADMwi6IMKQORfjdOe82p4wGccc4CEuQradylesojEMBgsUc2G7sDX3lQGBMLAJibufbSlKAAAh+QQJCgAAACwAAAAAEAAQAAADMgi63P7wyklrE2MIOggZnAdOmGYJRbExwroUmcG2LmDEwnHQLVsYOd2mBzkYDAdKa+dIAAAh+QQJCgAAACwAAAAAEAAQAAADNAi63P5OjCEgG4QMu7DmikRxQlFUYDEZIGBMRVsaqHwctXXf7WEYB4Ag1xjihkMZsiUkKhIAIfkECQoAAAAsAAAAABAAEAAQAAADNgiuMiLKSesKgCmZNqgZqgZOUTFVNBsWZlFZVqiVGqJROUXBu7s2Jv0AAAAAEAAQAAADNgiuMiLKSesKgCmZNqgZqgZOUTFVNBsWZlFZVqiVGqJROUXBu7s2JvI8DyedCkdILSUZMAAAIfkECQoAAAAsAAAAABAAEAAAAy4gujIjykqJMkdTq5j8n5xI3QMnEHaYgKmXaIUhp0eNRgNlQPo5GyJVnB5BVWwSDwAAIfkECQoAAAAsAAAAABAAEAAAAzAIujIjykqNMkdTqphJhD4oQs6u8CUBhKx6KGOAEYPn3O3TEmWHCWNh/DU5lL6LAAAh+QQJCgAAACwAAAAAEAAQAAADKwi63P7R5esKhBcT+fLQ6FNNmGWKRglzN6Y5HmWJOapSdUZRoBZsVxLnGKoKCwAAIfkECQoAAAAsAAAAABAAEAAAAy4gujIjykqJMkdTq5j8n5xI3QMnEHaYgKmXaIUhp0eNRgNlQPo5GyJVnB5BVWwSDwAADQkJCQkJCQkJBQkJCQkJCQkJBQkJCQkJCQkJBQkJCQkJCQkJBQkJCQkJCQkJBQkJCQkJCQkJBQkJCQkJCQkJBQkJCQkJCQkJBQAAh+QQJCgAAACwAAAAAEAAQAAADNgiuMiLKSesKgCmZNqgZqgZOUTFVNBsWZlFZVqiVGqJROUXBu7s2JvI8DyedCkdILSUZMAAAIfkECQoAAAAsAAAAABAAEAAAAzAIujIjykqNMkdTqphJhD4oQs6u8CUBhKx6KGOAEYPn3O3TEmWHCWNh/DU5lL6LAAAh+QQJCgAAACwAAAAAEAAQAAADMQi63P7wyklrE2MIOggZnAdOmGYJRbExwroUmcG2LmDEwnHQLVsYOd2mBzkYDAdKa+dIAAAh+QQJCgAAACwAAAAAEAAQAAADNAi63P5OjCEgG4QMu7DmikRxQlFUYDEZIGBMRVsaqHwctXXf7WEYB4Ag1xjihkMZsiUkKhIAOw==")
                    left top
                    no-repeat
                `,
                showClass: {
                    popup: 'animate__animated animate__zoomIn',
                    backdrop: 'animate__animated animate__fadeIn'
                },
                hideClass: {
                    popup: 'animate__animated animate__zoomOut',
                    backdrop: 'animate__animated animate__fadeOut'
                },
                customClass: {
                    title: 'swal-title-custom',
                    popup: 'swal-popup-custom',
                    confirmButton: 'swal-button-custom'
                }
            }).then((result) => {
                if (result.isConfirmed || result.dismiss === Swal.DismissReason.timer) {
                    // Tampilkan loading sebelum redirect ke login
                    Swal.fire({
                        title: 'Mengarahkan ke Login...',
                        html: 'Please wait...',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Redirect ke login setelah delay singkat
                    setTimeout(() => {
                        window.location.href = 'login.php?logout=success';
                    }, 1500);
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    // Jika tombol "Halaman Utama" ditekan, redirect ke halaman publik
                    Swal.fire({
                        title: 'Kembali ke Halaman Utama...',
                        html: 'Mengarahkan Anda ke halaman utama...',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        timer: 1500,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    }).then(() => {
                        // Ganti dengan halaman yang tidak memerlukan login
                        // Misalnya halaman landing page, home page publik, atau website utama
                        window.location.href = 'index.php'; // atau 'landing.php', 'welcome.php', dll
                        
                        // Jika tidak ada halaman publik, redirect ke login dengan pesan
                        // window.location.href = 'login.php?from=home';
                    });
                }
            });
            
        }, 1000); // Delay 1 detik untuk loading effect
        
        // Fallback auto redirect jika ada masalah
        setTimeout(function() {
            window.location.href = 'login.php?logout=success';
        }, 8000);
    </script>
    
    <style>
        /* Custom styles untuk SweetAlert2 */
        .swal-title-custom {
            font-size: 28px !important;
            font-weight: bold !important;
            color: #2c3e50 !important;
        }
        
        .swal-popup-custom {
            border-radius: 20px !important;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3) !important;
        }
        
        .swal-button-custom {
            border-radius: 25px !important;
            padding: 12px 30px !important;
            font-weight: bold !important;
            font-size: 16px !important;
            transition: all 0.3s ease !important;
        }
        
        .swal-button-custom:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2) !important;
        }
    </style>
</body>
</html>     