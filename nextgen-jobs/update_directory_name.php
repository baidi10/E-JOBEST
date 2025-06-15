<?php
/**
 * This script provides instructions for renaming the project directory
 * from nextgen-jobs to jobest
 */

echo "============================================\n";
echo "  RENAME DIRECTORY INSTRUCTIONS\n";
echo "============================================\n\n";

echo "To rename your project directory from 'nextgen-jobs' to 'jobest',\n";
echo "follow these steps:\n\n";

echo "1. Navigate to the parent directory of your project\n";
echo "   (e.g., cd C:/xampp/htdocs/)\n\n";

echo "2. Execute one of these commands based on your operating system:\n\n";

echo "   - Windows (Command Prompt):\n";
echo "     rename nextgen-jobs jobest\n\n";

echo "   - Windows (PowerShell):\n";
echo "     Rename-Item -Path \"nextgen-jobs\" -NewName \"jobest\"\n\n";

echo "   - Linux/Mac:\n";
echo "     mv nextgen-jobs jobest\n\n";

echo "3. Update your web server configuration if necessary\n\n";

echo "4. Update your database with this command:\n";
echo "   CREATE DATABASE jobest;\n";
echo "   USE jobest;\n";
echo "   SOURCE C:/xampp/htdocs/jobest/database_setup.sql;\n\n";

echo "5. Access your site at: http://localhost/jobest\n\n";

echo "============================================\n";
?> 