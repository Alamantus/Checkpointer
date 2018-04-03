<?php
require_once('../config.php');
require_once('../funct.php');
require_once('../outputs.php');
if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $sort = $_GET["sort"];
    $parent = $_GET["parent"];
    
    $update_sql = "UPDATE checkpoint SET sort=?, parent=?, last_modified= " . time() . " WHERE id=?;";
    $result = query($update_sql, array($sort, $parent, $id), false);
    if ($result && $result->rowCount() > 0) {
        echo "success";
    } else {
        echo "Error: " . $update_sql . "<br><pre>" . var_export($result->errorInfo(), true) . "</pre>";
    }
} else {
    echo "No Checkpoint ID Specified!";
}
?>