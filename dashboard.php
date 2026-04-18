<?php
include("../header.php");
include("../config/db.php");

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin'){
    header("Location: ../authentication/login.php");
    exit;
}

$res = mysqli_query($conn,"SELECT * FROM items ORDER BY id DESC");
?>

<div class="dashboard-header">
    <div class="d-flex align-items-center justify-content-between gap-3">
        <div></div>
        <div>
            <h2 class="text-center mb-0">Admin Dashboard</h2>
            <p class="text-center text-muted mb-0">Review all reported lost and found items from the community.</p>
        </div>
        <a href="../authentication/logout.php" class="btn btn-logout btn-lg">
            <i class="fas fa-sign-out-alt me-2"></i>Logout
        </a>
    </div>
</div>

<div class="search-container">
    <div class="search-wrapper">
        <input type="text" id="search-input" class="search-input" placeholder="Search items by title, description, location, or type..." onkeyup="searchItems()">
        <i class="fas fa-search search-icon"></i>
        <button id="clear-search" class="clear-search" title="Clear search">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div class="search-filters">
        <button class="filter-btn active" data-filter="all">All Items</button>
        <button class="filter-btn" data-filter="lost">Lost Items</button>
        <button class="filter-btn" data-filter="found">Found Items</button>
    </div>
    <div id="search-results" class="search-results"></div>
</div>

<div class="row g-3">
<?php while($row = mysqli_fetch_assoc($res)){ ?>
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <?php if($row['photo']){ ?>
                    <img src="../uploads/<?php echo htmlspecialchars($row['photo']); ?>" class="card-img-top mb-3" alt="Item photo">
                <?php } else { ?>
                    <div class="card-image-placeholder mb-3">
                        <i class="fas fa-image"></i>
                        <span>No photo provided</span>
                    </div>
                <?php } ?>
                <h5 class="card-title"><?php echo $row['title']; ?></h5>
                <p class="card-text"><?php echo $row['description']; ?></p>
                <span class="badge bg-<?php echo ($row['type']=='lost') ? 'danger' : 'success'; ?>">
                    <?php echo strtoupper($row['type']); ?>
                </span>
                <p class="mt-2 text-muted">📍 <?php echo $row['location']; ?></p>

                <div class="mt-3">
                    <a href="../item_details.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-eye me-1"></i>View Details
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
</div>

<?php include("../footer.php"); ?>