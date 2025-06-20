<?php
// File ini akan diinclude di file yang membutuhkan header
// Session sudah dimulai di file induk, jadi kita bisa langsung menggunakan data session

// Pastikan session variables tersedia
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
$role = isset($_SESSION['role']) ? $_SESSION['role'] : 'pengguna';

// Format role untuk ditampilkan
$role_display = '';
$role_description = '';
switch($role) {
    case 'admin':
        $role_display = 'Administrator';
        $role_description = 'Admin';
        break;
    case 'pengguna':
        $role_display = 'User';
        $role_description = 'Pengguna';
        break;
    default:
        $role_display = 'User';
        $role_description = 'Pengguna';
}
?>

<!-- Header -->
<nav class="app-header navbar navbar-expand bg-body">
  <div class="container-fluid">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
          <i class="bi bi-list"></i>
        </a>
      </li>
      <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Home</a></li>
      <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Contact</a></li>
    </ul>
    
    <ul class="navbar-nav ms-auto">
      <!-- Search -->
      <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="bi bi-search"></i>
        </a>
      </li>
      
      <!-- Messages -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-bs-toggle="dropdown" href="#">
          <i class="bi bi-chat-text"></i>
          <span class="navbar-badge badge text-bg-danger">3</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
          <a href="#" class="dropdown-item">
            <div class="d-flex">
              <div class="flex-shrink-0">
                <img src="dist/assets/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 rounded-circle me-3" />
              </div>
              <div class="flex-grow-1">
                <h3 class="dropdown-item-title">Brad Diesel <span class="float-end fs-7 text-danger"><i class="bi bi-star-fill"></i></span></h3>
                <p class="fs-7">Call me whenever you can...</p>
                <p class="fs-7 text-secondary"><i class="bi bi-clock-fill me-1"></i> 4 Hours Ago</p>
              </div>
            </div>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
        </div>
      </li>
      
      <!-- Notifications -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-bs-toggle="dropdown" href="#">
          <i class="bi bi-bell-fill"></i>
          <span class="navbar-badge badge text-bg-warning">15</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
          <span class="dropdown-item dropdown-header">15 Notifications</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="bi bi-envelope me-2"></i> 4 new messages
            <span class="float-end text-secondary fs-7">3 mins</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
      </li>
      
      <!-- Fullscreen -->
      <li class="nav-item">
        <a class="nav-link" href="#" data-lte-toggle="fullscreen">
          <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
          <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
        </a>
      </li>
      
      <!-- User Menu -->
      <li class="nav-item dropdown user-menu">
        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
          <img src="dist/assets/img/user2-160x160.jpg" class="user-image rounded-circle shadow" alt="User Image" />
          <span class="d-none d-md-inline"><?php echo htmlspecialchars($username); ?></span>
        </a>
        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
          <li class="user-header user-profile-info">
            <img src="dist/assets/img/user2-160x160.jpg" class="rounded-circle shadow" alt="User Image" />
            <p>
              <?php echo htmlspecialchars($username); ?> - <?php echo htmlspecialchars($role_display); ?>
              <small>Email: <?php echo htmlspecialchars($email); ?></small>
              <span class="user-profile-role"><?php echo htmlspecialchars($role_description); ?></span>
            </p>
          </li>
          <li class="user-body">
            <div class="row">
              <div class="col-4 text-center"><a href="#">Followers</a></div>
              <div class="col-4 text-center"><a href="#">Sales</a></div>
              <div class="col-4 text-center"><a href="#">Friends</a></div>
            </div>
          </li>
          <li class="user-footer">
            <a href="#" class="btn btn-default btn-flat">Profile</a>
            <a href="logout.php" class="btn btn-default btn-flat float-end">Sign out</a>
          </li>
        </ul>
      </li>
    </ul>
  </div>
</nav>