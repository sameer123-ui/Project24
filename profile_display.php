<?php
// Fetch employee data including profile photo
$employee_id = $_SESSION["employee_id"];
$sql = "SELECT name, profile_photo FROM employees WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$employee_data = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Display the profile photo
if (!empty($employee_data['profile_photo'])) {
    echo "<img src='" . htmlspecialchars($employee_data['profile_photo']) . "' alt='Profile Photo' width='100' height='100'>";
} else {
    echo "<p>No profile photo uploaded.</p>";
}
?>
