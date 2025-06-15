<?php
/**
 * Fix Reset Password Functionality
 * 
 * This script adds missing columns to the users table and creates
 * the necessary pages for password reset functionality.
 */

require_once 'includes/config.php';
require_once 'classes/Database.php';

// Check if running in browser and display a simple interface
$isCli = php_sapi_name() === 'cli';

if (!$isCli) {
    echo '<html><head><title>Fix Reset Password</title>';
    echo '<link href="assets/css/bootstrap.min.css" rel="stylesheet">';
    echo '</head><body class="p-4">';
    echo '<div class="container">';
    echo '<h1 class="mb-4">Fix Reset Password Functionality</h1>';
}

try {
    $db = Database::getInstance()->getConnection();
    
    // Step 1: Check if resetToken and resetTokenExpires columns exist
    $output = "";
    
    $output .= "Step 1: Checking/Adding missing columns to users table...\n";
    
    // Execute the ALTER TABLE statements directly (ignore errors if columns already exist)
    try {
        $db->exec("ALTER TABLE users ADD COLUMN resetToken VARCHAR(64) NULL");
        $output .= "  - Added resetToken column\n";
    } catch (PDOException $e) {
        $output .= "  - resetToken column already exists\n";
    }
    
    try {
        $db->exec("ALTER TABLE users ADD COLUMN resetTokenExpires DATETIME NULL");
        $output .= "  - Added resetTokenExpires column\n";
    } catch (PDOException $e) {
        $output .= "  - resetTokenExpires column already exists\n";
    }
    
    // Step 2: Verify the columns exist
    $output .= "\nStep 2: Verifying columns in users table...\n";
    
    $stmt = $db->query("SELECT 
        COUNT(*) as total_columns,
        SUM(CASE WHEN COLUMN_NAME = 'resetToken' THEN 1 ELSE 0 END) as has_reset_token,
        SUM(CASE WHEN COLUMN_NAME = 'resetTokenExpires' THEN 1 ELSE 0 END) as has_reset_token_expires
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'users'");
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $output .= "  - Total columns in users table: " . $result['total_columns'] . "\n";
    $output .= "  - resetToken column exists: " . ($result['has_reset_token'] ? "YES" : "NO") . "\n";
    $output .= "  - resetTokenExpires column exists: " . ($result['has_reset_token_expires'] ? "YES" : "NO") . "\n";
    
    // Step 3: Create/verify reset_password.php page exists in pages/public/auth directory
    $output .= "\nStep 3: Checking reset-password.php page...\n";
    
    $resetPasswordDir = __DIR__ . '/pages/public/auth';
    if (!is_dir($resetPasswordDir)) {
        mkdir($resetPasswordDir, 0755, true);
        $output .= "  - Created auth directory\n";
    } else {
        $output .= "  - Auth directory already exists\n";
    }
    
    $resetPasswordFile = $resetPasswordDir . '/reset-password.php';
    if (!file_exists($resetPasswordFile)) {
        $resetPasswordContent = '<?php
require_once __DIR__ . "/../../../includes/config.php";
require_once __DIR__ . "/../../../includes/functions.php";
require_once __DIR__ . "/../../../classes/Database.php";
require_once __DIR__ . "/../../../classes/Auth.php";

$token = isset($_GET[\'token\']) ? $_GET[\'token\'] : null;

if (!$token) {
    redirect(\'/pages/public/forgot-password.php\');
}

$error = \'\';
$success = \'\';

if ($_SERVER[\'REQUEST_METHOD\'] === \'POST\') {
    $password = $_POST[\'password\'];
    $confirmPassword = $_POST[\'confirm_password\'];
    
    if (strlen($password) < 8) {
        $error = "Password must be at least 8 characters";
    } elseif ($password !== $confirmPassword) {
        $error = "Passwords do not match";
    } else {
        try {
            $auth = new Auth();
            $auth->resetPassword($token, $password);
            $success = "Your password has been reset successfully. You can now login.";
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}

$pageTitle = "Reset Password - JOBEST";
include __DIR__ . "/../../../includes/header.php";
?>

<div class="auth-container py-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8 col-lg-6">
        <div class="card shadow-sm border-0">
          <div class="card-body p-4 p-sm-5">
            <div class="text-center mb-5">
              <img src="/assets/images/logo.svg" alt="JOBEST" width="120" class="mb-4">
              <h1 class="h4 fw-bold">Reset Your Password</h1>
              <p class="text-muted small">Enter your new password below</p>
            </div>

            <?php if ($error): ?>
              <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
              <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
              <div class="text-center mt-4">
                <a href="/pages/public/login.php" class="btn btn-primary">Go to Login</a>
              </div>
            <?php else: ?>
              <form method="POST">
                <div class="mb-3">
                  <label class="form-label">New Password</label>
                  <input type="password" name="password" 
                         class="form-control form-control-lg" required>
                </div>

                <div class="mb-4">
                  <label class="form-label">Confirm Password</label>
                  <input type="password" name="confirm_password" 
                         class="form-control form-control-lg" required>
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100">
                  Reset Password
                </button>
              </form>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . "/../../../includes/footer.php"; ?>';
        
        file_put_contents($resetPasswordFile, $resetPasswordContent);
        $output .= "  - Created reset-password.php file\n";
    } else {
        $output .= "  - reset-password.php already exists\n";
    }
    
    // Output results
    if ($isCli) {
        echo $output;
    } else {
        echo '<pre class="p-3 bg-light">' . htmlspecialchars($output) . '</pre>';
        echo '<div class="alert alert-success">Reset password functionality has been fixed!</div>';
        echo '<p><a href="pages/public/forgot-password.php" class="btn btn-primary">Go to Forgot Password Page</a></p>';
    }
    
} catch (PDOException $e) {
    $error = "ERROR: " . $e->getMessage();
    
    if ($isCli) {
        echo $error . "\n";
    } else {
        echo '<div class="alert alert-danger">' . htmlspecialchars($error) . '</div>';
    }
}

if (!$isCli) {
    echo '</div></body></html>';
}
?> 