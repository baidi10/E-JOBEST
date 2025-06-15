<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/Avatar.php';

// Check if the script is being run by an admin
if (!isLoggedIn() || !isAdmin()) {
    echo "Unauthorized access. This script can only be run by an administrator.";
    exit;
}

// Initialize avatar class
$avatar = new Avatar();

// Update user avatars
$usersUpdated = $avatar->updateAllUserAvatars();
echo "<p>Updated {$usersUpdated} user avatars.</p>";

// Update company avatars
$companiesUpdated = $avatar->updateAllCompanyAvatars();
echo "<p>Updated {$companiesUpdated} company avatars.</p>";

echo "<p>Avatar update complete. All users and companies now have letter-based avatars.</p>";

// Show a sample of different avatars
echo "<h3>Sample User Avatars:</h3>";
echo "<div style='display: flex; flex-wrap: wrap; gap: 10px;'>";

$db = Database::getInstance()->getConnection();
$stmt = $db->query("SELECT email FROM users LIMIT 10");
$emails = $stmt->fetchAll(PDO::FETCH_COLUMN);

foreach ($emails as $email) {
    echo "<div style='text-align: center;'>";
    echo "<div style='margin-bottom: 5px;'>" . htmlspecialchars($email) . "</div>";
    echo $avatar->generateSVG($email, 60);
    echo "</div>";
}

echo "</div>";

echo "<p><a href='/pages/admin/dashboard.php'>Return to Admin Dashboard</a></p>";
?> 