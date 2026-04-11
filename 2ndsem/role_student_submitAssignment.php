<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php"); exit();
}

$af = "assignments.json";
$msg = ''; $msgType = '';
$asgns = file_exists($af) ? (json_decode(file_get_contents($af),true)??[]) : [];

if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['action'])) {
    if ($_POST['action']==='submit') {
        $title=trim($_POST['title']??''); $subject=trim($_POST['subject']??'');
        $note=trim($_POST['note']??''); $fn='';
        if (!$title||!$subject) { $msg="Title and subject are required."; $msgType='error'; }
        else {
            if (isset($_FILES['file'])&&$_FILES['file']['error']===UPLOAD_ERR_OK) {
                $dir="uploads/"; if(!is_dir($dir)) mkdir($dir,0755,true);
                $ext=pathinfo($_FILES['file']['name'],PATHINFO_EXTENSION);
                $safe=uniqid("asgn_").'.'.$ext;
                move_uploaded_file($_FILES['file']['tmp_name'],$dir.$safe);
                $fn=$_FILES['file']['name'].'|'.$safe;
            }
            $asgns[]=['id'=>uniqid(),'student_username'=>$_SESSION['username'],
                      'title'=>$title,'subject'=>$subject,'note'=>$note,
                      'file'=>$fn,'status'=>'pending','submitted_at'=>date('Y-m-d H:i:s')];
            file_put_contents($af,json_encode($asgns,JSON_PRETTY_PRINT));
            $msg="Assignment '{$title}' submitted!";
        }
    }
    if ($_POST['action']==='delete' && isset($_POST['asgn_id'])) {
        foreach ($asgns as $i=>$a) {
            if ($a['id']===$_POST['asgn_id'] && $a['student_username']===$_SESSION['username']) {
                if ($a['file']){$p=explode('|',$a['file']);if(!empty($p[1])&&file_exists("uploads/".$p[1]))unlink("uploads/".$p[1]);}
                array_splice($asgns,$i,1);
                file_put_contents($af,json_encode($asgns,JSON_PRETTY_PRINT));
                $msg="Submission deleted."; break;
            }
        }
    }
    $asgns = file_exists($af)?(json_decode(file_get_contents($af),true)??[]):[];
}

$mine = array_filter($asgns, fn($a)=>$a['student_username']===$_SESSION['username']);
usort($mine, fn($a,$b)=>strcmp($b['submitted_at']??'',$a['submitted_at']??''));
$initial = strtoupper(substr($_SESSION['username'],0,1));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Submit Assignment — EduPortal</title>
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
      <a href="role_student_viewMaterial.php" class="nav-item"><i class="fa fa-book-open"></i> View Material</a>
      <a href="role_student_submitAssignment.php" class="nav-item active"><i class="fa fa-paper-plane"></i> Submit Assignment</a>
    </nav>
    <div class="sidebar-foot">
      <a href="logout.php" class="nav-item logout"><i class="fa fa-right-from-bracket"></i> Log Out</a>
    </div>
  </aside>

  <div class="main-area">
    <div class="topbar">
      <div class="topbar-left">
        <span class="topbar-crumb">Student</span>
        <span class="topbar-title">Submit Assignment</span>
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

      <?php if ($msg):?>
        <div class="alert <?php echo $msgType==='error'?'alert-error':'alert-success';?> fade-up">
          <i class="fa <?php echo $msgType==='error'?'fa-circle-xmark':'fa-circle-check';?>"></i>
          <?php echo htmlspecialchars($msg);?>
        </div>
      <?php endif;?>

      <!-- Submit form -->
      <div class="panel fade-up-2">
        <div class="panel-head">
          <div class="panel-ico"><i class="fa fa-paper-plane"></i></div>
          <div class="panel-title">Submit New Assignment</div>
        </div>
        <div class="panel-body">
          <form action="role_student_submitAssignment.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="submit">
            <div class="form-row">
              <div class="form-field"><label>Title *</label><input type="text" name="title" placeholder="e.g. Problem Set 3" required></div>
              <div class="form-field"><label>Subject *</label><input type="text" name="subject" placeholder="e.g. Mathematics"></div>
            </div>
            <div class="form-row">
              <div class="form-field"><label>Note to Teacher</label><textarea name="note" placeholder="Optional message..."></textarea></div>
            </div>
            <div class="form-row">
              <div class="form-field"><label>Attach File (optional)</label><input type="file" name="file"></div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa fa-paper-plane"></i> Submit Assignment</button>
          </form>
        </div>
      </div>

      <!-- My submissions -->
      <div class="panel fade-up-3">
        <div class="panel-head">
          <div class="panel-ico"><i class="fa fa-list"></i></div>
          <div class="panel-title">My Submissions</div>
          <div class="panel-sub"><?php echo count($mine);?> total</div>
        </div>
        <div class="panel-body np">
          <?php if (empty($mine)):?>
            <div class="empty-state"><i class="fa fa-inbox"></i><p>No assignments submitted yet.</p></div>
          <?php else:?>
            <div class="t-wrap"><table>
              <thead><tr><th>#</th><th>Title</th><th>Subject</th><th>Note</th><th>File</th><th>Status</th><th>Date</th><th></th></tr></thead>
              <tbody>
                <?php $n=1; foreach ($mine as $a):$st=$a['status']??'pending';?>
                <tr>
                  <td><?php echo $n++;?></td>
                  <td><?php echo htmlspecialchars($a['title']);?></td>
                  <td><?php echo htmlspecialchars($a['subject']??'—');?></td>
                  <td><?php echo htmlspecialchars($a['note']?:'—');?></td>
                  <td><?php if(!empty($a['file'])){$p=explode('|',$a['file']);?><a href="uploads/<?php echo htmlspecialchars($p[1]??'');?>" target="_blank"><span class="file-chip"><i class="fa fa-file"></i><?php echo htmlspecialchars($p[0]);?></span></a><?php }else{echo '<span style="color:var(--text-light)">No file</span>';}?></td>
                  <td><span class="badge <?php echo $st==='pending'?'badge-faculty':'badge-admin';?>"><?php echo ucfirst($st);?></span></td>
                  <td><?php echo substr($a['submitted_at']??'',0,10);?></td>
                  <td>
                    <?php if ($st==='pending'):?>
                      <form action="role_student_submitAssignment.php" method="POST" onsubmit="return confirm('Delete?')">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="asgn_id" value="<?php echo htmlspecialchars($a['id']);?>">
                        <button type="submit" class="btn btn-danger" style="padding:5px 12px;font-size:12px;">Delete</button>
                      </form>
                    <?php else:?><span style="color:var(--text-light);font-size:12px">Reviewed</span><?php endif;?>
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