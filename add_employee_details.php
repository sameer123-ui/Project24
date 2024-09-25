<?php
//session_start();

// Check if admin is logged in and has the correct role
//if (!isset($_SESSION["admin_id"]) || $_SESSION["role_id"] != 1) {
    //header("Location: login.php");
    //exit();
//}

include 'db.php';

// Check if database connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch employees for the dropdown
$sql = "SELECT id, name FROM employees";
$result = $conn->query($sql);

// Check if query is successful
if (!$result) {
    die("Query failed: " . $conn->error);
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Employee Details</title>
    <style>
        body {
            display: flex;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        aside {
            width: 250px;
            background-color: #333;
            color: white;
            min-height: 100vh;
            padding: 20px;
            box-sizing: border-box;
        }
        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        nav ul li {
            margin-bottom: 15px;
        }
        nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            display: block;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        nav ul li a:hover {
            background-color: #555;
        }
        .container {
            flex: 1;
            padding: 20px;
        }
        .container h1 {
            margin-top: 0;
        }
        .container form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .container label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .container input[type="text"], .container textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
        }
        .container input[type="submit"] {
            background-color: #5cb85c;
            border: none;
            color: white;
            padding: 15px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        .container input[type="submit"]:hover {
            background-color: #4cae4c;
        }
    </style>
</head>
<body>
    <aside>
        <nav>
            <ul>
                <li><a href="admin_dashboard.php">Home</a></li>
                <li><a href="manage_branches.php">Manage Branches</a></li>
                <li><a href="assign_branches.php">Assign Branches</a></li>
                <li><a href="view_users.php">View Users</a></li>
                <li><a href="assign_task.php">Assign Tasks</a></li>
                <li><a href="view_attendance.php">View Attendance</a></li>
                <li><a href="view_warnings.php">View Warnings</a></li>
                <li><a href="issue_warning.php">Issue Warning</a></li>
                <li><a href="view_promotions.php">View Promotions</a></li>
                <li><a href="issue_promotion.php">Issue Promotion</a></li>
                <li><a href="add_employee_details.php">Add Employee Details</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </aside>
    <div class="container">
        <h1>Add Employee Details</h1>
        <form action="process_employee_details.php" method="POST">
            <!-- Select Employee -->
            <label for="employee">Select Employee:</label>
            <select name="employee_id" id="employee" required>
                <option value="">--Select Employee--</option>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($row['id']); ?>"><?php echo htmlspecialchars($row['name']); ?></option>
                <?php endwhile; ?>
            </select>

            <!-- Address -->
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>

            <!-- Citizenship -->
            <label for="citizenship">Citizenship:</label>
            <input type="text" id="citizenship" name="citizenship" required>

            <!-- National ID Card Number -->
            <label for="nid">National ID Card Number:</label>
            <input type="text" id="nid" name="nid" required>

            <!-- Educational Qualification -->
            <label for="education">Educational Qualification:</label>
            <input type="text" id="education" name="education" required>

            <!-- Personal Information -->
            <label for="personal_info">Personal Information:</label>
            <textarea id="personal_info" name="personal_info" required></textarea>

            <!-- Job Experience -->
            <label for="experience">Job Experience:</label>
            <textarea id="experience" name="experience" required></textarea>

            <!-- Training Information -->
            <label for="training">Training Information:</label>
            <textarea id="training" name="training" required></textarea>

            <!-- Award Information -->
            <label for="awards">Award Information:</label>
            <textarea id="awards" name="awards" required></textarea>

            <!-- Skill Information -->
            <label for="skills">Skill Information:</label>
            <textarea id="skills" name="skills" required></textarea>

            <!-- Appreciation or Depreciation -->
            <label for="feedback">Appreciation/Depreciation:</label>
            <textarea id="feedback" name="feedback" required></textarea>

            <!-- Submit Button -->
            <input type="submit" value="Submit">
        </form>
    </div>
</body>
</html>
