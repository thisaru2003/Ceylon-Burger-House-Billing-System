<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel - Staff Management</title>
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
    <div class="Mtext"><b>Manage Staff</b></div>
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
    <tbody id="staff-table-body">
      <!-- Staff rows will be dynamically inserted here -->
    </tbody>
  </table>
  </div>
  <a href="ContentAdmin" class="back-btn">Back</a>
  <script>
    // Fetch staff details from the database and populate the table
    fetch('get_staff.php')
      .then(response => response.json())
      .then(data => {
        const staffTableBody = document.getElementById('staff-table-body');
        data.forEach(staff => {
          const statusText = staff.is_verified == 1 ? 'Active' : 'Inactive';
          const statusClass = staff.is_verified == 1 ? 'status-active' : 'status-inactive';
          staffTableBody.innerHTML += `
            <tr>
              <td>${staff.id}</td>
              <td>${staff.full_name}</td>
              <td>${staff.username}</td>
              <td>${staff.email}</td>
              <td>${staff.phone}</td>
              <td>${staff.address}</td>
              <td class="${statusClass}">${statusText}</td>
              <td>
                <button onclick="toggleStatus(${staff.id}, ${staff.is_verified})">
                  ${staff.is_verified == 1 ? 'Deactivate' : 'Activate'}
                </button>
                <button onclick="editStaff(${staff.id})">Edit</button>
              </td>
            </tr>
          `;
        });
      });

    function toggleStatus(staffId, currentStatus) {
      const newStatus = currentStatus == 1 ? 0 : 1;
      fetch('update_staff_status.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: staffId, is_verified: newStatus })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          location.reload(); 
        } else {
          alert('Failed to update staff status');
        }
      });
    }

    function editStaff(staffId) {
      window.location.href = `edit_staff.php?id=${staffId}`;
    }
  </script>
</body>
</html>
