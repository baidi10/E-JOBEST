<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';

$pageTitle = "Career Resources | JOBEST";
include __DIR__ . '/../../includes/header.php';
?>

<div class="container py-5">
    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-lg-8 mx-auto text-center">
            <h1 class="display-5 fw-bold mb-4">Resources</h1>
            <p class="lead text-muted">
                Access guides, tools, and expert advice to help you succeed in your job search and career development.
            </p>
        </div>
    </div>

    <!-- Resource Categories -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <a href="<?= Config::BASE_URL ?>/pages/public/resources-job-search.php" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body p-4 text-center">
                        <div class="icon-circle bg-light mb-3 mx-auto">
                            <i class="bi bi-search fs-3 text-primary"></i>
                        </div>
                        <h3 class="h5 fw-bold">Job Search Tips</h3>
                        <p class="text-muted">Strategies to find the right job and stand out to employers.</p>
                    </div>
                </div>
            </a>
        </div>
        
        <div class="col-md-4">
            <a href="<?= Config::BASE_URL ?>/pages/public/resources-resume.php" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body p-4 text-center">
                        <div class="icon-circle bg-light mb-3 mx-auto">
                            <i class="bi bi-file-earmark-text fs-3 text-primary"></i>
                        </div>
                        <h3 class="h5 fw-bold">Resume & Cover Letter</h3>
                        <p class="text-muted">Create compelling documents that get you interviews.</p>
                    </div>
                </div>
            </a>
        </div>
        
        <div class="col-md-4">
            <a href="<?= Config::BASE_URL ?>/pages/public/resources-interview.php" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body p-4 text-center">
                        <div class="icon-circle bg-light mb-3 mx-auto">
                            <i class="bi bi-chat-left-text fs-3 text-primary"></i>
                        </div>
                        <h3 class="h5 fw-bold">Interview Preparation</h3>
                        <p class="text-muted">Ace your interviews with our preparation guides and tips.</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
    
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <a href="<?= Config::BASE_URL ?>/pages/public/salary-guide.php" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body p-4 text-center">
                        <div class="icon-circle bg-light mb-3 mx-auto">
                            <i class="bi bi-cash-stack fs-3 text-primary"></i>
                        </div>
                        <h3 class="h5 fw-bold">Salary Guide</h3>
                        <p class="text-muted">Comprehensive salary information for different roles and industries.</p>
                    </div>
                </div>
            </a>
        </div>
        
        <div class="col-md-4">
            <a href="<?= Config::BASE_URL ?>/pages/public/resources-career-growth.php" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body p-4 text-center">
                        <div class="icon-circle bg-light mb-3 mx-auto">
                            <i class="bi bi-graph-up-arrow fs-3 text-primary"></i>
                        </div>
                        <h3 class="h5 fw-bold">Career Development</h3>
                        <p class="text-muted">Tools and advice for professional growth and advancement.</p>
                    </div>
                </div>
            </a>
        </div>
        
        <div class="col-md-4">
            <a href="<?= Config::BASE_URL ?>/pages/public/resources-remote-work.php" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body p-4 text-center">
                        <div class="icon-circle bg-light mb-3 mx-auto">
                            <i class="bi bi-laptop fs-3 text-primary"></i>
                        </div>
                        <h3 class="h5 fw-bold">Remote Work</h3>
                        <p class="text-muted">Tips for finding and succeeding in remote positions.</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Featured Resource -->
    <div class="card border-0 bg-light shadow-sm mb-5">
        <div class="card-body p-4 p-lg-5">
            <div class="row align-items-center">
                <div class="col-lg-8 mb-4 mb-lg-0">
                    <h3 class="h4 fw-bold">Complete Tech Career Guide</h3>
                    <p class="mb-4">
                        Our most comprehensive resource for tech professionals. Learn about in-demand skills, 
                        career paths, and strategies for long-term success in the technology sector.
                    </p>
                    <a href="<?= Config::BASE_URL ?>/pages/public/resources-tech-career.php" class="btn btn-dark rounded-pill px-4">
                        Read the Guide
                    </a>
                </div>
                <div class="col-lg-4 text-center">
                    <img src="/assets/images/tech-career-guide.svg" alt="Tech Career Guide" class="img-fluid" style="max-height: 200px;">
                </div>
            </div>
        </div>
    </div>
    
    <!-- For Employers -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="border-bottom pb-2 mb-4">
                <h2 class="h3 fw-bold">For Employers</h2>
            </div>
        </div>
    </div>
    
    <div class="row g-4 mb-5">
        <div class="col-md-6">
            <a href="<?= Config::BASE_URL ?>/pages/employer/hiring-guide.php" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body p-4">
                        <h3 class="h5 fw-bold">Hiring Guide</h3>
                        <p class="text-muted mb-3">Best practices for attracting and selecting top talent.</p>
                        <div class="text-primary">Learn more <i class="bi bi-arrow-right"></i></div>
                    </div>
                </div>
            </a>
        </div>
        
        <div class="col-md-6">
            <a href="<?= Config::BASE_URL ?>/pages/employer/post-job.php" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body p-4">
                        <h3 class="h5 fw-bold">Post a Job</h3>
                        <p class="text-muted mb-3">Ready to find your next great hire? Post a job opening today.</p>
                        <div class="text-primary">Get Started <i class="bi bi-arrow-right"></i></div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<style>
.icon-circle {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.hover-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}
</style>

<?php include __DIR__ . '/../../includes/footer.php'; ?> 