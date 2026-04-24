<?php
include 'db.php'; // connects to your final_milktea database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = trim($_POST['first_name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = 'customer'; // Default role

    // Check if email already exists
    $check_query = "SELECT user_id FROM users WHERE email = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $check_result = $stmt->get_result();

    if ($check_result->num_rows > 0) {
        $error = "Email already exists.";
    } else {
        // Insert new user
        $insert_query = "INSERT INTO users (first_name, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("ssss", $first_name, $email, $password, $role);

        if ($stmt->execute()) {
            header("Location: login.php");
            exit();
        } else {
            $error = "Error: " . $stmt->error;
        }
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <link rel="stylesheet" href="style.css">
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>

<div class="container">
  <div class="form-section">
    <h2>Hello, friend!</h2>
    <!-- Post to the same file -->
    <form action="" method="POST">
      <div class="input-group">
        <input type="text" name="first_name" placeholder="Name" required>
      </div>
      <div class="input-group">
        <input type="email" name="email" placeholder="Email" required>
      </div>
      <div class="input-group">
        <input type="password" name="password" id="registerPass" placeholder="Password" required>
        <i class="fas fa-eye" id="registerToggle" onclick="togglePassword('registerPass', 'registerToggle')"></i>
      </div>
      <div style="margin-bottom: 10px;">
        <input type="checkbox" required> I read and agree to Terms & Conditions
      </div>
      <button type="submit">Create Account</button>
      <small>Already have an account? <a href="login.php">Sign in</a></small>
      <small>Back to home? <a href="index.php">Home</a></small>
      <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
    </form>
  </div>
  <div class="welcome-section">
    <h2>Glad to see you!</h2>
    <p>Create your account and start connecting with us.</p>
  </div>
</div>

<script src="scripts.js"></script>
</body>
</html>
