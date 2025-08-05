<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    header("Location: ../login.php");
    exit();
}

$student_id = $_SESSION['user']['id'];
$search = isset($_GET['search']) ? $_GET['search'] : "";
?>
<!DOCTYPE html>
<html>
<head>
  <title>Student Dashboard</title>
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
  <h2 class="mb-3">Available Jobs</h2>

  <!-- Search Bar -->
  <form class="mb-4" method="GET">
    <div class="input-group">
      <input type="text" name="search" class="form-control" placeholder="Search by title or location..." value="<?php echo htmlspecialchars($search); ?>">
      <button class="btn btn-outline-secondary" type="submit">Search</button>
    </div>
  </form>

  <!-- Job Cards -->
  <div class="row">
    <?php
    $query = "SELECT * FROM jobs WHERE title LIKE '%$search%' OR location LIKE '%$search%' ORDER BY posted_on DESC";
    $jobs = $conn->query($query);

    if ($jobs->num_rows > 0) {
        while ($job = $jobs->fetch_assoc()) {
            $job_id = $job['id'];
            $check_applied = $conn->query("SELECT * FROM applications WHERE student_id = $student_id AND job_id = $job_id");
            $already_applied = $check_applied->num_rows > 0;
    ?>
      <div class="col-md-6 mb-4">
        <div class="card h-100 shadow-sm">
          <div class="card-body">
            <h5 class="card-title"><?php echo $job['title']; ?></h5>
            <h6 class="card-subtitle mb-2 text-muted"><?php echo $job['location']; ?></h6>
            <p class="card-text"><?php echo $job['description']; ?></p>

            <?php if ($already_applied): ?>
              <div class="alert alert-warning p-2 mb-0">
                ✅ You already applied for this job.
              </div>
            <?php else: ?>
              <form method="post" action="../apply_job.php" enctype="multipart/form-data">
                <input type="hidden" name="job_id" value="<?php echo $job['id']; ?>">
                <div class="mb-2">
                  <input type="file" name="resume" accept=".pdf" class="form-control form-control-sm" required>
                </div>
                <button type="submit" class="btn btn-success btn-sm">Apply</button>
              </form>
            <?php endif; ?>
          </div>
          <div class="card-footer text-muted">
            Posted on: <?php echo date("d M Y", strtotime($job['posted_on'])); ?>
          </div>
        </div>
      </div>
    <?php
        }
    } else {
        echo "<p>No jobs found matching your search.</p>";
    }
    ?>
  </div>

  <hr>

  <h3>Your Applications</h3>
  <ul class="list-group">
    <?php
    $apps = $conn->query("SELECT jobs.title, applications.applied_on 
                          FROM applications 
                          JOIN jobs ON applications.job_id = jobs.id 
                          WHERE applications.student_id = $student_id");

    while ($app = $apps->fetch_assoc()) {
        echo "<li class='list-group-item'>{$app['title']} <span class='text-muted'>(Applied on {$app['applied_on']})</span></li>";
    }
    ?>
  </ul>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>