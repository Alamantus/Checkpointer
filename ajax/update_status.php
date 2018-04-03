<?php
require_once('../config.php');
require_once('../funct.php');
require_once('../outputs.php');
if (isset($_POST["id"])) {
    $id = $_POST["id"];
    if (isset($_POST["status"])) {
        $status = intval($_POST["status"]);
        
        $update_sql = "UPDATE checkpoint SET status=? WHERE id=?;";
        $result = query($update_sql, array($status, $id), false);
        if ($result && $result->rowCount() > 0) {
            echo "success";
        } else {
            echo "Error: " . $update_sql . "<br><pre>" . var_export($result->errorInfo(), true) . "</pre>";
        }
    } else {
        echo "No status specified!";
    }
} else {
    echo "No Post ID Specified!";
}
?>