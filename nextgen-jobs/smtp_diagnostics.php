<?php
require_once 'includes/config.php';
require_once 'classes/Database.php';
require_once 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Check if running in browser and display a simple interface
$isCli = php_sapi_name() === 'cli';

if (!$isCli) {
    echo '<html><head><title>SMTP Diagnostics Tool</title>';
    echo '<link href="assets/css/bootstrap.min.css" rel="stylesheet">';
    echo '</head><body class="p-4">';
    echo '<div class="container">';
    echo '<h1 class="mb-4">SMTP Diagnostics Tool</h1>';
}

// Get current settings
try {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->query("SELECT settingKey, settingValue FROM settings WHERE settingGroup = 'email'");
    $currentSettings = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $currentSettings[$row['settingKey']] = $row['settingValue'];
    }
} catch (PDOException $e) {
    $currentSettings = [];
    if (!$isCli) {
        echo '<div class="alert alert-danger">Database error: ' . htmlspecialchars($e->getMessage()) . '</div>';
    } else {
        echo "Database error: " . $e->getMessage() . "\n";
    }
}

$success = '';
$error = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? $currentSettings['smtp_username'] ?? '';
    $password = $_POST['password'] ?? '';
    $testMode = $_POST['test_mode'] ?? 'basic';
    $debugLevel = (int)($_POST['debug_level'] ?? 2);
    
    if (empty($email) || empty($password)) {
        $error = "Email and password are required";
    } else {
        try {
            // Create a new PHPMailer instance
            $mail = new PHPMailer(true);
            
            // Enable debug output
            $mail->SMTPDebug = $debugLevel; // 0 = off, 1 = client messages, 2 = client and server messages
            
            // Use a callback function to capture debug output
            $debugOutput = [];
            $mail->Debugoutput = function($str, $level) use (&$debugOutput) {
                $debugOutput[] = $str;
            };
            
            // Test different parts of the email sending process based on the selected mode
            if ($testMode === 'connection') {
                // Just test SMTP connection without authenticating
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = false;
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;
                
                // Connect to the server
                if (!$mail->smtpConnect()) {
                    throw new Exception('SMTP connection failed');
                }
                
                $success = "Successfully connected to SMTP server!";
            } elseif ($testMode === 'auth') {
                // Test authentication only
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = $email;
                $mail->Password = $password;
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;
                
                // Connect to the server with authentication
                if (!$mail->smtpConnect()) {
                    throw new Exception('SMTP authentication failed');
                }
                
                $success = "Successfully authenticated with SMTP server!";
            } else {
                // Full test (send an email)
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = $email;
                $mail->Password = $password;
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;
                
                // Recipients
                $mail->setFrom($email, 'Test Sender');
                $mail->addAddress($email);
                
                // Content
                $mail->isHTML(true);
                $mail->Subject = 'SMTP Test Email';
                $mail->Body = '<h2>Test Email</h2><p>This is a test email from the SMTP Diagnostics Tool.</p>';
                $mail->AltBody = 'This is a test email from the SMTP Diagnostics Tool.';
                
                $mail->send();
                $success = "Email was sent successfully!";
            }
        } catch (Exception $e) {
            $error = "Error: " . $mail->ErrorInfo;
        }
    }
}

// Display form
if (!$isCli) {
    // Show messages if any
    if ($success) {
        echo '<div class="alert alert-success">' . htmlspecialchars($success) . '</div>';
    }
    
    if ($error) {
        echo '<div class="alert alert-danger">' . htmlspecialchars($error) . '</div>';
    }
    
    echo '<div class="card border-0 shadow-sm mb-4">';
    echo '<div class="card-header bg-white">';
    echo '<h2 class="h5 fw-bold mb-0">Gmail SMTP Test</h2>';
    echo '</div>';
    echo '<div class="card-body">';
    
    echo '<div class="alert alert-info">';
    echo '<h4>Fixing Gmail Authentication Issues:</h4>';
    echo '<ol>';
    echo '<li><strong>App Password Required</strong>: If 2-Step Verification is enabled, you <strong>must</strong> use an App Password instead of your regular password.</li>';
    echo '<li><strong>Create App Password</strong>: Go to <a href="https://myaccount.google.com/apppasswords" target="_blank">Google App Passwords</a>, select "Other" as the app, name it "JOBEST", and use the generated 16-character password.</li>';
    echo '<li><strong>Less Secure App Access</strong>: If not using 2-Step Verification, you may need to allow <a href="https://myaccount.google.com/lesssecureapps" target="_blank">less secure app access</a>.</li>';
    echo '<li><strong>Unlock Captcha</strong>: Try visiting <a href="https://accounts.google.com/DisplayUnlockCaptcha" target="_blank">Unlock Captcha</a> to allow access.</li>';
    echo '</ol>';
    echo '</div>';
    
    echo '<form method="POST">';
    
    echo '<div class="mb-3">';
    echo '<label for="email" class="form-label">Gmail Email Address</label>';
    echo '<input type="email" class="form-control" id="email" name="email" value="' . htmlspecialchars($currentSettings['smtp_username'] ?? '') . '" required>';
    echo '</div>';
    
    echo '<div class="mb-3">';
    echo '<label for="password" class="form-label">Gmail Password or App Password</label>';
    echo '<input type="password" class="form-control" id="password" name="password" required>';
    echo '<div class="form-text text-danger"><strong>Important:</strong> If you have 2-Step Verification enabled, you must use an App Password instead.</div>';
    echo '</div>';
    
    echo '<div class="mb-3">';
    echo '<label class="form-label">Test Mode</label>';
    echo '<div class="form-check">';
    echo '<input class="form-check-input" type="radio" name="test_mode" id="test_connection" value="connection">';
    echo '<label class="form-check-label" for="test_connection">Test Connection Only</label>';
    echo '</div>';
    echo '<div class="form-check">';
    echo '<input class="form-check-input" type="radio" name="test_mode" id="test_auth" value="auth">';
    echo '<label class="form-check-label" for="test_auth">Test Authentication Only</label>';
    echo '</div>';
    echo '<div class="form-check">';
    echo '<input class="form-check-input" type="radio" name="test_mode" id="test_full" value="basic" checked>';
    echo '<label class="form-check-label" for="test_full">Full Test (Send Email)</label>';
    echo '</div>';
    echo '</div>';
    
    echo '<div class="mb-3">';
    echo '<label for="debug_level" class="form-label">Debug Level</label>';
    echo '<select class="form-select" id="debug_level" name="debug_level">';
    echo '<option value="0">0 - No output</option>';
    echo '<option value="1">1 - Client messages</option>';
    echo '<option value="2" selected>2 - Client and server messages</option>';
    echo '<option value="3">3 - Connection info</option>';
    echo '<option value="4">4 - Low-level data output</option>';
    echo '</select>';
    echo '</div>';
    
    echo '<button type="submit" class="btn btn-primary">Run Test</button>';
    
    echo '</form>';
    
    // Display debug output if available
    if (!empty($debugOutput)) {
        echo '<div class="mt-4">';
        echo '<h3>Debug Output</h3>';
        echo '<pre class="bg-light p-3 mt-2" style="max-height: 300px; overflow-y: auto;">';
        echo htmlspecialchars(implode("\n", $debugOutput));
        echo '</pre>';
        echo '</div>';
    }
    
    echo '</div></div>';
    
    echo '<div class="mt-4">';
    echo '<a href="configure_email.php" class="btn btn-outline-primary me-2">Return to Email Configuration</a>';
    echo '</div>';
    
} else {
    // CLI mode
    echo "SMTP Diagnostics Tool\n";
    echo "====================\n\n";
    
    echo "Enter your Gmail address [" . ($currentSettings['smtp_username'] ?? '') . "]: ";
    $input = trim(fgets(STDIN));
    $email = !empty($input) ? $input : ($currentSettings['smtp_username'] ?? '');
    
    echo "Enter your Gmail password or App Password: ";
    $password = trim(fgets(STDIN));
    
    echo "Select test mode:\n";
    echo "1. Test Connection Only\n";
    echo "2. Test Authentication Only\n";
    echo "3. Full Test (Send Email)\n";
    echo "Enter choice [3]: ";
    $input = trim(fgets(STDIN));
    $testMode = match ($input) {
        '1' => 'connection',
        '2' => 'auth',
        default => 'basic'
    };
    
    echo "Select debug level (0-4) [2]: ";
    $input = trim(fgets(STDIN));
    $debugLevel = is_numeric($input) ? (int)$input : 2;
    
    try {
        // Create a new PHPMailer instance
        $mail = new PHPMailer(true);
        
        // Enable debug output
        $mail->SMTPDebug = $debugLevel;
        
        // Use a callback function to capture debug output
        $mail->Debugoutput = function($str, $level) {
            echo $str;
        };
        
        echo "\nRunning test...\n\n";
        
        // Test different parts of the email sending process based on the selected mode
        if ($testMode === 'connection') {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = false;
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            
            if (!$mail->smtpConnect()) {
                throw new Exception('SMTP connection failed');
            }
            
            echo "\nSuccess: Connected to SMTP server!\n";
        } elseif ($testMode === 'auth') {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = $email;
            $mail->Password = $password;
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            
            if (!$mail->smtpConnect()) {
                throw new Exception('SMTP authentication failed');
            }
            
            echo "\nSuccess: Authenticated with SMTP server!\n";
        } else {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = $email;
            $mail->Password = $password;
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            
            $mail->setFrom($email, 'Test Sender');
            $mail->addAddress($email);
            
            $mail->isHTML(true);
            $mail->Subject = 'SMTP Test Email';
            $mail->Body = '<h2>Test Email</h2><p>This is a test email from the SMTP Diagnostics Tool.</p>';
            $mail->AltBody = 'This is a test email from the SMTP Diagnostics Tool.';
            
            $mail->send();
            echo "\nSuccess: Email was sent successfully!\n";
        }
    } catch (Exception $e) {
        echo "\nError: " . $mail->ErrorInfo . "\n";
        
        echo "\nTroubleshooting Tips:\n";
        echo "1. If you have 2-Step Verification enabled, you MUST use an App Password\n";
        echo "2. Create an App Password at: https://myaccount.google.com/apppasswords\n";
        echo "3. If not using 2-Step Verification, check Less Secure App Access\n";
        echo "4. Try visiting: https://accounts.google.com/DisplayUnlockCaptcha\n";
    }
}

if (!$isCli) {
    echo '</div></body></html>';
}
?> 