<?php
include 'db.php';
$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST['email'];
  $identifier = $_POST['identifier']; // security check
  $new = $_POST['new_password'];
  $confirm = $_POST['confirm_password'];

  $res = $conn->query("SELECT * FROM users WHERE email = '$email'");
  if ($res->num_rows == 1) {
    $user = $res->fetch_assoc();

    // You can change 'phone' to 'college' or anything else
    if ($user['phone'] === $identifier) {
      if ($new === $confirm) {
        $hash = password_hash($new, PASSWORD_BCRYPT);
        $conn->query("UPDATE users SET password='$hash' WHERE email='$email'");
        $success = "✅ Password has been reset. <a href='login.php'>Login here</a>";
      } else {
        $error = "❌ New passwords do not match.";
      }
    } else {
      $error = "❌ Security check failed. Identifier incorrect.";
    }
  } else {
    $error = "❌ Email not found.";
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Forgot Password</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5" style="max-width: 500px;">
  <h3 class="text-center mb-4">Reset Your Password</h3>

  <?php if ($success) echo "<div class='alert alert-success'>$success</div>"; ?>
  <?php if ($error) echo "<div class='alert alert-danger'>$error</div>"; ?>

  <form method="POST">
    <div class="mb-3">
      <label>Email</label>
      <input type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Enter Your Phone Number (for verification)</label>
      <input type="text" name="identifier" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>New Password</label>
      <input type="password" name="new_password" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Confirm New Password</label>
      <input type="password" name="confirm_password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary w-100">Reset Password</button>
    <div class="text-center mt-3">
      <a href="login.php">← Back to Login</a>
    </div>
  </form>
</div>
</body>
</html>
