<?php
session_start();
include 'db.php';

// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Custom hash function (same as in registration)
function custom_hash($str) {
    $hash = 5381; // Initialize the hash value
    for ($i = 0; $i < strlen($str); $i++) {
        $hash = (($hash << 5) + $hash) + ord($str[$i]); // Equivalent to $hash * 33 + ASCII value
    }
    return dechex($hash);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // SQL query to check if the employee exists with the provided email and if the account is active (status = TRUE)
    $sql = "SELECT id, password, role_id FROM employees WHERE email = ? AND status = TRUE";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $stored_hashed_password, $role_id);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();

        // Use the custom hash function to hash the input password
        $hashed_input_password = custom_hash($password);

        // Compare the hashed input password with the stored hash
        if ($hashed_input_password === $stored_hashed_password) {
            // Set session variables for employee
            $_SESSION["employee_id"] = $id;
            $_SESSION["role_id"] = $role_id;

            // Redirect based on role_id: 1 for admin, 2 for employee
            if ($role_id == 1) {
                header("Location: admin_dashboard.php");
            } elseif ($role_id == 2) {
                header("Location: employee_dashboard.php");
            } else {
                $error = "Invalid role assigned.";
            }
            exit();
        } else {
            $error = "Invalid password.";  // Incorrect password
        }
    } else {
        $error = "No user found with this email.";  // Email not found or inactive account
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        h2 {
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
        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
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
        input[type="submit"]:hover {
            background-color: #4cae4c;
        }
        .links {
            text-align: center;
            margin-top: 15px;
        }
        .links a {
            color: #007bff;
            text-decoration: none;
        }
        .links a:hover {
            text-decoration: underline;
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="post" action="login.php">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <input type="submit" value="Login">
        </form>
        <div class="links">
            <p><a href="register.php">Register</a></p>
            <p><a href="forgot_password.php">Forgot Password?</a></p>
        </div>
    </div>
</body>
</html>
