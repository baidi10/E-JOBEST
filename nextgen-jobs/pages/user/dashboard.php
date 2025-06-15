<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../classes/Database.php';
require_once __DIR__ . '/../../classes/User.php';
require_once __DIR__ . '/../../classes/Application.php';
require_once __DIR__ . '/../../classes/Job.php';

// Authentication check
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'jobSeeker') {
    header('Location: /pages/public/login.php');
    exit;
}

$user = new User();
$application = new Application();
$job = new Job();

// Get user data
$userData = $user->findById($_SESSION['user_id']);
$stats = $user->getApplicationStats($_SESSION['user_id']);
$recentApplications = $application->getApplicationsForUser($_SESSION['user_id'], null, 5);
$recommendedJobs = $job->getRecommendedJobs($_SESSION['user_id'], 4);

$pageTitle = "Dashboard - JOBEST";
include __DIR__ . '/../../includes/header.php';
?>

<div class="dashboard-container">
  <div class="container py-5">
    <!-- Welcome Header -->
    <div class="d-flex justify-content-between align-items-center mb-5">
      <div>
        <h1 class="h3 fw-bold">Welcome back, <?= htmlspecialchars($userData['firstName']) ?></h1>
        <p class="text-muted mb-0">Your job search at a glance</p>
      </div>
      <a href="profile.php" class="btn btn-outline-primary">
        <i class="bi bi-pencil me-2"></i>Edit Profile
      </a>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
      <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="bg-primary text-white rounded-circle p-3 me-3">
                <i class="bi bi-file-earmark-text fs-4"></i>
              </div>
              <div>
                <h3 class="h2 fw-bold mb-0"><?= $stats['totalApplications'] ?></h3>
                <span class="text-muted small">Applications</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Add similar cards for Saved Jobs, Profile Views, etc. -->
    </div>

    <!-- Recent Applications -->
    <div class="card border-0 shadow-sm mb-5">
      <div class="card-header bg-white border-0 py-3">
        <h2 class="h5 fw-bold mb-0">Recent Applications</h2>
      </div>
      <div class="card-body">
        <?php if (!empty($recentApplications)): ?>
          <div class="list-group">
            <?php foreach ($recentApplications as $app): ?>
              <div class="list-group-item border-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <h3 class="h6 fw-bold mb-1">
                      <a href="/pages/public/job-details.php?id=<?= $app['jobId'] ?>" 
                         class="text-decoration-none">
                        <?= htmlspecialchars($app['jobTitle']) ?>
                      </a>
                    </h3>
                    <p class="text-muted small mb-0">
                      <?= htmlspecialchars($app['companyName']) ?> • 
                      Applied <?= timeElapsed($app['createdAt']) ?>
                    </p>
                  </div>
                  <span class="badge bg-<?= statusColor($app['status']) ?>">
                    <?= ucfirst($app['status']) ?>
                  </span>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="text-center py-5">
            <p class="text-muted">No recent applications</p>
            <a href="/pages/public/jobs.php" class="btn btn-primary">
              Browse Jobs
            </a>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Recommended Jobs -->
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white border-0 py-3">
        <h2 class="h5 fw-bold mb-0">Recommended for You</h2>
      </div>
      <div class="card-body">
        <div class="row g-4">
          <?php foreach ($recommendedJobs as $job): ?>
            <div class="col-md-6">
              <?php include __DIR__ . '/../../includes/job-card.php'; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>