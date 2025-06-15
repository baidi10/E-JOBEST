<?php
// Check if running in browser and display a simple interface
$isCli = php_sapi_name() === 'cli';

if (!$isCli) {
    echo '<!DOCTYPE html>
<html>
<head>
    <title>Gmail App Password Guide</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .step-img {
            max-width: 100%;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin: 15px 0;
        }
    </style>
</head>
<body class="p-4">
    <div class="container">
        <h1 class="mb-4">Gmail App Password Guide</h1>
        
        <div class="alert alert-warning">
            <strong>Important:</strong> The SMTP authentication error you\'re experiencing is because Google requires 
            an <strong>App Password</strong> when using 2-Step Verification.
        </div>
        
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h2 class="h5 fw-bold mb-0">Step 1: Enable 2-Step Verification (if not already enabled)</h2>
            </div>
            <div class="card-body">
                <ol>
                    <li>Go to your <a href="https://myaccount.google.com/security" target="_blank">Google Account Security settings</a></li>
                    <li>Scroll to "Signing in to Google" and select "2-Step Verification"</li>
                    <li>Follow the steps to turn on 2-Step Verification</li>
                </ol>
            </div>
        </div>
        
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h2 class="h5 fw-bold mb-0">Step 2: Generate an App Password</h2>
            </div>
            <div class="card-body">
                <ol>
                    <li>Go to your <a href="https://myaccount.google.com/apppasswords" target="_blank">App passwords</a> page
                        <ul>
                            <li>Note: This page is only accessible if 2-Step Verification is enabled</li>
                        </ul>
                    </li>
                    <li>At the bottom, click "Select app" and choose "Other (Custom name)"</li>
                    <li>Enter "JOBEST" as the name</li>
                    <li>Click "Generate"</li>
                    <li>Google will display a 16-character password without spaces</li>
                    <li><strong>Important:</strong> Copy this password immediately - you won\'t be able to see it again!</li>
                </ol>
                
                <div class="alert alert-info">
                    Your App Password will look something like: <code>hqwz abcd efgh ijkl</code>
                    <br>
                    When copying it to use in the email configuration, you can either:
                    <ul>
                        <li>Include the spaces exactly as shown, OR</li>
                        <li>Remove all spaces (both will work)</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h2 class="h5 fw-bold mb-0">Step 3: Use the App Password</h2>
            </div>
            <div class="card-body">
                <ol>
                    <li>Return to the <a href="configure_email.php">Email Configuration page</a></li>
                    <li>Enter your full Gmail address (e.g., youremail@gmail.com)</li>
                    <li>In the password field, enter the 16-character App Password you generated</li>
                    <li>Click "Save Email Settings"</li>
                </ol>
                
                <div class="alert alert-success">
                    The App Password will <strong>only</strong> be used for JOBEST and won\'t give full access to your Google account.
                    You can revoke it anytime from your Google Account security settings.
                </div>
            </div>
        </div>
        
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h2 class="h5 fw-bold mb-0">Troubleshooting</h2>
            </div>
            <div class="card-body">
                <h5>Still having issues?</h5>
                <ul>
                    <li>Make sure you\'re using your complete Gmail address (e.g., <code>youremail@gmail.com</code>)</li>
                    <li>Verify you\'re using the App Password exactly as generated</li>
                    <li>Try creating a new App Password</li>
                    <li>Check if your Gmail account has any security blocks by visiting <a href="https://accounts.google.com/DisplayUnlockCaptcha" target="_blank">Unlock Captcha</a></li>
                    <li>For more advanced debugging, use the <a href="smtp_diagnostics.php">SMTP Diagnostics Tool</a></li>
                </ul>
            </div>
        </div>
        
        <div class="mt-4">
            <a href="configure_email.php" class="btn btn-primary">Return to Email Configuration</a>
            <a href="smtp_diagnostics.php" class="btn btn-outline-secondary ms-2">Go to SMTP Diagnostics Tool</a>
        </div>
    </div>
</body>
</html>';
} else {
    echo "Gmail App Password Guide\n";
    echo "=======================\n\n";
    
    echo "To fix the 'Could not authenticate' error with Gmail, follow these steps:\n\n";
    
    echo "Step 1: Enable 2-Step Verification (if not already enabled)\n";
    echo "  1. Go to your Google Account Security settings: https://myaccount.google.com/security\n";
    echo "  2. Scroll to 'Signing in to Google' and select '2-Step Verification'\n";
    echo "  3. Follow the steps to turn on 2-Step Verification\n\n";
    
    echo "Step 2: Generate an App Password\n";
    echo "  1. Go to your App passwords page: https://myaccount.google.com/apppasswords\n";
    echo "     Note: This page is only accessible if 2-Step Verification is enabled\n";
    echo "  2. At the bottom, click 'Select app' and choose 'Other (Custom name)'\n";
    echo "  3. Enter 'JOBEST' as the name\n";
    echo "  4. Click 'Generate'\n";
    echo "  5. Google will display a 16-character password\n";
    echo "  6. IMPORTANT: Copy this password immediately - you won't be able to see it again!\n\n";
    
    echo "Step 3: Use the App Password\n";
    echo "  1. Return to the Email Configuration page\n";
    echo "  2. Enter your full Gmail address (e.g., youremail@gmail.com)\n";
    echo "  3. In the password field, enter the 16-character App Password you generated\n";
    echo "  4. Click 'Save Email Settings'\n\n";
    
    echo "Troubleshooting:\n";
    echo "  - Make sure you're using your complete Gmail address\n";
    echo "  - Verify you're using the App Password exactly as generated\n";
    echo "  - Try creating a new App Password\n";
    echo "  - Check if your Gmail account has any security blocks by visiting: https://accounts.google.com/DisplayUnlockCaptcha\n";
}
?> 