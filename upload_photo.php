<?php
session_start();
if (!isset($_SESSION["employee_id"]) || $_SESSION["role_id"] != 2) { // Assuming role_id 2 is for employees
    header("Location: login.php");
    exit();
}

include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["profile_photo"])) {
    $employee_id = $_SESSION["employee_id"];
    $target_dir = "uploads/"; // Directory where photos will be stored

    // Create the uploads directory if it doesn't exist
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true); // Create directory with permissions
    }

    // Generate unique name for the file
    $target_file = $target_dir . time() . basename($_FILES["profile_photo"]["name"]);
    $upload_ok = 1;
    $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a valid image
    $check = getimagesize($_FILES["profile_photo"]["tmp_name"]);
    if ($check === false) {
        echo "File is not an image.";
        $upload_ok = 0;
    }

    // Allow only certain file formats
    if ($image_file_type != "jpg" && $image_file_type != "png" && $image_file_type != "jpeg") {
        echo "Sorry, only JPG, JPEG, and PNG files are allowed.";
        $upload_ok = 0;
    }

    // Check if $upload_ok is set to 0 by an error
    if ($upload_ok == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $target_file)) {
            // Update the database with the photo path
            $sql = "UPDATE employees SET profile_photo = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $target_file, $employee_id);
            $stmt->execute();
            $stmt->close();

            echo "The file ". basename($_FILES["profile_photo"]["name"]). " has been uploaded.";
            header("Location: employee_dashboard.php"); // Redirect to dashboard after upload
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
    $conn->close();
}
?>
