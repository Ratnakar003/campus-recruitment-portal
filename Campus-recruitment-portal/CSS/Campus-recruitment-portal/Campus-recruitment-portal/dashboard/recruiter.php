<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'recruiter') {
    header("Location: ../login.php");
    exit();
}

$recruiter_id = $_SESSION['user']['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $loc = $_POST['location'];
    $sql = "INSERT INTO jobs (recruiter_id, title, description, location) VALUES ('$recruiter_id', '$title', '$desc', '$loc')";
    $conn->query($sql);
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Recruiter Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
<a href="../change_password.php" class="btn btn-outline-light me-2">Change Password</a>

  <div class="container-fluid">
    <a class="navbar-brand" href="../index.html">Campus Portal</a>
    <div class="d-flex">
      <a href="../index.html" class="btn btn-outline-light me-2">Home</a>
      <a href="../logout.php" class="btn btn-light">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-4">
  <h2>Post a New Job</h2>
  <form method="post" class="mb-5">
    <div class="mb-3">
      <label class="form-label">Job Title</label>
      <input type="text" name="title" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Description</label>
      <textarea name="description" class="form-control" required></textarea>
    </div>
    <div class="mb-3">
      <label class="form-label">Location</label>
      <input type="text" name="location" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-success">Post Job</button>
  </form>

  <h3>Your Posted Jobs & Applicants</h3>

  <?php
  $jobs = $conn->query("SELECT * FROM jobs WHERE recruiter_id = $recruiter_id ORDER BY posted_on DESC");
  if ($jobs->num_rows > 0) {
      while ($job = $jobs->fetch_assoc()) {
          $job_id = $job['id'];
          echo "<div class='card mb-4'>";
          echo "<div class='card-body'>";
          echo "<h5 class='card-title'>{$job['title']}</h5>";
          echo "<h6 class='card-subtitle mb-2 text-muted'>{$job['location']}</h6>";
          echo "<p class='card-text'>{$job['description']}</p>";

          $apps = $conn->query("
  SELECT applications.id, users.name, users.email, applications.resume_path, applications.applied_on, applications.status 
  FROM applications 
  JOIN users ON applications.student_id = users.id 
  WHERE applications.job_id = $job_id
");

if ($apps->num_rows > 0) {
  echo "<h6>Applicants:</h6><ul class='list-group'>";
  while ($a = $apps->fetch_assoc()) {
    $resume_link = "../" . $a['resume_path'];
    $status = $a['status'];

    echo "<li class='list-group-item'>";
    echo "<div class='d-flex justify-content-between align-items-center'>";
    echo "<div>
            <strong>{$a['name']}</strong> ({$a['email']})<br>
            <small>Applied on: {$a['applied_on']}</small><br>
            <span class='badge bg-".($status == 'Shortlisted' ? 'success' : ($status == 'Rejected' ? 'danger' : 'secondary'))."'>$status</span>
          </div>";
    echo "<div class='d-flex gap-2'>";
    echo "<a href='$resume_link' class='btn btn-sm btn-outline-primary' target='_blank'>📄 Resume</a>";
    if ($status == 'Pending') {
      echo "<a href='../update_status.php?app_id={$a['id']}&status=Shortlisted' class='btn btn-sm btn-success'>Shortlist</a>";
      echo "<a href='../update_status.php?app_id={$a['id']}&status=Rejected' class='btn btn-sm btn-danger'>Reject</a>";
    }
    echo "</div>";
    echo "</div></li>";
  }
  echo "</ul>";
}
 else {
              echo "<p class='text-muted'>No applications yet.</p>";
          }

          echo "</div>";
          echo "<div class='card-footer text-muted'>Posted on: {$job['posted_on']}</div>";
          echo "</div>";
      }
  } else {
      echo "<p class='text-muted'>You haven't posted any jobs yet.</p>";
  }
  ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
