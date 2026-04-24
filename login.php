<?php
session_start();
include 'db.php'; // connects to final_milktea

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Fetch user by email
    $query = "SELECT user_id, first_name, password, role FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Store session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            if ($user['role'] === 'admin') {
                header("Location: products.php");
            } else {
                header("Location: products.php");
            }
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "No user found with that email.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="style.css">
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>

<div class="container">
  <div class="form-section">
    <h2>Hello!</h2>
    <!-- Post to same file -->
    <form action="" method="POST">
      <div class="input-group">
        <input type="email" name="email" placeholder="Email" required>
      </div>
      <div class="input-group">
        <input type="password" name="password" id="loginPass" placeholder="Password" required>
        <i class="fas fa-eye" id="loginToggle" onclick="togglePassword('loginPass', 'loginToggle')"></i>
      </div>
      <button type="submit">Login</button>
      <small>Don't have an account? <a href="register.php">Create</a></small>
      <small>Back to home? <a href="index.php">Home</a></small>
      <?php if (!empty($error)) echo "<p style='color:red; margin-top:10px;'>$error</p>"; ?>
    </form>
  </div>
  <div class="welcome-section">
    <h2>Welcome Back!</h2>
    <p>Enter your credentials to access your account.</p>
  </div>
</div>

<script src="scripts.js"></script>
</body>
</html>
