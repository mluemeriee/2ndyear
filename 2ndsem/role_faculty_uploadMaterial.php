<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'faculty') {
    header("Location: login.php"); exit();
}

$mf = "materials.json";
$msg = ''; $msgType = '';
$materials = file_exists($mf) ? (json_decode(file_get_contents($mf), true) ?? []) : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'upload') {
        $title = trim($_POST['title'] ?? ''); $subject = trim($_POST['subject'] ?? '');
        $desc  = trim($_POST['description'] ?? ''); $fn = '';
        if (!$title || !$subject) { $msg = "Title and subject are required."; $msgType = 'error'; }
        else {
            if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                $dir = "uploads/"; if (!is_dir($dir)) mkdir($dir,0755,true);
                $ext = pathinfo($_FILES['file']['name'],PATHINFO_EXTENSION);
                $safe = uniqid("mat_").'.'.$ext;
                move_uploaded_file($_FILES['file']['tmp_name'], $dir.$safe);
                $fn = $_FILES['file']['name'].'|'.$safe;
            }
            $materials[] = ['id'=>uniqid(),'title'=>$title,'subject'=>$subject,'description'=>$desc,
                            'file'=>$fn,'uploaded_by'=>$_SESSION['username'],'uploaded_at'=>date('Y-m-d H:i:s')];
            file_put_contents($mf, json_encode($materials,JSON_PRETTY_PRINT));
            $msg = "Material '{$title}' uploaded.";
        }
    }
    if ($_POST['action'] === 'delete' && isset($_POST['mat_id'])) {
        foreach ($materials as $i => $m) {
            if ($m['id'] === $_POST['mat_id'] && $m['uploaded_by'] === $_SESSION['username']) {
                if ($m['file']) { $p=explode('|',$m['file']); if (!empty($p[1])&&file_exists("uploads/".$p[1])) unlink("uploads/".$p[1]); }
                array_splice($materials,$i,1);
                file_put_contents($mf,json_encode($materials,JSON_PRETTY_PRINT));
                $msg = "Material deleted."; break;
            }
        }
    }
}

$mine = array_filter($materials, fn($m)=>$m['uploaded_by']===$_SESSION['username']);
$initial = strtoupper(substr($_SESSION['username'],0,1));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Upload Material — EduPortal</title>
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
        <div class="s-role">Faculty</div>
      </div>
    </div>
    <nav class="sidebar-nav">
      <div class="nav-section">Menu</div>
      <a href="dashboard.php" class="nav-item"><i class="fa fa-house"></i> Dashboard</a>
      <div class="nav-section">Faculty</div>
      <a href="role_faculty_uploadMaterial.php" class="nav-item active"><i class="fa fa-file-arrow-up"></i> Upload Material</a>
      <a href="role_faculty_manageGrades.php" class="nav-item"><i class="fa fa-chart-bar"></i> Manage Grades</a>
    </nav>
    <div class="sidebar-foot">
      <a href="logout.php" class="nav-item logout"><i class="fa fa-right-from-bracket"></i> Log Out</a>
    </div>
  </aside>

  <div class="main-area">
    <div class="topbar">
      <div class="topbar-left">
        <span class="topbar-crumb">Faculty</span>
        <span class="topbar-title">Upload Material</span>
      </div>
      <div class="topbar-right">
        <span class="role-pill">Faculty</span>
        <div class="user-chip">
          <div class="chip-avatar"><?php echo $initial; ?></div>
          <span class="chip-name"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
        </div>
      </div>
    </div>

    <div class="page-content">
      <a href="dashboard.php" class="back-link fade-up"><i class="fa fa-arrow-left"></i> Back to Dashboard</a>

      <?php if ($msg): ?>
        <div class="alert <?php echo $msgType==='error'?'alert-error':'alert-success'; ?> fade-up">
          <i class="fa <?php echo $msgType==='error'?'fa-circle-xmark':'fa-circle-check'; ?>"></i>
          <?php echo htmlspecialchars($msg); ?>
        </div>
      <?php endif; ?>

      <div class="panel fade-up-2">
        <div class="panel-head">
          <div class="panel-ico"><i class="fa fa-file-arrow-up"></i></div>
          <div class="panel-title">Upload New Material</div>
        </div>
        <div class="panel-body">
          <form action="role_faculty_uploadMaterial.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="upload">
            <div class="form-row">
              <div class="form-field"><label>Title *</label><input type="text" name="title" placeholder="e.g. Chapter 3 Notes" required></div>
              <div class="form-field"><label>Subject *</label><input type="text" name="subject" placeholder="e.g. Mathematics"></div>
            </div>
            <div class="form-row">
              <div class="form-field"><label>Description</label><textarea name="description" placeholder="Short description (optional)..."></textarea></div>
            </div>
            <div class="form-row">
              <div class="form-field"><label>Attach File (optional)</label><input type="file" name="file"></div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Upload Material</button>
          </form>
        </div>
      </div>

      <div class="panel fade-up-3">
        <div class="panel-head">
          <div class="panel-ico"><i class="fa fa-layer-group"></i></div>
          <div class="panel-title">My Uploaded Materials</div>
          <div class="panel-sub"><?php echo count($mine); ?> items</div>
        </div>
        <div class="panel-body np">
          <?php if (empty($mine)): ?>
            <div class="empty-state"><i class="fa fa-box-open"></i><p>No materials uploaded yet.</p></div>
          <?php else: ?>
            <div class="t-wrap"><table>
              <thead><tr><th>#</th><th>Title</th><th>Subject</th><th>Description</th><th>File</th><th>Date</th><th></th></tr></thead>
              <tbody>
                <?php $n=1; foreach ($mine as $m): ?>
                <tr>
                  <td><?php echo $n++; ?></td>
                  <td><?php echo htmlspecialchars($m['title']); ?></td>
                  <td><?php echo htmlspecialchars($m['subject']); ?></td>
                  <td><?php echo htmlspecialchars($m['description']?:'—'); ?></td>
                  <td><?php if($m['file']){$p=explode('|',$m['file']);?><a href="uploads/<?php echo htmlspecialchars($p[1]??'');?>" target="_blank"><span class="file-chip"><i class="fa fa-file"></i><?php echo htmlspecialchars($p[0]);?></span></a><?php }else{echo '<span style="color:var(--text-light)">None</span>';}?></td>
                  <td><?php echo substr($m['uploaded_at'],0,10);?></td>
                  <td>
                    <form action="role_faculty_uploadMaterial.php" method="POST" onsubmit="return confirm('Delete this material?')">
                      <input type="hidden" name="action" value="delete">
                      <input type="hidden" name="mat_id" value="<?php echo htmlspecialchars($m['id']);?>">
                      <button type="submit" class="btn btn-danger" style="padding:5px 12px;font-size:12px;">Delete</button>
                    </form>
                  </td>
                </tr>
                <?php endforeach;?>
              </tbody>
            </table></div>
          <?php endif;?>
        </div>
      </div>
    </div>
  </div>

</div>
</body>
</html>