<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ML RealEstate</title>
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 0;
            border-radius: 8px;
            width: 90%;
            max-width: 400px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #eee;
        }

        .modal-header h2 {
            margin: 0;
            color: #cda434;
            font-size: 1.5rem;
        }

        .close-modal {
            font-size: 24px;
            cursor: pointer;
            color: #999;
        }

        .close-modal:hover {
            color: #333;
        }

        .modal-body {
            padding: 20px;
        }

        .auth-form {
            display: none;
        }

        .auth-form.active {
            display: block;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .form-input:focus {
            outline: none;
            border-color: #cda434;
        }

        .password-field {
            position: relative;
        }

        .btn {
            background: #cda434;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s;
            position: relative;
        }

        .btn:hover {
            background: #a88428;
        }

        .btn:disabled {
            background: #cccccc;
            cursor: not-allowed;
        }

        .form-switch {
            text-align: center;
            margin-top: 15px;
        }

        .form-switch a {
            color: #cda434;
            text-decoration: none;
        }

        .form-switch a:hover {
            text-decoration: underline;
        }

        .message {
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            text-align: center;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .spinner {
            border: 2px solid #f3f3f3;
            border-top: 2px solid #cda434;
            border-radius: 50%;
            width: 16px;
            height: 16px;
            animation: spin 1s linear infinite;
            display: inline-block;
            margin-left: 10px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Navbar Auth Styles */
        #auth-buttons {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .login-btn, .signup-btn {
            padding: 8px 20px !important;
            border-radius: 4px;
            transition: all 0.3s;
            text-decoration: none;
        }

        .login-btn {
            color: #cda434 !important;
            border: 1px solid #cda434;
        }

        .login-btn:hover {
            background: #cda434;
            color: white !important;
        }

        .signup-btn {
            background: #cda434;
            color: white !important;
        }

        .signup-btn:hover {
            background: #a88428;
            color: white !important;
        }

        /* User Menu Styles */
        #user-menu {
            display: none;
            position: relative;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 15px;
            background: #f8f9fa;
            border-radius: 20px;
            text-decoration: none;
            color: #333 !important;
        }

        .user-dropdown {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            min-width: 200px;
            z-index: 1000;
        }

        .user-dropdown a {
            display: block;
            padding: 10px 15px;
            color: #333;
            text-decoration: none;
            border-bottom: 1px solid #eee;
        }

        .user-dropdown a:hover {
            background: #f8f9fa;
        }

        .user-dropdown a:last-child {
            border-bottom: none;
        }

        #user-menu:hover .user-dropdown {
            display: block;
        }
    </style>
</head>
<body>
    <!-- Header & Navigation -->
    <header id="header">
        <div class="container header-container">
            <a href="#" class="logo">
                <i class="bi bi-house"></i>
                ML RealEstate 
            </a>
            
            <nav class="desktop-nav">
                <ul class="nav-links">
                    <li><a href="#home">Home</a></li>
                    <li><a href="#search">Search</a></li>
                    <li><a href="#featured">Properties</a></li>
                    <li><a href="#gallery">Gallery</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#contact">Contact</a></li>
                    <li id="auth-buttons">
                        <!-- These will be shown when user is not logged in -->
                        <a href="#" class="login-btn" id="nav-login-btn">Login</a>
                        <a href="#" class="signup-btn" id="nav-signup-btn">Sign Up</a>
                        
                        <!-- These will be shown when user is logged in -->
                        <div id="user-menu" style="display: none;">
                            <a href="#" class="user-profile" id="user-profile-link">
                                <i class="bi bi-person-circle"></i>
                                <span id="user-name">User</span>
                            </a>
                            <div class="user-dropdown">
                                <a href="admin_dashboard.php" id="dashboard-link">Dashboard</a>
                                <a href="profile.php">My Profile</a>
                                <a href="my-properties.php">My Properties</a>
                                <a href="favorites.php">Favorites</a>
                                <a href="#" id="logout-btn">Logout</a>
                            </div>
                        </div>
                    </li>
                </ul>
            </nav>

            <div class="mobile-toggle" id="mobile-toggle">
                <i class="bi bi-list"></i>
            </div>
        </div>
    </header>
    
      <!-- Mobile Navigation -->
<nav class="mobile-nav" id="mobile-nav">
    <div class="close-mobile-nav" id="close-mobile-nav">
        <i class="bi bi-x"></i>
    </div>
    <a href="#home">Home</a>
    <a href="#search">Search</a>
    <a href="#featured">Properties</a>
    <a href="#gallery">Gallery</a>
    <a href="#about">About</a>
    <a href="#contact">Contact</a>
    
    <!-- Mobile Auth Links - UPDATED -->
    <div id="mobile-auth-buttons">
        <a href="#" class="mobile-login-btn">Login</a>
        <a href="#" class="mobile-signup-btn">Sign Up</a>
    </div>
    
    <!-- Mobile User Menu - ADD THIS -->
    <div id="mobile-user-menu" style="display: none;">
        <a href="user_dashboard.php" class="mobile-dashboard-link">Dashboard</a>
        <a href="profile.php" class="mobile-profile-link">My Profile</a>
        <a href="favorites.php" class="mobile-favorites-link">Favorites</a>
        <a href="#" class="mobile-logout-btn" style="color: #e74c3c;">Logout</a>
    </div>
</nav>
    </section>

    <!-- Search Section -->
    <section class="search-section" id="search">
        <div class="container">
            <h2 class="search-title">Find Your Dream Property</h2>
            <div class="search-container">
                <form class="search-form">
                    <div class="form-group">
                        <label for="location">Location</label>
                        <input type="text" id="location" placeholder="Enter city or area">
                    </div>
                    
                    <div class="form-group">
                        <label for="property-type">Property Type</label>
                        <select id="property-type">
                            <option value="">Any Type</option>
                            <option value="residential">Residential</option>
                            <option value="commercial">Commercial</option>
                            <option value="land">Land</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="transaction-type">Transaction Type</label>
                        <select id="transaction-type">
                            <option value="">For Sale or Rent</option>
                            <option value="sale">For Sale</option>
                            <option value="rent">For Rent</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="max-price">Max Price (K)</label>
                        <input type="number" id="max-price" placeholder="No limit">
                    </div>
                    
                    <button type="submit" class="btn search-btn">Search Properties</button>
                </form>
            </div>
        </div>
    </section>

    <!-- Featured Properties -->
    <section class="featured-properties" id="featured">
        <div class="container">
            <h2 class="section-title">Featured Properties</h2>
            
            <div class="properties-grid">
                <!-- Property 1 -->
                <div class="property-card">
                    <div class="property-img">
                        <img src="https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1176&q=80" alt="Luxury Villa">
                        <div class="property-badge">Featured</div>
                    </div>
                    <div class="property-content">
                        <div class="property-price">K350,000</div>
                        <h3 class="property-title">Luxury Villa in Lusaka</h3>
                        <div class="property-location">
                            <i class="bi bi-geo-alt"></i>
                            Lusaka, Zambia
                        </div>
                        <div class="property-features">
                            <div class="property-feature">
                                <i class="bi bi-bed"></i>
                                <span>4 Bedrooms</span>
                            </div>
                            <div class="property-feature">
                                <i class="bi bi-droplet"></i>
                                <span>3 Bathrooms</span>
                            </div>
                            <div class="property-feature">
                                <i class="bi bi-rulers"></i>
                                <span>280 m²</span>
                            </div>
                        </div>
                        <a href="https://wa.me/260955123456?text=I'm interested in the Luxury Villa in Lusaka" class="whatsapp-btn">
                            <i class="bi bi-whatsapp"></i> Contact via WhatsApp
                        </a>
                    </div>
                </div>
                
                <!-- Property 2 -->
                <div class="property-card">
                    <div class="property-img">
                        <img src="https://images.unsplash.com/photo-1574362848149-11496d93a7c7?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=784&q=80" alt="Modern Apartment">
                        <div class="property-badge">New</div>
                    </div>
                    <div class="property-content">
                        <div class="property-price">K180,000</div>
                        <h3 class="property-title">Modern Apartment in Livingstone</h3>
                        <div class="property-location">
                            <i class="bi bi-geo-alt"></i>
                            Livingstone, Zambia
                        </div>
                        <div class="property-features">
                            <div class="property-feature">
                                <i class="bi bi-bed"></i>
                                <span>3 Bedrooms</span>
                            </div>
                            <div class="property-feature">
                                <i class="bi bi-droplet"></i>
                                <span>2 Bathrooms</span>
                            </div>
                            <div class="property-feature">
                                <i class="bi bi-rulers"></i>
                                <span>120 m²</span>
                            </div>
                        </div>
                        <a href="https://wa.me/260955123456?text=I'm interested in the Modern Apartment in Livingstone" class="whatsapp-btn">
                            <i class="bi bi-whatsapp"></i> Contact via WhatsApp
                        </a>
                    </div>
                </div>
                
                <!-- Property 3 -->
                <div class="property-card">
                    <div class="property-img">
                        <img src="https://images.unsplash.com/photo-1564013799919-ab600027ffc6?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80" alt="Family House">
                    </div>
                    <div class="property-content">
                        <div class="property-price">K275,000</div>
                        <h3 class="property-title">Family House in Ndola</h3>
                        <div class="property-location">
                            <i class="bi bi-geo-alt"></i>
                            Ndola, Zambia
                        </div>
                        <div class="property-features">
                            <div class="property-feature">
                                <i class="bi bi-bed"></i>
                                <span>5 Bedrooms</span>
                            </div>
                            <div class="property-feature">
                                <i class="bi bi-droplet"></i>
                                <span>3 Bathrooms</span>
                            </div>
                            <div class="property-feature">
                                <i class="bi bi-rulers"></i>
                                <span>320 m²</span>
                            </div>
                        </div>
                        <a href="https://wa.me/260979569888?text=I'm interested in the Family House in Ndola" class="whatsapp-btn">
                            <i class="bi bi-whatsapp"></i> Contact via WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section" id="about">
        <div class="container">
            <h2 class="section-title">About ML RealEstate</h2>
            
            <div class="about-content">
                <div class="about-text">
                    <h2>We're Changing the Real Estate Experience</h2>
                    <p>ML (Muwana Legacy) is a leading real estate agency in Zambia, specializing in connecting property buyers and sellers with trusted, verified listings. With over 10 years of experience, we've helped thousands of clients find their dream properties.</p>
                    <p>Our mission is to make property transactions seamless, transparent, and rewarding for all parties involved. We leverage technology and local expertise to deliver exceptional service.</p>
                    <a href="#contact" class="btn">Get In Touch</a>
                </div>
                
                <div class="features-grid">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="bi bi-house"></i>
                        </div>
                        <h3 class="feature-title">Property Listings</h3>
                        <p>Wide range of verified properties across Zambia</p>
                    </div>
                    
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="bi bi-search"></i>
                        </div>
                        <h3 class="feature-title">Easy Search</h3>
                        <p>Find properties that match your criteria quickly</p>
                    </div>
                    
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h3 class="feature-title">Secure Transactions</h3>
                        <p>Safe and transparent property transactions</p>
                    </div>
                    
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="bi bi-headset"></i>
                        </div>
                        <h3 class="feature-title">24/7 Support</h3>
                        <p>Our team is always ready to assist you</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="gallery-section" id="gallery">
        <div class="container">
            <h2 class="section-title">Property Gallery</h2>
            
            <div class="gallery-grid">
                <div class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1153&q=80" alt="Modern Kitchen">
                    <div class="gallery-overlay">
                        <h3>Modern Kitchen</h3>
                        <p>Lusaka Property</p>
                    </div>
                </div>
                
                <div class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1600585154526-990dced4db0d?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80" alt="Luxury Villa">
                    <div class="gallery-overlay">
                        <h3>Luxury Villa</h3>
                        <p>Livingstone Property</p>
                    </div>
                </div>
                
                <div class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80" alt="House Exterior">
                    <div class="gallery-overlay">
                        <h3>Modern Exterior</h3>
                        <p>Kitwe Property</p>
                    </div>
                </div>
                
                <div class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80" alt="Bedroom">
                    <div class="gallery-overlay">
                        <h3>Master Bedroom</h3>
                        <p>Lusaka Property</p>
                    </div>
                </div>
                
                <div class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1600585154084-4e5fe7c39198?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80" alt="Swimming Pool">
                    <div class="gallery-overlay">
                        <h3>Swimming Pool</h3>
                        <p>Lusaka Property</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section" id="contact">
        <div class="container">
            <h2 class="section-title">Contact Us</h2>
            
            <div class="contact-container">
                <div class="contact-info">
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="bi bi-geo-alt"></i>
                        </div>
                        <div class="contact-text">
                            <h3>Our Location</h3>
                            <p>Barlastone, Lusaka, Zambia</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="bi bi-telephone"></i>
                        </div>
                        <div class="contact-text">
                            <h3>Call Us</h3>
                            <p>+260 979569888</p>
                            <p>+260 968069681</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <div class="contact-text">
                            <h3>Email Us</h3>
                            <p>info@mlrealestate.com</p>
                            <p>support@mlrealestate.com</p>
                        </div>
                    </div>
                    
                    <div class="social-links">
                        <a href="#" class="social-link">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="#" class="social-link">
                            <i class="bi bi-twitter"></i>
                        </a>
                        <a href="#" class="social-link">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="#" class="social-link">
                            <i class="bi bi-linkedin"></i>
                        </a>
                    </div>
                </div>
                
                <div class="contact-form">
                    <h3>Send us a Message</h3>
                    <form id="contactForm">
                        <input type="text" class="form-input" placeholder="Your Name" required>
                        <input type="email" class="form-input" placeholder="Your Email" required>
                        <input type="text" class="form-input" placeholder="Subject">
                        <textarea class="form-input" placeholder="Your Message" required></textarea>
                        <button type="submit" class="btn">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- WhatsApp Floating Button -->
    <a href="https://wa.me/260955123456?text=Hello, I'm interested in your real estate services" class="whatsapp-float" target="_blank">
        <i class="bi bi-whatsapp"></i>
    </a>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    <i class="bi bi-house"></i> ML RealEstate
                </div>
                
                <div class="footer-links">
                    <a href="#home">Home</a>
                    <a href="#search">Search</a>
                    <a href="#featured">Properties</a>
                    <a href="#gallery">Gallery</a>
                    <a href="#about">About</a>
                    <a href="#contact">Contact</a>
                </div>
                
                <div class="copyright">
                    <p>&copy; 2025 ML RealEstate. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Authentication Modal -->
    <div class="modal" id="auth-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modal-title">Login to Your Account</h2>
                <span class="close-modal" id="close-modal">&times;</span>
            </div>
            
            <div class="modal-body">
                <!-- Login Form -->
                <form id="login-form" class="auth-form active">
                    <div class="form-group">
                        <input type="email" id="login-email" class="form-input" placeholder="Email Address" required>
                    </div>
                    
                    <div class="form-group">
                        <div class="password-field">
                            <input type="password" id="login-password" class="form-input" placeholder="Password" required>
                        </div>
                    </div>

                    <div id="login-message" class="message"></div>
                    
                    <button type="submit" class="btn" style="width: 100%;">
                        <span id="login-btn-text">Login</span>
                        <div id="login-spinner" class="spinner" style="display: none;"></div>
                    </button>
                    
                    <div class="form-switch">
                        Don't have an account? <a href="#" id="switch-to-signup">Sign Up</a>
                    </div>
                </form>
                
                <!-- Signup Form -->
                <form id="signup-form" class="auth-form">
                    <div class="form-group">
                        <input type="text" id="signup-fullname" class="form-input" placeholder="Full Name" required>
                    </div>
                    
                    <div class="form-group">
                        <input type="email" id="signup-email" class="form-input" placeholder="Email Address" required>
                    </div>
                    
                    <div class="form-group">
                        <select id="signup-usertype" class="form-input" required>
                            <option value="">Select User Type</option>
                            <option value="user">Buyer</option>
                            <option value="seller">Property Owner</option>
                            <option value="agent">Admin</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <div class="password-field">
                            <input type="password" id="signup-password" class="form-input" placeholder="Password" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="password-field">
                            <input type="password" id="signup-confirm-password" class="form-input" placeholder="Confirm Password" required>
                        </div>
                    </div>

                    <div id="signup-message" class="message"></div>
                    
                    <button type="submit" class="btn" style="width: 100%;">
                        <span id="signup-btn-text">Sign Up</span>
                        <div id="signup-spinner" class="spinner" style="display: none;"></div>
                    </button>
                    
                    <div class="form-switch">
                        Already have an account? <a href="#" id="switch-to-login">Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Authentication Modal Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const authModal = document.getElementById('auth-modal');
            const closeModal = document.getElementById('close-modal');
            const navLoginBtn = document.getElementById('nav-login-btn');
            const navSignupBtn = document.getElementById('nav-signup-btn');
            const switchToSignup = document.getElementById('switch-to-signup');
            const switchToLogin = document.getElementById('switch-to-login');
            const loginForm = document.getElementById('login-form');
            const signupForm = document.getElementById('signup-form');
            const modalTitle = document.getElementById('modal-title');
            const userMenu = document.getElementById('user-menu');
            const authButtons = document.getElementById('auth-buttons');
            const logoutBtn = document.getElementById('logout-btn');
            const dashboardLink = document.getElementById('dashboard-link');

            // Check if user is logged in
            function checkAuthStatus() {
                const token = localStorage.getItem('authToken');
                const userData = localStorage.getItem('userData');
                
                if (token && userData) {
                    // User is logged in
                    document.querySelector('.login-btn').style.display = 'none';
                    document.querySelector('.signup-btn').style.display = 'none';
                    userMenu.style.display = 'block';
                    
                    try {
                        const user = JSON.parse(userData);
                        document.getElementById('user-name').textContent = user.fullName || 'User';
                        
                        // Set dashboard link based on user type
                        const dashboardUrl = getDashboardUrl(user.userType);
                        dashboardLink.href = dashboardUrl;
                    } catch (e) {
                        console.error('Error parsing user data:', e);
                    }
                } else {
                    // User is not logged in
                    document.querySelector('.login-btn').style.display = 'block';
                    document.querySelector('.signup-btn').style.display = 'block';
                    userMenu.style.display = 'none';
                }
            }

            // Get dashboard URL based on user type
            function getDashboardUrl(userType) {
                switch (userType) {
                    case 'admin':
                    case 'agent':
                        return 'admin_dashboard.php';
                    case 'seller':
                    case 'owner':
                        return 'seller_dashboard.php';
                    case 'user':
                    case 'buyer':
                    default:
                        return 'user_dashboard.php';
                }
            }

            // Show modal
            function showModal(formType = 'login') {
                authModal.style.display = 'block';
                if (formType === 'login') {
                    showLoginForm();
                } else {
                    showSignupForm();
                }
            }

            // Hide modal
            function hideModal() {
                authModal.style.display = 'none';
                clearForms();
            }

            // Show login form
            function showLoginForm() {
                loginForm.classList.add('active');
                signupForm.classList.remove('active');
                modalTitle.textContent = 'Login to Your Account';
            }

            // Show signup form
            function showSignupForm() {
                signupForm.classList.remove('active');
                loginForm.classList.remove('active');
                signupForm.classList.add('active');
                modalTitle.textContent = 'Create Your Account';
            }

            // Clear form fields and messages
            function clearForms() {
                document.getElementById('login-email').value = '';
                document.getElementById('login-password').value = '';
                document.getElementById('signup-fullname').value = '';
                document.getElementById('signup-email').value = '';
                document.getElementById('signup-password').value = '';
                document.getElementById('signup-confirm-password').value = '';
                document.getElementById('signup-usertype').value = '';
                
                const messages = document.querySelectorAll('.message');
                messages.forEach(msg => {
                    msg.textContent = '';
                    msg.className = 'message';
                });
            }

            // Show loading state
            function showLoading(button, spinner, buttonText) {
                button.disabled = true;
                spinner.style.display = 'inline-block';
                buttonText.style.display = 'none';
            }

            // Hide loading state
            function hideLoading(button, spinner, buttonText) {
                button.disabled = false;
                spinner.style.display = 'none';
                buttonText.style.display = 'inline';
            }

            // Show message
            function showMessage(element, message, type) {
                element.textContent = message;
                element.className = `message ${type}`;
            }

            // Handle login form submission
            loginForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const email = document.getElementById('login-email').value;
                const password = document.getElementById('login-password').value;
                const loginBtn = this.querySelector('button[type="submit"]');
                const loginSpinner = document.getElementById('login-spinner');
                const loginBtnText = document.getElementById('login-btn-text');
                const loginMessage = document.getElementById('login-message');

                if (!email || !password) {
                    showMessage(loginMessage, 'Please fill in all fields', 'error');
                    return;
                }

                showLoading(loginBtn, loginSpinner, loginBtnText);

                try {
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

                    if (data.success) {
                        // Store token and user data
                        localStorage.setItem('authToken', data.token);
                        localStorage.setItem('userData', JSON.stringify(data.user));
                        
                        showMessage(loginMessage, 'Login successful! Redirecting...', 'success');
                        setTimeout(() => {
                            hideModal();
                            checkAuthStatus();
                            // Redirect to dashboard
                            window.location.href = data.dashboardUrl || getDashboardUrl(data.user.userType);
                        }, 1500);
                    } else {
                        showMessage(loginMessage, data.message || 'Login failed', 'error');
                    }
                } catch (error) {
                    console.error('Login error:', error);
                    showMessage(loginMessage, 'Network error. Please try again.', 'error');
                } finally {
                    hideLoading(loginBtn, loginSpinner, loginBtnText);
                }
            });

            // Handle signup form submission
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

                // Validation
                if (!fullName || !email || !password || !confirmPassword || !userType) {
                    showMessage(signupMessage, 'Please fill in all fields', 'error');
                    return;
                }

                if (password !== confirmPassword) {
                    showMessage(signupMessage, 'Passwords do not match', 'error');
                    return;
                }

                if (password.length < 6) {
                    showMessage(signupMessage, 'Password must be at least 6 characters', 'error');
                    return;
                }

                showLoading(signupBtn, signupSpinner, signupBtnText);

                try {
                    const response = await fetch('signup.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            fullName: fullName,
                            email: email,
                            password: password,
                            userType: userType
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Store token and user data
                        localStorage.setItem('authToken', data.token);
                        localStorage.setItem('userData', JSON.stringify(data.user));
                        
                        showMessage(signupMessage, 'Registration successful! Redirecting...', 'success');
                        setTimeout(() => {
                            hideModal();
                            checkAuthStatus();
                            // Redirect to dashboard
                            window.location.href = getDashboardUrl(data.user.userType);
                        }, 1500);
                    } else {
                        showMessage(signupMessage, data.message || 'Registration failed', 'error');
                    }
                } catch (error) {
                    console.error('Signup error:', error);
                    showMessage(signupMessage, 'Network error. Please try again.', 'error');
                } finally {
                    hideLoading(signupBtn, signupSpinner, signupBtnText);
                }
            });

            // Handle logout
            logoutBtn.addEventListener('click', function(e) {
                e.preventDefault();
                localStorage.removeItem('authToken');
                localStorage.removeItem('userData');
                // Also clear session by calling logout.php
                fetch('logout.php')
                    .then(() => {
                        checkAuthStatus();
                        window.location.reload();
                    })
                    .catch(error => {
                        console.error('Logout error:', error);
                        checkAuthStatus();
                        window.location.reload();
                    });
            });

            // Event listeners
            navLoginBtn.addEventListener('click', function(e) {
                e.preventDefault();
                showModal('login');
            });

            navSignupBtn.addEventListener('click', function(e) {
                e.preventDefault();
                showModal('signup');
            });

            closeModal.addEventListener('click', hideModal);
            switchToSignup.addEventListener('click', function(e) {
                e.preventDefault();
                showSignupForm();
            });
            switchToLogin.addEventListener('click', function(e) {
                e.preventDefault();
                showLoginForm();
            });

            // Close modal when clicking outside
            window.addEventListener('click', function(e) {
                if (e.target === authModal) {
                    hideModal();
                }
            });

            // Mobile nav auth buttons
            document.querySelector('.mobile-login-btn')?.addEventListener('click', function(e) {
                e.preventDefault();
                showModal('login');
                // Close mobile nav if open
                document.getElementById('mobile-nav').classList.remove('active');
            });

            document.querySelector('.mobile-signup-btn')?.addEventListener('click', function(e) {
                e.preventDefault();
                showModal('signup');
                // Close mobile nav if open
                document.getElementById('mobile-nav').classList.remove('active');
            });

            // Check auth status on page load
            checkAuthStatus();
        });
    </script>
    <script src="script.js"></script>      
</body>
</html>