<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>lawda Professional University - Lost & Found</title>
    <!-- Bootstrap CSS -->
    <link href="./css/bootstrap5.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Style CSS -->
    <link href="./css/style.css" rel="stylesheet">
</head>
<body>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="#">UniFind</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#how-it-works">How It Works</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-outline-primary btn-sm rounded-pill px-3" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Login</a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-primary btn-sm rounded-pill px-3" href="#" data-bs-toggle="modal" data-bs-target="#registerModal">Register</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero-section">
        <div class="container">
            <h1>Lost Something? Find It Here.</h1>
            <p class="lead">The official Lost and Found hub for our university community. <br> Reconnecting you with your lost belongings.</p>
            <div>
                <a href="<?php echo (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) ? './methods/dashboard.php' : '#'; ?>"
                   <?php echo (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) ? '' : 'data-bs-toggle="modal" data-bs-target="#loginModal"'; ?>
                   class="btn btn-custom-primary me-2">Report a Lost Item</a>
                
                <a href="<?php echo (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) ? './methods/dashboard.php' : '#'; ?>"
                   <?php echo (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) ? '' : 'data-bs-toggle="modal" data-bs-target="#loginModal"'; ?>
                   class="btn btn-custom-secondary ms-2">Browse Found Items</a>
            </div>
        </div>
    </header>

    <!-- How It Works Section -->
    <section id="how-it-works" class="section">
        <div class="container">
            <h2 class="section-title">How It Works</h2>
            <div class="row g-4">
                <!-- Step 1 -->
                <div class="col-md-4">
                    <div class="step-card">
                        <div class="step-icon"><i class="bi bi-file-earmark-text"></i></div>
                        <h3 class="h5 fw-bold">1. Report or Browse</h3>
                        <p class="text-muted">If you've lost an item, create a detailed report. If you've found something, browse existing reports or post a new found item.</p>
                    </div>
                </div>
                <!-- Step 2 -->
                <div class="col-md-4">
                    <div class="step-card">
                        <div class="step-icon"><i class="bi bi-bell"></i></div>
                        <h3 class="h5 fw-bold">2. Get Notified</h3>
                        <p class="text-muted">Our system automatically tries to match lost and found items. You'll receive an email notification if a potential match is found.</p>
                    </div>
                </div>
                <!-- Step 3 -->
                <div class="col-md-4">
                    <div class="step-card">
                        <div class="step-icon"><i class="bi bi-arrow-repeat"></i></div>
                        <h3 class="h5 fw-bold">3. Reclaim Your Item</h3>
                        <p class="text-muted">Once a match is confirmed, arrange a secure pickup at a designated campus location to get your item back safely.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="section bg-light">
        <div class="container">
            <h2 class="section-title">Key Features</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">🔍</div>
                        <h3 class="h5 fw-bold">Advanced Search</h3>
                        <p class="text-muted">Filter items by category, date, location, and keywords to quickly find what you're looking for.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">📸</div>
                        <h3 class="h5 fw-bold">Image Uploads</h3>
                        <p class="text-muted">Attach photos to your reports to make identification easier and more accurate for everyone.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">🔒</div>
                        <h3 class="h5 fw-bold">Secure & Private</h3>
                        <p class="text-muted">Your personal information is kept private. Communication happens anonymously through the platform until you're ready to meet.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="section contact-section">
        <div class="container">
            <h2 class="section-title">Contact Us</h2>
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <form>
                        <div class="mb-3">
                            <label for="contactName" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="contactName" placeholder="John Doe">
                        </div>
                        <div class="mb-3">
                            <label for="contactEmail" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="contactEmail" placeholder="name@example.com">
                        </div>
                        <div class="mb-3">
                            <label for="contactMessage" class="form-label">Message</label>
                            <textarea class="form-control" id="contactMessage" rows="5" placeholder="Your message here..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-custom-primary">Send Message</button>
                    </form>
                </div>
                <div class="col-lg-6">
                    <div class="contact-info">
                        <h3 class="fw-bold">Get in Touch</h3>
                        <p>Have questions? We're here to help. Reach out to the Student Affairs office for any inquiries regarding the lost and found service.</p>
                        <p><i class="bi bi-geo-alt-fill"></i> Lovely Professional University, Jalandhar, Punjab, 144411</p>
                        <p><i class="bi bi-telephone-fill"></i> (123) 456-7890</p>
                        <p><i class="bi bi-envelope-fill"></i> lostandfound@university.edu</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="container text-center">
            <p class="mb-0">&copy; 2025 Lovely Professional University. All Rights Reserved.</p>
            <p><a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a></p>
        </div>
    </footer>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow">
          <div class="modal-header p-5 pb-4 border-bottom-0">
            <h1 class="fw-bold mb-0 fs-2" id="loginModalLabel">Login</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body p-5 pt-0">
            <form action="./methods/login.php" method="post">
              <div class="form-floating mb-3">
                <input type="text" class="form-control rounded-3" id="loginNumber" name="loginNumber" placeholder="12345678" required>
                <label for="loginNumber">Registration Number</label>
              </div>
              <div class="form-floating mb-3">
                <input type="password" class="form-control rounded-3" id="loginPassword" name="loginPassword" placeholder="Password" required>
                <label for="loginPassword">Password</label>
              </div>
              <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit">Login</button>
              <small class="text-muted">By clicking Login, you agree to the terms of use.</small>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Register Modal -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow">
          <div class="modal-header p-5 pb-4 border-bottom-0">
            <h1 class="fw-bold mb-0 fs-2" id="registerModalLabel">Register for free</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body p-5 pt-0">
            <form action="./methods/register.php" method="post">
                <div class="form-floating mb-3">
                <input type="text" class="form-control rounded-3" id="registerName" name="registerName" placeholder="John Doe" required>
                <label for="registerName">Full Name</label>
              </div>
              <div class="form-floating mb-3">
                <input type="text" class="form-control rounded-3" id="registerNumber" name="registerNumber" placeholder="12345678" required>
                <label for="registerNumber">Registration Number</label>
              </div>
              <div class="form-floating mb-3">
                <input type="password" class="form-control rounded-3" id="registerPassword" name="registerPassword" placeholder="Password" required>
                <label for="registerPassword">Password</label>
              </div>
               <div class="form-floating mb-3">
                <input type="password" class="form-control rounded-3" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" required>
                <label for="confirmPassword">Confirm Password</label>
              </div>
              <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit">Register</button>
              <small class="text-muted">By clicking Register, you agree to the terms of use.</small>
            </form>
          </div>
        </div>
      </div>
    </div>


    <!-- Bootstrap JS -->
    <script src="./js/bootstrap5.min.js"></script>
</body>
</html>