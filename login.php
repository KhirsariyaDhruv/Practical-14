<?php
session_start();
include 'db.php';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $role  = $_POST['role'];

    // Use prepared statement for safety
    $stmt = $conn->prepare("SELECT * FROM users WHERE email=? AND role=?");
    $stmt->bind_param("ss", $email, $role);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['email'] = $email;
        $_SESSION['role'] = $role;
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid credentials!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f5f5f5; }
        .container { width: 300px; margin: 100px auto; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 0 10px #ccc; }
        input, select { width: 100%; padding: 8px; margin: 8px 0; border-radius: 4px; border: 1px solid #ccc; }
        input[type=submit] { background-color: #4CAF50; color: white; border: none; cursor: pointer; }
        input[type=submit]:hover { background-color: #45a049; }
        .error { color: red; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form method="post">
            Email: <input type="email" name="email" required><br>
            Role: 
            <select name="role">
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select><br>
            <input type="submit" name="login" value="Login">
        </form>
        <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
    </div>
</body>
</html>
