<?php
session_start();
if (!isset($_SESSION["employee_id"]) || $_SESSION["role_id"] != 1) { // Assuming role_id 1 is for admin
    header("Location: login.php");
    exit();
}

include 'db.php';

// Fetch warnings from the database
$sql = "SELECT * FROM warnings ORDER BY date_issued DESC";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Warnings</title>
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
        <h1>Warnings</h1>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Employee ID</th>
                        <th>Issued By</th>
                        <th>Reason</th>
                        <th>Date Issued</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['employee_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['issued_by']); ?></td>
                            <td><?php echo htmlspecialchars($row['reason']); ?></td>
                            <td><?php echo htmlspecialchars($row['date_issued']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No warnings to display.</p>
        <?php endif; ?>
    </div>
    <footer>
        &copy; <?php echo date("Y"); ?> Sameer Chhetri. All rights reserved.
    </footer>
</body>
</html>
