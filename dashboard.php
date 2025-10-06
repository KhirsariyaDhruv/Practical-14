<?php
session_start();
include 'db.php';

// Only admin can access
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    die("Access denied. Admins only.");
}

// Delete user
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: dashboard.php");
    exit;
}

// Toggle status
if (isset($_GET['toggle'])) {
    $id = $_GET['toggle'];
    $stmt = $conn->prepare("SELECT status FROM users WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $new_status = ($user['status'] == 'active') ? 'inactive' : 'active';

    $stmt = $conn->prepare("UPDATE users SET status=? WHERE id=?");
    $stmt->bind_param("si", $new_status, $id);
    $stmt->execute();

    header("Location: dashboard.php");
    exit;
}

// Fetch all users
$users = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>
<style>
    /* Global Styles */
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f6f8;
        margin: 0;
        padding: 0;
    }

    h2 {
        text-align: center;
        margin-top: 40px;
        color: #333;
    }

    /* Container */
    .dashboard-container {
        width: 90%;
        max-width: 1000px;
        margin: 20px auto;
        background-color: #fff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    /* Table */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th, td {
        padding: 12px;
        text-align: center;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #007BFF;
        color: white;
        font-weight: 600;
    }

    tr:hover {
        background-color: #f1f1f1;
    }

    /* Action buttons */
    .btn-toggle, .btn-delete {
        padding: 5px 10px;
        border-radius: 5px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-toggle {
        color: white;
        background-color: #28a745;
        text-decoration: none;
    }

    .btn-toggle:hover {
        background-color: #218838;
    }

    .btn-delete {
        color: white;
        background-color: #dc3545;
        text-decoration: none;
    }

    .btn-delete:hover {
        background-color: #c82333;
    }

    /* Logout link */
    .logout {
        text-align: center;
        margin-bottom: 20px;
    }

    .logout a {
        text-decoration: none;
        color: #fff;
        background-color: #6c757d;
        padding: 8px 15px;
        border-radius: 5px;
        transition: background 0.3s ease;
    }

    .logout a:hover {
        background-color: #5a6268;
    }

    /* Status badges */
    .status-active {
        color: #fff;
        background-color: #28a745;
        padding: 5px 10px;
        border-radius: 5px;
        font-weight: 500;
    }

    .status-inactive {
        color: #fff;
        background-color: #dc3545;
        padding: 5px 10px;
        border-radius: 5px;
        font-weight: 500;
    }
</style>
</head>
<body>

<h2>Admin Dashboard</h2>

<div class="dashboard-container">
    <div class="logout"><a href="logout.php">Logout</a></div>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php while($row = $users->fetch_assoc()) { ?>
        <tr>
            <td><?= htmlspecialchars($row['id']) ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['role']) ?></td>
            <td>
                <span class="status-<?= $row['status'] ?>"><?= htmlspecialchars($row['status']) ?></span>
            </td>
            <td>
                <a class="btn-toggle" href="dashboard.php?toggle=<?= $row['id'] ?>">Toggle Status</a>
                <a class="btn-delete" href="dashboard.php?delete=<?= $row['id'] ?>" onclick="return confirm('Delete user?')">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>

</body>
</html>
