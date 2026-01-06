<?php
include_once "config/settings-configuration.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AVO - Premium Pet Care Platform</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link href="src/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg avo-navbar">
        <div class="container">
            <a class="navbar-brand nav-brand" href="#">
                <i class="fas fa-paw me-2"></i><span>AVO</span>Care
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Book Consultation</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About</a>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link login-btn" id="openLoginModal">
                            <i class="fas fa-user me-2"></i>Login
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="date-display mb-3">
                        <span class="badge bg-light text-dark">
                            <i class="fas fa-calendar me-2"></i>
                            <span id="currentDate"></span>
                        </span>
                    </div>
                    <h1 class="hero-title">
                        Premium Care for<br>
                        Your <span class="hero-highlight">Beloved Pets</span>
                    </h1>
                    <p class="hero-subtitle">
                        Shop premium pet food & supplies while accessing expert veterinary 
                        teleconsultation services—all in one trusted platform.
                    </p>
                    <div class="hero-buttons">
                        <button class="btn btn-primary-hero btn-hero">
                            <i class="fas fa-shopping-cart me-2"></i>Shop Products
                        </button>
                        <button class="btn btn-outline-hero btn-hero">
                            <i class="fas fa-calendar-check me-2"></i>Book Vet Consultation
                        </button>
                    </div>
                </div>
                <div class="col-lg-6">
                    <!-- Placeholder for pet image -->
                    <div class="text-center">
                        <div style="width: 100%; height: 300px; background: linear-gradient(135deg, var(--avo-primary), var(--avo-secondary)); border-radius: 20px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-paw" style="font-size: 10rem; color: rgba(255, 255, 255, 0.2);"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-4 stat-item">
                    <span class="stat-number">10K+</span>
                    <span class="stat-label">Happy Pets</span>
                </div>
                <div class="col-md-4 stat-item">
                    <span class="stat-number">500+</span>
                    <span class="stat-label">Expert Vets</span>
                </div>
                <div class="col-md-4 stat-item">
                    <span class="stat-number">4.9/5</span>
                    <span class="stat-label">Average Rating</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Login Modal (Hidden by default) -->
    <div class="login-modal" id="loginModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">
                    <i class="fas fa-user-circle me-2"></i>Welcome to AVOCare
                </h3>
                <button class="close-btn" id="closeLoginModal">&times;</button>
            </div>
            <div class="modal-body">
                <!-- Tab Navigation -->
                <div class="form-tabs">
                    <button class="tab-btn active" data-tab="login">Sign In</button>
                    <button class="tab-btn" data-tab="register">Register</button>
                </div>
                
                <!-- Login Form (YOUR CODE - Modified with tab system) -->
                <div class="tab-content" id="loginTab">
                    <div class="form-container">
                        <form action="dashboard/admin/authentication/admin-class.php" method="POST">
                            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                            </div>
                            <button type="submit" name="btn-signin" class="btn btn-primary w-100">
                                <i class="fas fa-sign-in-alt me-2"></i>SIGN IN
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Registration Form (YOUR CODE - Modified with tab system) -->
                <div class="tab-content" id="registerTab" style="display: none;">
                    <div class="form-container">
                        <form action="dashboard/admin/authentication/admin-class.php" method="POST">
                            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" class="form-control" placeholder="Choose a username" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Create a password" required>
                            </div>
                            <button type="submit" name="btn-signup" class="btn btn-primary w-100">
                                <i class="fas fa-user-plus me-2"></i>SIGN UP
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Date Display
        function updateDate() {
            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            document.getElementById('currentDate').textContent = now.toLocaleDateString('en-US', options);
        }
        updateDate();
        
        // Modal Functions
        const loginModal = document.getElementById('loginModal');
        const openLoginBtn = document.getElementById('openLoginModal');
        const closeLoginBtn = document.getElementById('closeLoginModal');
        const tabBtns = document.querySelectorAll('.tab-btn');
        
        openLoginBtn.addEventListener('click', () => {
            loginModal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        });
        
        closeLoginBtn.addEventListener('click', () => {
            loginModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        });
        
        // Close modal when clicking outside
        loginModal.addEventListener('click', (e) => {
            if (e.target === loginModal) {
                loginModal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        });
        
        // Tab switching
        tabBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // Remove active class from all tabs
                tabBtns.forEach(b => b.classList.remove('active'));
                
                // Add active class to clicked tab
                btn.classList.add('active');
                
                // Show/hide tab content
                const tabId = btn.getAttribute('data-tab');
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.style.display = 'none';
                });
                document.getElementById(tabId + 'Tab').style.display = 'block';
            });
        });
        
        // Esc key to close modal
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && loginModal.style.display === 'block') {
                loginModal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        });
    </script>
</body>
</html>