<?php
session_start();
if (!isset($_SESSION["employee_id"]) || $_SESSION["role_id"] != 2) { // Ensure only employees can access this page
    header("Location: login.php");
    exit();
}

include 'db.php';

$employee_id = $_SESSION["employee_id"];

// Handle task status update form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task_id']) && isset($_POST['status'])) {
    $task_id = $_POST['task_id'];
    $status = $_POST['status'];
    
    // Update the task status in the database
    $update_sql = "UPDATE tasks SET status = ? WHERE id = ? AND assigned_to = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sii", $status, $task_id, $employee_id);
    
    if ($update_stmt->execute()) {
        $success_message = "Task status updated successfully!";
    } else {
        $error_message = "Failed to update task status.";
    }
    
    $update_stmt->close();
}

// Fetch tasks assigned to the employee
$sql = "SELECT tasks.id, tasks.title, tasks.description, tasks.due_date, tasks.status 
        FROM tasks 
        WHERE tasks.assigned_to = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$tasks = $stmt->get_result();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tasks</title>
    <style>
        body {
            display: flex;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        aside {
            width: 250px;
            background-color: #333; /* Restored to original color */
            color: white;
            min-height: 100vh;
            padding: 20px;
        }
        .content {
            flex: 1;
            padding: 20px;
        }
        h1 {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
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
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .status-complete {
            color: green;
        }
        .status-pending {
            color: red;
        }
    </style>
</head>
<body>
    <aside>
        <?php include 'employee_sidebar.php'; ?>
    </aside>
    <div class="content">
        <h1>My Tasks</h1>
        
        <!-- Display success or error message -->
        <?php if (!empty($success_message)): ?>
            <p style="color: green;"><?php echo $success_message; ?></p>
        <?php endif; ?>
        <?php if (!empty($error_message)): ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>Task ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Update Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($task = $tasks->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($task['id']); ?></td>
                        <td><?php echo htmlspecialchars($task['title']); ?></td>
                        <td><?php echo htmlspecialchars($task['description']); ?></td>
                        <td><?php echo htmlspecialchars($task['due_date']); ?></td>
                        <td class="<?php echo $task['status'] == 'Complete' ? 'status-complete' : 'status-pending'; ?>">
                            <?php echo htmlspecialchars($task['status']); ?>
                        </td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                <select name="status">
                                    <option value="Pending" <?php if ($task['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                                    <option value="In Progress" <?php if ($task['status'] == 'In Progress') echo 'selected'; ?>>In Progress</option>
                                    <option value="Complete" <?php if ($task['status'] == 'Complete') echo 'selected'; ?>>Complete</option>
                                </select>
                                <button type="submit">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
