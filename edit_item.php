<?php
include("../header.php");
if(!isset($_SESSION['user'])){
    header("Location: ../authentication/login.php");
    exit;
}
include("../config/db.php");

$id = $_GET['id'];
$res = mysqli_query($conn,"SELECT * FROM items WHERE id=$id AND user_id=".$_SESSION['user']['id']);
$row = mysqli_fetch_assoc($res);

if(!$row){
    header("Location: dashboard.php");
    exit;
}

if(isset($_POST['update'])){
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $type = $_POST['type'];
    $loc = $_POST['location'];

    $photo = $row['photo']; // Keep existing photo by default
    if(isset($_FILES['photo']) && $_FILES['photo']['error'] == 0){
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['photo']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if(in_array($ext, $allowed)){
            $newname = uniqid() . '.' . $ext;
            $destination = '../uploads/' . $newname;
            if(move_uploaded_file($_FILES['photo']['tmp_name'], $destination)){
                // Delete old photo if exists
                if($row['photo'] && file_exists('../uploads/' . $row['photo'])){
                    unlink('../uploads/' . $row['photo']);
                }
                $photo = $newname;
            }
        }
    }

    mysqli_query($conn,"UPDATE items SET title='$title', description='$desc', type='$type', location='$loc', photo='$photo' WHERE id=$id");
    header("Location: dashboard.php");
}
?>

<div class="dashboard-header">
    <div class="d-sm-flex align-items-center justify-content-between gap-3">
        <div>
            <h2>Edit Item</h2>
            <p>Update the details of your reported item.</p>
        </div>
        <a href="dashboard.php" class="btn btn-secondary btn-lg">Back to Dashboard</a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow">
            <div class="card-body p-4">
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="title" class="form-label">Item Title</label>
                        <input type="text" name="title" id="title" class="form-control" value="<?php echo htmlspecialchars($row['title']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="4" required><?php echo htmlspecialchars($row['description']); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="type" class="form-label">Item Type</label>
                        <select name="type" id="type" class="form-select" required>
                            <option value="lost" <?php echo ($row['type']=='lost')?'selected':''; ?>>Lost Item</option>
                            <option value="found" <?php echo ($row['type']=='found')?'selected':''; ?>>Found Item</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" name="location" id="location" class="form-control" value="<?php echo htmlspecialchars($row['location']); ?>" required>
                    </div>

                    <div class="mb-4">
                        <label for="photo" class="form-label">Photo (Optional)</label>
                        <?php if($row['photo']){ ?>
                            <div class="mb-2">
                                <img src="../uploads/<?php echo htmlspecialchars($row['photo']); ?>" alt="Current photo" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                        <?php } ?>
                        <input type="file" name="photo" id="photo" class="form-control" accept="image/*">
                        <div class="form-text">Upload a new photo to replace the current one (JPG, PNG, GIF)</div>
                    </div>

                    <button type="submit" name="update" class="btn btn-add-item btn-lg w-100">
                        <i class="fas fa-edit me-2"></i>Update Item
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include("../footer.php"); ?>