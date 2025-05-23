<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Dashboard</title>
  <style>
    /* [SAME CSS AS BEFORE — STYLED CLEAN DASHBOARD] */
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f6f9;
      margin: 0;
      padding: 20px;
    }

    h2, h3 {
      color: #333;
    }

    #message {
      margin-bottom: 20px;
      font-weight: bold;
      color: #0066cc;
    }

    #employeeFormContainer {
      background-color: #fff;
      padding: 20px;
      margin-bottom: 30px;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      max-width: 400px;
    }

    #addEmployeeForm input[type="text"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    #addEmployeeForm button {
      background-color: #28a745;
      color: white;
      border: none;
      padding: 10px 15px;
      border-radius: 5px;
      cursor: pointer;
    }

    #addEmployeeForm button:hover {
      background-color: #218838;
    }

    #responseMessage {
      margin-top: 10px;
      font-weight: bold;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background-color: #fff;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    table thead {
      background-color: #007bff;
      color: white;
    }

    table th, table td {
      padding: 10px;
      border: 1px solid #ddd;
      text-align: left;
    }

    table tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    table input[type="text"] {
      width: 90%;
      padding: 5px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    table button {
      margin-right: 5px;
      padding: 5px 10px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    table button:first-child {
      background-color: #17a2b8;
      color: white;
    }

    table button:first-child:hover {
      background-color: #138496;
    }

    table button:last-child {
      background-color: #dc3545;
      color: white;
    }

    table button:last-child:hover {
      background-color: #c82333;
    }
  </style>
</head>
<body>
  <h2>Dashboard</h2>
  <div id="message">Loading...</div>

  <div id="employeeFormContainer" style="display:none;">
    <h3>Add New Employee</h3>
    <form id="addEmployeeForm">
      <input type="text" name="first_name" placeholder="First Name" required><br>
      <input type="text" name="last_name" placeholder="Last Name" required><br>
      <input type="text" name="office" placeholder="Office" required><br>
      <button type="submit">Add Employee</button>
    </form>
    <div id="responseMessage"></div>
  </div>

  <h3>Employee List</h3>
  <table border="1" id="employeeTable">
    <thead>
      <tr>
        <th>ID</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Office</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>

  <script>
    const token = localStorage.getItem('authToken');

    if (!token) {
      document.getElementById('message').innerText = "Not logged in. Redirecting to login...";
      setTimeout(() => {
        window.location.href = "/mvc-main/login";
      }, 1500);
    } else {
      document.getElementById('message').innerText = "Welcome to the Dashboard!";
      document.getElementById('employeeFormContainer').style.display = 'block';
      loadEmployees();
    }

    document.getElementById('addEmployeeForm').addEventListener('submit', function (e) {
      e.preventDefault();
      const formData = new FormData(e.target);
      const data = {};
      formData.forEach((value, key) => data[key] = value);

      fetch('/mvc-main/addEmployee', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': token
        },
        body: JSON.stringify(data)
      })
      .then(async res => {
        const text = await res.text();
        try {
          const json = JSON.parse(text);
          document.getElementById('responseMessage').innerText = json.message || json.error || "No message";
          loadEmployees(); // refresh table
        } catch {
          console.error("Response is not valid JSON:", text);
          document.getElementById('responseMessage').innerText = "Unexpected server response.";
        }
        if (!res.ok) throw new Error("HTTP status " + res.status);
      })
      .catch(err => {
        console.error(err);
        document.getElementById('responseMessage').innerText = "Failed to add Employee.";
      });
    });

    function loadEmployees() {
      fetch('/mvc-main/getEmployee', {
        headers: {
          'Authorization': token
        }
      })
      .then(res => res.json())
      .then(data => {
        const employees = Array.isArray(data) ? data : data.data || [];
        const tbody = document.querySelector('#employeeTable tbody');
        tbody.innerHTML = '';
        employees.forEach(emp => {
          const row = document.createElement('tr');
          row.innerHTML = `
            <td>${emp.id}</td>
            <td><input type="text" value="${emp.first_name}" data-id="${emp.id}" data-field="first_name" /></td>
            <td><input type="text" value="${emp.last_name}" data-id="${emp.id}" data-field="last_name" /></td>
            <td><input type="text" value="${emp.office}" data-id="${emp.id}" data-field="office" /></td>
            <td>
              <button onclick="updateEmployee(${emp.id})">Update</button>
              <button onclick="deleteEmployee(${emp.id})">Delete</button>
            </td>
          `;
          tbody.appendChild(row);
        });
      });
    }

  function updateEmployee(id) {
  const inputs = document.querySelectorAll(`input[data-id="${id}"]`);
  const updatedData = {};
  inputs.forEach(input => {
    updatedData[input.dataset.field] = input.value;
  });
console.log("Sending data:", updatedData);

 fetch(`/mvc-main/updateEmployee/${id}`, {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Authorization': token
  },
  body: JSON.stringify(updatedData)
})


  .then(async res => {
  const text = await res.text();
  try {
    const json = JSON.parse(text);
    alert(json.message || json.error);
  } catch (e) {
    console.error("Invalid JSON:", text);
    alert("Invalid server response.");
  }
  loadEmployees();
  });

}


    function deleteEmployee(id) {
      if (!confirm("Are you sure you want to delete this employee?")) return;

      fetch(`/mvc-main/deleteEmployee/${id}`, {
      method: 'DELETE',
      headers: {
        'Authorization': token
      }
    })

      .then(res => res.json())
      .then(data => {
        alert(data.message || "Employee deleted.");
        loadEmployees();
      });
    }
  </script>
</body>
</html>
