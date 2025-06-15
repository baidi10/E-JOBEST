<?php
// includes/header.php
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/helpers.php';
$pageTitle = $pageTitle ?? 'JOBEST';
$themeColors = getThemeColors();

// Get user information from database using helper functions
$userInfo = getCurrentUser();
$userType = getUserType();
$isLoggedIn = isLoggedIn();
$userId = $_SESSION['user_id'] ?? null;

// User display name
$userDisplayName = $userInfo ? $userInfo['firstName'] . ' ' . $userInfo['lastName'] : 'Account';

// Get current page to highlight active navigation item
$currentPage = basename($_SERVER['PHP_SELF']);
$currentDir = basename(dirname($_SERVER['PHP_SELF']));

// Helper function to check if a link should be active
if (!function_exists('isActiveLink')) {
    function isActiveLink($path) {
        $currentPage = basename($_SERVER['PHP_SELF']);
        return $currentPage === $path ? 'active' : '';
    }
}

// Load necessary classes and get database instance
require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/Job.php';

$db = Database::getInstance();

$job = new Job();
$jobTypes = $job->getJobTypes();
$popularSearchTerms = array_slice($job->getPopularSearchTerms(5), 0, 3);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    
    <!-- Inter font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- CSS -->
    <link href="<?= Config::BASE_URL ?>/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= Config::BASE_URL ?>/assets/css/style.css" rel="stylesheet">
    <link href="<?= Config::BASE_URL ?>/assets/css/responsive.css" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" href="<?= Config::BASE_URL ?>/assets/images/logo2.png" type="image/png">
    
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Meta tags -->
    <meta name="description" content="Find your next dream job at JOBEST. Browse thousands of job listings from top companies.">
    <meta property="og:image" content="<?= Config::BASE_URL ?>/assets/images/social-share.jpg">
    
    <style>
        /* Remove obsolete admin layout styles */
        .admin-content,
        .admin-header {
           /* These styles are no longer needed with the top header */
           /* You can remove or comment them out */
        }

        /* Active navigation styles */
        .nav-link.active {
            color: #000 !important;
            font-weight: 700 !important;
        }
        
        .dropdown-item.active {
            background-color: #f8f9fa;
            color: #000;
            font-weight: 600;
        }

        /* Adjust header and logo size */
        .navbar {
            padding: 0.5rem 0;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            background-color: rgba(255, 255, 255, 0.8);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand img {
            height: 28px;
        }

        .nav-link {
            padding: 0.4rem 0.8rem;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .rounded-circle {
            width: 28px !important;
            height: 28px !important;
        }

        /* Notification badge styles */
        .notification-badge {
            position: absolute;
            top:6px;
            
            width: 7px !important;
            height: 7px !important;
            padding: 0 !important;
            font-size: 0 !important;
            transform: translate(50%, -50%);
            border-radius: 50%;
            background-color: #dc3545;
        }

        /* Profile status indicator styles */
        .profile-status {
            position: absolute;
            bottom: 2PX;
            left: 75%;
            width: 8px !important;
            height: 8px !important;
            display: inline-block !important;
            flex-shrink: 0;
            flex-grow: 0;
            transform: translate(-50%, 50%);
            border-radius: 50%;
            background-color: #198754;
        }
    </style>
</head>
<body>
    <!-- Regular Header for All Users -->
    <header class="navbar navbar-expand-lg py-2 border-bottom">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center w-100">
                <!-- Logo -->
                <a class="navbar-brand me-0" href="<?php 
                    if ($isLoggedIn) {
                        if ($userType === 'admin') {
                            echo Config::BASE_URL . '/pages/admin/dashboard.php';
                        } elseif ($userType === 'employer') {
                            echo Config::BASE_URL . '/pages/employer/dashboard.php';
                        } elseif ($userType === 'jobSeeker') {
                            echo Config::BASE_URL . '/pages/user/index.php';
                        } else {
                            echo Config::BASE_URL . '/pages/public/index.php';
                        }
                    } else {
                        echo Config::BASE_URL . '/pages/public/index.php';
                    }
                ?>">
                    <img src="<?= Config::BASE_URL ?>/assets/images/V5NR.png" alt="JOBEST" height="24">
                </a>
                
                <!-- Main Navigation -->
                <div class="d-none d-lg-flex flex-grow-1 mx-5">
                    <ul class="navbar-nav me-auto main-navigation">
                        <?php if (!$isLoggedIn): ?>
                        <!-- PUBLIC NAVIGATION MENU -->
                        <li class="nav-item">
                            <a class="nav-link fw-medium <?= isActiveLink('index.php', 'public') ?>" href="<?= Config::BASE_URL ?>/pages/public/index.php">Home</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle fw-medium <?= isActiveLink('jobs.php', 'public') ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Find Jobs
                            </a>
                            <ul class="dropdown-menu shadow border-0 rounded-3 p-2">
                                <li><a class="dropdown-item py-2 px-3 rounded-2 <?= isActiveLink('jobs.php', 'public') ?>" href="<?= Config::BASE_URL ?>/pages/public/jobs.php">Browse All Jobs</a></li>
                                
                                <?php foreach ($jobTypes as $type): ?>
                                <li><a class="dropdown-item py-2 px-3 rounded-2" href="<?= Config::BASE_URL ?>/pages/public/jobs.php?jobType=<?= htmlspecialchars($type) ?>"><?= htmlspecialchars(ucfirst($type)) ?> Jobs</a></li>
                                <?php endforeach; ?>
                                
                                <li><hr class="dropdown-divider my-2"></li>
                                
                                <?php foreach ($popularSearchTerms as $term => $count): ?>
                                <li><a class="dropdown-item py-2 px-3 rounded-2" href="<?= Config::BASE_URL ?>/pages/public/jobs.php?q=<?= htmlspecialchars($term) ?>"><?= htmlspecialchars(ucfirst($term)) ?> Jobs</a></li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-medium <?= isActiveLink('companies.php', 'public') ?>" href="<?= Config::BASE_URL ?>/pages/public/companies.php">Companies</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-medium <?= isActiveLink('about.php', 'public') ?>" href="<?= Config::BASE_URL ?>/pages/public/about.php">About Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-medium <?= isActiveLink('contact.php', 'public') ?>" href="<?= Config::BASE_URL ?>/pages/public/contact.php">Contact</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle fw-medium <?= isActiveLink('salary-guide.php', 'public') ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Resources
                            </a>
                            <ul class="dropdown-menu shadow border-0 rounded-3 p-2">
                                <li><a class="dropdown-item py-2 px-3 rounded-2 <?= isActiveLink('salary-guide.php', 'public') ?>" href="<?= Config::BASE_URL ?>/pages/public/salary-guide.php">Salary Guide</a></li>
                                <li><a class="dropdown-item py-2 px-3 rounded-2 <?= isActiveLink('resources-job-search.php', 'public') ?>" href="<?= Config::BASE_URL ?>/pages/public/resources-job-search.php">Job Search Tips</a></li>
                                <li><a class="dropdown-item py-2 px-3 rounded-2 <?= isActiveLink('resources-interview.php', 'public') ?>" href="<?= Config::BASE_URL ?>/pages/public/resources-interview.php">Interview Preparation</a></li>
                            </ul>
                        </li>
                        
                        <?php elseif ($userType === 'admin'): ?>
                        <!-- ADMIN NAVIGATION MENU -->
                        <li class="nav-item">
                            <a class="nav-link fw-medium <?= isActiveLink('dashboard.php', 'admin') ?>" href="<?= Config::BASE_URL ?>/pages/admin/dashboard.php">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-medium <?= isActiveLink('manage-users.php', 'admin') ?>" href="<?= Config::BASE_URL ?>/pages/admin/manage-users.php">Users</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-medium <?= isActiveLink('manage-jobs.php', 'admin') ?>" href="<?= Config::BASE_URL ?>/pages/admin/manage-jobs.php">Jobs</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-medium <?= isActiveLink('reports.php', 'admin') ?>" href="<?= Config::BASE_URL ?>/pages/admin/reports.php">Reports</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-medium <?= isActiveLink('settings.php', 'admin') ?>" href="<?= Config::BASE_URL ?>/pages/admin/settings.php">Settings</a>
                        </li>

                        <?php elseif ($userType === 'employer'): ?>
                        <!-- EMPLOYER NAVIGATION MENU -->
                        <li class="nav-item">
                            <a class="nav-link fw-medium <?= isActiveLink('dashboard.php', 'employer') ?>" href="<?= Config::BASE_URL ?>/pages/employer/dashboard.php">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-medium <?= isActiveLink('post-job.php', 'employer') ?>" href="<?= Config::BASE_URL ?>/pages/employer/post-job.php">Post Job</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-medium <?= isActiveLink('manage-jobs.php', 'employer') ?>" href="<?= Config::BASE_URL ?>/pages/employer/manage-jobs.php">Manage Jobs</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-medium <?= isActiveLink('applicants.php', 'employer') ?>" href="<?= Config::BASE_URL ?>/pages/employer/applicants.php">Applicants</a>
                        </li>

                        <?php elseif ($userType === 'jobSeeker'): ?>
                        <!-- JOBSEEKER NAVIGATION MENU -->
                        <li class="nav-item">
                            <a class="nav-link fw-medium <?= isActiveLink('index.php', 'user') ?>" href="<?= Config::BASE_URL ?>/pages/user/index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-medium <?= isActiveLink('dashboard.php', 'user') ?>" href="<?= Config::BASE_URL ?>/pages/user/dashboard.php">Dashboard</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle fw-medium <?= in_array($currentPage, ['jobs.php', 'saved-jobs.php', 'applications.php']) && $currentDir === 'user' ? 'active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Jobs
                            </a>
                            <ul class="dropdown-menu shadow border-0 rounded-3 p-2">
                                <li><a class="dropdown-item py-2 px-3 rounded-2 <?= isActiveLink('jobs.php', 'user') ?>" href="<?= Config::BASE_URL ?>/pages/user/jobs.php">Find Jobs</a></li>
                                <li><a class="dropdown-item py-2 px-3 rounded-2 <?= isActiveLink('saved-jobs.php', 'user') ?>" href="<?= Config::BASE_URL ?>/pages/user/saved-jobs.php">Saved Jobs</a></li>
                            </ul>
                        </li>
                    <li class="nav-item">
                            <a class="nav-link fw-medium <?= isActiveLink('companies.php', 'user') ?>" href="<?= Config::BASE_URL ?>/pages/user/companies.php">Companies</a>
                    </li>
                    <li class="nav-item">
                            <a class="nav-link fw-medium <?= isActiveLink('applications.php', 'user') ?>" href="<?= Config::BASE_URL ?>/pages/user/applications.php">Applications</a>
                    </li>
                        <?php endif; ?>
                </ul>
                </div>
                
                <!-- User Actions -->
                <div class="d-flex align-items-center">
                    <?php if ($isLoggedIn): ?>
                        <!-- Icons for Messages and Notifications with fixed 10px spacing -->
                        <div class="d-flex align-items-center">
                            <?php 
                            // Only show icons for Job Seekers and Employers
                            if ($userType === 'jobSeeker' || $userType === 'employer'): 
                                
                                // Get unread messages count
                                $unreadMessages = 0;
                                try {
                                    $pdo = $db->getConnection(); // Get the PDO connection
                                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM messages WHERE recipientId = ? AND isRead = 0");
                                    $stmt->execute([$userId]);
                                    $unreadMessages = $stmt->fetchColumn();
                                } catch (Exception $e) {
                                    error_log("Error fetching unread messages: " . $e->getMessage());
                                }

                                // Get unread notifications count
                                $unreadNotifications = 0;
                                try {
                                    $pdo = $db->getConnection(); // Get the PDO connection
                                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM notifications WHERE userId = ? AND isRead = 0");
                                    $stmt->execute([$userId]);
                                    $unreadNotifications = $stmt->fetchColumn();
                                } catch (Exception $e) {
                                    error_log("Error fetching unread notifications: " . $e->getMessage());
                                }
                            ?>
                            <a href="<?= Config::BASE_URL ?>/pages/<?= $userType === 'jobSeeker' ? 'user' : 'employer' ?>/messages.php" class="btn btn-link p-0 text-dark position-relative" style="margin-right: 10px;" title="Messages">
                                <i class="bi bi-chat-dots fs-5"></i>
                                <?php if ($unreadMessages > 0): ?>
                                <span class="notification-badge"></span>
                                <?php endif; ?>
                            </a>
                            <!-- Notification bell icon -->
                            <a href="<?= Config::BASE_URL ?>/pages/<?= $userType === 'jobSeeker' ? 'user' : 'employer' ?>/notifications.php" class="btn btn-link p-0 text-dark position-relative" style="margin-right: 10px;" title="Notifications">
                                <i class="bi bi-bell fs-5"></i>
                                <?php if ($unreadNotifications > 0): ?>
                                <span class="notification-badge"></span>
                                <?php endif; ?>
                            </a>
                            <?php endif; ?>
                            <?php if ($userType === 'jobSeeker'): ?>
                            <a href="<?= Config::BASE_URL ?>/pages/user/saved-jobs.php" class="btn btn-link p-0 text-dark" style="margin-right: 10px;" title="Saved Jobs">
                                <i class="bi bi-bookmark fs-5"></i>
                            </a>
                            <?php endif; ?>
                            <!-- Logout Button with 10px margin -->
                            <a href="<?= Config::BASE_URL ?>/includes/auth.php?action=logout" class="btn btn-outline-dark btn-sm rounded-pill d-none d-lg-flex align-items-center" style="margin-right: 10px;">
                                <i class="bi bi-box-arrow-right me-1"></i> Logout
                            </a>
                        </div>
                        
                        <!-- User Avatar with direct link to profile -->
                        <?php 
                        $profileLink = '';
                        if ($userType === 'admin') {
                            $profileLink = Config::BASE_URL . '/pages/admin/settings.php';
                        } elseif ($userType === 'employer') {
                            $profileLink = Config::BASE_URL . '/pages/employer/company-profile.php';
                        } else {
                            $profileLink = Config::BASE_URL . '/pages/user/profile.php';
                        }

                        // Get user's profile photo for job seekers
                        $profilePhotoUrl = '';
                        if ($userType === 'jobSeeker') {
                            require_once __DIR__ . '/../classes/User.php';
                            $userObj = new User();
                            $jobSeekerData = $userObj->getJobSeekerProfile($userId);
                            
                            if (!empty($jobSeekerData['photo'])) {
                                $uploadedPhotoPath = Config::BASE_URL . '/assets/uploads/profiles/' . htmlspecialchars($jobSeekerData['photo']);
                                // Check if the file exists on the server
                                if (file_exists(__DIR__ . '/../assets/uploads/profiles/' . $jobSeekerData['photo'])) {
                                    $profilePhotoUrl = $uploadedPhotoPath;
                                }
                            }
                        }
                        ?>
                        <a href="<?= $profileLink ?>" class="d-flex align-items-center text-dark text-decoration-none position-relative">
                            <?php 
                            if ($userType === 'admin') {
                                // Show default admin photo
                                echo '<img src="' . Config::BASE_URL . '/assets/images/admin-default.png" width="40" height="40" alt="Admin" class="rounded-circle" title="Go to profile">';
                            } elseif ($userType === 'employer') {
                                require_once __DIR__ . '/../classes/Company.php';
                                $companyObj = new Company();
                                $companyId = $companyObj->getCompanyIdByUser($userId);
                                $companyProfile = $companyObj->getCompanyProfile($companyId);
                                $companyLogo = $companyProfile['logo'] ?? '';
                                if ($companyLogo && $companyLogo !== 'default.png') {
                                    // Show company logo
                                    $logoUrl = strpos($companyLogo, 'http') === 0 ? $companyLogo : (Config::BASE_URL . '/assets/uploads/company_logos/' . $companyLogo);
                                    echo '<img src="' . htmlspecialchars($logoUrl) . '" width="40" height="40" alt="Company Logo" class="rounded-circle" title="Go to profile">';
                                } else {
                                    echo '<div class="bg-dark text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;" title="Go to profile">
                                        <i class="bi bi-person-fill fs-5"></i>
                                    </div>';
                                }
                            } elseif ($userType === 'jobSeeker' && !empty($profilePhotoUrl)) {
                                echo '<img src="' . htmlspecialchars($profilePhotoUrl) . '" width="40" height="40" alt="' . htmlspecialchars($userDisplayName) . '" class="rounded-circle" title="Go to profile">';
                            } elseif (isset($userInfo['email'])) {
                                echo '<img src="' . getUserAvatar($_SESSION['user_id']) . '" width="40" height="40" alt="' . htmlspecialchars($userDisplayName) . '" class="rounded-circle" title="Go to profile">';
                            } else {
                                echo '<div class="bg-dark text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;" title="Go to profile">
                                    <i class="bi bi-person-fill fs-5"></i>
                                </div>';
                            }
                            ?>
                             <?php // Show active status dot only for Job Seekers and Employers
                             if ($userType === 'jobSeeker' || $userType === 'employer'): ?>
                            <!-- Active status indicator -->
                            <span class="profile-status">
                            
                            </span>
                            <?php endif; ?>
                        </a>
                    <?php else: ?>
                        <!-- Login/Register Buttons with 10px spacing -->
                        <div class="d-none d-lg-flex">
                            <a href="<?= Config::BASE_URL ?>/pages/public/login.php" class="btn btn-sm btn-outline-dark rounded-pill px-3" style="margin-right: 10px;">Log In</a>
                            <a href="<?= Config::BASE_URL ?>/pages/public/register.php" class="btn btn-sm btn-dark rounded-pill px-3">Sign Up</a>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Mobile menu toggle -->
                    <button class="navbar-toggler ms-3 d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>
            </div>
            
            <!-- Mobile navigation menu -->
            <div class="collapse navbar-collapse mt-3 d-lg-none" id="navbarNav">
                <ul class="navbar-nav">
                    <?php if (!$isLoggedIn): ?>
                        <!-- PUBLIC MOBILE MENU -->
                        <li class="nav-item"><a class="nav-link py-2 <?= isActiveLink('index.php', 'public') ?>" href="<?= Config::BASE_URL ?>/pages/public/index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link py-2 <?= isActiveLink('jobs.php', 'public') ?>" href="<?= Config::BASE_URL ?>/pages/public/jobs.php">Browse Jobs</a></li>
                        <li class="nav-item"><a class="nav-link py-2 <?= isActiveLink('companies.php', 'public') ?>" href="<?= Config::BASE_URL ?>/pages/public/companies.php">Companies</a></li>
                        <li class="nav-item"><a class="nav-link py-2 <?= isActiveLink('about.php', 'public') ?>" href="<?= Config::BASE_URL ?>/pages/public/about.php">About Us</a></li>
                        <li class="nav-item"><a class="nav-link py-2 <?= isActiveLink('contact.php', 'public') ?>" href="<?= Config::BASE_URL ?>/pages/public/contact.php">Contact</a></li>
                        <li class="nav-item"><a class="nav-link py-2 <?= isActiveLink('salary-guide.php', 'public') ?>" href="<?= Config::BASE_URL ?>/pages/public/salary-guide.php">Salary Guide</a></li>
                        <li><hr class="dropdown-divider my-2"></li>
                        <li class="nav-item"><a class="nav-link py-2 <?= isActiveLink('login.php', 'public') ?>" href="<?= Config::BASE_URL ?>/pages/public/login.php">Log In</a></li>
                        <li class="nav-item"><a class="nav-link py-2 <?= isActiveLink('register.php', 'public') ?>" href="<?= Config::BASE_URL ?>/pages/public/register.php">Sign Up</a></li>
                    <?php elseif ($userType === 'admin'): ?>
                        <!-- ADMIN MOBILE MENU -->
                        <li class="nav-item"><a class="nav-link py-2 <?= isActiveLink('dashboard.php', 'admin') ?>" href="<?= Config::BASE_URL ?>/pages/admin/dashboard.php">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link py-2 <?= isActiveLink('manage-users.php', 'admin') ?>" href="<?= Config::BASE_URL ?>/pages/admin/manage-users.php">Manage Users</a></li>
                        <li class="nav-item"><a class="nav-link py-2 <?= isActiveLink('manage-jobs.php', 'admin') ?>" href="<?= Config::BASE_URL ?>/pages/admin/manage-jobs.php">Manage Jobs</a></li>
                        <li class="nav-item"><a class="nav-link py-2 <?= isActiveLink('reports.php', 'admin') ?>" href="<?= Config::BASE_URL ?>/pages/admin/reports.php">Reports</a></li>
                        <li class="nav-item"><a class="nav-link py-2 <?= isActiveLink('settings.php', 'admin') ?>" href="<?= Config::BASE_URL ?>/pages/admin/settings.php">Settings</a></li>
                        <li><hr class="dropdown-divider my-2"></li>
                        <li class="nav-item"><a class="nav-link py-2" href="<?= Config::BASE_URL ?>/includes/auth.php?action=logout">Sign Out</a></li>
                    <?php elseif ($userType === 'employer'): ?>
                        <!-- EMPLOYER MOBILE MENU -->
                        <li class="nav-item"><a class="nav-link py-2 <?= isActiveLink('dashboard.php', 'employer') ?>" href="<?= Config::BASE_URL ?>/pages/employer/dashboard.php">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link py-2 <?= isActiveLink('post-job.php', 'employer') ?>" href="<?= Config::BASE_URL ?>/pages/employer/post-job.php">Post Job</a></li>
                        <li class="nav-item"><a class="nav-link py-2 <?= isActiveLink('manage-jobs.php', 'employer') ?>" href="<?= Config::BASE_URL ?>/pages/employer/manage-jobs.php">Manage Jobs</a></li>
                        <li class="nav-item"><a class="nav-link py-2 <?= isActiveLink('applicants.php', 'employer') ?>" href="<?= Config::BASE_URL ?>/pages/employer/applicants.php">Applicants</a></li>
                        <li><hr class="dropdown-divider my-2"></li>
                        <li class="nav-item"><a class="nav-link py-2" href="<?= Config::BASE_URL ?>/includes/auth.php?action=logout">Sign Out</a></li>
                    <?php elseif ($userType === 'jobSeeker'): ?>
                        <!-- JOBSEEKER MOBILE MENU -->
                        <li class="nav-item"><a class="nav-link py-2 <?= isActiveLink('index.php', 'user') ?>" href="<?= Config::BASE_URL ?>/pages/user/index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link py-2 <?= isActiveLink('dashboard.php', 'user') ?>" href="<?= Config::BASE_URL ?>/pages/user/dashboard.php">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link py-2 <?= isActiveLink('jobs.php', 'user') ?>" href="<?= Config::BASE_URL ?>/pages/user/jobs.php">Find Jobs</a></li>
                        <li class="nav-item"><a class="nav-link py-2 <?= isActiveLink('saved-jobs.php', 'user') ?>" href="<?= Config::BASE_URL ?>/pages/user/saved-jobs.php">Saved Jobs</a></li>
                        <li class="nav-item"><a class="nav-link py-2 <?= isActiveLink('companies.php', 'user') ?>" href="<?= Config::BASE_URL ?>/pages/user/companies.php">Companies</a></li>
                        <li class="nav-item"><a class="nav-link py-2 <?= isActiveLink('applications.php', 'user') ?>" href="<?= Config::BASE_URL ?>/pages/user/applications.php">Applications</a></li>
                        <li><hr class="dropdown-divider my-2"></li>
                        <li class="nav-item"><a class="nav-link py-2" href="<?= Config::BASE_URL ?>/includes/auth.php?action=logout">Sign Out</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </header>
    <main>
        <?php if (function_exists('getAlerts')): ?>
        <!-- Alerts will be displayed here -->
        <?php include __DIR__ . '/alerts.php'; ?>
        <?php endif; ?>

        <!-- The content of the specific page (e.g., dashboard.php) will be inserted here -->

    </main>

    <!-- Bootstrap JavaScript -->
    <script src="<?= Config::BASE_URL ?>/assets/js/bootstrap.bundle.min.js"></script>
    <?php $loadedBootstrap = true; ?>
    
</body>
</html>