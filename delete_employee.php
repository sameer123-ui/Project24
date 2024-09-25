<?php
session_start();
if (!isset($_SESSION["employee_id"]) || $_SESSION["role_id"] != 1) {
    header("Location: login.php");
    exit();
}

include 'db.php';

if (isset($_GET['id'])) {
    $employee_id = $_GET['id'];

    // Delete attendance records for the employee
    $stmt = $conn->prepare("DELETE FROM attendance WHERE employee_id = ?");
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $stmt->close();

    // Delete any assignments related to this employee
    $stmt = $conn->prepare("DELETE FROM assignments WHERE employee_id = ?");
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $stmt->close();

    // Delete any password reset records related to this employee
    $stmt = $conn->prepare("DELETE FROM password_resets WHERE employee_id = ?");
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $stmt->close();

    // Delete any promotions issued by this employee
    $stmt = $conn->prepare("DELETE FROM promotions WHERE issued_by = ?");
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $stmt->close();

    // Delete any promotions received by this employee
    $stmt = $conn->prepare("DELETE FROM promotions WHERE employee_id = ?");
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $stmt->close();

    // Delete any warnings issued by this employee
    $stmt = $conn->prepare("DELETE FROM warnings WHERE issued_by = ?");
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $stmt->close();

    // Delete any warnings related to this employee
    $stmt = $conn->prepare("DELETE FROM warnings WHERE employee_id = ?");
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $stmt->close();

    // Delete any employee details related to this employee
    $stmt = $conn->prepare("DELETE FROM employee_details WHERE employee_id = ?");
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $stmt->close();

    // Now delete the employee
    $stmt = $conn->prepare("DELETE FROM employees WHERE id = ?");
    $stmt->bind_param("i", $employee_id);

    if ($stmt->execute()) {
        header("Location: view_users.php?status=deleted");
    } else {
        echo "Error deleting record: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
