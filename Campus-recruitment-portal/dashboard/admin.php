<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Count totals for charts
$total_users = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$total_students = $conn->query("SELECT COUNT(*) as count FROM users WHERE role='student'")->fetch_assoc()['count'];
$total_recruiters = $conn->query("SELECT COUNT(*) as count FROM users WHERE role='recruiter'")->fetch_assoc()['count'];
$total_admins = $conn->query("SELECT COUNT(*) as count FROM users WHERE role='admin'")->fetch_assoc()['count'];

$total_jobs = $conn->query("SELECT COUNT(*) as count FROM jobs")->fetch_assoc()['count'];
$total_apps = $conn->query("SELECT COUNT(*) as count FROM applications")->fetch_assoc()['count'];
?>
<!DOCTYPE html>
<html>
<head>
  <title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
  <h2 class="mb-4">Admin Dashboard</h2>

  <div class="row mb-5">
    <div class="col-md-6">
      <canvas id="userChart"></canvas>
    </div>
    <div class="col-md-6">
      <canvas id="jobAppChart"></canvas>
    </div>
  </div>

  <h4>All Users</h4>
  <ul class="list-group mb-4">
  <?php
  $users = $conn->query("SELECT * FROM users");
  while ($u = $users->fetch_assoc()) {
      echo "<li class='list-group-item'>{$u['name']} ({$u['role']}) - {$u['email']}</li>";
  }
  ?>
  </ul>

  <h4>All Jobs</h4>
  <ul class="list-group mb-4">
  <?php
  $jobs = $conn->query("SELECT * FROM jobs");
  while ($job = $jobs->fetch_assoc()) {
      echo "<li class='list-group-item'>{$job['title']} by Recruiter ID {$job['recruiter_id']} ({$job['location']})</li>";
  }
  ?>
  </ul>

  <h4>All Applications</h4>
  <ul class="list-group">
  <?php
  $apps = $conn->query("SELECT * FROM applications");
  while ($a = $apps->fetch_assoc()) {
      echo "<li class='list-group-item'>Student ID {$a['student_id']} applied to Job ID {$a['job_id']} on {$a['applied_on']}</li>";
  }
  ?>
  </ul>
</div>

<script>
  // User Roles Chart
  const userCtx = document.getElementById('userChart').getContext('2d');
  new Chart(userCtx, {
    type: 'pie',
    data: {
      labels: ['Students', 'Recruiters', 'Admins'],
      datasets: [{
        label: 'User Roles',
        data: [<?= $total_students ?>, <?= $total_recruiters ?>, <?= $total_admins ?>],
        backgroundColor: ['#0d6efd', '#198754', '#ffc107'],
      }]
    },
    options: {
      plugins: {
        title: {
          display: true,
          text: 'Users by Role'
        }
      }
    }
  });

  // Jobs vs Applications Chart
  const jobAppCtx = document.getElementById('jobAppChart').getContext('2d');
  new Chart(jobAppCtx, {
    type: 'bar',
    data: {
      labels: ['Jobs Posted', 'Applications'],
      datasets: [{
        label: 'Counts',
        data: [<?= $total_jobs ?>, <?= $total_apps ?>],
        backgroundColor: ['#6610f2', '#dc3545']
      }]
    },
    options: {
      plugins: {
        title: {
          display: true,
          text: 'Jobs vs Applications'
        }
      },
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
</script>
</body>
</html>
