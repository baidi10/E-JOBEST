<?php
/**
 * Script to update site name from "JOBEST" to "JOBEST" across the site
 */

// Directory to search
$directory = __DIR__;

// Function to recursively scan directory and update files
function updateSiteName($dir, $oldName, $newName) {
    $files = scandir($dir);
    $count = 0;

    foreach ($files as $file) {
        if ($file == '.' || $file == '..') {
            continue;
        }

        $path = $dir . '/' . $file;

        if (is_dir($path)) {
            // Skip certain directories if needed
            if (basename($path) == 'vendor' || basename($path) == 'node_modules') {
                continue;
            }
            $count += updateSiteName($path, $oldName, $newName);
        } else {
            // Only process PHP files, SQL, JS, etc.
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            if (in_array($ext, ['php', 'sql', 'js', 'html', 'css'])) {
                $content = file_get_contents($path);
                if (strpos($content, $oldName) !== false) {
                    $newContent = str_replace($oldName, $newName, $content);
                    file_put_contents($path, $newContent);
                    echo "Updated: $path\n";
                    $count++;
                }
            }
        }
    }
    return $count;
}

// Run the update
echo "Starting update process...\n";
$totalUpdated = updateSiteName($directory, 'JOBEST', 'JOBEST');
$totalUpdated += updateSiteName($directory, 'JOBEST', 'JOBEST');
$totalUpdated += updateSiteName($directory, 'jobest', 'jobest');
echo "Total files updated: $totalUpdated\n";
echo "Update complete!\n"; 