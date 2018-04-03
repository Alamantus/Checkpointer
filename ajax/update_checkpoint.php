<?php
require_once('../config.php');
require_once('../funct.php');
require_once('../outputs.php');
if (isset($_POST["id"])) {
    $id = $_POST["id"];
    $title = str_replace("\\", "&#34;", str_replace("\"", "&#34;", str_replace("'", "&#39;", htmlspecialchars($_POST["title"]))));
    $text = str_replace("\\", "&#92;", str_replace("\"", "&#34;", str_replace("'", "&#39;", htmlspecialchars($_POST["text"]))));
    $text = str_replace("\n", "<br />", $text);
    $is_public = isset($_POST["privacy"]) ? $_POST["privacy"] : "0";
    
    $update_sql = "UPDATE checkpoint SET title=?, text=?, is_public=?, last_modified=" . time() . " WHERE id=?;";
    $cleaned_title = htmlspecialchars($title);
    $cleaned_text = htmlspecialchars($text);
    $result = query($update_sql, array($cleaned_title, $cleaned_text, $is_public, $id), false);
    if ($result && $result->rowCount() > 0) {
        echo "success";
    } else {
        echo "Error: " . $update_sql . "<br><pre>" . var_export($result->errorInfo(), true) . "</pre>";
    }
} else {
    echo "No Post ID Specified!";
}
?>