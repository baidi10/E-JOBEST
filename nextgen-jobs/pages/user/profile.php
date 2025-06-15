<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../classes/Database.php';
require_once __DIR__ . '/../../classes/User.php';

// Authentication check
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'jobSeeker') {
    header('Location: /pages/public/login.php');
    exit;
}

$user = new User();
$userData = $user->findById($_SESSION['user_id']);
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $updateData = [
            'firstName' => $_POST['firstName'],
            'lastName' => $_POST['lastName'],
            'location' => $_POST['location'],
            'bio' => $_POST['bio'],
            'websiteUrl' => $_POST['websiteUrl'],
            'linkedinUrl' => $_POST['linkedinUrl'],
            'githubUrl' => $_POST['githubUrl']
        ];
        
        if ($user->updateProfile($_SESSION['user_id'], $updateData)) {
            $success = "Profile updated successfully";
            $userData = $user->findById($_SESSION['user_id']); // Refresh data
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

$pageTitle = "Profile - JOBEST";
include __DIR__ . '/../../includes/header.php';
?>

<div class="container py-5">
    <div class="row g-4">
      <!-- Profile Sidebar -->
      <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
          <div class="card-body text-center">
            <div class="position-relative mb-3">
              <img src="<?= getUserAvatar($_SESSION['user_id']) ?>" 
                   class="rounded-circle mb-3" width="128" height="128">
              <button class="btn btn-primary btn-sm position-absolute bottom-0 end-0"
                      data-bs-toggle="modal" data-bs-target="#avatarModal">
                <i class="bi bi-camera"></i>
              </button>
            </div>
            <h2 class="h5 fw-bold"><?= htmlspecialchars($userData['firstName'] . ' ' . $userData['lastName']) ?></h2>
            <p class="text-muted small"><?= htmlspecialchars($userData['location'] ?? '') ?></p>
            
            <!-- Skills -->
            <div class="text-start">
              <h3 class="h6 fw-bold">Skills</h3>
              <div class="d-flex flex-wrap gap-2">
                <?php 
                try {
                  $skills = $user->getSkills($_SESSION['user_id']);
                  if (!empty($skills)):
                    foreach ($skills as $skill): ?>
                      <span class="badge bg-light text-dark"><?= htmlspecialchars($skill) ?></span>
                    <?php endforeach; 
                  else: ?>
                    <p class="text-muted small">No skills added yet</p>
                  <?php endif;
                } catch (Exception $e) {
                  echo '<p class="text-muted small">Skills functionality unavailable</p>';
                  error_log('Error loading skills: ' . $e->getMessage());
                }
                ?>
                <button class="btn btn-link p-0" data-bs-toggle="modal" data-bs-target="#skillsModal">
                  <i class="bi bi-plus-circle"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Profile Form -->
      <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
          <div class="card-body">
            <?php if ($error): ?>
              <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
              <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <form method="POST">
              <!-- Basic Info -->
              <div class="mb-4">
                <h3 class="h5 fw-bold mb-4">Basic Information</h3>
                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="form-label">First Name</label>
                    <input type="text" name="firstName" 
                           class="form-control" 
                           value="<?= htmlspecialchars($userData['firstName']) ?>" required>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Last Name</label>
                    <input type="text" name="lastName" 
                           class="form-control" 
                           value="<?= htmlspecialchars($userData['lastName']) ?>" required>
                  </div>
                </div>
              </div>

              <!-- Location -->
              <div class="mb-4">
                <h3 class="h5 fw-bold mb-4">Location</h3>
                <div class="row g-3">
                  <div class="col-12">
                    <input type="text" name="location" 
                           class="form-control" 
                           value="<?= htmlspecialchars($userData['location'] ?? '') ?>"
                           placeholder="City, Country">
                  </div>
                </div>
              </div>

              <!-- Social Links -->
              <div class="mb-4">
                <h3 class="h5 fw-bold mb-4">Social Profiles</h3>
                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="form-label">LinkedIn</label>
                    <div class="input-group">
                      <span class="input-group-text">
                        <i class="bi bi-linkedin"></i>
                      </span>
                      <input type="url" name="linkedinUrl" 
                             class="form-control" 
                             value="<?= htmlspecialchars($userData['linkedinUrl'] ?? '') ?>"
                             placeholder="https://linkedin.com/in/username">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">GitHub</label>
                    <div class="input-group">
                      <span class="input-group-text">
                        <i class="bi bi-github"></i>
                      </span>
                      <input type="url" name="githubUrl" 
                             class="form-control" 
                             value="<?= htmlspecialchars($userData['githubUrl'] ?? '') ?>"
                             placeholder="https://github.com/username">
                    </div>
                  </div>
                </div>
              </div>

              <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>
          </div>
        </div>
      </div>
    </div>
</div>

<!-- Avatar Modal -->
<div class="modal fade" id="avatarModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Update Profile Picture</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <p>Your avatar is automatically generated based on your email address.</p>
        <img src="<?= getUserAvatar($_SESSION['user_id']) ?>" 
             class="rounded-circle mb-3" width="150" height="150">
      </div>
    </div>
  </div>
</div>

<!-- Skills Modal -->
<div class="modal fade" id="skillsModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Manage Skills</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <?php if (isset($skills)): ?>
          <form id="addSkillForm" action="/ajax/add-skill.php" method="POST">
            <div class="input-group mb-3">
              <input type="text" name="skill" class="form-control" placeholder="Add a skill">
              <button class="btn btn-primary" type="submit">Add</button>
            </div>
          </form>
          
          <div class="mt-4">
            <h6 class="fw-bold">Your Skills</h6>
            <div id="skillsList" class="d-flex flex-wrap gap-2 mt-3">
              <?php if (!empty($skills)): ?>
                <?php foreach ($skills as $skill): ?>
                  <div class="badge bg-light text-dark d-flex align-items-center p-2">
                    <?= htmlspecialchars($skill) ?>
                    <button type="button" class="btn-close ms-2" style="font-size: 0.5rem;"
                            onclick="removeSkill('<?= htmlspecialchars($skill) ?>')"></button>
                  </div>
                <?php endforeach; ?>
              <?php else: ?>
                <p class="text-muted small">No skills added yet</p>
              <?php endif; ?>
            </div>
          </div>
        <?php else: ?>
          <div class="alert alert-warning">
            <p>Skills functionality is not available at this time.</p>
            <p>You need to run the <code>create_skills_tables.php</code> script to set up the database tables.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<script>
  // Add skill functionality
  document.getElementById('addSkillForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const skillInput = this.querySelector('input[name="skill"]');
    const skill = skillInput.value.trim();
    
    if (skill.length < 2 || skill.length > 50) {
      alert('Skill name must be between 2 and 50 characters');
      return;
    }
    
    // Send AJAX request to add skill
    fetch('/ajax/add-skill.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: 'skill=' + encodeURIComponent(skill)
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Add skill to the list
        addSkillToDOM(data.skill);
        skillInput.value = '';
      } else {
        alert(data.message || 'Error adding skill');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('An error occurred. Please try again.');
    });
  });
  
  // Add skill to DOM
  function addSkillToDOM(skill) {
    const skillsList = document.getElementById('skillsList');
    
    // Check if skills list contains a "No skills" message
    if (skillsList.querySelector('p.text-muted')) {
      skillsList.innerHTML = '';
    }
    
    const skillElement = document.createElement('div');
    skillElement.className = 'badge bg-light text-dark d-flex align-items-center p-2';
    skillElement.innerHTML = `
      ${skill}
      <button type="button" class="btn-close ms-2" style="font-size: 0.5rem;"
              onclick="removeSkill('${skill}')"></button>
    `;
    
    skillsList.appendChild(skillElement);
  }
  
  // Remove skill functionality
  function removeSkill(skill) {
    if (!confirm(`Remove skill: ${skill}?`)) {
      return;
    }
    
    // Here we would normally need the skill ID, but we're simplifying
    // In a real implementation, you'd pass the skill ID to this function
    
    // For now, just remove the element from DOM on successful AJAX
    const skillElements = document.querySelectorAll('.badge');
    skillElements.forEach(el => {
      if (el.textContent.trim().startsWith(skill)) {
        el.remove();
      }
    });
    
    // If no skills left, show "No skills" message
    const skillsList = document.getElementById('skillsList');
    if (skillsList.children.length === 0) {
      skillsList.innerHTML = '<p class="text-muted small">No skills added yet</p>';
    }
  }
</script>

<?php include __DIR__ . '/../../includes/footer.php'; ?>