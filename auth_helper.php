<?php
/**
 * Helper functions untuk autentikasi dan otorisasi
 */

// Fungsi untuk cek apakah user sudah login
function isLoggedIn() {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

// Fungsi untuk cek apakah user adalah admin
function isAdmin() {
    return isLoggedIn() && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Fungsi untuk cek apakah user adalah pengguna biasa
function isPengguna() {
    return isLoggedIn() && isset($_SESSION['role']) && $_SESSION['role'] === 'pengguna';
}

// Fungsi untuk redirect jika tidak login
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

// Fungsi untuk redirect jika bukan admin
function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        header("Location: access_denied.php");
        exit();
    }
}

// Fungsi untuk mendapatkan role user saat ini
function getCurrentUserRole() {
    return isset($_SESSION['role']) ? $_SESSION['role'] : null;
}

// Fungsi untuk mendapatkan username saat ini
function getCurrentUsername() {
    return isset($_SESSION['username']) ? $_SESSION['username'] : null;
}

// Fungsi untuk mendapatkan email saat ini
function getCurrentUserEmail() {
    return isset($_SESSION['email']) ? $_SESSION['email'] : null;
}

// Fungsi untuk mengecek permission berdasarkan action
function hasPermission($action) {
    if (!isLoggedIn()) {
        return false;
    }
    
    $role = getCurrentUserRole();
    
    switch ($action) {
        case 'view':
            // Semua user yang login bisa melihat data
            return true;
            
        case 'create':
        case 'edit':
        case 'delete':
            // Hanya admin yang bisa create, edit, delete
            return $role === 'admin';
            
        default:
            return false;
    }
}

// Fungsi untuk generate button berdasarkan permission
function generateActionButtons($id, $editUrl, $deleteUrl, $viewUrl = null) {
    $buttons = '';
    
    // Button View (jika ada)
    if ($viewUrl && hasPermission('view')) {
        $buttons .= '<a href="' . $viewUrl . '?id=' . $id . '" class="btn btn-info btn-sm" title="Lihat Detail">
                        <i class="fas fa-eye"></i>
                    </a> ';
    }
    
    // Button Edit (hanya admin)
    if (hasPermission('edit')) {
        $buttons .= '<a href="' . $editUrl . '?id=' . $id . '" class="btn btn-warning btn-sm" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a> ';
    }
    
    // Button Delete (hanya admin)
    if (hasPermission('delete')) {
        $buttons .= '<a href="' . $deleteUrl . '?id=' . $id . '" class="btn btn-danger btn-sm" 
                       onclick="return confirm(\'Apakah Anda yakin ingin menghapus data ini?\')" title="Hapus">
                        <i class="fas fa-trash"></i>
                    </a>';
    }
    
    // Jika tidak ada button, tampilkan pesan
    if (empty($buttons)) {
        $buttons = '<span class="text-muted">Tidak ada aksi tersedia</span>';
    }
    
    return $buttons;
}

// Fungsi untuk generate button tambah data
function generateAddButton($addUrl, $buttonText = 'Tambah Data') {
    if (hasPermission('create')) {
        return '<a href="' . $addUrl . '" class="btn btn-primary mb-3">
                    <i class="fas fa-plus"></i> ' . $buttonText . '
                </a>';
    }
    return '';
}

// Fungsi untuk menampilkan alert berdasarkan role
function showRoleBasedAlert() {
    if (isPengguna()) {
        return '<div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle"></i>
                    <strong>Info:</strong> Anda login sebagai Pengguna. Anda hanya dapat melihat data, tidak dapat menambah, mengedit, atau menghapus data.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
    }
    return '';
}

// Fungsi untuk mendapatkan CSS class berdasarkan role
function getRoleClass() {
    $role = getCurrentUserRole();
    switch ($role) {
        case 'admin':
            return 'admin-user';
        case 'pengguna':
            return 'regular-user';
        default:
            return 'unknown-user';
    }
}

// Fungsi untuk mendapatkan badge role
function getRoleBadge() {
    $role = getCurrentUserRole();
    switch ($role) {
        case 'admin':
            return '<span class="badge bg-danger">Administrator</span>';
        case 'pengguna':
            return '<span class="badge bg-primary">Pengguna</span>';
        default:
            return '<span class="badge bg-secondary">Unknown</span>';
    }
}
?>