<?php
session_start();
include("config/db.php");

$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;

if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
    header("Location: dashboard.php");
    exit;
}

$item_id = (int)$_GET['id'];

// Get item details
$item_query = mysqli_query($conn, "SELECT i.*, u.name as user_name FROM items i JOIN users u ON i.user_id = u.id WHERE i.id = $item_id");
$item = mysqli_fetch_assoc($item_query);

if(!$item){
    header("Location: dashboard.php");
    exit;
}

// Handle comment submission
if(isset($_POST['add_comment'])){
    if(!$user){
        $nextUrl = urlencode("item_details.php?id=$item_id");
        header("Location: authentication/login.php?next=$nextUrl");
        exit;
    }

    $comment = mysqli_real_escape_string($conn, trim($_POST['comment']));
    if(!empty($comment)){
        mysqli_query($conn, "INSERT INTO comments (item_id, user_id, comment) VALUES ($item_id, {$user['id']}, '$comment')");
        header("Location: item_details.php?id=$item_id");
        exit;
    }
}

// Get comments
$comments_query = mysqli_query($conn, "SELECT c.*, u.name FROM comments c JOIN users u ON c.user_id = u.id WHERE c.item_id = $item_id ORDER BY c.created_at DESC");
?>
<?php include("header.php"); ?>

<div class="dashboard-header">
    <div class="d-flex align-items-center justify-content-between gap-3">
        <a href="dashboard.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
        </a>
        <div>
            <h2 class="text-center mb-0">Item Details</h2>
            <p class="text-center text-muted mb-0">View complete item information and comments</p>
        </div>
        <div></div> <!-- Empty space for balance -->
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <?php if($item['photo']){ ?>
                    <img src="uploads/<?php echo htmlspecialchars($item['photo']); ?>" class="card-img-top mb-4" alt="Item photo" style="max-height: 400px; width: 100%; object-fit: cover; border-radius: 1rem;">
                <?php } ?>

                <h3 class="card-title mb-3"><?php echo htmlspecialchars($item['title']); ?></h3>

                <div class="mb-3">
                    <span class="badge bg-<?php echo ($item['type']=='lost')?'danger':'success'; ?> fs-6">
                        <?php echo strtoupper($item['type']); ?>
                    </span>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-2"><strong>Location:</strong> 📍 <?php echo htmlspecialchars($item['location']); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-2"><strong>Posted by:</strong> <?php echo htmlspecialchars($item['user_name']); ?></p>
                    </div>
                </div>

                <div class="mb-4">
                    <h5>Description:</h5>
                    <p class="card-text lead"><?php echo nl2br(htmlspecialchars($item['description'])); ?></p>
                </div>

                <?php if($user && $item['user_id'] == $user['id']){ ?>
                    <div class="d-flex gap-2">
                        <a href="user/edit_item.php?id=<?php echo $item['id']; ?>" class="btn btn-primary">
                            <i class="fas fa-edit me-1"></i>Edit Item
                        </a>
                        <a href="user/delete_item.php?id=<?php echo $item['id']; ?>" class="btn btn-danger">
                            <i class="fas fa-trash me-1"></i>Delete Item
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Comments (<?php echo mysqli_num_rows($comments_query); ?>)</h5>
            </div>
            <div class="card-body">
                <?php if($user){ ?>
                    <form method="post" class="comment-form">
                        <div class="mb-3">
                            <label for="comment" class="form-label">Add a comment:</label>
                            <textarea class="form-control" id="comment" name="comment" rows="3" placeholder="Share information about this item..." required></textarea>
                        </div>
                        <button type="submit" name="add_comment" class="btn btn-primary">
                            <i class="fas fa-comment me-1"></i>Post Comment
                        </button>
                    </form>
                <?php } else { ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-1"></i>
                        You can view comments here without logging in. <a href="authentication/login.php">Login</a> or <a href="authentication/register.php">register</a> to post a comment.
                    </div>
                <?php } ?>

                <div class="comments-section">
                    <?php if(mysqli_num_rows($comments_query) > 0){ ?>
                        <?php while($comment = mysqli_fetch_assoc($comments_query)){ ?>
                            <div class="comment-card">
                                <div class="comment-author"><?php echo htmlspecialchars($comment['name']); ?></div>
                                <div class="comment-date"><?php echo date('M j, Y g:i A', strtotime($comment['created_at'])); ?></div>
                                <div class="comment-text"><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></div>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <p class="text-muted text-center">No comments yet. Be the first to comment!</p>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("footer.php"); ?>