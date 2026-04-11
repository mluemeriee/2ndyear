<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Login — EduPortal</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
<link rel="stylesheet" href="style.css" />
</head>
<body class="login-page">

<div class="card">

  <div class="card-header">
    <h2>Welcome Back!</h2>
    <p>Login to continue your journey</p>
  </div>

  <div class="card-body">
    <h3>Log In</h3>

    <form action="process_login.php" method="POST">

      <div class="input-group">
        <i class="fa fa-user"></i>
        <input type="text" name="username" placeholder="Username" required />
      </div>

      <div class="input-group">
        <i class="fa fa-lock"></i>
        <input type="password" name="password" placeholder="Password" required />
      </div>

      <button type="submit">Login</button>

    </form>

    <div class="footer">
      Don't have an account? <a href="register.php">Sign up</a>
    </div>
  </div>

</div>

</body>
</html>