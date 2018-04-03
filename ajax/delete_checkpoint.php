<?php
require_once('../config.php');
require_once('../funct.php');
require_once('../outputs.php');
if (isset($_POST["id"])) {
    $id = $_POST["id"];
    
    //Delete_Children_And_Self($id);
    //$delete_sql = "DELETE FROM checkpoint WHERE id=" . $id . ";";
    
    if (Delete_Children_And_Self($id)) {
        echo "success";
    } else {
        echo "Error: " . $delete_sql . "<br>" . mysqli_error(connection());
    }
} else {
    echo "No Post ID Specified!";
}

function Delete_Children_And_Self ($id) {
    $children_query = "SELECT * FROM checkpoint WHERE parent=? ORDER BY sort ASC";
    $children = query($children_query, array($id));
    
    if ($children != false && count($children) > 0) {
        foreach($children as $child) {
            Delete_Children_And_Self($child["id"]);
        }
    }
    
    $delete_sql = "DELETE FROM checkpoint WHERE id=?;";
    $result = query($delete_sql, array($id), false);
    if ($result && $result->rowCount() > 0) {
        return true;
    } else {
        echo "Error: " . $delete_sql . "<br><pre>" . var_export($result->errorInfo(), true) . "</pre>";
        return false;
    }
}
?>