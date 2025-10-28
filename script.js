document.addEventListener('DOMContentLoaded', function() {
    // Header scroll effect
    const header = document.getElementById('header');
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });
    
    // Mobile navigation toggle
    const mobileToggle = document.getElementById('mobile-toggle');
    const mobileNav = document.getElementById('mobile-nav');
    const closeMobileNav = document.getElementById('close-mobile-nav');
    
    mobileToggle.addEventListener('click', function() {
        mobileNav.classList.add('active');
    });
    
    closeMobileNav.addEventListener('click', function() {
        mobileNav.classList.remove('active');
    });
    
    // Smooth scrolling for navigation links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            if (this.getAttribute('href') === '#') return;
            
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                // Close mobile nav if open
                mobileNav.classList.remove('active');
                
                window.scrollTo({
                    top: targetElement.offsetTop - 80,
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // Authentication modal
    const authModal = document.getElementById('auth-modal');
    const loginLink = document.getElementById('login-link');
    const mobileLoginLink = document.getElementById('mobile-login-link');
    const closeModal = document.getElementById('close-modal');
    const switchToSignup = document.getElementById('switch-to-signup');
    const switchToLogin = document.getElementById('switch-to-login');
    const loginForm = document.getElementById('login-form');
    const signupForm = document.getElementById('signup-form');
    const modalTitle = document.getElementById('modal-title');
    
    // Message elements
    const loginMessage = document.getElementById('login-message');
    const signupMessage = document.getElementById('signup-message');
    
    function openModal() {
        authModal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        // Clear messages when opening modal
        clearMessages();
    }
    
    function closeAuthModal() {
        authModal.style.display = 'none';
        document.body.style.overflow = 'auto';
        clearMessages();
    }
    
    function clearMessages() {
        if (loginMessage) loginMessage.textContent = '';
        if (signupMessage) signupMessage.textContent = '';
    }
    
    function showMessage(element, message, type) {
        element.textContent = message;
        element.className = `message ${type}`;
    }
    
    function showLoading(button, spinner, buttonText) {
        button.disabled = true;
        spinner.style.display = 'inline-block';
        buttonText.style.display = 'none';
    }
    
    function hideLoading(button, spinner, buttonText) {
        button.disabled = false;
        spinner.style.display = 'none';
        buttonText.style.display = 'inline';
    }
    
    loginLink.addEventListener('click', function(e) {
        e.preventDefault();
        openModal();
    });
    
    mobileLoginLink.addEventListener('click', function(e) {
        e.preventDefault();
        openModal();
        mobileNav.classList.remove('active');
    });
    
    closeModal.addEventListener('click', closeAuthModal);
    
    authModal.addEventListener('click', function(e) {
        if (e.target === authModal) {
            closeAuthModal();
        }
    });
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && authModal.style.display === 'flex') {
            closeAuthModal();
        }
    });
    
    switchToSignup.addEventListener('click', function(e) {
        e.preventDefault();
        loginForm.classList.remove('active');
        signupForm.classList.add('active');
        modalTitle.textContent = 'Create an Account';
        clearMessages();
    });
    
    switchToLogin.addEventListener('click', function(e) {
        e.preventDefault();
        signupForm.classList.remove('active');
        loginForm.classList.add('active');
        modalTitle.textContent = 'Login to Your Account';
        clearMessages();
    });
    
    // UPDATED: Login form submission
    loginForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const email = document.getElementById('login-email').value;
        const password = document.getElementById('login-password').value;
        const loginBtn = this.querySelector('button[type="submit"]');
        const loginSpinner = document.getElementById('login-spinner');
        const loginBtnText = document.getElementById('login-btn-text');
        const loginMessage = document.getElementById('login-message');

        // Show loading state
        loginBtn.disabled = true;
        if (loginSpinner) loginSpinner.style.display = 'inline-block';
        if (loginBtnText) loginBtnText.style.display = 'none';

        try {
            console.log('Sending login request...');
            
            const response = await fetch('login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    email: email,
                    password: password
                })
            });

            const data = await response.json();
            console.log('Login response:', data);

            if (data.success) {
                // Store token and user data
                localStorage.setItem('authToken', data.token);
                localStorage.setItem('userData', JSON.stringify(data.user));
                
                showMessage(loginMessage, 'Login successful! Redirecting...', 'success');
                
                // FIXED: Wait a bit then redirect
                setTimeout(() => {
                    closeAuthModal();
                    checkAuthStatus();
                    
                    // Use the dashboardUrl from server response, or fallback
                    const dashboardUrl = data.dashboardUrl || getDashboardUrl(data.user.userType);
                    console.log('Redirecting to:', dashboardUrl);
                    
                    // Force redirect
                    window.location.href = dashboardUrl;
                }, 1000);
                
            } else {
                showMessage(loginMessage, data.message || 'Login failed', 'error');
            }
        } catch (error) {
            console.error('Login error:', error);
            showMessage(loginMessage, 'Network error. Please try again.', 'error');
        } finally {
            // Hide loading state
            loginBtn.disabled = false;
            if (loginSpinner) loginSpinner.style.display = 'none';
            if (loginBtnText) loginBtnText.style.display = 'inline';
        }
    });
   
    // UPDATED: Signup form submission
    signupForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const fullName = document.getElementById('signup-fullname').value;
        const email = document.getElementById('signup-email').value;
        const password = document.getElementById('signup-password').value;
        const confirmPassword = document.getElementById('signup-confirm-password').value;
        const userType = document.getElementById('signup-usertype').value;
        const signupBtn = this.querySelector('button[type="submit"]');
        const signupSpinner = document.getElementById('signup-spinner');
        const signupBtnText = document.getElementById('signup-btn-text');
        const signupMessage = document.getElementById('signup-message');

        // Show loading state
        signupBtn.disabled = true;
        if (signupSpinner) signupSpinner.style.display = 'inline-block';
        if (signupBtnText) signupBtnText.style.display = 'none';

        try {
            console.log('Sending signup request...');
            
            const response = await fetch('signup.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    fullName: fullName,
                    email: email,
                    password: password,
                    confirmPassword: confirmPassword,
                    userType: userType
                })
            });

            const data = await response.json();
            console.log('Signup response:', data);

            if (data.success) {
                // Store token and user data
                localStorage.setItem('authToken', data.token);
                localStorage.setItem('userData', JSON.stringify(data.user));
                
                showMessage(signupMessage, 'Registration successful! Redirecting...', 'success');
                
                // FIXED: Wait a bit then redirect
                setTimeout(() => {
                    closeAuthModal();
                    checkAuthStatus();
                    
                    // Use the dashboardUrl from server response, or fallback
                    const dashboardUrl = data.dashboardUrl || getDashboardUrl(data.user.userType);
                    console.log('Redirecting to:', dashboardUrl);
                    
                    // Force redirect
                    window.location.href = dashboardUrl;
                }, 1000);
                
            } else {
                showMessage(signupMessage, data.message || 'Registration failed', 'error');
            }
        } catch (error) {
            console.error('Signup error:', error);
            showMessage(signupMessage, 'Network error. Please try again.', 'error');
        } finally {
            // Hide loading state
            signupBtn.disabled = false;
            if (signupSpinner) signupSpinner.style.display = 'none';
            if (signupBtnText) signupBtnText.style.display = 'inline';
        }
    });
    
    // Check authentication status
    function checkAuthStatus() {
        const token = localStorage.getItem('authToken');
        const userData = localStorage.getItem('userData');
        const authButtons = document.getElementById('auth-buttons');
        const userMenu = document.getElementById('user-menu');
        
        if (token && userData) {
            try {
                const user = JSON.parse(userData);
                
                // Update desktop navigation
                if (authButtons && userMenu) {
                    document.querySelector('.login-btn').style.display = 'none';
                    document.querySelector('.signup-btn').style.display = 'none';
                    userMenu.style.display = 'block';
                    
                    const userName = document.getElementById('user-name');
                    if (userName) {
                        userName.textContent = user.fullName || 'User';
                    }
                    
                    const dashboardLink = document.getElementById('dashboard-link');
                    if (dashboardLink) {
                        const dashboardUrl = getDashboardUrl(user.userType);
                        dashboardLink.href = dashboardUrl;
                    }
                }
                
                // Update mobile navigation
                updateMobileNavigation();
                
            } catch (e) {
                console.error('Error parsing user data:', e);
            }
        } else {
            // User not logged in
            if (authButtons && userMenu) {
                document.querySelector('.login-btn').style.display = 'block';
                document.querySelector('.signup-btn').style.display = 'block';
                userMenu.style.display = 'none';
            }
            updateMobileNavigation();
        }
        
        // Setup mobile logout
        setupMobileLogout();
    }
    
    function getDashboardUrl(userType) {
        switch (userType) {
            case 'admin':
            case 'agent':
                return 'dashboard/admin_dashboard.php';
            case 'seller':
            case 'owner':
                return 'dashboard/seller_dashboard.php';
            case 'user':
            case 'buyer':
            default:
                return 'dashboard/user_dashboard.php';
        }
    }
    
    // Update mobile navigation based on auth status
    function updateMobileNavigation() {
        const token = localStorage.getItem('authToken');
        const userData = localStorage.getItem('userData');
        const mobileAuthButtons = document.getElementById('mobile-auth-buttons');
        const mobileUserMenu = document.getElementById('mobile-user-menu');
        
        if (token && userData && mobileAuthButtons && mobileUserMenu) {
            try {
                const user = JSON.parse(userData);
                // Hide login/signup buttons, show user menu
                mobileAuthButtons.style.display = 'none';
                mobileUserMenu.style.display = 'block';
                
                // Update dashboard link based on user type
                const dashboardLink = document.querySelector('.mobile-dashboard-link');
                if (dashboardLink) {
                    const dashboardUrl = getDashboardUrl(user.userType);
                    dashboardLink.href = dashboardUrl;
                }
            } catch (e) {
                console.error('Error updating mobile navigation:', e);
            }
        } else if (mobileAuthButtons && mobileUserMenu) {
            // Show auth buttons, hide user menu
            mobileAuthButtons.style.display = 'block';
            mobileUserMenu.style.display = 'none';
        }
    }

    // Mobile logout functionality
    function setupMobileLogout() {
        const mobileLogoutBtn = document.querySelector('.mobile-logout-btn');
        if (mobileLogoutBtn) {
            mobileLogoutBtn.addEventListener('click', function(e) {
                e.preventDefault();
                // Clear local storage
                localStorage.removeItem('authToken');
                localStorage.removeItem('userData');
                // Close mobile nav
                document.getElementById('mobile-nav').classList.remove('active');
                // Redirect to logout page
                window.location.href = 'logout.php';
            });
        }
    }
    
    // Logout functionality
    const logoutBtn = document.getElementById('logout-btn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            localStorage.removeItem('authToken');
            localStorage.removeItem('userData');
            // Redirect to logout.php to clear session
            window.location.href = 'logout.php';
        });
    }
    
    // Check auth status on page load
    checkAuthStatus();
    
    // Contact form submission
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Thank you for your message! We will get back to you soon.');
            this.reset();
        });
    }
    
    // Property search form
    const searchForm = document.querySelector('.search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Search functionality would filter properties in a real implementation');
        });
    }
});