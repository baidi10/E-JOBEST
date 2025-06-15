<?php
require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../../../includes/functions.php';
require_once __DIR__ . '/../../../classes/Application.php';

// Authentication check
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'jobSeeker') {
    header('Location: /pages/public/login.php');
    exit;
}

$application = new Application();
$currentPage = max(1, $_GET['page'] ?? 1);
$perPage = 10;
$statusFilter = $_GET['status'] ?? null;

$applications = $application->getApplicationsForUser($_SESSION['user_id'], $statusFilter, $perPage, ($currentPage - 1) * $perPage);
$totalApplications = $application->countApplications($_SESSION['user_id'], $statusFilter);

$pageTitle = "My Applications - JOBEST";
include __DIR__ . '/../../../includes/header.php';
?>

<div class="dashboard-container">
  <div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-5">
      <h1 class="h3 fw-bold">Job Applications</h1>
      <div class="dropdown">
        <button class="btn btn-outline-secondary dropdown-toggle" type="button" 
                data-bs-toggle="dropdown">
          Filter by status: <?= ucfirst($statusFilter ?? 'All') ?>
        </button>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="?status=">All Applications</a></li>
          <li><hr class="dropdown-divider"></li>
          <?php foreach (['submitted', 'reviewed', 'interviewing', 'offered', 'hired', 'rejected'] as $status): ?>
            <li>
              <a class="dropdown-item" href="?status=<?= $status ?>">
                <?= ucfirst($status) ?>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>

    <div class="card border-0 shadow-sm">
      <div class="card-body p-0">
        <?php if (!empty($applications)): ?>
          <div class="list-group">
            <?php foreach ($applications as $app): ?>
              <div class="list-group-item border-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                  <div class="flex-grow-1 me-3">
                    <h3 class="h6 fw-bold mb-1">
                      <a href="/pages/public/job-details.php?id=<?= $app['jobId'] ?>" 
                         class="text-decoration-none">
                        <?= htmlspecialchars($app['jobTitle']) ?>
                      </a>
                    </h3>
                    <div class="d-flex align-items-center gap-3 small">
                      <span class="text-muted"><?= htmlspecialchars($app['companyName']) ?></span>
                      <span class="text-muted">•</span>
                      <span class="text-muted">Applied <?= timeElapsed($app['appliedAt']) ?></span>
                    </div>
                  </div>
                  <div class="d-flex align-items-center gap-3">
                    <span class="badge bg-<?= statusColor($app['status']) ?>">
                      <?= ucfirst($app['status']) ?>
                    </span>
                    <div class="dropdown">
                      <button class="btn btn-link text-muted p-0" 
                              data-bs-toggle="dropdown">
                        <i class="bi bi-three-dots-vertical"></i>
                      </button>
                      <ul class="dropdown-menu">
                        <li>
                          <a class="dropdown-item" 
                             href="/pages/public/job-details.php?id=<?= $app['jobId'] ?>">
                            <i class="bi bi-eye me-2"></i>View Job
                          </a>
                        </li>
                        <li>
                          <button class="dropdown-item text-danger" 
                                  data-bs-toggle="modal" 
                                  data-bs-target="#withdrawModal-<?= $app['applicationId'] ?>">
                            <i class="bi bi-x-circle me-2"></i>Withdraw
                          </button>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="text-center py-5">
            <p class="text-muted">No applications found</p>
            <a href="/pages/public/jobs.php" class="btn btn-primary">
              Browse Jobs
            </a>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Pagination -->
    <?php if ($totalApplications > $perPage): ?>
      <nav class="mt-4">
        <?php include __DIR__ . '/../../../includes/pagination.php'; ?>
      </nav>
    <?php endif; ?>
  </div>
</div>

<?php include __DIR__ . '/../../../includes/footer.php'; ?>