<?php
require_once 'includes/config.php';
require_once 'classes/Database.php';

// Check if running in browser or CLI
$isCli = php_sapi_name() === 'cli';

// Output functions
function outputSuccess($message) {
    global $isCli;
    if ($isCli) {
        echo "SUCCESS: $message\n";
    } else {
        echo "<div class=\"alert alert-success\">$message</div>";
    }
}

function outputWarning($message) {
    global $isCli;
    if ($isCli) {
        echo "WARNING: $message\n";
    } else {
        echo "<div class=\"alert alert-warning\">$message</div>";
    }
}

function outputError($message) {
    global $isCli;
    if ($isCli) {
        echo "ERROR: $message\n";
    } else {
        echo "<div class=\"alert alert-danger\">Error: $message</div>";
    }
}

// Check if running in browser and display a simple interface
if (!$isCli) {
    echo '<html><head><title>Create Settings Table</title>';
    echo '<link href="assets/css/bootstrap.min.css" rel="stylesheet">';
    echo '</head><body class="p-4">';
    echo '<div class="container">';
    echo '<h1 class="mb-4">Create Settings Table</h1>';
}

try {
    $db = Database::getInstance()->getConnection();
    
    // Check if table already exists
    $tableExists = $db->query("SHOW TABLES LIKE 'settings'")->rowCount() > 0;
    
    if ($tableExists) {
        outputWarning('Settings table already exists. No action taken.');
    } else {
        // Create settings table
        $db->exec("
            CREATE TABLE settings (
                settingId INT AUTO_INCREMENT PRIMARY KEY,
                settingGroup VARCHAR(50) NOT NULL,
                settingKey VARCHAR(100) NOT NULL,
                settingValue TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                UNIQUE KEY unique_setting (settingGroup, settingKey)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");
        
        outputSuccess('Settings table created successfully.');
        
        // Insert default general settings
        $generalSettings = [
            ['general', 'site_name', 'JOBEST'],
            ['general', 'site_description', 'Find your dream job'],
            ['general', 'admin_email', 'admin@nextgen-jobs.com'],
            ['general', 'jobs_per_page', '10'],
            ['general', 'currency', 'USD'],
            ['general', 'enable_registration', '1'],
        ];
        
        // Insert default email settings
        $emailSettings = [
            ['email', 'smtp_host', 'smtp.example.com'],
            ['email', 'smtp_port', '587'],
            ['email', 'smtp_username', ''],
            ['email', 'smtp_password', ''],
            ['email', 'smtp_encryption', 'tls'],
            ['email', 'from_email', 'noreply@jobest.com'],
            ['email', 'from_name', 'JOBEST'],
            ['email', 'email_verification', '1'],
        ];
        
        // Insert default security settings
        $securitySettings = [
            ['security', 'password_min_length', '8'],
            ['security', 'require_special_chars', '1'],
            ['security', 'require_uppercase', '1'],
            ['security', 'require_numbers', '1'],
            ['security', 'login_attempts', '5'],
            ['security', 'lockout_time', '30'],
            ['security', 'enable_recaptcha', '0'],
        ];
        
        // Insert danger zone settings
        $dangerSettings = [
            ['danger', 'maintenance_mode', '0']
        ];
        
        // Combine all settings
        $allSettings = array_merge($generalSettings, $emailSettings, $securitySettings, $dangerSettings);
        
        // Prepare the insert statement
        $stmt = $db->prepare("INSERT INTO settings (settingGroup, settingKey, settingValue) VALUES (?, ?, ?)");
        
        // Insert all settings
        foreach ($allSettings as $setting) {
            $stmt->execute($setting);
        }
        
        outputSuccess('Default settings inserted successfully.');
    }
    
    if (!$isCli) {
        echo '<p>You can now <a href="admin/settings.php" class="btn btn-primary">Go to Settings Page</a></p>';
    }
    
} catch (PDOException $e) {
    outputError($e->getMessage());
}

// Close the HTML tags if running in browser
if (!$isCli) {
    echo '</div></body></html>';
}
?> 