<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

session_regenerate_id(true);
include 'db.php';

$query = "SELECT user_id, first_name, email, role FROM users";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f6f9;
            color: #333;
        }
        header {
            background-color: #343a40;
            padding: 20px;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        header h1 {
            margin: 0;
            font-size: 24px;
        }
        nav ul {
            list-style: none;
            padding: 0;
            display: flex;
            gap: 15px;
            margin: 0;
        }
        nav ul li a {
            color: #f8f9fa;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s;
        }
        nav ul li a:hover {
            color: #ffc107;
        }
        main {
            max-width: 1100px;
            margin: 30px auto;
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        h2 {
            color: #212529;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            border-radius: 5px;
            overflow: hidden;
        }
        thead {
            background-color: #007bff;
            color: white;
        }
        th, td {
            padding: 14px 16px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        tbody tr:hover {
            background-color: #f1f3f5;
        }
        .action-links a {
            text-decoration: none;
            color: #007bff;
            margin-right: 10px;
        }
        .action-links a:hover {
            text-decoration: underline;
        }
        p {
            text-align: center;
            font-style: italic;
            color: #666;
        }
        @media (max-width: 768px) {
            nav ul {
                flex-direction: column;
                gap: 10px;
                margin-top: 15px;
            }
            main {
                padding: 15px;
            }
            th, td {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
        <nav>
            <ul>
                <li><a href="manage_orders.php">Manage Orders</a></li>
                <li><a href="manage_products.php">Manage Products</a></li>
                <li><a href="admin_logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <h2>Manage Users</h2>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['user_id']); ?></td>
                                <td><?= htmlspecialchars($row['first_name']); ?></td>
                                <td><?= htmlspecialchars($row['email']); ?></td>
                                <td><?= htmlspecialchars($row['role']); ?></td>
                                <td class="action-links">
                                    <a href="edit_user.php?id=<?= urlencode($row['user_id']); ?>">✏ Edit</a>
                                    <a href="delete_user.php?id=<?= urlencode($row['user_id']); ?>" 
                                       onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                                       🗑 Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No users found.</p>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
<?php mysqli_close($conn); ?>
