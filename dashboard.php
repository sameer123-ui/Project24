<?php
session_start();
if (!isset($_SESSION["employee_id"])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$employee_id = $_SESSION["employee_id"];

$sql = "SELECT name, email FROM employees WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$stmt->bind_result($name, $email);
$stmt->fetch();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($name); ?></h2>
    <p>Email: <?php echo htmlspecialchars($email); ?></p>
    <p><a href="logout.php">Logout</a></p>
</body>
</html>
