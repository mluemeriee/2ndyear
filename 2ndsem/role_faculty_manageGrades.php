<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'faculty') {
    header("Location: login.php"); exit();
}

$gf = "grades.json"; $af = "assignments.json"; $uf = "users.json";
$msg = ''; $msgType = '';
$grades = file_exists($gf) ? (json_decode(file_get_contents($gf),true)??[]) : [];
$asgns  = file_exists($af) ? (json_decode(file_get_contents($af),true)??[]) : [];
$allUsers = [];
if (file_exists($uf)) { $ud=json_decode(file_get_contents($uf),true); $allUsers=array_filter($ud['users']??[],fn($u)=>($u['role']??'')==='student'); }

if ($_SERVER['REQUEST_METHOD']==='POST' && ($_POST['action']??'')==='save_grade') {
    $su=trim($_POST['student_username']??''); $sb=trim($_POST['subject']??'');
    $gv=trim($_POST['grade']??''); $rm=trim($_POST['remarks']??'');
    if (!$su||!$sb||!$gv) { $msg="Student, subject, and grade are required."; $msgType='error'; }
    else {
        $found=false;
        foreach ($grades as &$g) {
            if ($g['student_username']===$su && $g['subject']===$sb) {
                $g['grade']=$gv; $g['remarks']=$rm; $g['graded_by']=$_SESSION['username']; $g['graded_at']=date('Y-m-d H:i:s'); $found=true; break;
            }
        } unset($g);
        if (!$found) $grades[]=['id'=>uniqid(),'student_username'=>$su,'subject'=>$sb,'grade'=>$gv,'remarks'=>$rm,'graded_by'=>$_SESSION['username'],'graded_at'=>date('Y-m-d H:i:s')];
        file_put_contents($gf,json_encode($grades,JSON_PRETTY_PRINT));
        $msg="Grade saved for '{$su}' in '{$sb}'.";
    }
}

$myGrades = array_filter($grades, fn($g)=>$g['graded_by']===$_SESSION['username']);
$pending  = array_filter($asgns,  fn($a)=>($a['status']??'pending')==='pending');
$initial  = strtoupper(substr($_SESSION['username'],0,1));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Grades — EduPortal</title>
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
      <a href="role_faculty_uploadMaterial.php" class="nav-item"><i class="fa fa-file-arrow-up"></i> Upload Material</a>
      <a href="role_faculty_manageGrades.php" class="nav-item active"><i class="fa fa-chart-bar"></i> Manage Grades</a>
    </nav>
    <div class="sidebar-foot">
      <a href="logout.php" class="nav-item logout"><i class="fa fa-right-from-bracket"></i> Log Out</a>
    </div>
  </aside>

  <div class="main-area">
    <div class="topbar">
      <div class="topbar-left">
        <span class="topbar-crumb">Faculty</span>
        <span class="topbar-title">Manage Grades</span>
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

      <!-- Grade form -->
      <div class="panel fade-up-2">
        <div class="panel-head">
          <div class="panel-ico"><i class="fa fa-pen-to-square"></i></div>
          <div class="panel-title">Enter / Update Grade</div>
        </div>
        <div class="panel-body">
          <form action="role_faculty_manageGrades.php" method="POST">
            <input type="hidden" name="action" value="save_grade">
            <div class="form-row">
              <div class="form-field">
                <label>Student *</label>
                <select name="student_username" required>
                  <option value="">— Select student —</option>
                  <?php foreach ($allUsers as $u): ?>
                    <option value="<?php echo htmlspecialchars($u['username']);?>"><?php echo htmlspecialchars(($u['fullname']??$u['username']).' ('.$u['username'].')');?></option>
                  <?php endforeach;?>
                </select>
              </div>
              <div class="form-field"><label>Subject *</label><input type="text" name="subject" placeholder="e.g. Mathematics"></div>
            </div>
            <div class="form-row">
              <div class="form-field" style="max-width:180px"><label>Grade *</label><input type="text" name="grade" placeholder="e.g. 92 or A"></div>
              <div class="form-field"><label>Remarks</label><input type="text" name="remarks" placeholder="Optional remarks"></div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-disk"></i> Save Grade</button>
          </form>
        </div>
      </div>

      <!-- Grades table -->
      <div class="panel fade-up-3">
        <div class="panel-head">
          <div class="panel-ico"><i class="fa fa-list-check"></i></div>
          <div class="panel-title">Grades I've Entered</div>
          <div class="panel-sub"><?php echo count($myGrades);?> records</div>
        </div>
        <div class="panel-body np">
          <?php if (empty($myGrades)): ?>
            <div class="empty-state"><i class="fa fa-clipboard"></i><p>No grades entered yet.</p></div>
          <?php else: ?>
            <div class="t-wrap"><table>
              <thead><tr><th>#</th><th>Student</th><th>Subject</th><th>Grade</th><th>Remarks</th><th>Date</th></tr></thead>
              <tbody>
                <?php $n=1; foreach ($myGrades as $g):
                  $num=is_numeric($g['grade'])?(float)$g['grade']:null;
                  $bc=$num!==null?($num>=75?'grade-pass':'grade-fail'):'grade-none';
                ?>
                <tr>
                  <td><?php echo $n++;?></td>
                  <td><?php echo htmlspecialchars($g['student_username']);?></td>
                  <td><?php echo htmlspecialchars($g['subject']);?></td>
                  <td><span class="grade-badge <?php echo $bc;?>"><?php echo htmlspecialchars($g['grade']);?></span></td>
                  <td><?php echo htmlspecialchars($g['remarks']?:'—');?></td>
                  <td><?php echo substr($g['graded_at'],0,10);?></td>
                </tr>
                <?php endforeach;?>
              </tbody>
            </table></div>
          <?php endif;?>
        </div>
      </div>

      <!-- Pending submissions -->
      <div class="panel fade-up-4">
        <div class="panel-head">
          <div class="panel-ico"><i class="fa fa-inbox"></i></div>
          <div class="panel-title">Student Submissions</div>
          <div class="panel-sub"><?php echo count($pending);?> pending</div>
        </div>
        <div class="panel-body np">
          <?php if (empty($pending)): ?>
            <div class="empty-state"><i class="fa fa-inbox"></i><p>No pending submissions.</p></div>
          <?php else: ?>
            <div class="t-wrap"><table>
              <thead><tr><th>#</th><th>Student</th><th>Subject</th><th>Title</th><th>File</th><th>Note</th><th>Submitted</th></tr></thead>
              <tbody>
                <?php $n=1; foreach ($pending as $a):?>
                <tr>
                  <td><?php echo $n++;?></td>
                  <td><?php echo htmlspecialchars($a['student_username']);?></td>
                  <td><?php echo htmlspecialchars($a['subject']??'—');?></td>
                  <td><?php echo htmlspecialchars($a['title']??'—');?></td>
                  <td><?php if(!empty($a['file'])){$p=explode('|',$a['file']);?><a href="uploads/<?php echo htmlspecialchars($p[1]??'');?>" target="_blank"><span class="file-chip"><i class="fa fa-file"></i><?php echo htmlspecialchars($p[0]);?></span></a><?php }else{echo '<span style="color:var(--text-light)">None</span>';}?></td>
                  <td><?php echo htmlspecialchars($a['note']??'—');?></td>
                  <td><?php echo substr($a['submitted_at']??'',0,10);?></td>
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