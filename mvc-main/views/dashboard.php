

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Dashboard</title>
  <link rel="stylesheet" href="css/view.css">
</head>
<body>
  <?php
  session_start();

  if (!isset($_SESSION['token'])) {
      header('Location: /mvc-main/login');
      exit();
  }
  ?>
  <div class="logout-container">
    <h1>Dashboard</h1>
     <a href="/mvc-main/logout" style="text-decoration:none;">
    <button type="button">Logout</button>
  </a>
  </div>
  
  <div id="message"></div>

  <div class="logout-container">
    <h3>Employee List</h3>
    <button id="openAddEmployeeModal">Add New Employee</button>
  </div>
  <?php
  $success = $_SESSION['success'] ?? '';
  $error = $_SESSION['error'] ?? '';
  unset($_SESSION['success'], $_SESSION['error']);
  ?>

<?php if (!empty($error)): ?>
    <div class="error-alert">
        <?= htmlspecialchars($error) ?>
    </div>
<?php elseif (!empty($success)): ?>
    <div class="success-alert">
        <?= htmlspecialchars($success) ?>
    </div>
<?php endif; ?>

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
    <tbody>
  <?php if (!empty($employees)): ?>
    <?php foreach ($employees as $emp): ?>
      <tr>
        <td><?= htmlspecialchars($emp['id']) ?></td>
        <td><?= htmlspecialchars($emp['first_name']) ?></td>
        <td><?= htmlspecialchars($emp['last_name']) ?></td>
        <td><?= htmlspecialchars($emp['office']) ?></td>
        <td>
          <button class="update-btn" onclick="openUpdateModal(<?= $emp['id'] ?>, '<?= addslashes($emp['first_name']) ?>', '<?= addslashes($emp['last_name']) ?>', '<?= addslashes($emp['office']) ?>')">Update</button>
          <form method="POST" action="deleteEmployee" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this employee?');">
            <input type="hidden" name="id" value="<?= $emp['id'] ?>">
            <button type="submit" class="delete-btn">Delete</button>
        </form>

        </td>
      </tr>
    <?php endforeach; ?>
  <?php else: ?>
    <tr>
      <td colspan="5">No employees found.</td>
    </tr>
  <?php endif; ?>
</tbody>
  </table>
  <!-- Add -->
  <div id="addEmployeeModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Add New Employee</h3>
        <span class="close">&times;</span>
      </div>
      <form id="addEmployeeForm" method="POST" action="addEmployee">
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

  <!-- Update -->
  <div id="updateEmployeeModal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <h3>Update Employee</h3>
        <span class="close-update">&times;</span>
      </div>
       <form id="updateEmployeeForm" method="POST" action="updateEmployee">
        <input type="hidden" id="updateEmployeeId" name="id">
        <label for="fn">First Name:</label>
        <input type="text" id="updateFirstName" name="first_name" placeholder="First Name" required><br>
        <label for="ln">Last Name:</label>
        <input type="text" id="updateLastName" name="last_name" placeholder="Last Name" required><br>
        <label for="assigned">Office:</label>
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
    const addModal = document.getElementById('addEmployeeModal');
    const updateModal = document.getElementById('updateEmployeeModal');
    const openModalBtn = document.getElementById('openAddEmployeeModal');
    const closeAddBtn = document.querySelector('.close');
    const closeUpdateBtn = document.querySelector('.close-update');
    const cancelAddBtn = document.getElementById('cancelAddEmployee');
    const cancelUpdateBtn = document.getElementById('cancelUpdateEmployee');
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

     function openUpdateModal(id, firstName, lastName, office) {
      document.getElementById('updateEmployeeId').value = id;
      document.getElementById('updateFirstName').value = firstName;
      document.getElementById('updateLastName').value = lastName;
      document.getElementById('updateOffice').value = office;
      updateModal.style.display = 'block';
    }

  </script>

  
</body>
</html>