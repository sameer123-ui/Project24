<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION["employee_id"]) || $_SESSION["role_id"] != 1) {
    header("Location: login.php");
    exit();
}

include 'db.php'; // Database connection

// Fetch tasks from the database, along with the name of the employee they are assigned to
$query = "SELECT t.id as task_id, t.title, t.description, t.due_date, t.status, e.name as assigned_to_name, a.name as assigned_by_name 
          FROM tasks t
          JOIN employees e ON t.assigned_to = e.id
          JOIN employees a ON t.assigned_by = a.id";

$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin View Tasks</title>
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
            background-color: #333; /* Sidebar background color */
            color: white;
            min-height: 100vh;
            padding: 20px;
            box-sizing: border-box;
        }
        .main {
            display: flex;
            flex: 1;
        }
        .content {
            flex: 1;
            padding: 20px;
            box-sizing: border-box;
            background-color: #f4f4f4; /* Background color for the content area */
        }
        h1 {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .status-pending {
            color: orange;
        }
        .status-in-progress {
            color: blue;
        }
        .status-completed {
            color: green;
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
        <h1>View Assigned Tasks</h1>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Task ID</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Assigned To</th>
                        <th>Assigned By</th>
                        <th>Status</th>
                        <th>Due Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($task = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $task['task_id']; ?></td>
                        <td><?php echo htmlspecialchars($task['title']); ?></td>
                        <td><?php echo htmlspecialchars($task['description']); ?></td>
                        <td><?php echo htmlspecialchars($task['assigned_to_name']); ?></td>
                        <td><?php echo htmlspecialchars($task['assigned_by_name']); ?></td>
                        <td class="<?php echo 'status-' . strtolower($task['status']); ?>">
                            <?php echo ucfirst($task['status']); // Show the current status (chosen by employee) ?>
                        </td>
                        <td><?php echo $task['due_date']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No tasks found.</p>
        <?php endif; ?>
    </div>
</div>

<footer>
    &copy; <?php echo date("Y"); ?> Sameer Chhetri. All rights reserved.
</footer>

</body>
</html>
