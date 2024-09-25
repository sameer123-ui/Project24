<?php
session_start();
if (!isset($_SESSION["employee_id"]) || $_SESSION["role_id"] != 2) {
    header("Location: login.php");
    exit();
}

include 'db.php';

// Fetch employee information and branch details
$employee_id = $_SESSION["employee_id"];
$sql = "SELECT employees.name, employees.email, employees.status, branches.id AS branch_id, branches.name AS branch_name, branches.location 
        FROM employees 
        LEFT JOIN branches ON employees.branch_id = branches.id 
        WHERE employees.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$employee_data = $stmt->get_result()->fetch_assoc();

// Fetch branches
$branches = $conn->query("SELECT id, name, location FROM branches");

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
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
        form label {
            display: block;
            margin: 10px 0 5px;
        }
        form input, form select, form button {
            padding: 10px;
            width: 100%;
            margin-bottom: 10px;
        }
        form button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <aside>
        <?php include 'employee_sidebar.php'; ?>
    </aside>
    <div class="content">
        <h1>Edit Profile</h1>
        <form action="update_profile.php" method="post">
            <input type="hidden" name="employee_id" value="<?php echo htmlspecialchars($employee_id); ?>">

            <label for="name">Name:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($employee_data['name']); ?>" required><br>

            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($employee_data['email']); ?>" required><br>

            <label for="status">Status:</label>
            <select name="status" required>
                <option value="1" <?php if ($employee_data['status']) echo 'selected'; ?>>Active</option>
                <option value="0" <?php if (!$employee_data['status']) echo 'selected'; ?>>Inactive</option>
            </select><br>

            <label for="branch_id">Assign Branch:</label>
            <select name="branch_id" required>
                <?php while ($branch = $branches->fetch_assoc()): ?>
                    <option value="<?php echo $branch['id']; ?>" <?php if ($branch['id'] == $employee_data['branch_id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($branch['name']) . " - " . htmlspecialchars($branch['location']); ?>
                    </option>
                <?php endwhile; ?>
            </select><br>

            <button type="submit">Update Profile</button>
        </form>
    </div>
</body>
</html>
