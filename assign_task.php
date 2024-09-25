<?php
session_start();
if (!isset($_SESSION["employee_id"]) || $_SESSION["role_id"] != 1) {
    header("Location: login.php");
    exit();
}

include 'db.php';

// Fetch employees for task assignment
$employees = $conn->query("SELECT id, name FROM employees");

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];
    $assigned_to = $_POST['assigned_to'];
    $assigned_by = $_SESSION['employee_id']; // Admin ID

    // Insert the task into the database
    $stmt = $conn->prepare("INSERT INTO tasks (title, description, due_date, status, assigned_to, assigned_by) VALUES (?, ?, ?, 'Pending', ?, ?)");
    $stmt->bind_param("sssss", $title, $description, $due_date, $assigned_to, $assigned_by);

    if ($stmt->execute()) {
        $message = "Task assigned successfully.";
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Assign Task</title>
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
        form label {
            display: block;
            margin: 10px 0 5px;
        }
        form input, form select, form button {
            padding: 10px;
            width: 100%;
            margin-bottom: 10px;
            box-sizing: border-box;
        }
        form button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        .message {
            margin: 20px 0;
            padding: 10px;
            border-radius: 5px;
            color: white;
            background-color: #4CAF50; /* Green for success */
        }
        .message.error {
            background-color: #f44336; /* Red for error */
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
            <h1>Assign Task</h1>
            <?php if (isset($message)): ?>
                <div class="message <?php echo isset($error) ? 'error' : ''; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            <form method="post">
                <label for="title">Task Title:</label>
                <input type="text" name="title" required>

                <label for="description">Task Description:</label>
                <input type="text" name="description" required>

                <label for="due_date">Due Date:</label>
                <input type="date" name="due_date" required>

                <label for="assigned_to">Assign To:</label>
                <select name="assigned_to" required>
                    <?php while ($employee = $employees->fetch_assoc()): ?>
                        <option value="<?php echo $employee['id']; ?>">
                            <?php echo htmlspecialchars($employee['name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <button type="submit">Assign Task</button>
            </form>
        </div>
    </div>
    <footer>
        &copy; <?php echo date("Y"); ?> Sameer Chhetri. All rights reserved.
    </footer>
</body>
</html>
