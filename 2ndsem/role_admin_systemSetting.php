<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php"); exit();
}
$initial = strtoupper(substr($_SESSION['username'], 0, 1));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>System Settings — EduPortal</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="style.css" />
</head>
<body class="dash-page">
<div class="dash-frame">

  <aside class="sidebar">
    <div class="sidebar-brand">
      <div class="s-eyebrow">Platform</div>
      <div class="s-name">EduPortal</div>
    </div>
    <div class="sidebar-user">
      <div class="s-avatar"><?php echo $initial; ?></div>
      <div>
        <div class="s-uname"><?php echo htmlspecialchars($_SESSION['username']); ?></div>
        <div class="s-role">Admin</div>
      </div>
    </div>
    <nav class="sidebar-nav">
      <div class="nav-section">Menu</div>
      <a href="dashboard.php" class="nav-item"><i class="fa fa-house"></i> Dashboard</a>
      <div class="nav-section">Admin</div>
      <a href="role_admin_manageUsers.php" class="nav-item"><i class="fa fa-users"></i> Manage Users</a>
      <a href="role_admin_systemSetting.php" class="nav-item active"><i class="fa fa-gear"></i> System Settings</a>
    </nav>
    <div class="sidebar-foot">
      <a href="logout.php" class="nav-item logout"><i class="fa fa-right-from-bracket"></i> Log Out</a>
    </div>
  </aside>

  <div class="main-area">
    <div class="topbar">
      <div class="topbar-left">
        <span class="topbar-crumb">Admin</span>
        <span class="topbar-title">System Settings</span>
      </div>
      <div class="topbar-right">
        <span class="role-pill">Admin</span>
        <div class="user-chip">
          <div class="chip-avatar"><?php echo $initial; ?></div>
          <span class="chip-name"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
        </div>
      </div>
    </div>
    <div class="page-content">
      <a href="dashboard.php" class="back-link fade-up"><i class="fa fa-arrow-left"></i> Back to Dashboard</a>
      <div class="panel fade-up-2">
        <div class="panel-head">
          <div class="panel-ico"><i class="fa fa-gear"></i></div>
          <div class="panel-title">System Settings</div>
        </div>
        <div class="panel-body">
          <div class="empty-state">
            <i class="fa fa-gear"></i>
            <p>System settings will be configured here.</p>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
</body>
</html>