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
    $children_query = "SELECT * FROM checkpoint WHERE parent=" . $id . " ORDER BY sort ASC";
    $children = query($children_query);
    
    if ($children != false && num_rows($children) > 0) {
        while($child = fetch_assoc($children)) {
            Delete_Children_And_Self($child["id"]);
        }
    }
    
    $delete_sql = "DELETE FROM checkpoint WHERE id=" . $id . ";";
    
    if (query($delete_sql)) {
        return true;
    } else {
        echo "Error: " . $delete_sql . "<br>" . mysqli_error(connection());
    }
}
?>