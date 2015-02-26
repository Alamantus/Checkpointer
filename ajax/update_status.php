<?php
require_once('../config.php');
require_once('../funct.php');
require_once('../outputs.php');
if (isset($_POST["id"])) {
    $id = $_POST["id"];
    if (isset($_POST["status"])) {
        $status = intval($_POST["status"]);
        
        $update_sql = "UPDATE checkpoint SET status=" . $status . " WHERE id=" . $id . ";";
        
        if (query($update_sql)) {
            echo "success";
        } else {
            echo "Error: " . $update_sql . "<br>" . mysqli_error(connection());
        }
    } else {
        echo "No status specified!";
    }
} else {
    echo "No Post ID Specified!";
}
?>