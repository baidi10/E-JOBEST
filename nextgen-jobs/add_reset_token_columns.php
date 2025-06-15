<?php
require_once 'includes/config.php';
require_once 'classes/Database.php';

// Check if running in browser and display a simple interface
$isCli = php_sapi_name() === 'cli';

if (!$isCli) {
    echo '<html><head><title>Add Reset Token Columns</title>';
    echo '<link href="assets/css/bootstrap.min.css" rel="stylesheet">';
    echo '</head><body class="p-4">';
    echo '<div class="container">';
    echo '<h1 class="mb-4">Add Reset Token Columns to Users Table</h1>';
}

try {
    $db = Database::getInstance()->getConnection();
    
    // Check if columns already exist
    $columnsExist = false;
    $stmt = $db->query("SHOW COLUMNS FROM users LIKE 'resetToken'");
    if ($stmt->rowCount() > 0) {
        $columnsExist = true;
        echo $isCli 
            ? "INFO: Reset token columns already exist.\n" 
            : '<div class="alert alert-info">Reset token columns already exist.</div>';
    }
    
    if (!$columnsExist) {
        // Add the resetToken and resetTokenExpires columns
        $db->exec("
            ALTER TABLE users
            ADD COLUMN resetToken VARCHAR(64) NULL,
            ADD COLUMN resetTokenExpires DATETIME NULL
        ");
        
        echo $isCli 
            ? "SUCCESS: Reset token columns added successfully.\n" 
            : '<div class="alert alert-success">Reset token columns added successfully.</div>';
    }
    
    // Check the existing users table structure
    $stmt = $db->query("DESCRIBE users");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!$isCli) {
        echo '<h3 class="mt-4">Users Table Structure</h3>';
        echo '<table class="table table-striped">';
        echo '<thead><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr></thead>';
        echo '<tbody>';
        foreach ($columns as $column) {
            echo '<tr>';
            foreach ($column as $value) {
                echo '<td>' . htmlspecialchars($value ?? 'NULL') . '</td>';
            }
            echo '</tr>';
        }
        echo '</tbody></table>';
    } else {
        echo "\nUsers Table Structure:\n";
        echo str_repeat('-', 80) . "\n";
        echo sprintf("%-20s %-20s %-5s %-5s %-10s %s\n", 'Field', 'Type', 'Null', 'Key', 'Default', 'Extra');
        echo str_repeat('-', 80) . "\n";
        foreach ($columns as $column) {
            echo sprintf(
                "%-20s %-20s %-5s %-5s %-10s %s\n",
                $column['Field'],
                $column['Type'],
                $column['Null'],
                $column['Key'],
                $column['Default'] ?? 'NULL',
                $column['Extra']
            );
        }
    }
    
    if (!$isCli) {
        echo '<p class="mt-4"><a href="pages/public/forgot-password.php" class="btn btn-primary">Go to Forgot Password Page</a></p>';
    }
    
} catch (PDOException $e) {
    echo $isCli 
        ? "ERROR: " . $e->getMessage() . "\n" 
        : '<div class="alert alert-danger">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
}

if (!$isCli) {
    echo '</div></body></html>';
}
?> 