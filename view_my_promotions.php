<?php
session_start();
if (!isset($_SESSION["employee_id"]) || $_SESSION["role_id"] != 2) { // Ensuring it's an employee
    header("Location: login.php");
    exit();
}

include 'db.php';

$employee_id = $_SESSION["employee_id"];

// Fetch promotions for the logged-in employee
$sql = "SELECT promotion_title, new_position, date_issued, promotion_letter_file 
        FROM promotions 
        WHERE employee_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Promotions</title>
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
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <aside>
        <?php include 'employee_sidebar.php'; ?> <!-- Include the existing employee sidebar -->
    </aside>
    <div class="content">
        <h1>My Promotions</h1>
        <table>
            <tr>
                <th>Promotion Title</th>
                <th>New Position</th>
                <th>Date Issued</th>
                <th>Promotion Letter</th>
            </tr>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['promotion_title']); ?></td>
                        <td><?php echo htmlspecialchars($row['new_position']); ?></td>
                        <td><?php echo htmlspecialchars($row['date_issued']); ?></td>
                        <td>
                            <?php if (!empty($row['promotion_letter_file'])): ?>
                                <a href="uploads/<?php echo htmlspecialchars(basename($row['promotion_letter_file'])); ?>" target="_blank">View Letter</a>
                            <?php else: ?>
                                No letter available
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No promotions found.</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
