<?php
require_once('../config.php');
require_once('../funct.php');
require_once('../outputs.php');
if (isset($_POST["id"])) {
    $id = $_POST["id"];
    $title = $_POST["title"];
    $text = $_POST["text"];
    $is_public = isset($_POST["privacy"]) ? $_POST["privacy"] : "0";
    
    $update_sql = "UPDATE checkpoint SET title=?, text=?, is_public=?, last_modified=" . time() . " WHERE id=?;";
    $cleaned_title = htmlspecialchars($title);
    $cleaned_text = htmlspecialchars($text);
    $params = array($cleaned_title, $cleaned_text);
    if (ENCRYPT_DATA) {
        $params = array_map(function($data) {
            return easy_crypt('encrypt', $data);
        }, $params);
    }
    $params[] = $is_public;
    $params[] = $id;
    $result = query($update_sql, $params, false);
    if ($result && $result->rowCount() > 0) {
        echo "success";
    } else {
        echo "Error: " . $update_sql . "<br><pre>" . var_export($result->errorInfo(), true) . "</pre>";
    }
} else {
    echo "No Post ID Specified!";
}
?>