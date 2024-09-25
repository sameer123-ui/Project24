<?php
session_start();
if (!isset($_SESSION["employee_id"]) || $_SESSION["role_id"] != 1) {
    header("Location: login.php");
    exit();
}

include 'db.php';

// Fetch total registered employees
$sql_total = "SELECT COUNT(*) AS total FROM employees WHERE status = TRUE";

// Fetch employees registered yesterday
$sql_yesterday = "SELECT COUNT(*) AS total FROM employees WHERE DATE(created_at) = CURDATE() - INTERVAL 1 DAY AND status = TRUE";

// Fetch employees registered in the last 7 days
$sql_last7days = "SELECT COUNT(*) AS total FROM employees WHERE DATE(created_at) >= CURDATE() - INTERVAL 7 DAY AND status = TRUE";

// Fetch employees registered in the last 4 weeks
$sql_last4weeks = "SELECT COUNT(*) AS total FROM employees WHERE DATE(created_at) >= CURDATE() - INTERVAL 4 WEEK AND status = TRUE";

// Fetch employees registered in the last 6 months
$sql_last6months = "SELECT COUNT(*) AS total FROM employees WHERE DATE(created_at) >= CURDATE() - INTERVAL 6 MONTH AND status = TRUE";

// Fetch employees registered in the last 1 year
$sql_last1year = "SELECT COUNT(*) AS total FROM employees WHERE DATE(created_at) >= CURDATE() - INTERVAL 1 YEAR AND status = TRUE";

$total_result = $conn->query($sql_total);
$yesterday_result = $conn->query($sql_yesterday);
$last7days_result = $conn->query($sql_last7days);
$last4weeks_result = $conn->query($sql_last4weeks);
$last6months_result = $conn->query($sql_last6months);
$last1year_result = $conn->query($sql_last1year);

$total_employees = $total_result->fetch_assoc()['total'];
$employees_yesterday = $yesterday_result->fetch_assoc()['total'];
$employees_last7days = $last7days_result->fetch_assoc()['total'];
$employees_last4weeks = $last4weeks_result->fetch_assoc()['total'];
$employees_last6months = $last6months_result->fetch_assoc()['total'];
$employees_last1year = $last1year_result->fetch_assoc()['total'];

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
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
        .dashboard-stats {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 20px;
        }
        .stat-box {
            flex: 1 1 22%;
            min-width: 200px;
            padding: 30px;
            color: white;
            text-align: center;
            border-radius: 8px;
            font-size: 20px;
            font-weight: bold;
            position: relative;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .stat-box a {
            color: white;
            text-decoration: underline;
            font-size: 16px;
            position: absolute;
            bottom: 10px;
            right: 20px;
        }
        .stat-box.blue {
            background-color: #007bff;
        }
        .stat-box.red {
            background-color: #dc3545;
        }
        .stat-box.green {
            background-color: #28a745;
        }
        .stat-box.yellow {
            background-color: #ffc107;
        }
        .stat-box.orange {
            background-color: #fd7e14;
        }
        .stat-box.black {
            background-color: #343a40;
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
        <h1>Admin Dashboard</h1>
        <p>Welcome, Admin!</p>

        <!-- Dashboard Statistics -->
        <div class="dashboard-stats">
            <div class="stat-box blue">
                Total Registered Employees: <?php echo number_format($total_employees); ?>
                <a href="view_employees.php?filter=total">View Details</a>
            </div>
            <div class="stat-box red">
                Registered Yesterday: <?php echo number_format($employees_yesterday); ?>
                <a href="view_employees.php?filter=yesterday">View Details</a>
            </div>
            <div class="stat-box green">
                Registered in Last 7 Days: <?php echo number_format($employees_last7days); ?>
                <a href="view_employees.php?filter=last7days">View Details</a>
            </div>
            <div class="stat-box yellow">
                Registered in Last 4 Weeks: <?php echo number_format($employees_last4weeks); ?>
                <a href="view_employees.php?filter=last4weeks">View Details</a>
            </div>
            <div class="stat-box orange">
                Registered in Last 6 Months: <?php echo number_format($employees_last6months); ?>
                <a href="view_employees.php?filter=last6months">View Details</a>
            </div>
            <div class="stat-box black">
                Registered in Last 1 Year: <?php echo number_format($employees_last1year); ?>
                <a href="view_employees.php?filter=last1year">View Details</a>
            </div>
        </div>
        
        <!-- Add more admin-specific content here -->
    </div>
    <footer>
        &copy; <?php echo date("Y"); ?> Sameer Chhetri. All rights reserved.
    </footer>
</body>
</html>
