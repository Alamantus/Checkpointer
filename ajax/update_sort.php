<?php
require_once('../config.php');
require_once('../funct.php');
require_once('../outputs.php');
if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $sort = $_GET["sort"];
    $parent = $_GET["parent"];
    
    $update_sql = "UPDATE checkpoint SET sort=". $sort .", parent=". $parent .", last_modified= " . time() . " WHERE id=" . $id . ";";
    
    if (query($update_sql)) {
        echo "success";
    } else {
        echo "Error: " . $update_sql . "<br>" . mysqli_error(connection());
    }
} else {
    echo "No Checkpoint ID Specified!";
}
?>