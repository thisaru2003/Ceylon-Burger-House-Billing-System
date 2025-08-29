<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel - User Management</title>
  <link rel="stylesheet" href="../CSS/UserUpdate.css">
</head>
<body>
<?php
session_start(); 

if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') {
        include('AdminHeader.php');
    } elseif ($_SESSION['role'] === 'staff') {
        include('StaffHeader.php');
    } else {
        include('Header.php');
    }
} else {
    include('Header.php'); 
}
?>
    <div class="Mtext"><b>Manage Admins</b></div>
    <div class="container">
    
    
    <table id="reservations-table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Full Name</th>
        <th>Username</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Address</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody id="user-table-body">
      <!-- User rows will be dynamically inserted here -->
    </tbody>
  </table>
</div>
  <a href="ContentAdmin" class="back-btn">Back</a>
  <script>
    // Fetch users from the database and populate the table
    fetch('get_users.php')
      .then(response => response.json())
      .then(data => {
        const userTableBody = document.getElementById('user-table-body');
        data.forEach(user => {
          const statusText = user.is_verified == 1 ? 'Active' : 'Inactive';
          const statusClass = user.is_verified == 1 ? 'status-active' : 'status-inactive';
          userTableBody.innerHTML += `
            <tr>
              <td>${user.id}</td>
              <td>${user.full_name}</td>
              <td>${user.username}</td>
              <td>${user.email}</td>
              <td>${user.phone}</td>
              <td>${user.address}</td>
              <td class="${statusClass}">${statusText}</td>
              <td>
                <button onclick="toggleStatus(${user.id}, ${user.is_verified})">
                  ${user.is_verified == 1 ? 'Deactivate' : 'Activate'}
                </button>
                <button onclick="editUser(${user.id})">Edit</button>
              </td>
            </tr>
          `;
        });
      });

    function toggleStatus(userId, currentStatus) {
      const newStatus = currentStatus == 1 ? 0 : 1;
      fetch('update_user_status.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: userId, is_verified: newStatus })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          location.reload(); 
        } else {
          alert('Failed to update user status');
        }
      });
    }

    function editUser(userId) {
      window.location.href = `edit_user.php?id=${userId}`;
    }
  </script>
</body>
</html>
