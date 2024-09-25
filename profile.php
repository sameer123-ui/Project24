<?php
session_start();
if (!isset($_SESSION["employee_id"]) || $_SESSION["role_id"] != 2) { // Assuming role_id 2 is for employees
    header("Location: login.php");
    exit();
}

include 'db.php';

// Fetch employee information and branch details
$employee_id = $_SESSION["employee_id"];
$sql = "SELECT employees.name, employees.email, employees.status, branches.name AS branch_name, branches.location 
        FROM employees 
        LEFT JOIN branches ON employees.branch_id = branches.id 
        WHERE employees.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();
$employee_data = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profile</title>
    <style>
        body {
            display: flex;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        aside {
            width: 250px;
            background-color: #333; /* Original color */
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
        .info {
            margin-bottom: 20px;
        }
        .info label {
            font-weight: bold;
        }
        .info p {
            margin: 5px 0;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .info-table th, .info-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .info-table th {
            background-color: #f4f4f4;
        }
        .info-table td {
            background-color: #fff;
        }
        .edit-profile {
            margin-top: 20px;
        }
        .edit-profile a {
            color: #fff;
            text-decoration: none;
            font-size: 18px;
            background-color: #007bff; /* Original button color */
            border: 1px solid #007bff; /* Original border color */
            padding: 10px;
            border-radius: 5px;
            display: inline-block;
        }
        .edit-profile a:hover {
            background-color: #0056b3; /* Original hover color */
            color: #fff;
        }
    </style>
</head>
<body>
    <aside>
        <?php include 'employee_sidebar.php'; ?> <!-- Include the existing sidebar -->
    </aside>
    <div class="content">
        <h1>Profile</h1>
        <div class="info">
            <label>Name:</label>
            <p><?php echo htmlspecialchars($employee_data['name']); ?></p>

            <label>Email:</label>
            <p><?php echo htmlspecialchars($employee_data['email']); ?></p>

            <label>Status:</label>
            <p><?php echo $employee_data['status'] ? 'Active' : 'Inactive'; ?></p>

            <label>Branch:</label>
            <p><?php echo htmlspecialchars($employee_data['branch_name']); ?> - <?php echo htmlspecialchars($employee_data['location']); ?></p>
        </div>

        <div class="edit-profile">
            <a href="edit_profile.php">Edit Profile</a>
        </div>
    </div>
</body>
</html>
