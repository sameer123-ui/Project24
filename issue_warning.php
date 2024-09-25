<?php
session_start();
include 'db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if user is logged in and is an admin
if (!isset($_SESSION["employee_id"]) || $_SESSION["role_id"] != 1) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employee_id = $_POST["employee_id"];
    $issued_by = $_SESSION["employee_id"];
    $reason = $_POST["reason"];
    $date_issued = date("Y-m-d");

    // Insert warning
    $stmt = $conn->prepare("INSERT INTO warnings (employee_id, issued_by, reason, date_issued) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $employee_id, $issued_by, $reason, $date_issued);

    if ($stmt->execute()) {
        // Redirect to admin dashboard
        header("Location: admin_dashboard.php");
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
    <title>Issue Warning</title>
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
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        textarea {
            resize: vertical;
            height: 100px;
        }
        input[type="submit"] {
            background-color: #5cb85c;
            border: none;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        input[type="submit"]:hover {
            background-color: #4cae4c;
        }
        .message {
            text-align: center;
            margin-bottom: 20px;
        }
        footer {
            background: #333;
            color: white;
            padding: 10px;
            text-align: center;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <aside>
        <?php include 'admin_sidebar.php'; ?>
    </aside>
    <div class="content">
        <div class="container">
            <h1>Issue Warning</h1>
            <?php if (isset($message)): ?>
                <p class="message"><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>

            <form method="post">
                <div class="form-group">
                    <label for="employee_id">Employee ID:</label>
                    <input type="text" id="employee_id" name="employee_id" required>
                </div>

                <div class="form-group">
                    <label for="reason">Reason:</label>
                    <textarea id="reason" name="reason" required></textarea>
                </div>

                <input type="submit" value="Issue Warning">
            </form>
        </div>
    </div>
    <footer>
        &copy; <?php echo date("Y"); ?> Sameer Chhetri. All rights reserved.
    </footer>
</body>
</html>
