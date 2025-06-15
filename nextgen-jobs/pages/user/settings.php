<?php
require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../../../includes/functions.php';
require_once __DIR__ . '/../../../classes/User.php';

// Authentication check
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'jobSeeker') {
    header('Location: /pages/public/login.php');
    exit;
}

$user = new User();
$error = '';
$success = '';

// Handle different setting updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        switch ($_POST['form_type']) {
            case 'email':
                $user->updateEmail($_SESSION['user_id'], $_POST['new_email']);
                $success = "Email updated successfully";
                break;
                
            case 'password':
                $user->updatePassword($_SESSION['user_id'], 
                    $_POST['current_password'], $_POST['new_password']);
                $success = "Password updated successfully";
                break;
                
            case 'notifications':
                $user->updateNotificationSettings($_SESSION['user_id'], $_POST);
                $success = "Notification settings updated";
                break;
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

$pageTitle = "Account Settings - JOBEST";
include __DIR__ . '/../../../includes/header.php';
?>

<div class="dashboard-container">
  <div class="container py-5">
    <div class="row g-4">
      <!-- Settings Navigation -->
      <div class="col-lg-3">
        <div class="card border-0 shadow-sm">
          <div class="card-body">
            <nav class="nav flex-column">
              <a class="nav-link active" href="#account" data-bs-toggle="tab">
                <i class="bi bi-person me-2"></i>Account
              </a>
              <a class="nav-link" href="#security" data-bs-toggle="tab">
                <i class="bi bi-shield-lock me-2"></i>Security
              </a>
              <a class="nav-link" href="#notifications" data-bs-toggle="tab">
                <i class="bi bi-bell me-2"></i>Notifications
              </a>
              <a class="nav-link text-danger" href="#danger" data-bs-toggle="tab">
                <i class="bi bi-exclamation-octagon me-2"></i>Danger Zone
              </a>
            </nav>
          </div>
        </div>
      </div>

      <!-- Settings Content -->
      <div class="col-lg-9">
        <div class="tab-content">
          <!-- Account Settings Tab -->
          <div class="tab-pane fade show active" id="account">
            <?php include __DIR__ . '/settings-tabs/account.php'; ?>
          </div>

          <!-- Security Settings Tab -->
          <div class="tab-pane fade" id="security">
            <?php include __DIR__ . '/settings-tabs/security.php'; ?>
          </div>

          <!-- Notification Settings Tab -->
          <div class="tab-pane fade" id="notifications">
            <?php include __DIR__ . '/settings-tabs/notifications.php'; ?>
          </div>

          <!-- Danger Zone Tab -->
          <div class="tab-pane fade" id="danger">
            <?php include __DIR__ . '/settings-tabs/danger-zone.php'; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../../../includes/footer.php'; ?>