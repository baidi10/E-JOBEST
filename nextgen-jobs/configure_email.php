<?php
require_once 'includes/config.php';
require_once 'classes/Database.php';

// Check if running in browser and display a simple interface
$isCli = php_sapi_name() === 'cli';

if (!$isCli) {
    echo '<html><head><title>Configure Email Settings</title>';
    echo '<link href="assets/css/bootstrap.min.css" rel="stylesheet">';
    echo '</head><body class="p-4">';
    echo '<div class="container">';
    echo '<h1 class="mb-4">Configure Email Settings</h1>';
}

$success = '';
$error = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $displayName = $_POST['display_name'] ?? 'JOBEST Support';
    
    if (empty($email) || empty($password)) {
        $error = "Email and password are required";
    } else {
        try {
            $db = Database::getInstance()->getConnection();
            
            // Begin transaction
            $db->beginTransaction();
            
            // Update email settings
            $settings = [
                ['email', 'smtp_host', 'smtp.gmail.com'],
                ['email', 'smtp_port', '587'],
                ['email', 'smtp_username', $email],
                ['email', 'smtp_password', $password],
                ['email', 'smtp_encryption', 'tls'],
                ['email', 'from_email', $email],
                ['email', 'from_name', $displayName],
                ['email', 'email_verification', '1'],
            ];
            
            $stmt = $db->prepare("
                INSERT INTO settings (settingGroup, settingKey, settingValue)
                VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE settingValue = VALUES(settingValue)
            ");
            
            foreach ($settings as $setting) {
                $stmt->execute($setting);
            }
            
            // Commit transaction
            $db->commit();
            
            $success = "Email settings have been updated successfully!";
            
            // Send a test email
            if (isset($_POST['send_test'])) {
                // Include PHPMailer
                require_once 'vendor/autoload.php';
                
                try {
                    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
                    
                    // Enable debug output but capture it instead of displaying directly
                    $mail->SMTPDebug = 2; // Show client and server messages
                    $debugOutput = [];
                    $mail->Debugoutput = function($str, $level) use (&$debugOutput) {
                        $debugOutput[] = $str;
                    };
                    
                    // Server settings
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = $email;
                    $mail->Password = $password;
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;
                    
                    // Recipients
                    $mail->setFrom($email, $displayName);
                    $mail->addAddress($email);
                    
                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'Test Email from JOBEST';
                    $mail->Body = '<h2>Test Email</h2><p>This is a test email from your JOBEST application.</p>';
                    $mail->AltBody = 'This is a test email from your JOBEST application.';
                    
                    $mail->send();
                    $success .= " A test email has been sent to your address.";
                } catch (Exception $e) {
                    // Check for specific authentication errors
                    if (strpos($mail->ErrorInfo, 'Could not authenticate') !== false) {
                        $error = "Email configuration saved, but authentication failed. This is likely because:
                            <ul>
                            <li>If you use 2-Step Verification with Gmail, you must use an App Password</li>
                            <li>Your password may be incorrect</li>
                            <li>Google may be blocking the login attempt for security reasons</li>
                            </ul>
                            <a href='gmail_app_password_guide.php' class='btn btn-sm btn-outline-primary'>View App Password Guide</a>
                            <a href='smtp_diagnostics.php' class='btn btn-sm btn-outline-secondary'>Use SMTP Diagnostics Tool</a>";
                    } else {
                        $error = "Email configuration saved, but test email could not be sent. Error: " . $mail->ErrorInfo;
                        
                        // If we have debug output, add a collapsible section to show it
                        if (!empty($debugOutput)) {
                            $error .= "<div class='mt-2'>
                                <button class='btn btn-sm btn-outline-secondary' type='button' data-bs-toggle='collapse' data-bs-target='#debugOutput'>
                                    Show Technical Details
                                </button>
                                <div class='collapse mt-2' id='debugOutput'>
                                    <div class='card card-body'>
                                        <pre>" . htmlspecialchars(implode("\n", $debugOutput)) . "</pre>
                                    </div>
                                </div>
                            </div>";
                        }
                    }
                }
            }
        } catch (PDOException $e) {
            // Rollback transaction on error
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            $error = "Database error: " . $e->getMessage();
        }
    }
}

// Display form
if (!$isCli) {
    // Show messages if any
    if ($success) {
        echo '<div class="alert alert-success">' . $success . '</div>';
    }
    
    if ($error) {
        echo '<div class="alert alert-danger">' . $error . '</div>';
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
    }
    
    echo '<div class="card border-0 shadow-sm mb-4">';
    echo '<div class="card-header bg-white">';
    echo '<h2 class="h5 fw-bold mb-0">Gmail Configuration</h2>';
    echo '</div>';
    echo '<div class="card-body">';
    
    echo '<div class="alert alert-info">';
    echo '<strong>Important Note:</strong> If you have 2-Step Verification enabled for your Gmail account, you need to use an <a href="https://support.google.com/accounts/answer/185833" target="_blank">App Password</a> instead of your regular password. <a href="gmail_app_password_guide.php" class="btn btn-sm btn-outline-primary ms-2">View Detailed Guide</a>';
    echo '</div>';
    
    echo '<form method="POST">';
    
    echo '<div class="mb-3">';
    echo '<label for="email" class="form-label">Gmail Email Address</label>';
    echo '<input type="email" class="form-control" id="email" name="email" value="' . htmlspecialchars($currentSettings['smtp_username'] ?? 'baidioussama0@gmail.com') . '" required>';
    echo '<div class="form-text">This will be used as both the SMTP username and the sender email.</div>';
    echo '</div>';
    
    echo '<div class="mb-3">';
    echo '<label for="password" class="form-label">Gmail Password or App Password</label>';
    echo '<input type="password" class="form-control" id="password" name="password" required>';
    echo '<div class="form-text text-danger"><strong>Important:</strong> Use an App Password if you have 2-Step Verification enabled. <a href="gmail_app_password_guide.php">How to get an App Password?</a></div>';
    echo '</div>';
    
    echo '<div class="mb-3">';
    echo '<label for="display_name" class="form-label">Display Name</label>';
    echo '<input type="text" class="form-control" id="display_name" name="display_name" value="' . htmlspecialchars($currentSettings['from_name'] ?? 'JOBEST Support') . '">';
    echo '<div class="form-text">This name will appear in the "From" field of sent emails.</div>';
    echo '</div>';
    
    echo '<div class="mb-3 form-check">';
    echo '<input type="checkbox" class="form-check-input" id="send_test" name="send_test" checked>';
    echo '<label class="form-check-label" for="send_test">Send a test email after saving</label>';
    echo '</div>';
    
    echo '<button type="submit" class="btn btn-primary">Save Email Settings</button>';
    echo '<a href="smtp_diagnostics.php" class="btn btn-outline-secondary ms-2">Advanced Diagnostics</a>';
    
    echo '</form>';
    echo '</div></div>';
    
    echo '<div class="card border-0 shadow-sm">';
    echo '<div class="card-header bg-white">';
    echo '<h2 class="h5 fw-bold mb-0">Current Email Settings</h2>';
    echo '</div>';
    echo '<div class="card-body">';
    
    if (empty($currentSettings)) {
        echo '<p class="text-muted">No email settings found in the database.</p>';
    } else {
        echo '<table class="table">';
        echo '<tr><th>Setting</th><th>Value</th></tr>';
        
        $displayKeys = ['smtp_host', 'smtp_port', 'smtp_username', 'smtp_encryption', 'from_email', 'from_name'];
        
        foreach ($displayKeys as $key) {
            $value = isset($currentSettings[$key]) ? $currentSettings[$key] : 'Not set';
            
            // Don't display the password
            if ($key !== 'smtp_password') {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($key) . '</td>';
                echo '<td>' . htmlspecialchars($value) . '</td>';
                echo '</tr>';
            }
        }
        
        echo '</table>';
    }
    
    echo '</div></div>';
    
    echo '<div class="mt-4">';
    echo '<a href="pages/public/forgot-password.php" class="btn btn-outline-primary me-2">Go to Forgot Password Page</a>';
    echo '<a href="get_reset_link.php" class="btn btn-outline-secondary">Get Reset Link Tool</a>';
    echo '</div>';
    
    // Add Bootstrap JS for collapsible elements
    echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>';
    
} else {
    // CLI mode
    echo "Configure Gmail for sending emails\n";
    echo "=================================\n\n";
    
    echo "Enter your Gmail address: ";
    $email = trim(fgets(STDIN));
    
    echo "Enter your Gmail password or App Password: ";
    $password = trim(fgets(STDIN));
    
    echo "Enter display name [JOBEST Support]: ";
    $displayName = trim(fgets(STDIN));
    if (empty($displayName)) {
        $displayName = "JOBEST Support";
    }
    
    try {
        $db = Database::getInstance()->getConnection();
        
        // Update email settings
        $settings = [
            ['email', 'smtp_host', 'smtp.gmail.com'],
            ['email', 'smtp_port', '587'],
            ['email', 'smtp_username', $email],
            ['email', 'smtp_password', $password],
            ['email', 'smtp_encryption', 'tls'],
            ['email', 'from_email', $email],
            ['email', 'from_name', $displayName],
            ['email', 'email_verification', '1'],
        ];
        
        $stmt = $db->prepare("
            INSERT INTO settings (settingGroup, settingKey, settingValue)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE settingValue = VALUES(settingValue)
        ");
        
        foreach ($settings as $setting) {
            $stmt->execute($setting);
        }
        
        echo "\nEmail settings have been updated successfully!\n";
        echo "NOTE: If you have 2-Step Verification enabled for Gmail, you must use an App Password.\n";
        echo "See the guide in gmail_app_password_guide.php for instructions.\n";
        
    } catch (PDOException $e) {
        echo "\nDatabase error: " . $e->getMessage() . "\n";
    }
}

if (!$isCli) {
    echo '</div></body></html>';
}
?> 