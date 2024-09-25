<?php
session_start();
if (!isset($_SESSION["employee_id"]) || $_SESSION["role_id"] != 1) {
    header("Location: login.php");
    exit();
}

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee_id = $_POST['employee_id'];
    $name = $_POST['name'];
    $branch_id = $_POST['branch_id'];
    $status = $_POST['status']; // Include the status field

    // Update employee details, including status
    $stmt = $conn->prepare("UPDATE employees SET name = ?, branch_id = ?, status = ? WHERE id = ?");
    $stmt->bind_param("siii", $name, $branch_id, $status, $employee_id);

    if ($stmt->execute()) {
        header("Location: view_users.php?status=updated");
        exit();
    } else {
        echo "Error updating record: " . $stmt->error; // Use $stmt->error for prepared statement errors
    }

    $stmt->close();
}

$conn->close();
?>
