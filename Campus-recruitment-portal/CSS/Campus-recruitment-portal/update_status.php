<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'recruiter') {
    die("Unauthorized access");
}

$app_id = $_GET['app_id'];
$status = $_GET['status'];

if (in_array($status, ['Shortlisted', 'Rejected'])) {
    $sql = "UPDATE applications SET status='$status' WHERE id=$app_id";
    if ($conn->query($sql)) {
        header("Location: dashboard/recruiter.php");
    } else {
        echo "Error updating status.";
    }
}
?>
