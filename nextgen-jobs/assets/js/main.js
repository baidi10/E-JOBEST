// Global functions
/**
 * JOBEST - Main Application Script
 * Global functions and initialization
 */

document.addEventListener('DOMContentLoaded', function() {
  // Initialize tooltips
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl, {
      boundary: document.body
    });
  });

  // Initialize popovers
  const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
  popoverTriggerList.map(function (popoverTriggerEl) {
    return new bootstrap.Popover(popoverTriggerEl);
  });
  
  // Remove duplicate navigation bars
  removeDuplicateNavigation();
  
  // Fix dropdown menus
  fixDropdownMenus();

  // Mobile menu toggle
  const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
  const navMenu = document.querySelector('.main-nav');
  
  if (mobileMenuToggle && navMenu) {
    mobileMenuToggle.addEventListener('click', function() {
      this.classList.toggle('active');
      navMenu.classList.toggle('active');
      document.body.classList.toggle('menu-open');
    });
  }

  // Smooth scrolling for anchor links
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
        target.scrollIntoView({
          behavior: 'smooth',
          block: 'start'
        });
      }
    });
  });

  // Initialize form validation
  initFormValidation();
});

/**
 * Remove duplicate navigation bars
 */
function removeDuplicateNavigation() {
  // Keep only the first main navigation
  const navs = document.querySelectorAll('.navbar-nav');
  
  if (navs.length > 1) {
    // Keep the first one with main-navigation class, or just the first one
    const mainNav = document.querySelector('.navbar-nav.main-navigation') || navs[0];
    
    navs.forEach(nav => {
      if (nav !== mainNav) {
        nav.style.display = 'none';
      }
    });
  }
  
  // Also hide any elements that might be causing duplicate navigation
  const elementsToHide = [
    '.duplicate-nav',
    '.duplicate-navigation',
    '.employer-top-nav',
    '.employer-navigation',
    '.custom-employer-nav',
    '.secondary-nav',
    '.top-navbar:not(:first-of-type)',
    '.header-navigation:not(:first-of-type)'
  ];
  
  elementsToHide.forEach(selector => {
    document.querySelectorAll(selector).forEach(el => {
      el.style.display = 'none';
    });
  });
}

/**
 * Fix dropdown menus
 */
function fixDropdownMenus() {
  // Ensure all dropdowns work properly
  const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
  dropdownToggles.forEach(toggle => {
    // Ensure aria-expanded is properly set
    toggle.setAttribute('aria-expanded', 'false');
    
    // Make sure proper event listeners are added
    toggle.addEventListener('click', function(e) {
      if (!this.classList.contains('user-dropdown-toggle')) {
        e.preventDefault();
        const parentDropdown = this.closest('.dropdown');
        if (parentDropdown) {
          parentDropdown.classList.toggle('show');
          this.setAttribute('aria-expanded', parentDropdown.classList.contains('show'));
          
          const dropdownMenu = parentDropdown.querySelector('.dropdown-menu');
          if (dropdownMenu) {
            dropdownMenu.classList.toggle('show');
          }
        }
      }
    });
  });
  
  // Also handle clicks on dropdown items to prevent menu from closing when selecting submenus
  const dropdownItems = document.querySelectorAll('.dropdown-item');
  dropdownItems.forEach(item => {
    item.addEventListener('click', function(e) {
      const href = this.getAttribute('href');
      if (href && href !== '#') {
        window.location.href = href;
      }
    });
  });
  
  // Close dropdown menus when clicking outside
  document.addEventListener('click', function(e) {
    const dropdowns = document.querySelectorAll('.dropdown.show');
    if (dropdowns.length > 0) {
      dropdowns.forEach(dropdown => {
        if (!dropdown.contains(e.target)) {
          dropdown.classList.remove('show');
          const toggle = dropdown.querySelector('.dropdown-toggle');
          if (toggle) {
            toggle.setAttribute('aria-expanded', 'false');
          }
          const menu = dropdown.querySelector('.dropdown-menu');
          if (menu) {
            menu.classList.remove('show');
          }
        }
      });
    }
  });
  
  // Make sure the navigation items in mobile view work correctly
  const mobileNavToggler = document.querySelector('.navbar-toggler');
  if (mobileNavToggler) {
    mobileNavToggler.addEventListener('click', function() {
      const navbarCollapse = document.getElementById('navbarNav');
      if (navbarCollapse) {
        navbarCollapse.classList.toggle('show');
      }
    });
  }
}

/**
 * Global form validation handler
 */
function initFormValidation() {
  // Example for login form
  const loginForm = document.getElementById('login-form');
  if (loginForm) {
    loginForm.addEventListener('submit', function(e) {
      const email = this.querySelector('input[name="email"]');
      const password = this.querySelector('input[name="password"]');
      
      if (!email.value || !isValidEmail(email.value)) {
        e.preventDefault();
        showValidationError(email, 'Please enter a valid email address');
      }
      
      if (!password.value || password.value.length < 8) {
        e.preventDefault();
        showValidationError(password, 'Password must be at least 8 characters');
      }
    });
  }
}

function isValidEmail(email) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

function showValidationError(input, message) {
  const errorElement = document.createElement('div');
  errorElement.className = 'invalid-feedback';
  errorElement.textContent = message;
  
  const parent = input.parentElement;
  input.classList.add('is-invalid');
  
  const existingError = parent.querySelector('.invalid-feedback');
  if (existingError) {
    parent.removeChild(existingError);
  }
  
  parent.appendChild(errorElement);
}

// Global AJAX error handler
$(document).ajaxError(function(event, jqxhr, settings, thrownError) {
  showToast('Error', 'An error occurred while processing your request', 'error');
});

// Global toast notification
function showToast(title, message, type = 'success') {
  const toastContainer = document.getElementById('toast-container');
  if (!toastContainer) return;

  const toastEl = document.createElement('div');
  toastEl.className = `toast show align-items-center text-white bg-${type}`;
  toastEl.setAttribute('role', 'alert');
  toastEl.setAttribute('aria-live', 'assertive');
  toastEl.setAttribute('aria-atomic', 'true');
  
  toastEl.innerHTML = `
    <div class="d-flex">
      <div class="toast-body">
        <strong>${title}</strong><br>${message}
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  `;
  
  toastContainer.appendChild(toastEl);
  
  setTimeout(() => {
    toastEl.classList.remove('show');
    setTimeout(() => toastEl.remove(), 300);
  }, 5000);
}