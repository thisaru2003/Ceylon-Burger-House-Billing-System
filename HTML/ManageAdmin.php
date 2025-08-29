<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Manage Admins</title>
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
        <tbody id="admin-table-body">
            <!-- Admin rows will be dynamically inserted here -->
        </tbody>
    </table>
    </div>
    <a href="ContentAdmin" class="back-btn">Back</a>
    <script>
        // Fetch admins from the database and populate the table
        fetch('get_admins.php')
            .then(response => response.json())
            .then(data => {
                const adminTableBody = document.getElementById('admin-table-body');
                data.forEach(admin => {
                    const statusText = admin.is_verified == 1 ? 'Active' : 'Inactive';
                    const statusClass = admin.is_verified == 1 ? 'status-active' : 'status-inactive';
                    adminTableBody.innerHTML += `
                        <tr>
                            <td>${admin.id}</td>
                            <td>${admin.full_name}</td>
                            <td>${admin.username}</td>
                            <td>${admin.email}</td>
                            <td>${admin.phone}</td>
                            <td>${admin.address}</td>
                            <td class="${statusClass}">${statusText}</td>
                            <td>
                                <button onclick="toggleStatus(${admin.id}, ${admin.is_verified})">
                                    ${admin.is_verified == 1 ? 'Deactivate' : 'Activate'}
                                </button>
                                <button onclick="editAdmin(${admin.id})">Edit</button>
                            </td>
                        </tr>
                    `;
                });
            });

        function toggleStatus(adminId, currentStatus) {
            const newStatus = currentStatus == 1 ? 0 : 1;
            fetch('update_admin_status.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: adminId, is_verified: newStatus })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Failed to update admin status');
                }
            });
        }

        function editAdmin(adminId) {
            window.location.href = `edit_admin.php?id=${adminId}`;
        }
    </script>
</body>
</html>
