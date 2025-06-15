<?php
require_once 'includes/config.php';
require_once 'classes/Database.php';

// Check if running in browser and display a simple interface
$isCli = php_sapi_name() === 'cli';

if (!$isCli) {
    echo '<html><head><title>Get Reset Password Link</title>';
    echo '<link href="assets/css/bootstrap.min.css" rel="stylesheet">';
    echo '</head><body class="p-4">';
    echo '<div class="container">';
    echo '<h1 class="mb-4">Get Reset Password Link</h1>';
}

$email = '';
$resetLink = '';
$error = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'];
    
    try {
        $db = Database::getInstance()->getConnection();
        
        // Get user by email
        $stmt = $db->prepare("SELECT userId, resetToken, resetTokenExpires FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            $error = "No account found with that email address";
        } elseif (empty($user['resetToken'])) {
            // No reset token exists for this user, request a reset first
            $error = "No reset token found. Please request a password reset first.";
        } elseif ($user['resetTokenExpires'] && strtotime($user['resetTokenExpires']) < time()) {
            $error = "Reset token has expired. Please request a new password reset.";
        } else {
            // Build reset link
            $baseUrl = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
            $baseUrl .= $_SERVER['HTTP_HOST'];
            $resetLink = $baseUrl . "/pages/public/auth/reset-password.php?token=" . $user['resetToken'];
        }
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}

// Display form
if (!$isCli) {
    // Show error message if any
    if ($error) {
        echo '<div class="alert alert-danger">' . htmlspecialchars($error) . '</div>';
    }
    
    // Show reset link if available
    if ($resetLink) {
        echo '<div class="alert alert-success">Reset link for ' . htmlspecialchars($email) . ':</div>';
        echo '<div class="input-group mb-3">';
        echo '<input type="text" class="form-control" value="' . htmlspecialchars($resetLink) . '" id="resetLink" readonly>';
        echo '<button class="btn btn-outline-secondary" type="button" onclick="copyLink()">Copy</button>';
        echo '</div>';
        echo '<p><a href="' . htmlspecialchars($resetLink) . '" class="btn btn-primary">Go to Reset Password Page</a></p>';
    }
    
    // Show form if no link generated yet or there was an error
    if (!$resetLink || $error) {
        echo '<form method="POST">';
        echo '<div class="mb-3">';
        echo '<label for="email" class="form-label">Enter your email address</label>';
        echo '<input type="email" class="form-control" id="email" name="email" value="' . htmlspecialchars($email) . '" required>';
        echo '</div>';
        echo '<button type="submit" class="btn btn-primary">Get Reset Link</button>';
        echo '</form>';
    }
    
    echo '<hr class="my-4">';
    echo '<p>First request a password reset from the <a href="pages/public/forgot-password.php">forgot password page</a>, then use this tool to get the reset link.</p>';
    
    // Add copy functionality
    echo '<script>
    function copyLink() {
        var copyText = document.getElementById("resetLink");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        document.execCommand("copy");
        alert("Copied the reset link!");
    }
    </script>';
} else {
    // CLI mode
    echo "Please enter your email address: ";
    $email = trim(fgets(STDIN));
    
    try {
        $db = Database::getInstance()->getConnection();
        
        // Get user by email
        $stmt = $db->prepare("SELECT userId, resetToken, resetTokenExpires FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            echo "No account found with that email address\n";
        } elseif (empty($user['resetToken'])) {
            echo "No reset token found. Please request a password reset first.\n";
        } elseif ($user['resetTokenExpires'] && strtotime($user['resetTokenExpires']) < time()) {
            echo "Reset token has expired. Please request a new password reset.\n";
        } else {
            echo "Reset link for $email:\n";
            echo "http://localhost/pages/public/auth/reset-password.php?token=" . $user['resetToken'] . "\n";
        }
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage() . "\n";
    }
}

if (!$isCli) {
    echo '</div></body></html>';
}
?> 