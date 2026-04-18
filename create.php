<?php
session_start();
include("../config/db.php");

if(isset($_POST['add'])){
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $user_id = $_SESSION['user']['id'];

    if(empty($title)){
        echo "Title required";
        exit;
    }

    $conn->query("INSERT INTO tasks(user_id,title,description)
                  VALUES('$user_id','$title','$desc')");
    
    header("Location: ../dashboard/user.php");
}
?>