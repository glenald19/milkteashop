<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include 'db.php';

// Get user ID
if (!isset($_GET['id'])) {
    header("Location: admin_dashboard.php");
    exit();
}

$user_id = $_GET['id'];

// Fetch existing user data
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "User not found.";
    exit();
}

// Update user
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $role       = $_POST['role'];

    $update = $conn->prepare("UPDATE users SET first_name = ?, role = ? WHERE user_id = ?");
    $update->bind_param("ssi", $first_name, $role, $user_id);

    if ($update->execute()) {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Error updating user.";
    }
}
?>

<h2>Edit User</h2>
<form method="post">
    <label>First Name:</label><br>
    <input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name']); ?>" required><br><br>

    <label>Role:</label><br>
    <select name="role" required>
        <option value="customer" <?= $user['role'] == 'customer' ? 'selected' : ''; ?>>Customer</option>
        <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
    </select><br><br>

    <button type="submit">Update User</button>
</form>
<a href="admin_dashboard.php">Back to Dashboard</a>

<?php
$conn->close();
?>
