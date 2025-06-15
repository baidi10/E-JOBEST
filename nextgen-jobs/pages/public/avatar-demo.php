<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../classes/Database.php';
require_once __DIR__ . '/../../classes/Avatar.php';

$pageTitle = "Avatar Demo | JOBEST";
include __DIR__ . '/../../includes/header.php';

// Get a sample of users from the database
$db = Database::getInstance()->getConnection();
$stmt = $db->query("SELECT userId, email, firstName, lastName FROM users LIMIT 20");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Initialize avatar generator
$avatar = new Avatar();
?>

<div class="container py-5">
    <div class="card shadow-sm border-0 mb-5">
        <div class="card-body p-4">
            <h1 class="h3 fw-bold mb-4">Letter-Based Avatar System</h1>
            <p class="text-muted mb-4">This system automatically generates personalized avatars based on the first letter of a user's email address. Each avatar has a unique color that is consistently generated from the user's email.</p>
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <h2 class="h5 fw-bold mb-3">Benefits</h2>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item bg-transparent px-0">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            No image upload required
                        </li>
                        <li class="list-group-item bg-transparent px-0">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Instant visual identification
                        </li>
                        <li class="list-group-item bg-transparent px-0">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Efficient storage (uses SVG data URIs)
                        </li>
                        <li class="list-group-item bg-transparent px-0">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Consistent colors for the same user
                        </li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h2 class="h5 fw-bold mb-3">Implementation</h2>
                    <p>The avatars are generated using SVG with the following algorithm:</p>
                    <ol>
                        <li>Extract the first letter of the email address</li>
                        <li>Generate a consistent color using an MD5 hash of the email</li>
                        <li>Create an SVG with a colored background and white letter</li>
                        <li>Convert to a data URI for direct embedding in HTML</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    
    <h2 class="h4 fw-bold mb-4">User Avatar Examples</h2>
    
    <div class="row g-4">
        <?php foreach ($users as $user): ?>
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <img src="<?= $avatar->generateDataURI($user['email'], 80) ?>" 
                             alt="<?= htmlspecialchars($user['firstName'] . ' ' . $user['lastName']) ?>" 
                             class="mb-3">
                        <h3 class="h6 fw-bold mb-1"><?= htmlspecialchars($user['firstName'] . ' ' . $user['lastName']) ?></h3>
                        <p class="text-muted small mb-0"><?= htmlspecialchars($user['email']) ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <div class="text-center mt-5">
        <a href="/pages/admin/index.php" class="btn btn-outline-dark rounded-pill px-4 me-2">Admin Panel</a>
        <a href="/update_avatars.php" class="btn btn-dark rounded-pill px-4">Update All Avatars</a>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?> 