<?php
session_start();
if (!isset($_SESSION["employee_id"]) || $_SESSION["role_id"] != 2) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$employee_id = $_SESSION["employee_id"];
$date = date("Y-m-d");

// Check if attendance is already marked for today
$stmt = $conn->prepare("SELECT * FROM attendance WHERE employee_id = ? AND date = ?");
$stmt->bind_param("is", $employee_id, $date);
$stmt->execute();
$attendance = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST" && !$attendance) {
    $status = $_POST["status"];
    
    // Insert attendance record
    $stmt = $conn->prepare("INSERT INTO attendance (employee_id, date, status) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $employee_id, $date, $status);
    
    if ($stmt->execute()) {
        // Attendance marked successfully, redirect to employee dashboard
        $stmt->close();
        $conn->close();
        header("Location: employee_dashboard.php");
        exit();
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Mark Attendance</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 400px;
            margin: 0 auto;
        }
        .message {
            color: green;
            margin-bottom: 20px;
        }
        input[type="submit"] {
            background-color: #5cb85c;
            border: none;
            color: white;
            padding: 15px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Mark Attendance</h1>
        <?php if (isset($message)): ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <?php if ($attendance): ?>
            <p>You have already marked your attendance for today as "<?php echo htmlspecialchars($attendance['status']); ?>"</p>
        <?php else: ?>
            <form method="post">
                <label>
                    <input type="radio" name="status" value="Present" required> Present
                </label><br>
                <label>
                    <input type="radio" name="status" value="Absent" required> Absent
                </label><br>
                <label>
                    <input type="radio" name="status" value="Leave" required> Leave
                </label><br><br>
                <input type="submit" value="Mark Attendance">
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
