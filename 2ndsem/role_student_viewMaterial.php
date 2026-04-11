<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php"); exit();
}

$mf = "materials.json"; $gf = "grades.json";
$materials = file_exists($mf) ? (json_decode(file_get_contents($mf),true)??[]) : [];
$allGrades = file_exists($gf) ? (json_decode(file_get_contents($gf),true)??[]) : [];
$myGrades  = array_filter($allGrades, fn($g)=>$g['student_username']===$_SESSION['username']);

$search = trim($_GET['search']??'');
if ($search) $materials=array_filter($materials,fn($m)=>stripos($m['title'],$search)!==false||stripos($m['subject'],$search)!==false);

$initial = strtoupper(substr($_SESSION['username'],0,1));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Material — EduPortal</title>
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
        <div class="s-role">Student</div>
      </div>
    </div>
    <nav class="sidebar-nav">
      <div class="nav-section">Menu</div>
      <a href="dashboard.php" class="nav-item"><i class="fa fa-house"></i> Dashboard</a>
      <div class="nav-section">Student</div>
      <a href="role_student_viewMaterial.php" class="nav-item active"><i class="fa fa-book-open"></i> View Material</a>
      <a href="role_student_submitAssignment.php" class="nav-item"><i class="fa fa-paper-plane"></i> Submit Assignment</a>
    </nav>
    <div class="sidebar-foot">
      <a href="logout.php" class="nav-item logout"><i class="fa fa-right-from-bracket"></i> Log Out</a>
    </div>
  </aside>

  <div class="main-area">
    <div class="topbar">
      <div class="topbar-left">
        <span class="topbar-crumb">Student</span>
        <span class="topbar-title">View Material</span>
      </div>
      <div class="topbar-right">
        <span class="role-pill">Student</span>
        <div class="user-chip">
          <div class="chip-avatar"><?php echo $initial; ?></div>
          <span class="chip-name"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
        </div>
      </div>
    </div>

    <div class="page-content">
      <a href="dashboard.php" class="back-link fade-up"><i class="fa fa-arrow-left"></i> Back to Dashboard</a>

      <!-- Search -->
      <div class="panel fade-up">
        <div class="panel-head">
          <div class="panel-ico"><i class="fa fa-magnifying-glass"></i></div>
          <div class="panel-title">Search Materials</div>
        </div>
        <div class="panel-body">
          <form action="role_student_viewMaterial.php" method="GET">
            <div class="form-row" style="align-items:flex-end">
              <div class="form-field"><label>Title or Subject</label><input type="text" name="search" value="<?php echo htmlspecialchars($search);?>" placeholder="e.g. Mathematics or Chapter 3"></div>
              <div style="display:flex;gap:8px;padding-bottom:1px">
                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                <?php if($search):?><a href="role_student_viewMaterial.php" class="btn btn-ghost">Clear</a><?php endif;?>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- Materials -->
      <div class="panel fade-up-2">
        <div class="panel-head">
          <div class="panel-ico"><i class="fa fa-book-open"></i></div>
          <div class="panel-title">Available Materials</div>
          <div class="panel-sub"><?php echo count($materials);?> items</div>
        </div>
        <div class="panel-body np">
          <?php if (empty($materials)):?>
            <div class="empty-state"><i class="fa fa-box-open"></i><p>No materials found<?php echo $search?' for "'.htmlspecialchars($search).'"':'';?>.</p></div>
          <?php else:?>
            <div class="t-wrap"><table>
              <thead><tr><th>#</th><th>Title</th><th>Subject</th><th>Description</th><th>File</th><th>By</th><th>Date</th></tr></thead>
              <tbody>
                <?php $n=1; foreach ($materials as $m):?>
                <tr>
                  <td><?php echo $n++;?></td>
                  <td><?php echo htmlspecialchars($m['title']);?></td>
                  <td><?php echo htmlspecialchars($m['subject']);?></td>
                  <td><?php echo htmlspecialchars($m['description']?:'—');?></td>
                  <td><?php if(!empty($m['file'])){$p=explode('|',$m['file']);?><a href="uploads/<?php echo htmlspecialchars($p[1]??'');?>" target="_blank"><span class="file-chip"><i class="fa fa-download"></i><?php echo htmlspecialchars($p[0]);?></span></a><?php }else{echo '<span style="color:var(--text-light)">No file</span>';}?></td>
                  <td><?php echo htmlspecialchars($m['uploaded_by']);?></td>
                  <td><?php echo substr($m['uploaded_at'],0,10);?></td>
                </tr>
                <?php endforeach;?>
              </tbody>
            </table></div>
          <?php endif;?>
        </div>
      </div>

      <!-- My grades -->
      <div class="panel fade-up-3">
        <div class="panel-head">
          <div class="panel-ico"><i class="fa fa-graduation-cap"></i></div>
          <div class="panel-title">My Grades</div>
          <div class="panel-sub"><?php echo count($myGrades);?> subjects</div>
        </div>
        <div class="panel-body np">
          <?php if (empty($myGrades)):?>
            <div class="empty-state"><i class="fa fa-graduation-cap"></i><p>No grades assigned yet.</p></div>
          <?php else:?>
            <div class="t-wrap"><table>
              <thead><tr><th>#</th><th>Subject</th><th>Grade</th><th>Remarks</th><th>Graded By</th><th>Date</th></tr></thead>
              <tbody>
                <?php $n=1; foreach ($myGrades as $g):
                  $num=is_numeric($g['grade'])?(float)$g['grade']:null;
                  $bc=$num!==null?($num>=75?'grade-pass':'grade-fail'):'grade-none';
                ?>
                <tr>
                  <td><?php echo $n++;?></td>
                  <td><?php echo htmlspecialchars($g['subject']);?></td>
                  <td><span class="grade-badge <?php echo $bc;?>"><?php echo htmlspecialchars($g['grade']);?></span></td>
                  <td><?php echo htmlspecialchars($g['remarks']?:'—');?></td>
                  <td><?php echo htmlspecialchars($g['graded_by']);?></td>
                  <td><?php echo substr($g['graded_at'],0,10);?></td>
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