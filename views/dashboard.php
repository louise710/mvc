<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Dashboard</title>
</head>
<body>
  <h2>Dashboard</h2>
  <div id="message">Loading...</div>

  <div id="hunterFormContainer" style="display:none;">
    <h3>Add New Hunter</h3>
    <form id="addHunterForm">
      <input type="text" name="first_name" placeholder="First Name" required><br>
      <input type="text" name="rank" placeholder="Rank" required><br>
      <input type="number" name="level" placeholder="Level" required><br>
      <input type="text" name="class" placeholder="Class" required><br>
      <input type="text" name="race" placeholder="Race" required><br>
      <button type="submit">Add Hunter</button>
    </form>
    <div id="responseMessage"></div>
  </div>

  <script>
    const token = localStorage.getItem('authToken');

    if (!token) {
      document.getElementById('message').innerText = "Not logged in. Redirecting to login...";
      setTimeout(() => {
        window.location.href = "/login";
      }, 1500);
    } else {
      document.getElementById('message').innerText = "Welcome to the Dashboard!";
      document.getElementById('hunterFormContainer').style.display = 'block';
    }

    document.getElementById('addHunterForm').addEventListener('submit', function (e) {
      e.preventDefault();
      const formData = new FormData(e.target);
      const data = {};
      formData.forEach((value, key) => data[key] = value);

      fetch('/Hunter', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': token
        },
        body: JSON.stringify(data)
      })
      .then(res => res.json())
      .then(result => {
        document.getElementById('responseMessage').innerText = result.message || "Hunter added successfully!";
      })
      .catch(err => {
        document.getElementById('responseMessage').innerText = "Failed to add hunter.";
        console.error(err);
      });
    });
  </script>
</body>
</html>
