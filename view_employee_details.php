<?php
//session_start();
//if (!isset($_SESSION["admin_id"]) || $_SESSION["role_id"] != 1) {
//    header("Location: login.php");
//    exit();
//}

include 'db.php';

$details = [];

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and retrieve form data
    $employee_id = $conn->real_escape_string($_POST['employee_id']);

    // Query to fetch employee details
    $sql = "SELECT * FROM employee_details WHERE employee_id = '$employee_id'";
    $result = $conn->query($sql);

    if ($result) {
        if ($result->num_rows > 0) {
            $details = $result->fetch_assoc();
        } else {
            $details = [
                'address' => 'N/A',
                'citizenship' => 'N/A',
                'national_id' => 'N/A',
                'educational_qualification' => 'N/A',
                'personal_info' => 'N/A',
                'job_experience' => 'N/A',
                'training_info' => 'N/A',
                'award_info' => 'N/A',
                'skill_info' => 'N/A',
                'feedback' => 'N/A'
            ];
        }
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Employee Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            margin: 0;
        }
        aside {
            width: 250px;
            background-color: #333;
            color: white;
            min-height: 100vh;
            padding: 20px;
            box-sizing: border-box;
        }
        aside a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            display: block;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        aside a:hover {
            background-color: #575757;
        }
        main {
            flex: 1;
            padding: 20px;
            box-sizing: border-box;
        }
        h1, h2 {
            color: #333;
        }
        form, .details {
            margin-bottom: 20px;
        }
        .details p {
            margin: 10px 0;
        }
        .details label {
            font-weight: bold;
        }
        .actions a {
            margin-right: 10px;
            color: #007bff;
            text-decoration: none;
        }
        .actions a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <aside>
        <nav>
            <ul style="list-style: none; padding: 0; margin: 0;">
                <li style="margin-bottom: 15px;">
                    <a href="admin_dashboard.php">Home</a>
                </li>
                <li style="margin-bottom: 15px;">
                    <a href="manage_branches.php">Manage Branches</a>
                </li>
                <li style="margin-bottom: 15px;">
                    <a href="assign_branches.php">Assign Branches</a>
                </li>
                <li style="margin-bottom: 15px;">
                    <a href="view_users.php">View Users</a>
                </li>
                <li style="margin-bottom: 15px;">
                    <a href="assign_task.php">Assign Tasks</a>
                </li>
                <li style="margin-bottom: 15px;">
                    <a href="view_attendance.php">View Attendance</a>
                </li>
                <!-- New Link for Adding Employee Details -->
                <li style="margin-bottom: 15px;">
                    <a href="add_employee_details.php">Add Employee Details</a>
                </li>
                <!-- Link for Viewing Warnings -->
                <li style="margin-bottom: 15px;">
                    <a href="view_warnings.php">View Warnings</a>
                </li>
                <!-- Link for Issuing Warnings -->
                <li style="margin-bottom: 15px;">
                    <a href="issue_warning.php">Issue Warning</a>
                </li>
                <!-- New Link for Viewing Promotions -->
                <li style="margin-bottom: 15px;">
                    <a href="view_promotions.php">View Promotions</a>
                </li>
                <!-- New Link for Issuing Promotions -->
                <li style="margin-bottom: 15px;">
                    <a href="issue_promotion.php">Issue Promotion</a>
                </li>
                <li style="margin-top: auto;">
                    <a href="logout.php">Logout</a>
                </li>
            </ul>
        </nav>
    </aside>
    <main>
        <h1>View Employee Details</h1>
        <form method="POST" action="view_employee_details.php">
            <label for="employee">Select Employee:</label>
            <select name="employee_id" id="employee" required>
                <option value="">--Select Employee--</option>
                <?php
                // Fetch employees for the dropdown
                include 'db.php';
                $sql = "SELECT id, name FROM employees";
                $result = $conn->query($sql);

                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        $selected = (isset($_POST['employee_id']) && $row['id'] == $_POST['employee_id']) ? 'selected' : '';
                        echo "<option value='" . htmlspecialchars($row['id']) . "' $selected>" . htmlspecialchars($row['name']) . "</option>";
                    }
                }
                ?>
            </select>
            <input type="submit" value="View Details">
        </form>

        <div class="details">
            <h2>Employee Details</h2>
            <p><strong>Address:</strong> <?php echo isset($details['address']) ? htmlspecialchars($details['address']) : 'N/A'; ?></p>
            <p><strong>Citizenship:</strong> <?php echo isset($details['citizenship']) ? htmlspecialchars($details['citizenship']) : 'N/A'; ?></p>
            <p><strong>National ID Card Number:</strong> <?php echo isset($details['national_id']) ? htmlspecialchars($details['national_id']) : 'N/A'; ?></p>
            <p><strong>Educational Qualification:</strong> <?php echo isset($details['educational_qualification']) ? htmlspecialchars($details['educational_qualification']) : 'N/A'; ?></p>
            <p><strong>Personal Information:</strong> <?php echo isset($details['personal_info']) ? htmlspecialchars($details['personal_info']) : 'N/A'; ?></p>
            <p><strong>Job Experience:</strong> <?php echo isset($details['job_experience']) ? htmlspecialchars($details['job_experience']) : 'N/A'; ?></p>
            <p><strong>Training Information:</strong> <?php echo isset($details['training_info']) ? htmlspecialchars($details['training_info']) : 'N/A'; ?></p>
            <p><strong>Award Information:</strong> <?php echo isset($details['award_info']) ? htmlspecialchars($details['award_info']) : 'N/A'; ?></p>
            <p><strong>Skill Information:</strong> <?php echo isset($details['skill_info']) ? htmlspecialchars($details['skill_info']) : 'N/A'; ?></p>
            <p><strong>Feedback:</strong> <?php echo isset($details['feedback']) ? htmlspecialchars($details['feedback']) : 'N/A'; ?></p>
            
            <!-- Action Links -->
            <div class="actions">
                <a href="edit_employee_details.php?employee_id=<?php echo htmlspecialchars($employee_id); ?>">Edit</a>
                
            </div>
        </div>
    </main>
</body>
</html>
