<?php
session_start();
if (!isset($_SESSION["employee_id"]) || $_SESSION["role_id"] != 1) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["assign_branch"])) {
    $employee_id = $_POST["employee_id"];
    $branch_id = $_POST["branch_id"];

    // Update the employee's branch_id in the database
    $sql = "UPDATE employees SET branch_id = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $branch_id, $employee_id);

    if ($stmt->execute()) {
        $message = "Branch assigned successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch employees
$sql = "SELECT id, name FROM employees";
$employees_result = $conn->query($sql);

// Fetch branches including locations
$sql = "SELECT id, name, location FROM branches";
$branches_result = $conn->query($sql);

if ($employees_result === false || $branches_result === false) {
    die("Error: " . $conn->error);
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Assign Branches to Employees</title>
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
            background-color: #f4f4f4;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        select, input[type="submit"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
        }
        input[type="submit"] {
            background-color: #5cb85c;
            border: none;
            color: white;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #4cae4c;
        }
        .message {
            color: green;
            text-align: center;
            margin-bottom: 20px;
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
        <h1>Assign Branches to Employees</h1>
        
        <!-- Branch Assignment Form -->
        <form method="post" action="assign_branches.php">
            <h2>Assign Branch</h2>
            <?php if ($message): ?>
                <p class="message"><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>
            <div class="form-group">
                <label for="employee_id">Employee:</label>
                <select id="employee_id" name="employee_id" required>
                    <option value="">Select Employee</option>
                    <?php while ($row = $employees_result->fetch_assoc()): ?>
                        <option value="<?php echo htmlspecialchars($row['id']); ?>"><?php echo htmlspecialchars($row['name']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="branch_id">Branch:</label>
                <select id="branch_id" name="branch_id" required>
                    <option value="">Select Branch</option>
                    <?php while ($row = $branches_result->fetch_assoc()): ?>
                        <option value="<?php echo htmlspecialchars($row['id']); ?>">
                            <?php echo htmlspecialchars($row['name']); ?> - <?php echo htmlspecialchars($row['location']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <input type="submit" name="assign_branch" value="Assign Branch">
        </form>
    </div>
    <footer>
        &copy; <?php echo date("Y"); ?> Sameer Chhetri. All rights reserved.
    </footer>
</body>
</html>
