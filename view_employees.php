<?php
session_start();
if (!isset($_SESSION["employee_id"]) || $_SESSION["role_id"] != 1) {
    header("Location: login.php");
    exit();
}

include 'db.php';

// Determine the filter from the query parameters
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'total';

// Define the SQL query based on the filter
switch ($filter) {
    case 'yesterday':
        $sql = "SELECT id, name, email, status, created_at FROM employees WHERE DATE(created_at) = CURDATE() - INTERVAL 1 DAY AND status = TRUE";
        break;
    case 'last7days':
        $sql = "SELECT id, name, email, status, created_at FROM employees WHERE DATE(created_at) >= CURDATE() - INTERVAL 7 DAY AND status = TRUE";
        break;
    case 'last4weeks':
        $sql = "SELECT id, name, email, status, created_at FROM employees WHERE DATE(created_at) >= CURDATE() - INTERVAL 4 WEEK AND status = TRUE";
        break;
    case 'last6months':
        $sql = "SELECT id, name, email, status, created_at FROM employees WHERE DATE(created_at) >= CURDATE() - INTERVAL 6 MONTH AND status = TRUE";
        break;
    case 'last1year':
        $sql = "SELECT id, name, email, status, created_at FROM employees WHERE DATE(created_at) >= CURDATE() - INTERVAL 1 YEAR AND status = TRUE";
        break;
    case 'total':
    default:
        $sql = "SELECT id, name, email, status, created_at FROM employees WHERE status = TRUE";
        break;
}

$result = $conn->query($sql);
$employees = $result->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Employees</title>
    <style>
        body {
            display: flex;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        aside {
            width: 250px;
            background: #333;
            color: white;
            min-height: 100vh;
            padding: 20px;
        }
        .content {
            flex: 1;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        footer {
            background: #333;
            color: white;
            padding: 10px;
            text-align: center;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <aside>
        <?php include 'admin_sidebar.php'; ?>
    </aside>
    <div class="content">
        <h1>View Employees</h1>
        <p>Showing details for employees <?php echo $filter == 'total' ? 'overall' : 'registered ' . $filter; ?>.</p>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Registered Date</th> <!-- Added Registered Date header -->
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($employees)): ?>
                    <?php foreach ($employees as $employee): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($employee['id']); ?></td>
                            <td><?php echo htmlspecialchars($employee['name']); ?></td>
                            <td><?php echo htmlspecialchars($employee['email']); ?></td>
                            <td><?php echo $employee['status'] ? 'Active' : 'Inactive'; ?></td>
                            <td><?php echo htmlspecialchars(date('Y-m-d', strtotime($employee['created_at']))); ?></td> <!-- Added Registered Date data -->
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No employees found.</td> <!-- Adjust colspan to 5 -->
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <footer>
        &copy; <?php echo date("Y"); ?> Sameer Chhetri. All rights reserved.
    </footer>
</body>
</html>
