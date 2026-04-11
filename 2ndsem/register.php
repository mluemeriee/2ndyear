<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register — EduPortal</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700&family=DM+Serif+Display&display=swap">
<link rel="stylesheet" href="style.css">
</head>
<body class="register-page">

<div class="wrapper">

  <!-- LEFT -->
  <div class="left">
    <h1>Create Your Account</h1>
    <p>Sign up to get started and access all features of our system.</p>
  </div>

  <!-- RIGHT -->
  <div class="right">
    <div class="form-card">
      <h2>Register Account</h2>

      <form action="process_register.php" method="POST">

        <div class="field">
          <input type="text" name="fullname" required>
          <label>Full Name</label>
        </div>

        <div class="field">
          <input type="text" name="phonenumber" required>
          <label>Phone Number</label>
        </div>

        <div class="field">
          <input type="text" name="civilstatus" required>
          <label>Civil Status</label>
        </div>

        <div class="field">
          <input type="text" name="username" required>
          <label>Username</label>
        </div>

        <div class="field">
          <input type="email" name="email" required>
          <label>Email</label>
        </div>

        <div class="field">
          <input type="password" name="password" required>
          <label>Password</label>
        </div>

        <div class="field">
          <input type="password" name="confirm_password" required>
          <label>Confirm Password</label>
        </div>

        <button type="submit">Create Account</button>

      </form>

      <div class="foot">
        Already have an account? <a href="login.php">Login here</a>
      </div>
    </div>
  </div>

</div>

</body>
</html>