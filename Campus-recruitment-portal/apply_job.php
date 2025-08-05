<?php
session_start();
include 'db.php';

function sendApplicationEmail($to, $name, $job) {
    $subject = "Application Submitted: $job";
    $message = "Hi $name,\n\nYour application for the job '$job' has been received successfully.\nWe wish you all the best!\n\n- Campus Recruitment Portal";
    $headers = "From: campusportal@example.com";

    mail($to, $subject, $message, $headers);
}

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    die("Unauthorized access.");
}

$student_id = $_SESSION['user']['id'];
$student_name = $_SESSION['user']['name'];
$student_email = $_SESSION['user']['email'];
$job_id = $_POST['job_id'];

$target_dir = "resumes/";
if (!is_dir($target_dir)) {
    mkdir($target_dir);
}
$resume_file = $target_dir . basename($_FILES["resume"]["name"]);

if (move_uploaded_file($_FILES["resume"]["tmp_name"], $resume_file)) {
    $sql = "INSERT INTO applications (student_id, job_id, resume_path)
            VALUES ('$student_id', '$job_id', '$resume_file')";
    if ($conn->query($sql) === TRUE) {
        // Get job title
        $job_res = $conn->query("SELECT title FROM jobs WHERE id = $job_id");
        $job = $job_res->fetch_assoc();
        sendApplicationEmail($student_email, $student_name, $job['title']);

        echo "<script>alert('Application submitted and confirmation email sent.'); window.location.href='dashboard/student.php';</script>";
    } else {
        echo "<script>alert('Database error while applying.'); window.location.href='dashboard/student.php';</script>";
    }
} else {
    echo "<script>alert('Failed to upload resume.'); window.location.href='dashboard/student.php';</script>";
}
?>
