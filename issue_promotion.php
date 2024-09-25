<?php
session_start();
if (!isset($_SESSION["employee_id"]) || $_SESSION["role_id"] != 1) {
    header("Location: login.php");
    exit();
}

include 'db.php';

// Function to send promotion email with attachment
function sendPromotionEmail($email, $promotion_title, $new_position, $promotion_message, $file_path = null) {
    $subject = "Promotion Notification";
    $message = "Dear Employee,\n\nCongratulations! You have been promoted to the position of $new_position.\n\nPromotion Title: $promotion_title\n\nMessage from Admin: $promotion_message\n\nBest Regards,\nCompany";
    
    $headers = "From: no-reply@company.com\r\n";
    if ($file_path) {
        $boundary = md5(uniqid(time()));
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n\r\n";
        
        $message = "--$boundary\r\n";
        $message .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $message .= "$message\r\n\r\n";
        
        $file_name = basename($file_path);
        $file_data = file_get_contents($file_path);
        $file_data = chunk_split(base64_encode($file_data));
        
        $message .= "--$boundary\r\n";
        $message .= "Content-Type: application/octet-stream; name=\"$file_name\"\r\n";
        $message .= "Content-Transfer-Encoding: base64\r\n";
        $message .= "Content-Disposition: attachment; filename=\"$file_name\"\r\n\r\n";
        $message .= "$file_data\r\n\r\n";
        $message .= "--$boundary--";
    }
    
    return mail($email, $subject, $message, $headers);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employee_id = $_POST["employee_id"];
    $issued_by = $_SESSION["employee_id"]; // Admin issuing the promotion
    $promotion_title = $_POST["promotion_title"];
    $new_position = $_POST["new_position"];
    $date_issued = date("Y-m-d");
    $promotion_message = "You have been promoted to the position of $new_position.";
    $promotion_letter_file = null;

    // Handle the promotion letter upload
    if (isset($_FILES['promotion_letter_file']) && $_FILES['promotion_letter_file']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['promotion_letter_file']['tmp_name'];
        $fileName = $_FILES['promotion_letter_file']['name'];
        $fileSize = $_FILES['promotion_letter_file']['size'];
        $fileType = $_FILES['promotion_letter_file']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Define allowed file extensions and check file extension
        $allowedExts = ['pdf', 'doc', 'docx', 'txt'];
        if (in_array($fileExtension, $allowedExts)) {
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $uploadFileDir = './uploads/';
            $dest_path = $uploadFileDir . $newFileName;

            // Move the file to the uploads directory
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $promotion_letter_file = $dest_path;
            } else {
                $message = "There was an error moving the file.";
            }
        } else {
            $message = "Upload failed. Allowed file types: " . implode(", ", $allowedExts);
        }
    }

    // Check if employee exists
    $stmt = $conn->prepare("SELECT id, email FROM employees WHERE id = ?");
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $message = "Invalid Employee ID.";
    } else {
        $employee = $result->fetch_assoc();
        $email = $employee['email'];

        // Insert promotion record
        $stmt = $conn->prepare("INSERT INTO promotions (employee_id, issued_by, promotion_title, new_position, date_issued, promotion_letter_file) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iissss", $employee_id, $issued_by, $promotion_title, $new_position, $date_issued, $promotion_letter_file);

        if ($stmt->execute()) {
            // Send the promotion letter
            if (sendPromotionEmail($email, $promotion_title, $new_position, $promotion_message, $promotion_letter_file)) {
                $message = "Promotion issued successfully and promotion letter sent!";
            } else {
                $message = "Promotion issued, but failed to send the promotion letter.";
            }
            header("Location: admin_dashboard.php"); // Redirect after success
            exit();
        } else {
            $message = "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Issue Promotion</title>
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
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"], input[type="submit"], input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #5cb85c;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #4cae4c;
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
        <h1>Issue Promotion</h1>
        <?php if (isset($message)): ?>
            <p><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <label for="employee_id">Employee ID:</label>
            <input type="text" id="employee_id" name="employee_id" required>

            <label for="promotion_title">Promotion Title:</label>
            <input type="text" id="promotion_title" name="promotion_title" required>

            <label for="new_position">New Position:</label>
            <input type="text" id="new_position" name="new_position" required>

            <label for="promotion_letter_file">Upload Promotion Letter (optional):</label>
            <input type="file" id="promotion_letter_file" name="promotion_letter_file" accept=".pdf,.doc,.docx,.txt">

            <input type="submit" value="Issue Promotion">
        </form>
    </div>
    <footer>
        &copy; <?php echo date("Y"); ?> Sameer Chhetri. All rights reserved.
    </footer>
</body>
</html>
