<?php
session_start();
if (!isset($_SESSION["employee_id"]) || $_SESSION["role_id"] != 1) {
    header("Location: login.php");
    exit();
}

include 'db.php';

if (isset($_GET['id'])) {
    $branch_id = $_GET['id'];

    // Fetch branch details
    $stmt = $conn->prepare("SELECT * FROM branches WHERE id = ?");
    $stmt->bind_param("i", $branch_id);
    $stmt->execute();
    $branch = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Branch</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            margin: 0;
            font-family: Arial, sans-serif;
            min-height: 100vh;
        }
        aside {
            width: 250px;
            background-color: #333;
            color: white;
            min-height: 100vh;
            padding: 20px;
            box-sizing: border-box;
        }
        .main {
            display: flex;
            flex: 1;
        }
        .content {
            flex: 1;
            padding: 20px;
            background-color: #f4f4f4;
            box-sizing: border-box;
        }
        form label {
            display: block;
            margin: 10px 0 5px;
        }
        form input, form button {
            padding: 10px;
            width: 100%;
            margin-bottom: 10px;
        }
        form input[type="checkbox"] {
            width: auto;
        }
        form button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px;
            position: relative;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="main">
        <aside>
            <?php include 'admin_sidebar.php'; ?>
        </aside>
        <div class="content">
            <h1>Edit Branch</h1>
            <form action="update_branch.php" method="post">
                <input type="hidden" name="branch_id" value="<?php echo $branch_id; ?>">

                <label for="name">Name:</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($branch['name']); ?>" required>

                <label for="location">Location:</label>
                <input type="text" name="location" value="<?php echo htmlspecialchars($branch['location']); ?>" required>

                <label for="status">Active:</label>
                <input type="checkbox" name="status" id="status" <?php echo $branch['status'] ? 'checked' : ''; ?>>

                <button type="submit">Update Branch</button>
            </form>
        </div>
    </div>
    <footer>
        &copy; <?php echo date("Y"); ?> Sameer Chhetri. All rights reserved.
    </footer>
</body>
</html>
