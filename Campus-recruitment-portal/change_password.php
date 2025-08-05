<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION['user']['id'];
$user_email = $_SESSION['user']['email'];
$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $old = $_POST['old_password'];
  $new = $_POST['new_password'];
  $confirm = $_POST['confirm_password'];

  // Get current password hash
  $res = $conn->query("SELECT password FROM users WHERE id = $user_id");
  $row = $res->fetch_assoc();

  if (!password_verify($old, $row['password'])) {
    $error = "❌ Old password is incorrect.";
  } elseif ($new !== $confirm) {
    $error = "❌ New passwords do not match.";
  } else {
    $hash = password_hash($new, PASSWORD_BCRYPT);
    $conn->query("UPDATE users SET password='$hash' WHERE id=$user_id");
    $success = "✅ Password updated successfully.";
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Change Password</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5" style="max-width: 500px;">
  <h3 class="text-center mb-4">Change Password</h3>

  <?php if ($success) echo "<div class='alert alert-success'>$success</div>"; ?>
  <?php if ($error) echo "<div class='alert alert-danger'>$error</div>"; ?>

  <form method="POST">
    <div class="mb-3">
      <label>Old Password</label>
      <input type="password" name="old_password" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>New Password</label>
      <input type="password" name="new_password" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Confirm New Password</label>
      <input type="password" name="confirm_password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary w-100">Update Password</button>
    <div class="text-center mt-3">
      <a href="dashboard/<?php echo $_SESSION['user']['role']; ?>.php">← Back to Dashboard</a>
    </div>
  </form>
</div>
</body>
</html>
