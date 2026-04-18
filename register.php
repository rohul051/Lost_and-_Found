<?php include("../header.php"); ?>
<?php include("../config/db.php");

if(isset($_POST['register'])){
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $pass = trim($_POST['password']);

    $errors = [];

    if(empty($name)){
        $errors[] = "Name is required";
    }
    if(empty($email)){
        $errors[] = "Email is required";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors[] = "Invalid email format";
    }
    if(empty($pass)){
        $errors[] = "Password is required";
    } elseif(strlen($pass) < 6){
        $errors[] = "Password must be at least 6 characters";
    }

    if(empty($errors)){
        $pass_hashed = md5($pass);
        $result = mysqli_query($conn,"INSERT INTO users(name,email,password,role) VALUES('$name','$email','$pass_hashed','user')");

        if($result){
            header("Location: login.php");
            exit;
        } else {
            $errors[] = "Registration failed. Email might already be registered.";
        }
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-4">
        <div class="card shadow p-4">
            <h4 class="text-center">Register</h4>

            <?php if(!empty($errors)){ ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach($errors as $error){ ?>
                            <li><?php echo $error; ?></li>
                        <?php } ?>
                    </ul>
                </div>
            <?php } ?>

            <form method="POST">
                <div class="mb-3">
                    <input type="text" name="name" class="form-control" placeholder="Name" value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
                </div>
                <div class="mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <button class="btn btn-warning w-100 btn-lg" name="register">
                    <i class="fas fa-user-plus me-2"></i>Register
                </button>
            </form>
        </div>
    </div>
</div>

<?php include("../footer.php"); ?>