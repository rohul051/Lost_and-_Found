<?php
include("../config/db.php");

$result = $conn->query("SELECT * FROM tasks");

while($row = $result->fetch_assoc()){
    echo $row['title'] . " - " . $row['description'] . "<br>";
}
?>