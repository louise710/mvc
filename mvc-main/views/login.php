<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Login</title>
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

    .login-container {
      background: #fff;
      padding: 30px 40px;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 400px;
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #333;
    }

    label {
      display: block;
      margin-bottom: 5px;
      font-weight: 500;
    }

    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 14px;
    }

    button {
      width: 100%;
      background-color: #007bff;
      color: white;
      padding: 10px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 16px;
    }

    button:hover {
      background-color: #0056b3;
    }

    #loginMessage {
      margin-top: 15px;
      text-align: center;
      color: red;
      font-weight: 500;
    }

    .register-link {
      text-align: center;
      margin-top: 15px;
    }

    .register-link a {
      color: #007bff;
      text-decoration: none;
    }

    .register-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <h2>Login</h2>
    <form id="loginForm">
      <label for="email">Email:</label>
      <input type="email" id="email" required />

      <label for="password">Password:</label>
      <input type="password" id="password" required />

      <button type="submit">Login</button>
    </form>

    <div id="loginMessage"></div>
    
    <div class="register-link">
      Don't have an account? <a href="/mvc-main/register">Register here</a>
    </div>
  </div>

  <script>
    document.getElementById("loginForm").addEventListener("submit", async function (e) {
      e.preventDefault();

      const email = document.getElementById("email").value;
      const password = document.getElementById("password").value;
      const messageEl = document.getElementById("loginMessage");

      try {
        const res = await fetch("/mvc-main/login", {
          method: "POST",
          headers: {
            "Content-Type": "application/json"
          },
          body: JSON.stringify({ email, password })
        });

        const contentType = res.headers.get("content-type") || "";
        if (!contentType.includes("application/json")) {
          const text = await res.text();
          messageEl.innerText = "Server error or unexpected response:\n" + text;
          return;
        }

        const data = await res.json();

        if (!res.ok) {
          messageEl.innerText = data.error || "Login failed. Please try again.";
          return;
        }

        if (data.token) {
          localStorage.setItem("authToken", data.token);
          window.location.href = "/mvc-main/dashboard";
        } else {
          messageEl.innerText = "Login failed. Please try again.";
        }
      } catch (err) {
        messageEl.innerText = "Network or parsing error: " + err.message;
      }
    });
  </script>
</body>
</html>