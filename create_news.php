<?php
require_once 'data/auth_check.php';
require_once 'data/db_connect.php';
 
$message = '';
$message_type = '';
 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $status = $_POST['status'];
    $author_id = $_SESSION['user_id']; // Get author from the logged-in user

    if (empty($title) || empty($content) || empty($status)) {
        $message = "Title, Content, and Status are required.";
        $message_type = "danger";
    }
 
    $image_path = null;
    // --- Handle File Upload ---
    if (empty($message) && isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $upload_dir = 'uploads/news/';
        // Create directory if it doesn't exist
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
 
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        $file_name = $_FILES['image']['name'];
        $file_tmp_name = $_FILES['image']['tmp_name'];
        $file_size = $_FILES['image']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
 
        if (in_array($file_ext, $allowed_types)) {
            if ($file_size < 5000000) { // 5MB limit
                // Generate a unique name for the file to prevent overwrites
                $new_file_name = uniqid('news_', true) . '.' . $file_ext;
                $target_path = $upload_dir . $new_file_name;
 
                if (move_uploaded_file($file_tmp_name, $target_path)) {
                    $image_path = $target_path;
                } else {
                    $message = "Failed to move uploaded file.";
                    $message_type = "danger";
                }
            } else {
                $message = "File is too large. Maximum size is 5MB.";
                $message_type = "danger";
            }
        } else {
            $message = "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
            $message_type = "danger";
        }
    }
 
    // --- Insert into Database if no errors occurred ---
    if (empty($message)) {
        $sql = "INSERT INTO news (title, content, status, author_id, image_path) VALUES (?, ?, ?, ?, ?)";
        
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "sssis", $title, $content, $status, $author_id, $image_path);
 
            if (mysqli_stmt_execute($stmt)) {
                header("Location: news.php?status=created");
                exit();
            } else {
                $message = "Error: Could not create the article. " . mysqli_stmt_error($stmt);
                $message_type = "danger";
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($conn);
}

$page_title = "Create News Article";
include 'partials/header.php';
?>

<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h1 class="h4 mb-0"><i class="fas fa-plus-circle me-2"></i>Create News Article</h1>
        </div>
        <div class="card-body">
            <?php if ($message): ?>
            <div class="alert alert-<?php echo $message_type; ?>"><?php echo $message; ?></div>
            <?php endif; ?>

            <form action="create_news.php" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="mb-3">
                    <label for="content" class="form-label">Content</label>
                    <textarea class="form-control" id="content" name="content" rows="10" required></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="draft" selected>Draft</option>
                            <option value="published">Published</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="image" class="form-label">Featured Image (Optional)</label>
                        <input type="file" class="form-control" id="image" name="image">
                    </div>
                </div>
                
                <button type="submit" class="btn btn-success"><i class="fas fa-check me-1"></i> Publish Article</button>
                <a href="news.php" class="btn btn-secondary"><i class="fas fa-times me-1"></i> Cancel</a>
            </form>
        </div>
    </div>
</div>

<?php 
include 'partials/footer.php'; 
?>