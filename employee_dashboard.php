<?php
session_start();
if (!isset($_SESSION["employee_id"]) || $_SESSION["role_id"] != 2) { // Assuming role_id 2 is for employees
    header("Location: login.php");
    exit();
}

include 'db.php';

// Fetch employee information including the profile photo and branch details
$employee_id = $_SESSION["employee_id"];
$sql = "SELECT employees.name AS employee_name, employees.profile_photo, branches.name AS branch_name, branches.location 
        FROM employees 
        LEFT JOIN branches ON employees.branch_id = branches.id 
        WHERE employees.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();
$employee_data = $result->fetch_assoc();

// Fetch employee warnings
$warning_sql = "SELECT reason, date_issued FROM warnings WHERE employee_id = ?";
$warning_stmt = $conn->prepare($warning_sql);
$warning_stmt->bind_param("i", $employee_id);
$warning_stmt->execute();
$warnings = $warning_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$warning_stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Employee Dashboard</title>
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
        h1 {
            margin-bottom: 20px;
        }
        .info {
            margin-bottom: 20px;
        }
        .info label {
            font-weight: bold;
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
        .info-table {
            margin-top: 20px;
        }
        .info-table th, .info-table td {
            border: 1px solid #ddd;
            padding: 10px;
        }
        .profile-photo {
            margin-bottom: 20px;
        }
        .profile-photo img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
        }
        .warning-section {
            margin-top: 20px;
        }
        .warning-section h2 {
            margin-bottom: 10px;
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
        <?php include 'employee_sidebar.php'; ?> <!-- Include the existing sidebar -->
    </aside>
    <div class="content">
        <h1>Employee Dashboard</h1>
        <p>Welcome, <?php echo htmlspecialchars($employee_data['employee_name']); ?>!</p>

        <!-- Display Profile Photo Section -->
        <div class="profile-photo">
            <label>Profile Photo:</label><br>
            <?php if (!empty($employee_data['profile_photo'])): ?>
                <img src="<?php echo htmlspecialchars($employee_data['profile_photo']); ?>" alt="Profile Photo">
            <?php else: ?>
                <p>No profile photo uploaded.</p>
            <?php endif; ?>

            <!-- Form for uploading a profile photo -->
            <form action="upload_photo.php" method="post" enctype="multipart/form-data">
                <label for="profile_photo">Upload new photo:</label>
                <input type="file" name="profile_photo" id="profile_photo">
                <input type="submit" value="Upload Photo">
            </form>
        </div>

        <!-- Branch Details Section -->
        <div class="info">
            <label>Branch Details:</label>
            <table class="info-table">
                <thead>
                    <tr>
                        <th>Branch Name</th>
                        <th>Location</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo htmlspecialchars($employee_data['branch_name']); ?></td>
                        <td><?php echo htmlspecialchars($employee_data['location']); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Warning Section -->
        <div class="warning-section">
            <h2>Your Warnings</h2>
            <?php if (count($warnings) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Reason</th>
                            <th>Date Issued</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($warnings as $warning): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($warning['reason']); ?></td>
                                <td><?php echo htmlspecialchars($warning['date_issued']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No warnings issued.</p>
            <?php endif; ?>
        </div>
    </div>
    <footer>
        &copy; <?php echo date("Y"); ?> Sameer Chhetri. All rights reserved.
    </footer>
</body>
</html>
