<?php
session_start();
include("../config/db.php");

$next = isset($_REQUEST['next']) ? $_REQUEST['next'] : '';

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $pass = md5($_POST['password']);
    $next = isset($_POST['next']) ? $_POST['next'] : '';

    $res = mysqli_query($conn,"SELECT * FROM users WHERE email='$email' AND password='$pass'");
    $row = mysqli_fetch_assoc($res);

    if($row){
        $_SESSION['user'] = $row;

        if(!empty($next) && strpos($next, '..') === false){
            header("Location: $next");
            exit;
        }

        if($row['role']=='admin'){
            header("Location: ../admin/dashboard.php");
        } else {
            header("Location: ../user/dashboard.php");
        }
        exit;
    } else {
        echo "<div class='alert alert-danger'>Login Failed</div>";
    }
}
?>

<?php include("../header.php"); ?>
<div class="row justify-content-center">
    <div class="col-md-4">
        <div class="card shadow p-4">
            <h4 class="text-center">Login</h4>

            <form method="POST">
                <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
                <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
                <input type="hidden" name="next" value="<?php echo htmlspecialchars($next); ?>">
                <button class="btn btn-dark w-100 btn-lg" name="login">
                    <i class="fas fa-sign-in-alt me-2"></i>Login
                </button>
            </form>
        </div>
    </div>
</div>

<?php include("../footer.php"); ?>