<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../classes/Database.php';
require_once __DIR__ . '/../../classes/Auth.php';

$auth = new Auth();

// if ($auth->isLoggedIn()) {
//     header('Location: /pages/user/dashboard.php');
//     exit();
// }

$error = '';
$success = '';
$formData = [
    'firstName' => '',
    'lastName' => '',
    'email' => '',
    'userType' => 'jobSeeker'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $formData = [
            'firstName' => htmlspecialchars(trim($_POST['firstName'] ?? '')),
            'lastName' => htmlspecialchars(trim($_POST['lastName'] ?? '')),
            'email' => filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL),
            'userType' => in_array($_POST['userType'] ?? '', ['jobSeeker', 'employer']) 
                          ? $_POST['userType'] 
                          : 'jobSeeker'
        ];

        $password = $_POST['password'] ?? '';
        
        if (strlen($password) < 8) {
            throw new Exception("Password must be at least 8 characters long");
        }

        $auth->register(
            $formData['email'],
            $password,
            [
                'firstName' => $formData['firstName'],
                'lastName' => $formData['lastName'],
                'userType' => $formData['userType']
            ]
        );
        
        $success = "Registration successful! Please check your email to verify your account.";
        $formData = [];
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

$pageTitle = "Register - JOBEST";
include __DIR__ . '/../../includes/header.php';
?>

<div class="auth-container">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-10 col-lg-8 col-xl-6">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
          <div class="card-body p-4 p-md-5">
            <div class="text-center mb-4">
              <a href="/pages/public/index.php" class="d-inline-block mb-3" style="position: relative; z-index: 100;">
                <img src="/assets/images/V5NR.png" alt="JOBEST" height="40">
              </a>
              <h1 class="h3 fw-bold mb-2">Create Your Account</h1>
              <p class="text-muted">Join thousands of tech professionals and companies</p>
          </div>
          
            <div class="register-form">
            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
                  <div class="d-flex">
                    <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i>
                <div><?= htmlspecialchars($error) ?></div>
                  </div>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
                  <div class="d-flex">
                    <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                <div><?= htmlspecialchars($success) ?></div>
                  </div>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
              
                <div class="text-center my-5">
                  <a href="/pages/public/login.php" class="btn btn-dark rounded-pill px-5 py-3 fw-medium">
                    <i class="bi bi-arrow-right-circle me-2"></i> Continue to Login
                </a>
              </div>
            <?php else: ?>
                <form method="POST" class="needs-validation" novalidate>
                  <div class="row g-4 mb-4">
                    <div class="col-md-6">
                      <label for="firstName" class="form-label">First Name</label>
                    <input type="text" 
                             class="form-control rounded-3" 
                           id="firstName" 
                           name="firstName" 
                           value="<?= htmlspecialchars($formData['firstName']) ?>" 
                           required
                           autofocus>
                  </div>
                  
                    <div class="col-md-6">
                      <label for="lastName" class="form-label">Last Name</label>
                    <input type="text" 
                             class="form-control rounded-3" 
                           id="lastName" 
                           name="lastName" 
                           value="<?= htmlspecialchars($formData['lastName']) ?>" 
                           required>
                  </div>
                </div>

                  <div class="mb-4">
                    <label for="email" class="form-label">Email Address</label>
                  <input type="email" 
                           class="form-control rounded-3" 
                         id="email" 
                         name="email" 
                         value="<?= htmlspecialchars($formData['email']) ?>" 
                         required>
                </div>

                  <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group mb-2">
                    <input type="password" 
                             class="form-control rounded-start" 
                           id="password" 
                           name="password" 
                           required
                           minlength="8">
                      <button type="button" class="btn btn-outline-secondary toggle-password px-3">
                        <i class="bi bi-eye"></i>
                    </button>
                  </div>
                    <div class="form-text small text-muted">
                      <i class="bi bi-info-circle me-1"></i> Minimum 8 characters with at least one number or special character
                    </div>
                </div>

                  <div class="mb-4">
                    <label class="form-label d-block mb-3">I'm a...</label>
                    <div class="row">
                      <div class="col-6">
                        <div class="form-check custom-option border rounded-3 p-3">
                          <input class="form-check-input" type="radio" 
                           name="userType" 
                           id="jobSeeker" 
                           value="jobSeeker" 
                           <?= $formData['userType'] === 'jobSeeker' ? 'checked' : '' ?>>
                          <label class="form-check-label w-100" for="jobSeeker">
                            <i class="bi bi-person me-2"></i> Job Seeker
                    </label>
                        </div>
                      </div>
                      <div class="col-6">
                        <div class="form-check custom-option border rounded-3 p-3">
                          <input class="form-check-input" type="radio" 
                           name="userType" 
                           id="employer" 
                           value="employer" 
                           <?= $formData['userType'] === 'employer' ? 'checked' : '' ?>>
                          <label class="form-check-label w-100" for="employer">
                            <i class="bi bi-building me-2"></i> Employer
                    </label>
                        </div>
                      </div>
                  </div>
                </div>

                  <button type="submit" class="btn btn-dark w-100 py-3 rounded-pill fw-medium mb-4">
                  Create Account
                </button>

                  <div class="small text-center text-muted mb-4">
                  By registering, you agree to our 
                    <a href="/terms" class="text-decoration-none fw-medium">Terms of Service</a> and 
                    <a href="/privacy" class="text-decoration-none fw-medium">Privacy Policy</a>
                </div>

                  <div class="position-relative text-center my-4">
                    <hr class="text-muted">
                    <div class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted small">
                    OR
                  </div>
                </div>

                  <div class="d-grid gap-3 mb-4">
                    <a href="/auth/google" class="btn btn-outline-dark rounded-pill py-3 d-flex align-items-center justify-content-center gap-2">
                      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-google" viewBox="0 0 16 16">
                        <path d="M15.545 6.558a9.42 9.42 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.689 7.689 0 0 1 5.352 2.082l-2.284 2.284A4.347 4.347 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.792 4.792 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.702 3.702 0 0 0 1.599-2.431H8v-3.08h7.545z"/>
                      </svg>
                    Continue with Google
                  </a>
                    <a href="/auth/linkedin" class="btn btn-outline-dark rounded-pill py-3 d-flex align-items-center justify-content-center gap-2">
                      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-linkedin" viewBox="0 0 16 16">
                        <path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854V1.146zm4.943 12.248V6.169H2.542v7.225h2.401zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248-.822 0-1.359.54-1.359 1.248 0 .694.521 1.248 1.327 1.248h.016zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016a5.54 5.54 0 0 1 .016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225h2.4z"/>
                      </svg>
                    Continue with LinkedIn
                  </a>
                </div>

                  <div class="text-center text-muted">
                  Already have an account? 
                    <a href="/pages/public/login.php" class="text-decoration-none fw-medium">
                    Sign in here
                  </a>
                </div>
              </form>
            <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>

<script>
// Toggle password visibility
document.querySelectorAll('.toggle-password').forEach(button => {
  button.addEventListener('click', function() {
    const passwordInput = this.previousElementSibling;
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    
    // Update the eye icon
    if (type === 'password') {
      this.innerHTML = '<i class="bi bi-eye"></i>';
    } else {
      this.innerHTML = '<i class="bi bi-eye-slash"></i>';
    }
  });
});

// Enhanced validation with better UI feedback
(function() {
  'use strict';
  
  // Simple click handler for custom radio options
  document.querySelectorAll('.custom-option').forEach(option => {
    option.addEventListener('click', function(e) {
      const radio = this.querySelector('input[type="radio"]');
      if (radio) {
        radio.checked = true;
      }
    });
  });
  
  // Form validation
  const form = document.querySelector('form.needs-validation');
  if (form) {
    form.addEventListener('submit', function(event) {
      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
      }
      
      // Check password strength
  const password = document.getElementById('password');
      if (password && password.value.length < 8) {
        event.preventDefault();
        
        // Create or update feedback element
        let feedback = password.parentNode.querySelector('.invalid-feedback');
        if (!feedback) {
          feedback = document.createElement('div');
          feedback.className = 'invalid-feedback';
          password.parentNode.appendChild(feedback);
        }
        
        feedback.textContent = 'Password must be at least 8 characters long';
        password.classList.add('is-invalid');
      }
      
      form.classList.add('was-validated');
    });
  }
})();
</script>