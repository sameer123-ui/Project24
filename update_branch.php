<?php
session_start();
if (!isset($_SESSION["employee_id"]) || $_SESSION["role_id"] != 1) {
    header("Location: login.php");
    exit();
}

include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $branch_id = $_POST['branch_id'];
    $name = $_POST['name'];
    $location = $_POST['location'];
    $status = isset($_POST['status']) ? 1 : 0;

    // Update branch in the database
    $stmt = $conn->prepare("UPDATE branches SET name = ?, location = ?, status = ? WHERE id = ?");
    $stmt->bind_param("ssii", $name, $location, $status, $branch_id);

    if ($stmt->execute()) {
        header("Location: manage_branches.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
