<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../classes/Company.php';
require_once __DIR__ . '/../../classes/Job.php';

// Get company ID from URL
$companyId = $_GET['id'] ?? 0;

// Initialize Company class
$company = new Company();
$jobs = new Job();

// Get company details
$companyData = $company->getDetails($companyId);

// If company not found, redirect to companies list
if (!$companyData) {
    header('Location: /pages/public/companies.php');
    exit;
}

// Get jobs from this company - create a basic structure if method doesn't exist
$companyJobs = method_exists($jobs, 'getJobsByCompany') 
    ? $jobs->getJobsByCompany($companyId, 1, 6) 
    : ['jobs' => [], 'total' => 0];

$pageTitle = htmlspecialchars($companyData['companyName']) . " | JOBEST";
include __DIR__ . '/../../includes/header.php';
?>

<div class="container py-5">
    <!-- Company Header -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center mb-3">
                        <?php $logoFile = !empty($companyData['logo']) ? $companyData['logo'] : 'default.png'; ?>
                        <img src="<?= getCompanyAvatar($companyId, 80) ?>" 
                             alt="<?= htmlspecialchars($companyData['companyName']) ?>" 
                             class="rounded-circle me-3 detail-company-logo" width="80" height="80">
                        
                        <div>
                            <h1 class="h3 fw-bold mb-1">
                                <?= htmlspecialchars($companyData['companyName']) ?>
                                <?php if (!empty($companyData['isVerified'])): ?>
                                <span class="badge bg-success align-middle" style="font-size: 0.6rem;">
                                    <i class="bi bi-check-circle-fill me-1"></i>Verified
                                </span>
                                <?php endif; ?>
                            </h1>
                            <p class="text-muted mb-0">
                                <?= htmlspecialchars($companyData['industry'] ?? 'Technology') ?>
                                <?php if (!empty($companyData['headquarters'])): ?>
                                • <?= htmlspecialchars($companyData['headquarters']) ?>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                    <?php if (!empty($companyData['websiteUrl'])): ?>
                    <a href="<?= htmlspecialchars($companyData['websiteUrl']) ?>" target="_blank" class="btn btn-outline-primary me-2">
                        <i class="bi bi-globe me-1"></i>Visit Website
                    </a>
                    <?php endif; ?>
                    <a href="/pages/public/jobs.php?company=<?= $companyId ?>" class="btn btn-dark">
                        View All Jobs
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row g-4">
        <!-- Company Details -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <h2 class="h5 fw-bold mb-3">About <?= htmlspecialchars($companyData['companyName']) ?></h2>
                    
                    <div class="description mb-4">
                        <?php if (!empty($companyData['description'])): ?>
                            <p><?= nl2br(htmlspecialchars($companyData['description'])) ?></p>
                        <?php else: ?>
                            <p class="text-muted">No company description available.</p>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Company Jobs -->
                    <?php if (!empty($companyJobs['jobs'])): ?>
                    <div class="mt-5">
                        <h3 class="h5 fw-bold mb-3">Open Positions</h3>
                        <div class="list-group">
                            <?php foreach ($companyJobs['jobs'] as $job): ?>
                            <a href="/pages/public/job-details.php?id=<?= $job['jobId'] ?>" class="list-group-item list-group-item-action border-0 mb-2 rounded">
                                <div class="d-flex w-100 justify-content-between align-items-center">
                                    <h5 class="mb-1"><?= htmlspecialchars($job['jobTitle']) ?></h5>
                                    <?php if (!empty($job['location'])): ?>
                                    <span class="badge bg-light text-dark"><?= htmlspecialchars($job['location']) ?></span>
                                    <?php endif; ?>
                                </div>
                                <p class="mb-1 text-muted small"><?= htmlspecialchars($job['jobType'] ?? 'Full-time') ?></p>
                            </a>
                            <?php endforeach; ?>
                        </div>
                        
                        <?php if ($companyJobs['total'] > count($companyJobs['jobs'])): ?>
                        <div class="text-center mt-3">
                            <a href="/pages/public/jobs.php?company=<?= $companyId ?>" class="btn btn-outline-primary btn-sm">
                                View All <?= $companyJobs['total'] ?> Jobs
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Company Sidebar -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <h3 class="h6 fw-bold mb-3">Company Information</h3>
                    
                    <ul class="list-unstyled">
                        <?php if (!empty($companyData['industry'])): ?>
                        <li class="d-flex mb-3">
                            <i class="bi bi-building me-2 text-muted"></i>
                            <div>
                                <span class="d-block text-muted small">Industry</span>
                                <span><?= htmlspecialchars($companyData['industry']) ?></span>
                            </div>
                        </li>
                        <?php endif; ?>
                        
                        <?php if (!empty($companyData['employeeCount'])): ?>
                        <li class="d-flex mb-3">
                            <i class="bi bi-people me-2 text-muted"></i>
                            <div>
                                <span class="d-block text-muted small">Company Size</span>
                                <span><?= htmlspecialchars($companyData['employeeCount']) ?></span>
                            </div>
                        </li>
                        <?php endif; ?>
                        
                        <?php if (!empty($companyData['foundedYear'])): ?>
                        <li class="d-flex mb-3">
                            <i class="bi bi-calendar-event me-2 text-muted"></i>
                            <div>
                                <span class="d-block text-muted small">Founded</span>
                                <span><?= htmlspecialchars($companyData['foundedYear']) ?></span>
                            </div>
                        </li>
                        <?php endif; ?>
                        
                        <?php if (!empty($companyData['headquarters'])): ?>
                        <li class="d-flex mb-3">
                            <i class="bi bi-geo-alt me-2 text-muted"></i>
                            <div>
                                <span class="d-block text-muted small">Location</span>
                                <span><?= htmlspecialchars($companyData['headquarters']) ?></span>
                            </div>
                        </li>
                        <?php endif; ?>
                        
                        <?php if (!empty($companyData['websiteUrl'])): ?>
                        <li class="d-flex">
                            <i class="bi bi-globe me-2 text-muted"></i>
                            <div>
                                <span class="d-block text-muted small">Website</span>
                                <a href="<?= htmlspecialchars($companyData['websiteUrl']) ?>" target="_blank">
                                    <?= htmlspecialchars(preg_replace('#^https?://#', '', $companyData['websiteUrl'])) ?>
                                </a>
                            </div>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            
            <!-- Similar Companies - Optional Feature -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h3 class="h6 fw-bold mb-3">Similar Companies</h3>
                    
                    <div class="d-flex flex-column gap-3">
                        <?php if (!empty($companyData['industry'])): ?>
                        <p class="small text-muted">Explore more companies in <?= htmlspecialchars($companyData['industry']) ?></p>
                        <a href="/pages/public/companies.php?industry=<?= urlencode($companyData['industry']) ?>" class="btn btn-outline-dark btn-sm">
                            View Similar Companies
                        </a>
                        <?php else: ?>
                        <p class="small text-muted">Explore more companies on our platform</p>
                        <a href="/pages/public/companies.php" class="btn btn-outline-dark btn-sm">
                            Browse All Companies
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?> 