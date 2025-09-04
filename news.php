<?php
require_once 'data/auth_check.php';
require_once 'data/db_connect.php';

$sql = "SELECT 
            n.news_id,
            n.title,
            n.status,
            n.created_at,
            u.username AS author_name
        FROM news n
        LEFT JOIN users u ON n.author_id = u.user_id
        ORDER BY n.created_at DESC";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error fetching news: " . mysqli_error($conn));
}

$page_title = "Manage News";
include 'partials/header.php';
?>

<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h1 class="h4 mb-0"><i class="fas fa-newspaper me-2"></i>News Management</h1>
            <a href="create_news.php" class="btn btn-light"><i class="fas fa-plus me-1"></i> Add News Article</a>
        </div>
        <div class="card-body">
            <?php if (isset($_GET['status'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php 
                        if ($_GET['status'] == 'created') echo 'News article created successfully!'; 
                        if ($_GET['status'] == 'updated') echo 'News article updated successfully!'; 
                        if ($_GET['status'] == 'deleted') echo 'News article deleted successfully!'; 
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Status</th>
                            <th>Date Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['title']); ?></td>
                                <td><?php echo htmlspecialchars($row['author_name'] ?? 'N/A'); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $row['status'] == 'published' ? 'success' : 'warning'; ?>">
                                        <?php echo htmlspecialchars(ucfirst($row['status'])); ?>
                                    </span>
                                </td>
                                <td><?php echo date('F j, Y', strtotime($row['created_at'])); ?></td>
                                <td>
                                    <a href="edit_news.php?id=<?php echo $row['news_id']; ?>" class="btn btn-sm btn-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                    <a href="delete_news.php?id=<?php echo $row['news_id']; ?>" class="btn btn-sm btn-danger" title="Delete"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">No news articles found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php 
mysqli_close($conn);
include 'partials/footer.php'; 
?>