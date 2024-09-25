<?php
session_start();
if (!isset($_SESSION["employee_id"]) || $_SESSION["role_id"] != 2) {
    header("Location: login.php");
    exit();
}

include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employee_id = $_POST['employee_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $status = $_POST['status'];
    $branch_id = $_POST['branch_id'];

    // Update employee information
    $sql = "UPDATE employees SET name = ?, email = ?, status = ?, branch_id = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiii", $name, $email, $status, $branch_id, $employee_id);

    if ($stmt->execute()) {
        header("Location: profile.php?success=1");
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: profile.php");
}
