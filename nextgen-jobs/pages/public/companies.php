<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../classes/Company.php';

$currentPage = max(1, $_GET['page'] ?? 1);
$perPage = 12;
$searchQuery = $_GET['q'] ?? '';
$industryFilter = $_GET['industry'] ?? '';

$company = new Company();

// Check if the searchCompanies method exists in the Company class
if (method_exists($company, 'searchCompanies')) {
    $result = $company->searchCompanies($searchQuery, $industryFilter, $currentPage, $perPage);
} else {
    // If the method doesn't exist, create a default result structure
    $result = [
        'companies' => [],
        'total' => 0,
        'pages' => 0,
        'currentPage' => $currentPage
    ];
}

$pageTitle = "Tech Companies | JOBEST";
include __DIR__ . '/../../includes/header.php';
?>

<div class="container py-5">
  <div class="row">
    <!-- Filters -->
    <div class="col-lg-3 mb-4">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <h3 class="h6 fw-bold mb-3">Filter Companies</h3>
          
          <!-- Search Form -->
          <form method="get" class="mb-4">
            <div class="input-group">
              <input type="text" class="form-control" 
                     name="q" placeholder="Search companies"
                     value="<?= htmlspecialchars($searchQuery) ?>">
              <button class="btn btn-outline-secondary" type="submit">
                <i class="bi bi-search"></i>
              </button>
            </div>

          <!-- Industry Filter -->
            <div class="mt-3">
            <label class="form-label small fw-bold">Industry</label>
              <select class="form-select" name="industry" onchange="this.form.submit()">
              <option value="">All Industries</option>
              <option value="Software" <?= $industryFilter === 'Software' ? 'selected' : '' ?>>Software</option>
              <option value="Fintech" <?= $industryFilter === 'Fintech' ? 'selected' : '' ?>>Fintech</option>
              <option value="AI/ML" <?= $industryFilter === 'AI/ML' ? 'selected' : '' ?>>AI/ML</option>
              <option value="Cybersecurity" <?= $industryFilter === 'Cybersecurity' ? 'selected' : '' ?>>Cybersecurity</option>
            </select>
            </div>
          </form>

          <!-- This div can be used for additional filters if needed -->
          <div class="mb-4">
          </div>
        </div>
      </div>
    </div>

    <!-- Company List -->
    <div class="col-lg-9">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 fw-bold">Tech Companies</h1>
        <span class="text-muted small"><?= number_format($result['total']) ?> results</span>
      </div>

      <div class="row g-4">
        <?php if (!empty($result['companies']) && is_array($result['companies'])): ?>
          <?php foreach ($result['companies'] as $companyItem): ?>
            <div class="col-md-6 col-lg-4 mb-4">
              <div class="card border-0 shadow-sm h-100 company-card">
                <div class="card-body p-4">
                <div class="d-flex align-items-start gap-3 mb-3">
                    <?php 
                    $logoFile = !empty($companyItem['logo']) ? $companyItem['logo'] : 'default.png';
                    ?>
                    <img src="/assets/uploads/logos/<?= htmlspecialchars($logoFile) ?>" 
                        alt="<?= htmlspecialchars($companyItem['companyName'] ?? 'Company') ?>" 
                        class="rounded-circle company-logo"
                        onerror="this.src='/assets/uploads/logos/default.png'">
                  <div>
                    <h2 class="h6 fw-bold mb-0">
                        <a href="/pages/public/company-details.php?id=<?= $companyItem['companyId'] ?? '#' ?>" 
                         class="text-decoration-none">
                          <?= htmlspecialchars($companyItem['companyName'] ?? 'Company Name') ?>
                      </a>
                    </h2>
                    <p class="small text-muted mb-0">
                        <?= htmlspecialchars($companyItem['industry'] ?? 'Technology') ?>
                    </p>
                  </div>
                </div>
                <p class="small text-muted mb-3 line-clamp-3">
                    <?= htmlspecialchars($companyItem['description'] ?? 'No description available.') ?>
                </p>
                  <div class="d-flex gap-2 mt-auto">
                    <?php if (!empty($companyItem['employeeCount'])): ?>
                  <span class="badge bg-light text-dark">
                      <?= htmlspecialchars($companyItem['employeeCount']) ?>
                  </span>
                    <?php endif; ?>
                    <?php if (!empty($companyItem['isVerified']) && $companyItem['isVerified']): ?>
                    <span class="badge bg-success">
                      <i class="bi bi-check-circle me-1"></i>Verified
                    </span>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
        <?php else: ?>
          <div class="col-12">
            <div class="alert alert-info">
              <i class="bi bi-info-circle me-2"></i>No companies found. Please try a different search.
            </div>
          </div>
        <?php endif; ?>
      </div>

      <!-- Pagination -->
      <?php if (isset($result['pages']) && $result['pages'] > 1): ?>
        <nav class="mt-5">
          <?php include __DIR__ . '/../../includes/pagination.php'; ?>
        </nav>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>