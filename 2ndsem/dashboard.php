<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php"); exit();
}

$jsonData = file_get_contents("users.json");
$data     = json_decode($jsonData, true);
$role     = null;
foreach ($data['users'] as $user) {
    if ($user['username'] === $_SESSION['username']) {
        $role = $user['role'] ?? null; break;
    }
}
if (empty($role)) { session_destroy(); header("Location: login.php"); exit(); }
$_SESSION['role'] = $role;

$initial = strtoupper(substr($_SESSION['username'], 0, 1));

// Nav + links per role
$navItems = [];
$links    = [];
if ($role === 'admin') {
    $navItems = [
        ['href'=>'role_admin_manageUsers.php',   'icon'=>'fa-users',        'label'=>'Manage Users'],
        ['href'=>'role_admin_systemSetting.php', 'icon'=>'fa-gear',         'label'=>'System Settings'],
    ];
    $links = [
        ['href'=>'role_admin_manageUsers.php',   'icon'=>'fa-users',        'label'=>'Manage Users',   'desc'=>'Assign roles to registered accounts'],
        ['href'=>'role_admin_systemSetting.php', 'icon'=>'fa-gear',         'label'=>'System Settings','desc'=>'Configure system preferences'],
    ];
} elseif ($role === 'faculty') {
    $navItems = [
        ['href'=>'role_faculty_uploadMaterial.php', 'icon'=>'fa-file-arrow-up', 'label'=>'Upload Material'],
        ['href'=>'role_faculty_manageGrades.php',   'icon'=>'fa-chart-bar',     'label'=>'Manage Grades'],
    ];
    $links = [
        ['href'=>'role_faculty_uploadMaterial.php', 'icon'=>'fa-file-arrow-up', 'label'=>'Upload Material','desc'=>'Share files and notes with students'],
        ['href'=>'role_faculty_manageGrades.php',   'icon'=>'fa-chart-bar',     'label'=>'Manage Grades', 'desc'=>'Enter and update student grades'],
    ];
} elseif ($role === 'student') {
    $navItems = [
        ['href'=>'role_student_viewMaterial.php',     'icon'=>'fa-book-open',   'label'=>'View Material'],
        ['href'=>'role_student_submitAssignment.php', 'icon'=>'fa-paper-plane', 'label'=>'Submit Assignment'],
    ];
    $links = [
        ['href'=>'role_student_viewMaterial.php',     'icon'=>'fa-book-open',   'label'=>'View Material',    'desc'=>'Browse materials and see your grades'],
        ['href'=>'role_student_submitAssignment.php', 'icon'=>'fa-paper-plane', 'label'=>'Submit Assignment','desc'=>'Upload and track your submissions'],
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dashboard — EduPortal</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="style.css" />
</head>
<body class="dash-page">
<div class="dash-frame">

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="sidebar-brand">
      <div class="s-eyebrow">Platform</div>
      <div class="s-name">EduPortal</div>
    </div>
    <div class="sidebar-user">
      <div class="s-avatar"><?php echo $initial; ?></div>
      <div>
        <div class="s-uname"><?php echo htmlspecialchars($_SESSION['username']); ?></div>
        <div class="s-role"><?php echo htmlspecialchars($role); ?></div>
      </div>
    </div>
    <nav class="sidebar-nav">
      <div class="nav-section">Menu</div>
      <a href="dashboard.php" class="nav-item active"><i class="fa fa-house"></i> Dashboard</a>
      <div class="nav-section"><?php echo ucfirst($role); ?></div>
      <?php foreach ($navItems as $n): ?>
        <a href="<?php echo $n['href']; ?>" class="nav-item">
          <i class="fa <?php echo $n['icon']; ?>"></i> <?php echo $n['label']; ?>
        </a>
      <?php endforeach; ?>
    </nav>
    <div class="sidebar-foot">
      <a href="logout.php" class="nav-item logout"><i class="fa fa-right-from-bracket"></i> Log Out</a>
    </div>
  </aside>

  <!-- MAIN -->
  <div class="main-area">
    <div class="topbar">
      <div class="topbar-left">
        <span class="topbar-crumb">EduPortal</span>
        <span class="topbar-title">Dashboard</span>
      </div>
      <div class="topbar-right">
        <span class="role-pill"><?php echo ucfirst($role); ?></span>
        <div class="user-chip">
          <div class="chip-avatar"><?php echo $initial; ?></div>
          <span class="chip-name"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
        </div>
      </div>
    </div>

    <div class="page-content">
      <div class="page-head fade-up">
        <h2>Hello, <?php echo htmlspecialchars($_SESSION['username']); ?> 👋</h2>
        <p>Welcome back. Here's what you can do today.</p>
      </div>

      <div class="dash-grid fade-up-2">
        <?php foreach ($links as $link): ?>
          <a href="<?php echo $link['href']; ?>" class="dash-item">
            <div class="dash-ico"><i class="fa <?php echo $link['icon']; ?>"></i></div>
            <div class="dash-label"><?php echo $link['label']; ?></div>
            <div class="dash-desc"><?php echo $link['desc']; ?></div>
            <div class="dash-arrow">Open &rarr;</div>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

</div>
</body>
</html>