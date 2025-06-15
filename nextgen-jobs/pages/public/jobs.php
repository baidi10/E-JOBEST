<?php
require_once __DIR__ . '/../../includes/dependencies.php';

$currentPage = max(1, $_GET['page'] ?? 1);
$perPage = 12;
$query = $_GET['q'] ?? '';
$filters = [
    'location' => $_GET['location'] ?? '',
    'jobType' => $_GET['jobType'] ?? '',
    'experienceLevel' => $_GET['experienceLevel'] ?? ''
];

$job = new Job();
$result = $job->searchJobs($query, $filters, $currentPage, $perPage);

$pageTitle = "Tech Jobs | JOBEST";
include __DIR__ . '/../../includes/header.php';
?>

<div class="container py-5">
  <div class="row">
    <!-- Filters Sidebar -->
    <div class="col-lg-3 mb-4">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <h3 class="h6 fw-bold mb-3">Filter Jobs</h3>
          
          <!-- Location Filter -->
          <div class="mb-4">
            <label class="form-label small fw-bold">Location</label>
            <input type="text" class="form-control" 
                   name="location" placeholder="City or country"
                   value="<?= htmlspecialchars($filters['location']) ?>">
          </div>
          
          <!-- Job Type Filter -->
          <div class="mb-4">
            <label class="form-label small fw-bold">Job Type</label>
            <select class="form-select" name="jobType">
              <option value="">All Types</option>
              <option value="fullTime" <?= $filters['jobType'] === 'fullTime' ? 'selected' : '' ?>>Full Time</option>
              <option value="partTime" <?= $filters['jobType'] === 'partTime' ? 'selected' : '' ?>>Part Time</option>
              <option value="remote" <?= $filters['jobType'] === 'remote' ? 'selected' : '' ?>>Remote</option>
            </select>
          </div>
          
          <!-- Experience Filter -->
          <div class="mb-4">
            <label class="form-label small fw-bold">Experience</label>
            <select class="form-select" name="experienceLevel">
              <option value="">All Levels</option>
              <option value="entryLevel" <?= $filters['experienceLevel'] === 'entryLevel' ? 'selected' : '' ?>>Entry Level</option>
              <option value="midLevel" <?= $filters['experienceLevel'] === 'midLevel' ? 'selected' : '' ?>>Mid Level</option>
              <option value="senior" <?= $filters['experienceLevel'] === 'senior' ? 'selected' : '' ?>>Senior</option>
            </select>
          </div>
          
          <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
        </div>
      </div>
    </div>

    <!-- Job Listings -->
    <div class="col-lg-9">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 fw-bold">Tech Jobs</h1>
        <span class="text-muted small"><?= number_format($result['total']) ?> results</span>
      </div>

      <div class="row g-4">
        <?php if (count($result['jobs']) > 0): ?>
          <?php foreach ($result['jobs'] as $job): ?>
            <div class="col-md-6">
              <?php include __DIR__ . '/../../includes/job-card.php'; ?>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="col-12">
            <div class="card border-0 shadow-sm">
              <div class="card-body text-center py-5">
                <h3 class="h5 mb-3">No jobs found</h3>
                <p class="text-muted">Try adjusting your filters or search terms</p>
              </div>
            </div>
          </div>
        <?php endif; ?>
      </div>

      <!-- Pagination -->
      <?php if ($result['pages'] > 1): ?>
        <nav class="mt-5">
          <?php include __DIR__ . '/../../includes/pagination.php'; ?>
        </nav>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>