<?php
session_start();
if (!isset($_SESSION["employee_id"]) || $_SESSION["role_id"] != 1) {
    header("Location: login.php");
    exit();
}

include 'db.php';

if (isset($_GET['id'])) {
    $branch_id = $_GET['id'];

    // Check if there are employees associated with this branch
    $checkEmployeesSql = "SELECT COUNT(*) AS employee_count FROM employees WHERE branch_id = ?";
    $stmt = $conn->prepare($checkEmployeesSql);
    $stmt->bind_param("i", $branch_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['employee_count'] > 0) {
        // Optionally update branch_id to NULL or show an error
        echo "Error: This branch has associated employees and cannot be deleted.";
        // Alternatively, update employees to remove branch association
        $updateEmployeesSql = "UPDATE employees SET branch_id = NULL WHERE branch_id = ?";
        $updateStmt = $conn->prepare($updateEmployeesSql);
        $updateStmt->bind_param("i", $branch_id);
        if ($updateStmt->execute()) {
            // Proceed with deletion
            $deleteBranchSql = "DELETE FROM branches WHERE id = ?";
            $deleteStmt = $conn->prepare($deleteBranchSql);
            $deleteStmt->bind_param("i", $branch_id);
            if ($deleteStmt->execute()) {
                header("Location: manage_branches.php");
            } else {
                echo "Error deleting branch: " . $deleteStmt->error;
            }
            $deleteStmt->close();
        } else {
            echo "Error updating employees: " . $updateStmt->error;
        }
        $updateStmt->close();
    } else {
        // No employees, safe to delete branch
        $deleteBranchSql = "DELETE FROM branches WHERE id = ?";
        $stmt = $conn->prepare($deleteBranchSql);
        $stmt->bind_param("i", $branch_id);
        if ($stmt->execute()) {
            header("Location: manage_branches.php");
        } else {
            echo "Error deleting branch: " . $stmt->error;
        }
        $stmt->close();
    }

    $conn->close();
}
?>
