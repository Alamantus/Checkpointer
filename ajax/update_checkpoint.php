<?php
require_once('../config.php');
require_once('../funct.php');
require_once('../outputs.php');
if (isset($_POST["id"])) {
    $id = $_POST["id"];
    $title = str_replace("\\", "&#34;", str_replace("\"", "&#34;", str_replace("'", "&#39;", htmlspecialchars($_POST["title"]))));
    $text = str_replace("\\", "&#92;", str_replace("\"", "&#34;", str_replace("'", "&#39;", htmlspecialchars($_POST["text"]))));
    
    $update_sql = "UPDATE checkpoint SET title='" . easy_crypt('encrypt', $title) . "', text='" . easy_crypt('encrypt', $text) . "', last_modified= " . time() . " WHERE id=" . $id . ";";
    
    if (query($update_sql)) {
        echo "success";
    } else {
        echo "Error: " . $update_sql . "<br>" . mysqli_error(connection());
    }
} else {
    echo "No Post ID Specified!";
}
?>