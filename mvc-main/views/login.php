<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Login</title>
  <link rel="stylesheet" href="css/view.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f0f2f5;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <h2>Login</h2>
    <?php if (!empty($error)): ?>
    <div class="error-alert">
  <?= htmlspecialchars($error) ?>
  </div>
    <?php endif; ?>
    <form method="POST" action="login">
      <label for="email">Email:</label>
      <input type="email" name="email" required />

      <label for="password">Password:</label>
      <input type="password" name="password" required />

      <button class ="login-btn" type="submit">Login</button>
    </form>

    <div id="loginMessage"></div>
    
    <div class="register-link">
      Don't have an account? <a href="/mvc-main/register">Register here</a>
    </div>
  </div>

  
</body>

</html>