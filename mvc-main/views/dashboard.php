<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Dashboard</title>
  <style>
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

    /* Modal styles */
    .modal {
      display: none;
      position: fixed;
      z-index: 1;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0,0,0,0.4);
    }

    .modal-content {
      background-color: #fff;
      margin: 15% auto;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      width: 400px;
    }

    .modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
    }

    .close {
      color: #aaa;
      font-size: 28px;
      font-weight: bold;
      cursor: pointer;
    }

    .close:hover {
      color: black;
    }

    #addEmployeeForm input[type="text"],
    #updateEmployeeForm input[type="text"] {
      width: 95%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    .form-actions {
      display: flex;
      justify-content: flex-end;
      gap: 10px;
    }

    #addEmployeeBtn, #openAddEmployeeModal, #updateEmployeeBtn {
      background-color: #28a745;
      color: white;
      border: none;
      padding: 10px 15px;
      border-radius: 5px;
      cursor: pointer;
    }

    #addEmployeeBtn:hover, #openAddEmployeeModal:hover, #updateEmployeeBtn:hover {
      background-color: #218838;
    }

    #cancelAddEmployee, #cancelUpdateEmployee {
      background-color: #6c757d;
      color: white;
      border: none;
      padding: 10px 15px;
      border-radius: 5px;
      cursor: pointer;
    }

    #cancelAddEmployee:hover, #cancelUpdateEmployee:hover {
      background-color: #5a6268;
    }

    #responseMessage, #updateResponseMessage {
      margin-top: 10px;
      font-weight: bold;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background-color: #fff;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      display: center;
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

    .logout-container {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }
    
    #logoutBtn {
      background-color: #dc3545;
      color: white;
      border: none;
      padding: 8px 15px;
      border-radius: 5px;
      cursor: pointer;
    }
    
    #logoutBtn:hover {
      background-color: #c82333;
    }

    .action-btn {
      margin-right: 5px;
      padding: 5px 10px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    .update-btn {
      background-color: #17a2b8;
      color: white;
    }

    .update-btn:hover {
      background-color: #138496;
    }

    .delete-btn {
      background-color: #dc3545;
      color: white;
    }

    .delete-btn:hover {
      background-color: #c82333;
    }
  </style>
</head>
<body>
  <div class="logout-container">
    <h1>Dashboard</h1>
    <button id="logoutBtn">Logout</button>
  </div>
  
  <div id="message"></div>

  <div class="logout-container">
    <h3>Employee List</h3>
    <button id="openAddEmployeeModal">Add New Employee</button>
  </div>

  <table border=".5" id="employeeTable">
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

  <!-- Add Employee Modal -->
  <div id="addEmployeeModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Add New Employee</h3>
        <span class="close">&times;</span>
      </div>
      <form id="addEmployeeForm">
        <input type="text" name="first_name" placeholder="First Name" required><br>
        <input type="text" name="last_name" placeholder="Last Name" required><br>
        <input type="text" name="office" placeholder="Office" required><br>
        <div class="form-actions">
          <button type="button" id="cancelAddEmployee">Cancel</button>
          <button type="submit" id="addEmployeeBtn">Add Employee</button>
        </div>
      </form>
      <div id="responseMessage"></div>
    </div>
  </div>

  <!-- Update Employee Modal -->
  <div id="updateEmployeeModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Update Employee</h3>
        <span class="close-update">&times;</span>
      </div>
      <form id="updateEmployeeForm">
        <input type="hidden" id="updateEmployeeId" name="id">
        <input type="text" id="updateFirstName" name="first_name" placeholder="First Name" required><br>
        <input type="text" id="updateLastName" name="last_name" placeholder="Last Name" required><br>
        <input type="text" id="updateOffice" name="office" placeholder="Office" required><br>
        <div class="form-actions">
          <button type="button" id="cancelUpdateEmployee">Cancel</button>
          <button type="submit" id="updateEmployeeBtn">Update Employee</button>
        </div>
      </form>
      <div id="updateResponseMessage"></div>
    </div>
  </div>

  <script>
    const token = localStorage.getItem('authToken');
    const addModal = document.getElementById('addEmployeeModal');
    const updateModal = document.getElementById('updateEmployeeModal');
    const openModalBtn = document.getElementById('openAddEmployeeModal');
    const closeAddBtn = document.querySelector('.close');
    const closeUpdateBtn = document.querySelector('.close-update');
    const cancelAddBtn = document.getElementById('cancelAddEmployee');
    const cancelUpdateBtn = document.getElementById('cancelUpdateEmployee');

    if (!token) {
      document.getElementById('message').innerText = "Not logged in. Redirecting to login...";
      setTimeout(() => {
        window.location.href = "/mvc-main/login";
      }, 1500);
    } else {
      loadEmployees();
    }

    // Modal functionality
    openModalBtn.addEventListener('click', () => {
      addModal.style.display = 'block';
    });

    closeAddBtn.addEventListener('click', () => {
      addModal.style.display = 'none';
    });

    closeUpdateBtn.addEventListener('click', () => {
      updateModal.style.display = 'none';
    });

    cancelAddBtn.addEventListener('click', () => {
      addModal.style.display = 'none';
    });

    cancelUpdateBtn.addEventListener('click', () => {
      updateModal.style.display = 'none';
    });

    window.addEventListener('click', (event) => {
      if (event.target === addModal) {
        addModal.style.display = 'none';
      }
      if (event.target === updateModal) {
        updateModal.style.display = 'none';
      }
    });

    // Logout functionality
    document.getElementById('logoutBtn').addEventListener('click', function() {
      localStorage.removeItem('authToken');
      window.location.href = "/mvc-main/login";
    });

    // Add Employee Form
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
          alert(json.message || json.error || "Employee added successfully");
          e.target.reset();
          addModal.style.display = 'none';
          loadEmployees();
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

    // Update Employee Form
    document.getElementById('updateEmployeeForm').addEventListener('submit', function (e) {
      e.preventDefault();
      const formData = new FormData(e.target);
      const data = {};
      formData.forEach((value, key) => data[key] = value);
      const id = document.getElementById('updateEmployeeId').value;

      fetch(`/mvc-main/updateEmployee/${id}`, {
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
          alert(json.message || json.error || "Employee updated successfully");
          e.target.reset();
          updateModal.style.display = 'none';
          loadEmployees();
        } catch {
          console.error("Response is not valid JSON:", text);
          document.getElementById('updateResponseMessage').innerText = "Unexpected server response.";
        }
        if (!res.ok) throw new Error("HTTP status " + res.status);
      })
      .catch(err => {
        console.error(err);
        document.getElementById('updateResponseMessage').innerText = "Failed to update Employee.";
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
            <td>${emp.first_name}</td>
            <td>${emp.last_name}</td>
            <td>${emp.office}</td>
            <td>
              <button class="action-btn update-btn" onclick="openUpdateModal(${emp.id}, '${emp.first_name}', '${emp.last_name}', '${emp.office}')">Update</button>
              <button class="action-btn delete-btn" onclick="deleteEmployee(${emp.id})">Delete</button>
            </td>
          `;
          tbody.appendChild(row);
        });
      });
    }

    function openUpdateModal(id, firstName, lastName, office) {
      document.getElementById('updateEmployeeId').value = id;
      document.getElementById('updateFirstName').value = firstName;
      document.getElementById('updateLastName').value = lastName;
      document.getElementById('updateOffice').value = office;
      updateModal.style.display = 'block';
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