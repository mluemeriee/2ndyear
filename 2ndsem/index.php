<?php
session_start();

// Redirect already-authenticated users straight to the dashboard
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>EduPortal — Academic Management Platform</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="style.css" />
</head>
<body class="index-page">

<!-- ── NAVBAR ── -->
<header class="navbar">
  <div class="navbar-brand">
    <div class="nav-logo-mark"><i class="fa fa-graduation-cap"></i></div>
    <span class="nav-name">EduPortal</span>
  </div>
  <nav class="navbar-links">
    <a href="#features" class="nav-link">Features</a>
    <a href="#roles" class="nav-link">Who It's For</a>
    <a href="login.php" class="nav-link">Log In</a>
    <a href="register.php" class="nav-cta">Get Started</a>
  </nav>
</header>

<!-- ── HERO ── -->
<section class="hero">
  <div class="hero-geo-1"></div>
  <div class="hero-geo-2"></div>
  <div class="hero-inner">
    <div class="hero-left">
      <div class="hero-eyebrow"><i class="fa fa-circle-check"></i> Academic Management Platform</div>
      <h1 class="hero-title">
        One platform for <em>every role</em> in your institution
      </h1>
      <p class="hero-desc">
        EduPortal connects administrators, faculty, and students under a single, unified system — simplifying grade management, material sharing, and assignment submission.
      </p>
      <div class="hero-actions">
        <a href="register.php" class="btn-hero-primary">
          <i class="fa fa-user-plus"></i> Create an Account
        </a>
        <a href="login.php" class="btn-hero-secondary">
          <i class="fa fa-arrow-right-to-bracket"></i> Log In
        </a>
      </div>
    </div>
    <div class="hero-right">
      <div class="card-stack">
        <!-- Secure badge -->
        <div class="floating-card fc-badge badge-secure">
          <div class="badge-icon bg-forest"><i class="fa fa-shield-halved"></i></div>
          <div>
            <div class="badge-text-main">Role-Secured</div>
            <div class="badge-text-sub">Access control by role</div>
          </div>
        </div>
        <!-- Main card -->
        <div class="floating-card fc-main">
          <div class="fc-header">
            <div class="fc-header-eyebrow">EduPortal</div>
            <div class="fc-header-title">Your Dashboard</div>
          </div>
          <div class="fc-body">
            <div class="fc-row">
              <div class="fc-icon"><i class="fa fa-users"></i></div>
              <div><div class="fc-label">Manage Users</div><div class="fc-sub">Assign student & faculty roles</div></div>
              <div class="fc-arrow"><i class="fa fa-chevron-right"></i></div>
            </div>
            <div class="fc-row">
              <div class="fc-icon"><i class="fa fa-file-arrow-up"></i></div>
              <div><div class="fc-label">Upload Materials</div><div class="fc-sub">Share notes and resources</div></div>
              <div class="fc-arrow"><i class="fa fa-chevron-right"></i></div>
            </div>
            <div class="fc-row">
              <div class="fc-icon"><i class="fa fa-chart-bar"></i></div>
              <div><div class="fc-label">Manage Grades</div><div class="fc-sub">Enter and track grades</div></div>
              <div class="fc-arrow"><i class="fa fa-chevron-right"></i></div>
            </div>
            <div class="fc-row">
              <div class="fc-icon"><i class="fa fa-paper-plane"></i></div>
              <div><div class="fc-label">Submit Work</div><div class="fc-sub">Upload assignments easily</div></div>
              <div class="fc-arrow"><i class="fa fa-chevron-right"></i></div>
            </div>
          </div>
        </div>
        <!-- Roles badge -->
        <div class="floating-card fc-badge badge-roles">
          <div class="badge-icon bg-warn"><i class="fa fa-user-tie"></i></div>
          <div>
            <div class="badge-text-main">3 User Roles</div>
            <div class="badge-text-sub">Admin · Faculty · Student</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ── DIVIDER BAR ── -->
<div class="divider-bar">
  <div class="divider-inner">
    <div class="divider-item"><i class="fa fa-lock"></i> Session-protected pages</div>
    <div class="divider-dot"></div>
    <div class="divider-item"><i class="fa fa-shield-halved"></i> Role-based access control</div>
    <div class="divider-dot"></div>
    <div class="divider-item"><i class="fa fa-file-shield"></i> Secure file uploads</div>
    <div class="divider-dot"></div>
    <div class="divider-item"><i class="fa fa-graduation-cap"></i> Grade management</div>
    <div class="divider-dot"></div>
    <div class="divider-item"><i class="fa fa-mobile-screen"></i> Responsive layout</div>
  </div>
</div>

<!-- ── FEATURES ── -->
<section class="section" id="features">
  <div class="section-inner">
    <div class="section-label">Platform Capabilities</div>
    <h2 class="section-title">Everything your institution <em>needs</em></h2>
    <p class="section-desc">A complete academic workflow in one place — from user onboarding to grade reporting.</p>
    <div class="features-grid">
      <div class="feat-card">
        <div class="feat-icon"><i class="fa fa-users-gear"></i></div>
        <div class="feat-title">User Role Management</div>
        <div class="feat-desc">Admins can assign and modify roles for any registered account. Unverified users stay locked out until a role is granted.</div>
      </div>
      <div class="feat-card">
        <div class="feat-icon light"><i class="fa fa-file-arrow-up"></i></div>
        <div class="feat-title">Material Distribution</div>
        <div class="feat-desc">Faculty upload lecture notes, PDFs, and resources directly to the platform. Students can search and download materials by subject.</div>
      </div>
      <div class="feat-card">
        <div class="feat-icon"><i class="fa fa-chart-bar"></i></div>
        <div class="feat-title">Grade Entry & Tracking</div>
        <div class="feat-desc">Faculty enter grades per student and subject. Students see their results and pass/fail status in real time on their profile.</div>
      </div>
      <div class="feat-card">
        <div class="feat-icon light"><i class="fa fa-paper-plane"></i></div>
        <div class="feat-title">Assignment Submission</div>
        <div class="feat-desc">Students attach files and notes when submitting work. Faculty review all pending submissions in the grade management panel.</div>
      </div>
      <div class="feat-card">
        <div class="feat-icon"><i class="fa fa-lock"></i></div>
        <div class="feat-title">Authenticated Access</div>
        <div class="feat-desc">Every page is session-protected. Unauthenticated visitors are automatically redirected to the login screen.</div>
      </div>
      <div class="feat-card">
        <div class="feat-icon light"><i class="fa fa-mobile-screen"></i></div>
        <div class="feat-title">Responsive Interface</div>
        <div class="feat-desc">A consistent, clean design system works across desktops, tablets, and mobile devices without any extra configuration.</div>
      </div>
    </div>
  </div>
</section>

<!-- ── ROLES ── -->
<section class="roles-section" id="roles">
  <div class="roles-inner">
    <div class="section-label">User Roles</div>
    <h2 class="section-title">Built for <em>every member</em> of the institution</h2>
    <p class="section-desc">Each role has its own dedicated workspace with purpose-built tools.</p>
    <div class="roles-grid">
      <div class="role-card">
        <div class="role-pill"><i class="fa fa-circle-dot"></i> Administrator</div>
        <div class="role-title">Admin</div>
        <div class="role-desc">Full system oversight. Manage all registered accounts and configure platform settings.</div>
        <div class="role-perms">
          <div class="role-perm"><i class="fa fa-check"></i> View all registered users</div>
          <div class="role-perm"><i class="fa fa-check"></i> Assign faculty or student roles</div>
          <div class="role-perm"><i class="fa fa-check"></i> Access system settings</div>
        </div>
      </div>
      <div class="role-card">
        <div class="role-pill"><i class="fa fa-circle-dot"></i> Faculty</div>
        <div class="role-title">Faculty</div>
        <div class="role-desc">Teaching staff who upload course materials and evaluate student work through grades.</div>
        <div class="role-perms">
          <div class="role-perm"><i class="fa fa-check"></i> Upload and manage materials</div>
          <div class="role-perm"><i class="fa fa-check"></i> Enter and update student grades</div>
          <div class="role-perm"><i class="fa fa-check"></i> View pending assignment submissions</div>
        </div>
      </div>
      <div class="role-card">
        <div class="role-pill"><i class="fa fa-circle-dot"></i> Student</div>
        <div class="role-title">Student</div>
        <div class="role-desc">Learners who access course content, submit assignments, and track their academic progress.</div>
        <div class="role-perms">
          <div class="role-perm"><i class="fa fa-check"></i> Browse and download materials</div>
          <div class="role-perm"><i class="fa fa-check"></i> Submit and manage assignments</div>
          <div class="role-perm"><i class="fa fa-check"></i> View personal grade records</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ── CTA ── -->
<section class="cta-section">
  <div class="cta-inner">
    <div class="section-label">Ready to begin?</div>
    <h2 class="section-title">Join EduPortal <em>today</em></h2>
    <p class="section-desc">Register an account and an administrator will assign you the appropriate role to get started.</p>
    <div class="cta-actions">
      <a href="register.php" class="btn-hero-primary"><i class="fa fa-user-plus"></i> Create an Account</a>
      <a href="login.php" class="btn-hero-secondary"><i class="fa fa-arrow-right-to-bracket"></i> Log In</a>
    </div>
  </div>
</section>

<!-- ── FOOTER ── -->
<footer class="footer">
  <div class="footer-brand">
    <div class="footer-logo"><i class="fa fa-graduation-cap"></i></div>
    <span class="footer-name">EduPortal</span>
  </div>
  <div class="footer-copy">&copy; <?php echo date('Y'); ?> EduPortal. Academic Management Platform.</div>
  <div class="footer-links">
    <a href="login.php" class="footer-link">Log In</a>
    <a href="register.php" class="footer-link">Register</a>
  </div>
</footer>

</body>
</html>