<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employee_id = $_POST["employee_id"];
    $location_id = $_POST["location_id"];

    $sql = "INSERT INTO assignments (employee_id, location_id, status) VALUES (?, ?, TRUE)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $employee_id, $location_id);

    if ($stmt->execute()) {
        $assignment_id = $stmt->insert_id;

        // Update employee assignment_id
        $sql_update = "UPDATE employees SET assignment_id = ? WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ii", $assignment_id, $employee_id);
        $stmt_update->execute();
        $stmt_update->close();

        echo "Employee assigned successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Assign Employee</title>
</head>
<body>
    <h2>Assign Employee to Branch</h2>
    <form method="post" action="assign.php">
        <label for="employee_id">Employee ID:</label><br>
        <input type="number" id="employee_id" name="employee_id" required><br>
        <label for="location_id">Location ID:</label><br>
        <input type="number" id="location_id" name="location_id" required><br><br>
        <input type="submit" value="Assign">
    </form>
</body>
</html>
