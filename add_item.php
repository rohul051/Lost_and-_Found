<?php
include("../header.php");
if(!isset($_SESSION['user'])){
    header("Location: ../authentication/login.php");
    exit;
}
include("../config/db.php");

if(isset($_POST['add'])){
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $type = $_POST['type'];
    $loc = $_POST['location'];
    $uid = $_SESSION['user']['id'];

    $photo = '';
    if(isset($_FILES['photo']) && $_FILES['photo']['error'] == 0){
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['photo']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if(in_array($ext, $allowed)){
            $newname = uniqid() . '.' . $ext;
            $destination = '../uploads/' . $newname;
            if(move_uploaded_file($_FILES['photo']['tmp_name'], $destination)){
                $photo = $newname;
            }
        }
    }

    mysqli_query($conn,"INSERT INTO items (title,description,type,location,user_id,photo)
    VALUES ('$title','$desc','$type','$loc','$uid','$photo')");

    header("Location: dashboard.php");
}
?>

<div class="dashboard-header">
    <div class="d-sm-flex align-items-center justify-content-between gap-3">
        <div>
            <h2>Add New Item</h2>
            <p>Report a lost or found item to help reunite it with its owner.</p>
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
                        <input type="text" name="title" id="title" class="form-control" placeholder="e.g., Black iPhone 12" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="4" placeholder="Detailed description of the item..." required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="type" class="form-label">Item Type</label>
                        <select name="type" id="type" class="form-select" required>
                            <option value="lost">Lost Item</option>
                            <option value="found">Found Item</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" name="location" id="location" class="form-control" placeholder="Where was it lost/found?" required>
                    </div>

                    <div class="mb-4">
                        <label for="photo" class="form-label">Photo (Optional)</label>
                        <input type="file" name="photo" id="photo" class="form-control" accept="image/*">
                        <div class="form-text">Upload a photo of the item (JPG, PNG, GIF)</div>
                    </div>

                    <button type="submit" name="add" class="btn btn-add-item btn-lg w-100">
                        <i class="fas fa-save me-2"></i>Add Item
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include("../footer.php"); ?>