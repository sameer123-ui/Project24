<?php
session_start();
if (!isset($_SESSION["employee_id"]) || $_SESSION["role_id"] != 1) {
    header("Location: login.php");
    exit();
}

include 'db.php';
$employees = $conn->query("SELECT * FROM employees");
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Users</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            margin: 0;
            font-family: Arial, sans-serif;
            min-height: 100vh;
        }
        aside {
            width: 250px;
            background: #333;
            color: white;
            min-height: 100vh;
            padding: 20px;
        }
        .main {
            display: flex;
            flex: 1;
        }
        .content {
            flex: 1;
            padding: 20px;
            background-color: #f4f4f4;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        td {
            background-color: #fff;
        }
        .action-buttons a {
            margin-right: 10px;
            color: white;
            background-color: #007bff;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 5px;
        }
        .action-buttons a.delete {
            background-color: #dc3545;
        }
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px;
            position: relative;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="main">
        <aside>
            <?php include 'admin_sidebar.php'; ?>
        </aside>
        <div class="content">
            <h1>View Users</h1>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($employee = $employees->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $employee['id']; ?></td>
                            <td><?php echo htmlspecialchars($employee['name']); ?></td>
                            <td><?php echo htmlspecialchars($employee['email']); ?></td>
                            <td><?php echo htmlspecialchars($employee['role_id'] == 1 ? 'Admin' : 'Employee'); ?></td>
                            <td><?php echo $employee['status'] ? 'Active' : 'Inactive'; ?></td>
                            <td class="action-buttons">
                                <a href="edit_employee.php?id=<?php echo $employee['id']; ?>">Edit</a>
                                <a href="delete_employee.php?id=<?php echo $employee['id']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this employee?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    <footer>
        &copy; <?php echo date("Y"); ?> Sameer Chhetri. All rights reserved.
    </footer>
</body>
</html>
