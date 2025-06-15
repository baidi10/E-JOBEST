<?php
// Script to update all incorrect auth/login.php paths to the correct login.php path

function updateLoginPathsInDirectory($directory) {
    $files = glob($directory . '/*.php');
    
    foreach ($files as $file) {
        $content = file_get_contents($file);
        
        if (strpos($content, '/pages/public/login.php') !== false) {
            echo "Updating file: $file\n";
            
            $updatedContent = str_replace(
                '/pages/public/login.php',
                '/pages/public/login.php',
                $content
            );
            
            file_put_contents($file, $updatedContent);
        }
    }
    
    // Process subdirectories
    $subdirectories = glob($directory . '/*', GLOB_ONLYDIR);
    foreach ($subdirectories as $subdirectory) {
        updateLoginPathsInDirectory($subdirectory);
    }
}

echo "Starting to update incorrect login paths...\n";

// Update all PHP files in the project
updateLoginPathsInDirectory(__DIR__);

echo "Update complete!\n";
?> 