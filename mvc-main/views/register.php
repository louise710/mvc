<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
     <?php if (!empty($error)): ?>
    <div class="error-alert">
        <?= htmlspecialchars($error) ?>
    </div>
<?php elseif (!empty($success)): ?>
    <div class="success-alert">
        <?= htmlspecialchars($success) ?>
        <script>
            setTimeout(() => {
            window.location.href = "/mvc-main/login";
            }, 2000); // 2 second delay
        </script>
    </div>
<?php endif; ?>
    <div class="login-container">
        <h2>Register</h2>
    <form id="registerForm" method="POST" action="register">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <button type="submit">Register</button>
    </form>
    <div id="message"></div>
    </div>

   
</body>
</html>