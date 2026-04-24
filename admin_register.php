<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $email      = $_POST['email'];
    $password   = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role       = 'admin';

    // Check if email already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Email already registered!";
    } else {
        $insert = $conn->prepare("INSERT INTO users (first_name, email, password, role) VALUES (?, ?, ?, ?)");
        $insert->bind_param("ssss", $first_name, $email, $password, $role);
        if ($insert->execute()) {
            // Redirect to login page after successful registration
            header("Location: admin_login.php");
            exit;
        } else {
            echo "Error: " . $insert->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Register</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="login-container">
    <h2>Admin Registration</h2>
    
    <form method="post" class="login-form">
        <label for="first_name">First Name:</label>
        <input type="text" name="first_name" id="first_name" required>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>

        <button type="submit">Register Admin</button>
    </form>

    <div class="register-link">
        <a href="admin_login.php">← Back to Login</a><br>
        <a href="index.php">Home</a>
    </div>
</div>

</body>
</html>
