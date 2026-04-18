<?php
include("../header.php");
if(!isset($_SESSION['user'])){
    header("Location: ../authentication/login.php");
    exit;
}
include("../config/db.php");

$id = $_GET['id'];
if(!isset($id)){
    header("Location: dashboard.php");
    exit;
}

if(isset($_POST['confirm_delete'])){
    $res = mysqli_query($conn,"SELECT * FROM items WHERE id=$id AND user_id=".$_SESSION['user']['id']);
    $item = mysqli_fetch_assoc($res);
    if($item){
        mysqli_query($conn,"DELETE FROM items WHERE id=$id");
        // Delete photo file if exists
        if($item['photo'] && file_exists('../uploads/' . $item['photo'])){
            unlink('../uploads/' . $item['photo']);
        }
    }
    header("Location: dashboard.php");
    exit;
}

$res = mysqli_query($conn,"SELECT * FROM items WHERE id=$id AND user_id=".$_SESSION['user']['id']);
$row = mysqli_fetch_assoc($res);

if(!$row){
    header("Location: dashboard.php");
    exit;
}
?>

<div class="dashboard-header">
    <div class="d-sm-flex align-items-center justify-content-between gap-3">
        <div>
            <h2>Delete Item</h2>
            <p>Are you sure you want to delete this item? This action cannot be undone.</p>
        </div>
        <a href="dashboard.php" class="btn btn-secondary btn-lg">Cancel</a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow border-danger">
            <div class="card-body p-4">
                <div class="alert alert-danger">
                    <h5 class="alert-heading">⚠️ Confirm Deletion</h5>
                    <p>You are about to delete the following item:</p>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($row['description']); ?></p>
                        <span class="badge bg-<?php echo ($row['type']=='lost')?'danger':'success'; ?>">
                            <?php echo strtoupper($row['type']); ?>
                        </span>
                        <p class="mt-2 text-muted">📍 <?php echo htmlspecialchars($row['location']); ?></p>
                    </div>
                </div>

                <form method="POST">
                    <button type="submit" name="confirm_delete" class="btn btn-logout btn-lg w-100">
                        <i class="fas fa-trash-alt me-2"></i>Yes, Delete Item
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include("../footer.php"); ?>