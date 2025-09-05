<?php
require_once 'data/db_connect.php';

// --- Fetch Data for the Page ---

// Fetch Director's Information
$director = null;
$director_sql = "SELECT t.first_name, t.middle_name, t.last_name, t.email 
                 FROM teachers t
                 JOIN users u ON t.user_id = u.user_id
                 WHERE u.role = 'director'
                 LIMIT 1";
$director_result = mysqli_query($conn, $director_sql);
if ($director_result && mysqli_num_rows($director_result) > 0) {
    $director = mysqli_fetch_assoc($director_result);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Our School | Excellence in Education</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap & Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- AOS (Animate on Scroll) CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand" href="index.php"><i class="fas fa-graduation-cap me-2 text-primary"></i>Our School</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link active" href="about.php">About</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php#news">News</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php#gallery">Gallery</a></li>
                <li class="nav-item"><a class="btn btn-primary ms-lg-3" href="login.php">Admin Login</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Page Header Section -->
<section class="hero-small" style="background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://images.unsplash.com/photo-1509062522246-3755977927d7?q=80&w=2132&auto=format&fit=crop') no-repeat center center; background-size: cover;">
    <div class="container">
        <h1 class="display-4" data-aos="fade-down">About Our School</h1>
        <p class="lead" data-aos="fade-up" data-aos-delay="100">A tradition of excellence and a future of innovation.</p>
    </div>
</section>

<!-- Our Story Section -->
<section class="section-padding">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6" data-aos="fade-right">
                <h2 class="section-title text-start" style="margin-bottom: 20px;">Our Story</h2>
                <p>Founded in 1998, Our School has been a cornerstone of the community, dedicated to fostering an environment of academic excellence and personal growth. For over two decades, we have committed ourselves to nurturing young minds, encouraging critical thinking, and preparing students to become compassionate and successful global citizens.</p>
                <p>Our journey began with a small group of passionate educators and has grown into a thriving institution known for its innovative teaching methods and unwavering commitment to student success.</p>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <img src="https://images.unsplash.com/photo-1541339907198-e08756dedf3f?q=80&w=2070&auto=format&fit=crop" class="img-fluid rounded shadow-lg" alt="School Campus">
            </div>
        </div>
    </div>
</section>

<!-- Mission and Vision Section -->
<section class="section-padding bg-light">
    <div class="container">
        <div class="row">
            <div class="col-md-6 mb-4" data-aos="zoom-in">
                <div class="card h-100 text-center p-4 stats-card">
                    <div class="card-body">
                        <i class="fas fa-bullseye fa-3x text-primary mb-3"></i>
                        <h3 class="card-title">Our Mission</h3>
                        <p class="card-text">To provide a challenging and supportive learning environment where students can achieve their full potential, develop a love for lifelong learning, and become responsible, ethical leaders in a diverse world.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4" data-aos="zoom-in" data-aos-delay="100">
                <div class="card h-100 text-center p-4 stats-card">
                    <div class="card-body">
                        <i class="fas fa-eye fa-3x text-primary mb-3"></i>
                        <h3 class="card-title">Our Vision</h3>
                        <p class="card-text">To be a leading educational institution recognized for our academic excellence, innovative programs, and for inspiring students to make a positive impact on the world through knowledge, creativity, and compassion.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Meet the Director Section -->
<?php if ($director): ?>
<section class="section-padding">
    <div class="container">
        <h2 class="section-title" data-aos="fade-up">Meet Our Director</h2>
        <div class="row justify-content-center">
            <div class="col-lg-8" data-aos="fade-up" data-aos-delay="100">
                <div class="card p-4 shadow-sm" style="border: none;">
                    <div class="row g-0 align-items-center">
                        <div class="col-md-4 text-center">
                            <img src="https://via.placeholder.com/200/0D6EFD/FFFFFF?text=Director" class="img-fluid rounded-circle" alt="School Director">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h4 class="card-title"><?php echo htmlspecialchars($director['first_name'] . ' ' . $director['last_name']); ?></h4>
                                <h6 class="card-subtitle mb-2 text-muted">School Director</h6>
                                <p class="card-text">"Welcome to our school! We believe that every student has the potential to achieve greatness. Our dedicated team is here to guide, inspire, and support them on their journey to success. We are more than a school; we are a community."</p>
                                <p class="card-text"><small class="text-muted"><?php echo htmlspecialchars($director['email']); ?></small></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Footer -->
<footer class="bg-dark text-white text-center p-4">
    <div class="container">
        <p class="mb-0">&copy; <?php echo date('Y'); ?> Our School. All Rights Reserved.</p>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- AOS JS -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<!-- Custom JS -->
<script src="assets/js/main.js"></script>
</body>
</html>