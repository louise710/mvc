<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
</head>
<body>
  <h2>Login</h2>
  <form id="loginForm">
    <label for="email">Email:</label>
    <input type="email" id="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" required><br><br>

    <button type="submit">Login</button>
  </form>

  <div id="loginMessage"></div>

  <script>
    document.getElementById("loginForm").addEventListener("submit", async function(e) {
      e.preventDefault();

      const email = document.getElementById("email").value;
      const password = document.getElementById("password").value;

      try {
        const res = await fetch("/login", {
          method: "POST",
          headers: {
            "Content-Type": "application/json"
          },
          body: JSON.stringify({ email, password })
        });

        const data = await res.json();

        if (data.token) {
          localStorage.setItem("authToken", data.token);
          window.location.href = "/dashboard";
        } else {
          document.getElementById("loginMessage").innerText = "Login failed. Please try again.";
        }
      } catch (err) {
        document.getElementById("loginMessage").innerText = "Error: " + err;
      }
    });
  </script>
</body>
</html>
