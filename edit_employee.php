<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["employee_id"]) || $_SESSION["role_id"] != 1) {
    header("Location: login.php");
    exit();
}

include 'db.php';

if (isset($_GET['id'])) {
    $employee_id = $_GET['id'];

    // Fetch employee details
    $stmt = $conn->prepare("SELECT * FROM employees WHERE id = ?");
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $employee = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    // Fetch branches with their locations
    $branches = $conn->query("SELECT id, name, location FROM branches");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Employee</title>
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
            background-color: #f4f4f4;
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
            <?php include 'admin_sidebar.php'; ?> <!-- Include the admin sidebar -->
        </aside>
        <div class="content">
            <h1>Edit Employee</h1>
            <form action="update_employee.php" method="post">
                <input type="hidden" name="employee_id" value="<?php echo $employee_id; ?>">

                <label for="name">Name:</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($employee['name']); ?>" required><br>

                <label for="email">Email:</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($employee['email']); ?>" required><br>

                <label for="branch_id">Assign Branch:</label>
                <select name="branch_id" required>
                    <?php while ($branch = $branches->fetch_assoc()): ?>
                        <option value="<?php echo $branch['id']; ?>" <?php if ($branch['id'] == $employee['branch_id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($branch['name']) . " - " . htmlspecialchars($branch['location']); ?>
                        </option>
                    <?php endwhile; ?>
                </select><br>

                <label for="status">Status:</label>
                <select name="status" required>
                    <option value="1" <?php if ($employee['status'] == 1) echo 'selected'; ?>>Active</option>
                    <option value="0" <?php if ($employee['status'] == 0) echo 'selected'; ?>>Inactive</option>
                </select><br>

                <button type="submit">Update Employee</button>
            </form>
        </div>
    </div>
    <footer>
        &copy; <?php echo date("Y"); ?> Sameer Chhetri. All rights reserved.
    </footer>
</body>
</html>
