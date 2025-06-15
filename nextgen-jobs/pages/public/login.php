<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../classes/Database.php';
require_once __DIR__ . '/../../classes/Auth.php';

$auth = new Auth();

// if ($auth->isLoggedIn()) {
//     redirect('/pages/user/dashboard.php');
// }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Add debug output
        error_log("Login attempt: " . $_POST['email']);
        
        $result = $auth->login($_POST['email'], $_POST['password']);
        
        error_log("Login successful: " . $_POST['email']);
        
        // Get user type from database
        $userType = getUserType();
        error_log("User type from database: " . $userType ?? 'undefined');
        
        // Redirect based on user type
        redirectBasedOnUserType();
    } catch (Exception $e) {
        $error = $e->getMessage();
        error_log("Login error: " . $error);
    }
}

$pageTitle = "Login - JOBEST";
$customStyles = <<<EOT
<style>
  body {
    background-color: #fff;
    height: 100vh;
    margin: 0;
    padding: 0;
    overflow-x: hidden;
    font-family: 'Inter', sans-serif;
  }
  
  .navbar, 
  main.container,
  footer {
    display: none;
  }
  
  .login-container {
    display: flex;
    height: 100vh;
    width: 100%;
  }
  
  .login-form-container {
    flex: 1;
    padding: 3rem 6rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
    max-width: 480px;
  }

  .brand {
    color: #111;
    font-weight: 700;
    font-size: 1.25rem;
    margin-bottom: 1.5rem;
  }

  .brand::after {
    content: "·";
    margin-left: 2px;
    font-weight: 700;
  }
  
  .login-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 1.5rem 0 0.5rem;
    color: #111;
  }
  
  .login-subtitle {
    color: #6c757d;
    margin-bottom: 2rem;
    font-size: 1.1rem;
  }
  
  .divider {
    display: flex;
    align-items: center;
    margin: 2rem 0;
    color: #adb5bd;
    font-size: 0.875rem;
  }
  
  .divider:before,
  .divider:after {
    content: "";
    flex: 1;
    border-bottom: 1px solid #dee2e6;
  }
  
  .divider span {
    padding: 0 1rem;
  }
  
  .form-control {
    height: 3.2rem;
    border-radius: 0.25rem;
    border: 1px solid #ced4da;
    padding: 0.75rem 1rem;
    font-size: 1rem;
    margin-bottom: 1rem;
    width: 100%;
    box-sizing: border-box;
  }
  
  .btn-google {
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 0.25rem;
    height: 3.2rem;
    font-weight: 500;
    color: #212529;
    width: 100%;
    margin-bottom: 1rem;
    cursor: pointer;
    transition: background-color 0.2s;
    text-decoration: none;
  }

  .btn-google:hover {
    background-color: #f8f9fa;
    text-decoration: none;
  }
  
  .btn-google img {
    margin-right: 0.75rem;
    width: 1.5rem;
  }
  
  .btn-login {
    background: #000;
    color: #fff;
    border: none;
    border-radius: 0.25rem;
    height: 3.2rem;
    font-weight: 500;
    width: 100%;
    margin-top: 1rem;
    margin-bottom: 1.5rem;
    cursor: pointer;
    transition: background-color 0.2s;
  }

  .btn-login:hover {
    background: #333;
  }
  
  .forgot-password {
    display: block;
    text-align: right;
    color: #6c757d;
    text-decoration: none;
    margin-top: -0.5rem;
    margin-bottom: 1rem;
    font-size: 0.875rem;
  }

  .forgot-password:hover {
    color: #333;
    text-decoration: underline;
  }
  
  .create-account {
    color: #212529;
    font-weight: 500;
    text-decoration: none;
  }

  .create-account:hover {
    text-decoration: underline;
  }
  
  .visual-container {
    flex: 1.2;
    background: #f5f5f5;
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
  }
  
  .visual-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    grid-template-rows: repeat(5, 1fr);
    gap: 1rem;
    width: 80%;
    height: 80%;
    position: relative;
  }
  
  .visual-item {
    border-radius: 0.5rem;
    overflow: hidden;
    position: relative;
  }
  
  .visual-item:nth-child(1) {
    background: #17A34A;
    grid-column: 1 / 2;
    grid-row: 1 / 2;
  }
  
  .visual-item:nth-child(2) {
    background: #FF89A2;
    grid-column: 2 / 3;
    grid-row: 1 / 3;
  }
  
  .visual-item:nth-child(3) {
    background: #245BFF;
    grid-column: 3 / 4;
    grid-row: 1 / 3;
  }
  
  .visual-item:nth-child(4) {
    background: #FFCF23;
    grid-column: 2 / 3;
    grid-row: 3 / 5;
  }
  
  .visual-item:nth-child(5) {
    background: #FFA01C;
    grid-column: 1 / 2;
    grid-row: 4 / 6;
  }
  
  .visual-item:nth-child(6) {
    background: #008F63;
    grid-column: 4 / 5;
    grid-row: 5 / 6;
  }

  .visual-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .visual-shape {
    position: absolute;
    width: 100%;
    height: 100%;
  }

  .visual-item:nth-child(3) .visual-shape {
    background: #245BFF;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .visual-item:nth-child(3) .visual-shape:after {
    content: "";
    width: 40%;
    height: 40%;
    border-radius: 50%;
    background: #FF89A2;
    margin-bottom: 20%;
    box-shadow: 0 60% 0 #FF89A2;
  }

  .visual-item:nth-child(5) .visual-shape {
    background: #FFA01C;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-content: center;
  }

  .visual-item:nth-child(5) .visual-shape:after {
    content: "";
    width: 40%;
    height: 40%;
    background: #FFCF23;
    border-radius: 50%;
    margin: 5%;
    box-shadow: 0 0 0 5px #FFCF23, 60% 0 0 5px #FFCF23, 
                60% 60% 0 5px #FFCF23, 0 60% 0 5px #FFCF23;
  }
  
  .visual-headline {
    font-size: 3.5rem;
    font-weight: 700;
    line-height: 1.1;
    margin-bottom: 1rem;
    text-align: center;
    position: absolute;
    bottom: 15%;
    left: 0;
    width: 100%;
    padding: 0 3rem;
    color: #111;
  }
  
  .visual-subline {
    font-size: 1.1rem;
    color: #6c757d;
    text-align: center;
    position: absolute;
    bottom: 10%;
    left: 0;
    width: 100%;
    padding: 0 3rem;
  }

  @media (max-width: 991.98px) {
    .login-container {
      flex-direction: column;
    }
    
    .login-form-container {
      padding: 3rem 2rem;
      max-width: 100%;
    }
    
    .visual-container {
      display: none;
    }
  }
</style>
EOT;
include __DIR__ . '/../../includes/header.php';
echo $customStyles;
?>

<div class="login-container">
  <div class="login-form-container">
    <div>
      <a href="/pages/public/index.php" class="d-inline-block mb-4" style="position: relative; z-index: 100; cursor: pointer;">
        <img src="/assets/images/V5NR.png" alt="JOBEST" height="40" style="pointer-events: auto;">
      </a>
      <h1 class="login-title">Login</h1>
      <p class="login-subtitle">Find the job made for you!</p>

            <?php if ($error): ?>
              <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
      
      <a href="#" class="btn-google">
        <img src="https://upload.wikimedia.org/wikipedia/commons/5/53/Google_%22G%22_Logo.svg" alt="Google"> 
        Log in with Google
      </a>
      
      <div class="divider">
        <span>or Login with Email</span>
      </div>

            <form method="POST">
                <input type="email" name="email" 
               class="form-control" 
               placeholder="Email address"
                       required autofocus>

                <input type="password" name="password" 
               class="form-control"
               placeholder="Password" 
               required>
               
        <a href="forgot-password.php" class="forgot-password">
                    Forgot password?
                  </a>
        
        <button type="submit" class="btn-login">
          Log in
        </button>
      </form>
      
      <div class="text-center">
        Not registered? <a href="register.php" class="create-account">Create an Account</a>
      </div>
                </div>
              </div>

  <div class="visual-container">
    <div class="visual-grid">
      <div class="visual-item"></div>
      <div class="visual-item"></div>
      <div class="visual-item">
        <div class="visual-shape"></div>
              </div>
      <div class="visual-item">
        <img src="https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?q=80&w=500&auto=format&fit=crop" alt="">
          </div>
      <div class="visual-item">
        <div class="visual-shape"></div>
        </div>
      <div class="visual-item">
        <div class="visual-shape"></div>
      </div>
    </div>
    <h2 class="visual-headline">Find the job<br>made for you.</h2>
  </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>