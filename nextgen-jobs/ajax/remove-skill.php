<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/User.php';

// Set content type to JSON
header('Content-Type: application/json');

// Check if user is logged in and is a job seeker
if (!isLoggedIn() || !isJobSeeker()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Check if it's a POST request and has the required data
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['skillId'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

try {
    $user = new User();
    $skillId = (int)$_POST['skillId'];
    
    // Remove skill from user profile
    $result = $user->removeSkill($_SESSION['user_id'], $skillId);
    
    if ($result) {
        echo json_encode([
            'success' => true, 
            'message' => 'Skill removed successfully'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to remove skill']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} 