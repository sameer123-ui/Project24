<?php
// Start session and check for admin role (if uncommented)
// session_start();
// if (!isset($_SESSION["admin_id"]) || $_SESSION["role_id"] != 1) {
//     header("Location: login.php");
//     exit();
// }

include 'db.php';

$details = [
    'address' => '',
    'citizenship' => '',
    'national_id' => '',
    'educational_qualification' => '',
    'personal_info' => '',
    'job_experience' => '',
    'training_info' => '',
    'award_info' => '',
    'skill_info' => '',
    'feedback' => ''
];

if (isset($_GET['employee_id'])) {
    $employee_id = $conn->real_escape_string($_GET['employee_id']);

    $sql = "SELECT * FROM employee_details WHERE employee_id = '$employee_id'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $details = $result->fetch_assoc();
    } else {
        // Optionally handle the case where no details are found
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and retrieve form data
    $address = $conn->real_escape_string($_POST['address']);
    $citizenship = $conn->real_escape_string($_POST['citizenship']);
    $national_id = $conn->real_escape_string($_POST['national_id']);
    $educational_qualification = $conn->real_escape_string($_POST['educational_qualification']);
    $personal_info = $conn->real_escape_string($_POST['personal_info']);
    $job_experience = $conn->real_escape_string($_POST['job_experience']);
    $training_info = $conn->real_escape_string($_POST['training_info']);
    $award_info = $conn->real_escape_string($_POST['award_info']);
    $skill_info = $conn->real_escape_string($_POST['skill_info']);
    $feedback = $conn->real_escape_string($_POST['feedback']);

    $update_sql = "UPDATE employee_details SET
        address = '$address',
        citizenship = '$citizenship',
        national_id = '$national_id',
        educational_qualification = '$educational_qualification',
        personal_info = '$personal_info',
        job_experience = '$job_experience',
        training_info = '$training_info',
        award_info = '$award_info',
        skill_info = '$skill_info',
        feedback = '$feedback'
        WHERE employee_id = '$employee_id'";

    if ($conn->query($update_sql) === TRUE) {
        // Redirect to the view employee details page
        header("Location: view_employee_details.php?employee_id=$employee_id");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Employee Details</title>
    <style>
        /* Add your styles here */
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            display: flex;
        }
        .sidebar {
            width: 250px;
            background-color: #2C3E50;
            color: white;
            padding: 15px;
        }
        .main-content {
            flex: 1;
            padding: 15px;
        }
        .footer {
            background-color: #2C3E50;
            color: white;
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
        label {
            display: block;
            margin: 5px 0;
        }
        input[type="text"] {
            width: 100%;
            padding: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <?php include 'admin_sidebar.php'; ?>
        </div>
        <div class="main-content">
            <h1>Edit Employee Details</h1>
            <form method="POST" action="edit_employee_details.php?employee_id=<?php echo htmlspecialchars($employee_id); ?>">
                <label for="address">Address:</label>
                <input type="text" name="address" id="address" value="<?php echo htmlspecialchars($details['address']); ?>" required>
                <br>
                <label for="citizenship">Citizenship:</label>
                <input type="text" name="citizenship" id="citizenship" value="<?php echo htmlspecialchars($details['citizenship']); ?>" required>
                <br>
                <label for="national_id">National ID Card Number:</label>
                <input type="text" name="national_id" id="national_id" value="<?php echo htmlspecialchars($details['national_id']); ?>" required>
                <br>
                <label for="educational_qualification">Educational Qualification:</label>
                <input type="text" name="educational_qualification" id="educational_qualification" value="<?php echo htmlspecialchars($details['educational_qualification']); ?>" required>
                <br>
                <label for="personal_info">Personal Information:</label>
                <input type="text" name="personal_info" id="personal_info" value="<?php echo htmlspecialchars($details['personal_info']); ?>" required>
                <br>
                <label for="job_experience">Job Experience:</label>
                <input type="text" name="job_experience" id="job_experience" value="<?php echo htmlspecialchars($details['job_experience']); ?>" required>
                <br>
                <label for="training_info">Training Information:</label>
                <input type="text" name="training_info" id="training_info" value="<?php echo htmlspecialchars($details['training_info']); ?>" required>
                <br>
                <label for="award_info">Award Information:</label>
                <input type="text" name="award_info" id="award_info" value="<?php echo htmlspecialchars($details['award_info']); ?>" required>
                <br>
                <label for="skill_info">Skill Information:</label>
                <input type="text" name="skill_info" id="skill_info" value="<?php echo htmlspecialchars($details['skill_info']); ?>" required>
                <br>
                <label for="feedback">Feedback:</label>
                <input type="text" name="feedback" id="feedback" value="<?php echo htmlspecialchars($details['feedback']); ?>" required>
                <br>
                <input type="submit" value="Update Details">
            </form>
        </div>
    </div>
    <div class="footer">
        <p>&copy; 2024 Sameer Chhetri. All Rights Reserved.</p>
    </div>
</body>
</html>
