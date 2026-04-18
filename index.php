<?php include("header.php"); ?>
<?php include("config/db.php"); ?>

<div class="hero">
    <div class="row align-items-center">
        <div class="col-lg-7">
            <h1>Find lost items faster with a clean lost & found system.</h1>
            <p>Browse reported items, add new entries, and connect owners with lost or found belongings in a modern, easy-to-use interface.</p>
            <a href="/project/authentication/login.php" class="btn btn-light btn-lg me-2">Login</a>
            <a href="/project/authentication/register.php" class="btn btn-outline-light btn-lg">Register</a>
        </div>
    </div>
</div>

<h3 class="mb-4">All Lost & Found Items</h3>

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

<div class="row">
<?php
$res = mysqli_query($conn,"SELECT * FROM items ORDER BY id DESC");

while($row = mysqli_fetch_assoc($res)){
?>
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <?php if($row['photo']){ ?>
                    <img src="uploads/<?php echo htmlspecialchars($row['photo']); ?>" class="card-img-top mb-3" alt="Item photo" style="height: 200px; object-fit: cover; border-radius: 0.5rem;">
                <?php } ?>
                <h5 class="card-title"><?php echo $row['title']; ?></h5>
                <p class="card-text"><?php echo $row['description']; ?></p>

                <span class="badge bg-<?php echo ($row['type']=='lost')?'danger':'success'; ?>">
                    <?php echo strtoupper($row['type']); ?>
                </span>

                <p class="mt-2 text-muted">📍 <?php echo $row['location']; ?></p>
                <div class="mt-3">
                    <a href="item_details.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-comment me-1"></i>View Comments
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
</div>

<?php include("footer.php"); ?>