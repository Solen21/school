<?php
require_once 'data/db_connect.php';

// --- Fetch Data for the Page ---

// Student and Teacher Counts
$student_count_result = mysqli_query($conn, "SELECT COUNT(*) AS count FROM students WHERE status = 'active'");
$student_count = mysqli_fetch_assoc($student_count_result)['count'];

$teacher_count_result = mysqli_query($conn, "SELECT COUNT(*) AS count FROM teachers");
$teacher_count = mysqli_fetch_assoc($teacher_count_result)['count'];

// Latest News Articles (get 3 published articles)
$news_sql = "SELECT news_id, title, content, image_path FROM news WHERE status = 'published' ORDER BY created_at DESC LIMIT 3";
$news_result = mysqli_query($conn, $news_sql);

// Gallery Images (get 6 random images)
$gallery_sql = "SELECT title, image_path FROM gallery ORDER BY RAND() LIMIT 6";
$gallery_result = mysqli_query($conn, $gallery_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Our School | Excellence in Education</title>
    
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
        <a class="navbar-brand" href="#"><i class="fas fa-graduation-cap me-2 text-primary"></i>Our School</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link active" href="#home">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                <li class="nav-item"><a class="nav-link" href="#news">News</a></li>
                <li class="nav-item"><a class="nav-link" href="#gallery">Gallery</a></li>
                <li class="nav-item"><a class="btn btn-primary ms-lg-3" href="login.php">Admin Login</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section id="home" class="hero">
    <div class="container">
        <h1 class="display-3" data-aos="fade-down">Excellence in Education</h1>
        <p class="lead" data-aos="fade-up" data-aos-delay="200">Nurturing the leaders of tomorrow, today.</p>
    </div>
</section>

<!-- About / Stats Section -->
<section id="about" class="section-padding">
    <div class="container">
        <h2 class="section-title" data-aos="fade-up">Why Choose Us</h2>
        <div class="row text-center g-4">
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="stats-card">
                    <i class="fas fa-user-graduate"></i>
                    <h3 class="counter" data-target="<?php echo $student_count; ?>">0</h3>
                    <p class="text-muted">Enrolled Students</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="stats-card">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <h3 class="counter" data-target="<?php echo $teacher_count; ?>">0</h3>
                    <p class="text-muted">Qualified Teachers</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                <div class="stats-card">
                    <i class="fas fa-award"></i>
                    <h3 class="counter" data-target="25">0</h3>
                    <p class="text-muted">Years of Excellence</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- News Section -->
<section id="news" class="section-padding bg-light">
    <div class="container">
        <h2 class="section-title" data-aos="fade-up">Latest News & Events</h2>
        <div class="row g-4">
            <?php if (mysqli_num_rows($news_result) > 0): ?>
                <?php while($news_item = mysqli_fetch_assoc($news_result)): ?>
                <div class="col-md-4" data-aos="zoom-in">
                    <div class="card news-card h-100">
                        <img src="<?php echo htmlspecialchars($news_item['image_path'] ?? 'assets/img/placeholder.png'); ?>" class="card-img-top" alt="News Image">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?php echo htmlspecialchars($news_item['title']); ?></h5>
                            <p class="card-text flex-grow-1"><?php echo htmlspecialchars(substr($news_item['content'], 0, 100)) . '...'; ?></p>
                            <a href="view_article.php?id=<?php echo $news_item['news_id']; ?>" class="btn btn-outline-primary mt-auto">Read More</a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col text-center" data-aos="fade-up">
                    <p>No news articles to display at the moment. Please check back later.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Gallery Section -->
<section id="gallery" class="section-padding">
    <div class="container">
        <h2 class="section-title" data-aos="fade-up">Our Campus Gallery</h2>
        <div class="row g-4">
             <?php if (mysqli_num_rows($gallery_result) > 0): ?>
                <?php while($gallery_item = mysqli_fetch_assoc($gallery_result)): ?>
                <div class="col-md-4 col-sm-6" data-aos="zoom-in-up">
                    <div class="gallery-item">
                        <img src="<?php echo htmlspecialchars($gallery_item['image_path']); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($gallery_item['title']); ?>">
                        <div class="overlay">
                            <h5><?php echo htmlspecialchars($gallery_item['title']); ?></h5>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col text-center" data-aos="fade-up">
                    <p>The gallery is currently empty. Please check back soon!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

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
