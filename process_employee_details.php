<?php
//session_start();
//if (!isset($_SESSION["admin_id"]) || $_SESSION["role_id"] != 1) {
    //header("Location: login.php");
    //exit();
//}

include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and retrieve form data
    $employee_id = $conn->real_escape_string($_POST['employee_id']);
    $address = $conn->real_escape_string($_POST['address']);
    $citizenship = $conn->real_escape_string($_POST['citizenship']);
    $nid = $conn->real_escape_string($_POST['nid']);
    $education = $conn->real_escape_string($_POST['education']);
    $personal_info = $conn->real_escape_string($_POST['personal_info']);
    $experience = $conn->real_escape_string($_POST['experience']);
    $training = $conn->real_escape_string($_POST['training']);
    $awards = $conn->real_escape_string($_POST['awards']);
    $skills = $conn->real_escape_string($_POST['skills']);
    $feedback = $conn->real_escape_string($_POST['feedback']);

    // Insert the details into the employee details table (assuming a new table for this data)
    $sql = "INSERT INTO employee_details 
        (employee_id, address, citizenship, national_id, educational_qualification, personal_info, job_experience, training_info, award_info, skill_info, feedback) 
        VALUES 
        ('$employee_id', '$address', '$citizenship', '$nid', '$education', '$personal_info', '$experience', '$training', '$awards', '$skills', '$feedback')";

    if ($conn->query($sql) === TRUE) {
        echo "Employee details added successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
