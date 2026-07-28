<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Attendance Tracking Portal</title>
    <meta name="description" content="A modern authentication system with secure login, session management and role based access.">
    <!-- Google Fonts: Inter & Outfit -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Outfit:wght@500;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/auth.css">
    <script>
        // Check if already logged in via sessionStorage
        document.addEventListener('DOMContentLoaded', () => {
            const userSession = sessionStorage.getItem('user_session');
            if (userSession) {
                try {
                    const sessionData = JSON.parse(userSession);
                    if (sessionData && sessionData.role) {
                        window.location.href = `${sessionData.role}/dashboard.php`;
                    }
                } catch (e) {}
            }
            
            // Add loaded class for page transitions
            setTimeout(() => {
                document.body.classList.add('loaded');
            }, 100);
        });
    </script>
</head>
<body class="landing-page page-transition">

    <!-- Background Elements -->
    <div class="ambient-light main-glow"></div>
    <div class="particles-layer"></div>

    <!-- Navigation -->
    <nav class="premium-nav navbar navbar-expand-lg fixed-top" style="background: rgba(5, 8, 22, 0.75);">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="#">
                <div class="logo-shield"><i class="bi bi-mortarboard-fill"></i></div>
                <div class="brand-text">
                    <span class="d-block fw-bold text-white lh-1">Student Attendance</span>
                    <span class="d-block text-secondary small lh-1 mt-1">Tracking Portal</span>
                </div>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="bi bi-list text-white fs-2"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto gap-4">
                    <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about">About Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                </ul>
                <div class="nav-actions">
                    <a href="role_selection.html" class="btn btn-primary-lux px-4">Get Started</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section mt-5 pt-5 pb-5">
        <div class="container">
            <div class="row align-items-center min-vh-75">
                
                <!-- Left Side: Text content -->
                <div class="col-lg-6 hero-text-content animate-slide-up">
                    <div class="badge-premium mb-4">Smart. Secure. Seamless.</div>
                    <h1 class="hero-title mb-4">
                        Student Attendance<br>
                        <span class="text-gradient-blue">Tracking Portal</span>
                    </h1>
                    <p class="hero-subtitle mb-5 pe-lg-5">
                        A modern authentication system with secure login, session management and role based access for Super Admin, Faculty and Students.
                    </p>
                    <div class="d-flex flex-wrap gap-4 align-items-center">
                        <a href="role_selection.html" class="btn btn-primary-lux with-arrow">Explore Portal <i class="bi bi-arrow-right"></i></a>
                        <a href="#features" class="btn btn-outline-lux with-icon"><i class="bi bi-play-circle me-2"></i>Learn More</a>
                    </div>
                </div>

                <!-- Right Side: Floating Illustration -->
                <div class="col-lg-6 position-relative hero-illustration d-none d-lg-block">
                    <!-- Base glowing platform -->
                    <div class="platform-glow"></div>
                    
                    <!-- Abstract Laptop / Dashboard UI -->
                    <div class="floating-dashboard">
                        <div class="dash-header">
                            <div class="dot red"></div>
                            <div class="dot yellow"></div>
                            <div class="dot green"></div>
                        </div>
                        <div class="dash-body row g-2">
                            <div class="col-4"><div class="dash-card"></div></div>
                            <div class="col-4"><div class="dash-card"></div></div>
                            <div class="col-4"><div class="dash-card"></div></div>
                            <div class="col-12 mt-3"><div class="dash-chart"></div></div>
                        </div>
                    </div>

                    <!-- 3D Security Shield Floating -->
                    <div class="floating-shield-3d">
                        <i class="bi bi-shield-check"></i>
                    </div>

                    <!-- Smaller floating elements -->
                    <div class="floating-element el-1"><i class="bi bi-graph-up-arrow"></i></div>
                    <div class="floating-element el-2"><i class="bi bi-lock-fill"></i></div>
                    
                    <!-- Security rings -->
                    <div class="security-ring ring-1"></div>
                    <div class="security-ring ring-2"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features-section pb-5 pt-3">
        <div class="container pb-5">
            <div class="row g-4 justify-content-center">
                
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card animate-on-scroll">
                        <div class="f-icon-box bg-blue-subtle">
                            <i class="bi bi-shield-lock-fill text-blue"></i>
                        </div>
                        <div class="f-content">
                            <h4>Secure Authentication</h4>
                            <p>Advanced login security and data protection for all user accounts.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="feature-card animate-on-scroll delay-1">
                        <div class="f-icon-box bg-purple-subtle">
                            <i class="bi bi-people-fill text-purple"></i>
                        </div>
                        <div class="f-content">
                            <h4>Role Based Access</h4>
                            <p>Different dashboards and permissions tailored for every specific role.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="feature-card animate-on-scroll delay-2">
                        <div class="f-icon-box bg-emerald-subtle">
                            <i class="bi bi-clock-history text-emerald"></i>
                        </div>
                        <div class="f-content">
                            <h4>Session Management</h4>
                            <p>Smart session control and timeout protection to prevent unauthorized access.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- About Us Section -->
    <section id="about" class="about-section py-5 position-relative">
        <div class="container py-5">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 position-relative">
                    <div class="about-image-wrapper">
                        <!-- Creative composition of floating elements -->
                        <div class="glass-card p-4 animate-slide-up" style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 20px;">
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <div class="icon-box" style="width: 50px; height: 50px; background: var(--royal-blue); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                                    <i class="bi bi-rocket-takeoff text-white"></i>
                                </div>
                                <h4 class="mb-0 text-white">Our Mission</h4>
                            </div>
                            <p class="text-secondary mb-0">To revolutionize educational administration by providing a seamless, secure, and intelligent platform that empowers institutions to focus on what truly matters—education.</p>
                        </div>
                        
                        <div class="glass-card p-4 mt-4 animate-slide-up delay-1" style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 20px; margin-left: 2rem;">
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <div class="icon-box" style="width: 50px; height: 50px; background: var(--purple); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                                    <i class="bi bi-eye text-white"></i>
                                </div>
                                <h4 class="mb-0 text-white">Our Vision</h4>
                            </div>
                            <p class="text-secondary mb-0">Building the future of digital campuses where every interaction is intuitive and data-driven insights lead to better academic outcomes.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="about-content ps-lg-4 animate-slide-up delay-2">
                        <div class="badge-premium mb-3">About Us</div>
                        <h2 class="display-5 fw-bold mb-4 text-white">Empowering Education<br><span class="text-gradient-purple">Through Technology</span></h2>
                        <p class="text-secondary mb-4 fs-5">We are a dedicated team of developers, educators, and visionaries committed to bridging the gap between traditional teaching and modern technology.</p>
                        
                        <div class="d-flex gap-4 mb-4">
                            <div class="stats-item">
                                <h3 class="text-white fw-bold mb-1">50+</h3>
                                <span class="text-secondary small">Campuses</span>
                            </div>
                            <div class="stats-item border-start border-secondary ps-4">
                                <h3 class="text-white fw-bold mb-1">100k</h3>
                                <span class="text-secondary small">Students</span>
                            </div>
                            <div class="stats-item border-start border-secondary ps-4">
                                <h3 class="text-white fw-bold mb-1">99.9%</h3>
                                <span class="text-secondary small">Uptime</span>
                            </div>
                        </div>
                        
                        <a href="role_selection.html" class="btn btn-outline-lux mt-2">Join the Revolution <i class="bi bi-arrow-right ms-2"></i></a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Background decorative elements -->
        <div class="position-absolute top-0 end-0 rounded-circle" style="width: 400px; height: 400px; background: radial-gradient(circle, rgba(139, 92, 246, 0.1) 0%, rgba(9, 9, 11, 0) 70%); z-index: -1;"></div>
    </section>

    <!-- Trusted By Section -->
    <section class="trusted-by-section py-4 border-top border-bottom" style="border-color: rgba(255,255,255,0.05) !important; background: rgba(0,0,0,0.2);">
        <div class="container">
            <div class="trusted-by text-center">
                <p class="text-secondary small fw-medium mb-3 text-uppercase tracking-wider">Trusted by Educational Institutions</p>
                <div class="d-flex justify-content-center gap-5 flex-wrap opacity-50">
                    <i class="bi bi-buildings fs-4"></i>
                    <i class="bi bi-bank fs-4"></i>
                    <i class="bi bi-mortarboard fs-4"></i>
                    <i class="bi bi-award fs-4"></i>
                    <i class="bi bi-book fs-4"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer (Included via PHP) -->
    <?php include 'includes/components/footer/footer.php'; ?>

    <!-- Page Transition Overlay -->
    <div id="pageTransitionOverlay" class="page-transition-overlay"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Handle smooth transitions for links
        document.querySelectorAll('a').forEach(link => {
            if(link.getAttribute('href') && !link.getAttribute('href').startsWith('#')) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = this.getAttribute('href');
                    const overlay = document.getElementById('pageTransitionOverlay');
                    if(overlay) overlay.classList.add('active');
                    setTimeout(() => {
                        window.location.href = target;
                    }, 500);
                });
            }
        });

        // Fix BFCache blank screen issue when using back button
        window.addEventListener('pageshow', function (event) {
            const overlay = document.getElementById('pageTransitionOverlay');
            if (overlay) overlay.classList.remove('active');
            document.body.classList.add('loaded');
        });
    </script>
</body>
</html>
