<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php"); exit();
}
require 'cryptograph_process.php';

$message = ''; $msgType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assign_username'], $_POST['assign_role'])) {
    $target  = $_POST['assign_username'];
    $newRole = $_POST['assign_role'];
    if (!in_array($newRole, ['faculty','student'])) {
        $message = "Invalid role."; $msgType = 'error';
    } else {
        $d = json_decode(file_get_contents("users.json"), true);
        $ok = false;
        foreach ($d['users'] as &$u) {
            if ($u['username'] === $target) { $u['role'] = $newRole; $ok = true; break; }
        } unset($u);
        if ($ok) { file_put_contents("users.json", json_encode($d, JSON_PRETTY_PRINT)); $message = "Role '{$newRole}' assigned to '{$target}'."; }
        else     { $message = "User not found."; $msgType = 'error'; }
    }
}

$data  = json_decode(file_get_contents("users.json"), true);
$users = $data['users'] ?? [];
$initial = strtoupper(substr($_SESSION['username'], 0, 1));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Users — EduPortal</title>
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
      <a href="role_admin_manageUsers.php" class="nav-item active"><i class="fa fa-users"></i> Manage Users</a>
      <a href="role_admin_systemSetting.php" class="nav-item"><i class="fa fa-gear"></i> System Settings</a>
    </nav>
    <div class="sidebar-foot">
      <a href="logout.php" class="nav-item logout"><i class="fa fa-right-from-bracket"></i> Log Out</a>
    </div>
  </aside>

  <div class="main-area">
    <div class="topbar">
      <div class="topbar-left">
        <span class="topbar-crumb">Admin</span>
        <span class="topbar-title">Manage Users</span>
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

      <?php if ($message): ?>
        <div class="alert <?php echo $msgType === 'error' ? 'alert-error' : 'alert-success'; ?> fade-up">
          <i class="fa <?php echo $msgType === 'error' ? 'fa-circle-xmark' : 'fa-circle-check'; ?>"></i>
          <?php echo htmlspecialchars($message); ?>
        </div>
      <?php endif; ?>

      <div class="panel fade-up-2">
        <div class="panel-head">
          <div class="panel-ico"><i class="fa fa-users"></i></div>
          <div class="panel-title">All Registered Users</div>
          <div class="panel-sub"><?php echo count($users); ?> accounts</div>
        </div>
        <div class="panel-body np">
          <div class="t-wrap">
            <table>
              <thead>
                <tr><th>#</th><th>Full Name</th><th>Username</th><th>Role</th><th>Assign Role</th></tr>
              </thead>
              <tbody>
                <?php foreach ($users as $i => $u):
                  $cr = $u['role'] ?? null;
                  $isAdmin = ($cr === 'admin');
                  $bc = match($cr) { 'admin'=>'badge-admin','faculty'=>'badge-faculty','student'=>'badge-student', default=>'badge-unassigned' };
                ?>
                <tr <?php if ($isAdmin) echo 'class="admin-row"'; ?>>
                  <td><?php echo $i+1; ?></td>
                  <td><?php echo htmlspecialchars($u['fullname'] ?? '(no name)'); ?></td>
                  <td><?php echo htmlspecialchars($u['username']); ?></td>
                  <td><span class="badge <?php echo $bc; ?>"><?php echo ucfirst($cr ?? 'Unassigned'); ?></span></td>
                  <td>
                    <?php if ($isAdmin): ?>
                      <em style="color:var(--text-muted);font-size:12px">Admin — cannot reassign</em>
                    <?php else: ?>
                      <form class="assign-form" action="role_admin_manageUsers.php" method="POST">
                        <input type="hidden" name="assign_username" value="<?php echo htmlspecialchars($u['username']); ?>">
                        <select name="assign_role">
                          <option value="student" <?php if ($cr==='student') echo 'selected'; ?>>Student</option>
                          <option value="faculty" <?php if ($cr==='faculty') echo 'selected'; ?>>Faculty</option>
                        </select>
                        <button type="submit" class="btn btn-primary">Assign</button>
                      </form>
                    <?php endif; ?>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
</body>
</html>