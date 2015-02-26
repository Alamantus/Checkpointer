<?php
require_once('../config.php');
require_once('../funct.php');
require_once('../outputs.php');
if (isset($_POST["id"])) {
    $id = $_POST["id"];
    $title = isset($_POST["title"]) ? htmlspecialchars($_POST["title"]) : "";
    $text = isset($_POST["text"]) ? htmlspecialchars($_POST["text"]) : "";
    
    $update_sql = "UPDATE checkpoint SET title='" . $title . "', text='" . $text . "' WHERE id=" . $id . ";";
    
    if (query($update_sql)) {
        echo "success";
    } else {
        echo "Error: " . $update_sql . "<br>" . mysqli_error(connection());
    }
} else {
    echo "No Post ID Specified!";
}
?>