<div class="infoPage">
<?php
if (isset($_POST["id"])) {
    $id = $_POST["id"];
    $title = isset($_POST["title"]) ? htmlspecialchars($_POST["title"]) : "";
    $text = isset($_POST["text"]) ? htmlspecialchars($_POST["text"]) : "";
    $sort = isset($_POST["sort"]) ? $_POST["sort"] : 0;
    
    $update_sql = "UPDATE checkpoint SET title=?, text=?, sort=? WHERE id=?;";
    $result = query($update_sql, array($title, $text, $sort, $id), false);
    if ($result && $result->rowCount() > 0) {
        echo "Record edited successfully";
        header('Location: .');
    } else {
        echo "Error: " . $update_sql . "<br><pre>" . var_export($result->errorInfo(), true) . "</pre>";
    }
} else {
    if (isset($_GET["id"])) {
        $checkpoint_edit_query = "SELECT * FROM checkpoint WHERE id=?";
        $checkpoint_edit = query($checkpoint_edit_query, array($_GET["id"]));
        $checkpoint_edit_output = "";

        if ($checkpoint_edit && count($checkpoint_edit) === 1) {
            foreach($checkpoint_edit as $checkpoint) {
                echo "<strong>Edit</strong><br />" . Return_Edit_Checkpoint_Form($_GET["id"], $checkpoint["title"], $checkpoint["text"], $checkpoint["sort"]);
            }
        }
    } else {
        echo "Something went wrong: No ID specified";
    }
}
?>
</div>