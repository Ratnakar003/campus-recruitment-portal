<?php
include 'db.php';

function sendConfirmationEmail($to, $name) {
    return true; // Do nothing
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role     = $_POST['role'];
    $phone    = $_POST['phone'];
    $location = $_POST['location'];

    if ($role === "student") {
        $degree  = $_POST['degree'];
        $branch  = $_POST['branch'];
        $college = $_POST['college'];
        $marks   = $_POST['marks'];

        $sql = "INSERT INTO users (name, email, password, role, phone, location, degree, branch, college, marks)
                VALUES ('$name', '$email', '$password', '$role', '$phone', '$location', '$degree', '$branch', '$college', '$marks')";
    } else {
        $company_name = $_POST['company_name'];
        $designation  = $_POST['designation'];

        $sql = "INSERT INTO users (name, email, password, role, phone, location, company_name, designation)
                VALUES ('$name', '$email', '$password', '$role', '$phone', '$location', '$company_name', '$designation')";
    }

    if ($conn->query($sql) === TRUE) {
        sendConfirmationEmail($email, $name);
        $success = "Registration successful! A confirmation email was sent. <a href='login.php'>Login</a>";
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>


<!DOCTYPE html>
<html>
<head>
  <title>Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
  <script>
    function toggleFields(role) {
      document.getElementById("student-fields").style.display = role === 'student' ? 'block' : 'none';
      document.getElementById("recruiter-fields").style.display = role === 'recruiter' ? 'block' : 'none';
    }
  </script>
</head>
<body class="bg-light">

  <div class="container mt-5" style="max-width: 700px;">
    <h2 class="mb-4 text-center">Register</h2>

    <?php
    if (isset($success)) echo "<div class='alert alert-success'>$success</div>";
    if (isset($error)) echo "<div class='alert alert-danger'>$error</div>";
    ?>

    <form method="post">
      <div class="row">
        <div class="mb-3 col-md-6">
          <label class="form-label">Name</label>
          <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3 col-md-6">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3 col-md-6">
          <label class="form-label">Phone Number</label>
          <input type="text" name="phone" class="form-control" required>
        </div>
        <div class="mb-3 col-md-6">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3 col-md-4">
          <label class="form-label">Location</label>
          <input type="text" name="location" class="form-control" required>
        </div>
        <div class="mb-3 col-md-4">
          <label class="form-label">Role</label>
          <select name="role" class="form-select" onchange="toggleFields(this.value)" required>
            <option value="">Select Role</option>
            <option value="student">Student</option>
            <option value="recruiter">Recruiter</option>
          </select>
        </div>
      </div>

      <!-- Student fields -->
      <div id="student-fields" style="display:none;">
        <div class="row">
          <div class="mb-3 col-md-6">
            <label class="form-label">Degree</label>
            <input type="text" name="degree" class="form-control">
          </div>
          <div class="mb-3 col-md-6">
            <label class="form-label">Branch</label>
            <input type="text" name="branch" class="form-control">
          </div>
          <div class="mb-3 col-md-8">
            <label class="form-label">College</label>
            <input type="text" name="college" class="form-control">
          </div>
          <div class="mb-3 col-md-4">
            <label class="form-label">Marks/CGPA</label>
            <input type="text" name="marks" class="form-control">
          </div>
        </div>
      </div>

      <!-- Recruiter fields -->
      <div id="recruiter-fields" style="display:none;">
        <div class="row">
          <div class="mb-3 col-md-6">
            <label class="form-label">Company Name</label>
            <input type="text" name="company_name" class="form-control">
          </div>
          <div class="mb-3 col-md-6">
            <label class="form-label">Designation</label>
            <input type="text" name="designation" class="form-control">
          </div>
        </div>
      </div>

      <button type="submit" class="btn btn-success w-100">Register</button>
    </form>

    <p class="mt-3 text-center">Already have an account? <a href="login.php">Login here</a></p>
  </div>

</body>
</html>
