<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../classes/Database.php';
require_once __DIR__ . '/../../classes/Job.php';

// Admin authentication check
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: /pages/public/login.php');
    exit;
}

$job = new Job();
$jobId = $_GET['id'] ?? 0;

if (!$jobId) {
    header('Location: manage-jobs.php');
    exit;
}

// Handle job deletion
if ($job->deleteJob($jobId)) {
    $_SESSION['success'] = "Job deleted successfully!";
} else {
    $_SESSION['error'] = "Failed to delete job. Please try again.";
}

header('Location: manage-jobs.php');
exit; 