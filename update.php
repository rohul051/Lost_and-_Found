<?php
include("../config/db.php");

$id = $_GET['id'];

if(isset($_POST['update'])){
    $title = $_POST['title'];

    $conn->query("UPDATE tasks SET title='$title' WHERE id=$id");
    echo "Updated";
}
?>