<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../classes/Database.php';
require_once __DIR__ . '/../../classes/Job.php';

$job = new Job();
$featuredJobs = $job->getFeaturedJobs(8);
$topCompanies = method_exists($job, 'getTopCompanies') ? $job->getTopCompanies(6) : [];

$pageTitle = "Find Your Next Tech Job | JOBEST";
include __DIR__ . '/../../includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section position-relative overflow-hidden">
  <div class="container text-center py-5">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <h1 class="display-3 fw-bold mb-4">
          Find what's next
    </h1>
    
    <!-- Search Form -->
        <div class="search-box mx-auto" style="max-width: 600px;">
          <form action="<?= Config::BASE_URL ?>/pages/public/jobs.php" method="get" class="shadow-sm rounded-pill bg-white p-2 border">
        <div class="input-group">
          <span class="input-group-text border-0 bg-transparent ps-4">
            <i class="bi bi-search text-muted fs-5"></i>
          </span>
          <input type="text" class="form-control border-0 py-3" 
                 placeholder="Job title, keywords, or company" name="q"
                 aria-label="Search jobs">
              <button class="btn btn-dark rounded-pill px-4 fw-bold" type="submit">
            Search Jobs
          </button>
        </div>
      </form>
        </div>
      </div>
    </div>
    
    <!-- Quick Links -->
    <div class="quick-links mt-5 small">
      <div class="d-flex flex-wrap justify-content-center text-muted">
        <a href="<?= Config::BASE_URL ?>/pages/public/jobs.php?category=development" class="text-decoration-none text-muted mx-2 mb-2">Full Stack Developers</a>
        <a href="<?= Config::BASE_URL ?>/pages/public/jobs.php?category=frontend" class="text-decoration-none text-muted mx-2 mb-2">Front End Developers</a>
        <a href="<?= Config::BASE_URL ?>/pages/public/jobs.php?category=ios" class="text-decoration-none text-muted mx-2 mb-2">iOS Developers</a>
        <a href="<?= Config::BASE_URL ?>/pages/public/jobs.php?category=android" class="text-decoration-none text-muted mx-2 mb-2">Android Developers</a>
        <a href="<?= Config::BASE_URL ?>/pages/public/jobs.php?location=newyork" class="text-decoration-none text-muted mx-2 mb-2">New York</a>
        <a href="<?= Config::BASE_URL ?>/pages/public/jobs.php?location=sanfrancisco" class="text-decoration-none text-muted mx-2 mb-2">San Francisco</a>
        <a href="<?= Config::BASE_URL ?>/pages/public/jobs.php?category=ai" class="text-decoration-none text-muted mx-2 mb-2">Artificial Intelligence</a>
      </div>
    </div>
  </div>
</section>

<!-- Main Headline -->
<section class="py-5 border-bottom">
  <div class="container text-center">
    <h2 class="display-6 fw-bold mb-4">Where startups and job seekers connect</h2>
    <div class="d-flex justify-content-center gap-4">
      <a href="<?= Config::BASE_URL ?>/pages/employer/post-job.php" class="btn btn-dark rounded-pill px-4 fw-bold">Find your next hire</a>
      <a href="<?= Config::BASE_URL ?>/pages/public/jobs.php" class="btn btn-outline-dark rounded-pill px-4 fw-bold">Find your next job</a>
    </div>
  </div>
</section>

<!-- Stats Section -->
<section class="py-5 bg-light">
  <div class="container text-center">
    <div class="row justify-content-center">
      <div class="col-md-4">
        <div class="stat-item mb-4">
          <div class="display-4 fw-bold">8M+</div>
          <p class="text-muted">Matches Made</p>
            </div>
          </div>
      <div class="col-md-4">
        <div class="stat-item mb-4">
          <div class="display-4 fw-bold">150K+</div>
          <p class="text-muted">Tech Jobs</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="stat-item mb-4">
          <div class="display-4 fw-bold">10M+</div>
          <p class="text-muted">Startup Ready Candidates</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Company Logos -->
<section class="py-5 border-bottom">
  <div class="container">
    <div class="logo-scroll d-flex flex-wrap justify-content-center align-items-center gap-5">
      <?php for ($i = 1; $i <= 6; $i++): ?>
        <div class="logo-item">
          <img src="/assets/images/logos/company-<?= $i ?>.png" alt="Company Logo" height="30">
        </div>
      <?php endfor; ?>
            </div>
          </div>
</section>

<!-- Value Props Section -->
<section class="py-6">
  <div class="container">
    <div class="row">
      <div class="col-lg-6 mb-5">
        <h3 class="h2 fw-bold mb-4">Why job seekers love us</h3>
        <ul class="list-unstyled">
          <li class="mb-4">
            <p class="fw-medium">Connect directly with founders at top startups - no third party recruiters allowed.</p>
          </li>
          <li class="mb-4">
            <p class="fw-medium">Everything you need to know, all upfront. View salary, stock options, and more before applying.</p>
          </li>
          <li class="mb-4">
            <p class="fw-medium">Say goodbye to cover letters - your profile is all you need. One click to apply and you're done.</p>
          </li>
          <li class="mb-4">
            <p class="fw-medium">Unique jobs at startups and tech companies you can't find anywhere else.</p>
          </li>
        </ul>
        <div class="mt-4">
          <a href="/pages/public/register.php" class="btn btn-dark rounded-pill px-4 me-3">Sign up</a>
          <a href="#" class="text-decoration-none fw-medium">Learn more</a>
        </div>
      </div>
      
      <div class="col-lg-6 mb-5 bg-light rounded-3 p-5">
        <h3 class="h2 fw-bold mb-4">Why recruiters love us</h3>
        <ul class="list-unstyled">
          <li class="mb-4">
            <p class="fw-medium">Tap into a community of 10M+ engaged, startup-ready candidates.</p>
          </li>
          <li class="mb-4">
            <p class="fw-medium">Everything you need to kickstart your recruiting — set up job posts, company branding, and HR tools within 10 minutes, all for free.</p>
          </li>
          <li class="mb-4">
            <p class="fw-medium">A free applicant tracking system, or free integration with any ATS you may already use.</p>
          </li>
        </ul>
        <div class="mt-4">
          <a href="<?= Config::BASE_URL ?>/pages/employer/register.php" class="btn btn-dark rounded-pill px-4 me-3">Sign up</a>
          <a href="#" class="text-decoration-none fw-medium">Learn more</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Featured Jobs Section -->
<section class="py-6 bg-light">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-5">
      <h2 class="h3 fw-bold">Featured Tech Jobs</h2>
      <a href="<?= Config::BASE_URL ?>/pages/public/jobs.php" class="btn btn-link text-dark text-decoration-none d-flex align-items-center">
        View all jobs <i class="bi bi-arrow-right ms-2"></i>
      </a>
    </div>
    
    <div class="row g-4">
      <?php if (!empty($featuredJobs) && is_array($featuredJobs)): ?>
        <?php foreach ($featuredJobs as $job): ?>
          <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
              <div class="card-body p-4">
                <?php if (!empty($job['companyLogo'])): ?>
                  <img src="<?= htmlspecialchars($job['companyLogo']) ?>" alt="<?= htmlspecialchars($job['companyName']) ?>" class="mb-3" height="30">
                <?php else: ?>
                  <div class="company-placeholder mb-3 fw-bold"><?= substr(htmlspecialchars($job['companyName'] ?? 'CO'), 0, 2) ?></div>
                <?php endif; ?>
                <h5 class="card-title mb-1"><a href="<?= Config::BASE_URL ?>/pages/public/job-details.php?id=<?= $job['jobId'] ?>" class="text-decoration-none text-dark"><?= htmlspecialchars($job['jobTitle']) ?></a></h5>
                <p class="text-muted small mb-3"><?= htmlspecialchars($job['companyName']) ?></p>
                <div class="d-flex align-items-center small mb-3">
                  <i class="bi bi-geo-alt me-1"></i>
                  <span><?= !empty($job['location']) ? htmlspecialchars($job['location']) : 'Remote' ?></span>
                </div>
                <?php if (!empty($job['salaryMin']) && !empty($job['salaryMax'])): ?>
                  <div class="text-success fw-medium small mb-3">
                    $<?= number_format($job['salaryMin']) ?> - $<?= number_format($job['salaryMax']) ?>
                  </div>
                <?php endif; ?>
                <div class="mt-auto pt-2">
                  <a href="<?= Config::BASE_URL ?>/pages/public/job-details.php?id=<?= $job['jobId'] ?>" class="btn btn-sm btn-outline-dark rounded-pill px-3">View Job</a>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="col-12 text-center py-5">
          <p class="text-muted">No featured jobs found. Check back soon!</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- CTA Section -->
<section class="py-6 bg-black text-white">
  <div class="container text-center">
    <h2 class="display-6 fw-bold mb-4">Ready to find your dream job?</h2>
    <p class="lead mb-5 mx-auto" style="max-width: 600px;">
      Join thousands of candidates and companies already using JOBEST to connect
    </p>
    <div class="d-flex gap-3 justify-content-center">
      <a href="<?= Config::BASE_URL ?>/pages/public/register.php" class="btn btn-light btn-lg px-5 fw-bold rounded-pill">
        Sign Up Free
      </a>
      <a href="<?= Config::BASE_URL ?>/pages/public/jobs.php" class="btn btn-outline-light btn-lg px-5 fw-bold rounded-pill">
        Browse Jobs
      </a>
    </div>
  </div>
</section>

<?php include __DIR__ . '/../../includes/footer.php'; ?>