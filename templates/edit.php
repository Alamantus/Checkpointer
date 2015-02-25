<?php
if (isset($_POST["id"])) {
    $id = $_POST["id"];
    $title = isset($_POST["title"]) ? htmlspecialchars($_POST["title"]) : "";
    $text = isset($_POST["text"]) ? htmlspecialchars($_POST["text"]) : "";
    $sort = isset($_POST["sort"]) ? $_POST["sort"] : 0;
    
    $update_sql = "UPDATE checkpoint SET title='" . $title . "', text='" . $text . "', sort=" . $sort . " WHERE id=" . $id . ";";
    
    if (query($update_sql)) {
        echo "Record edited successfully";
        header('Location: .');
    } else {
        echo "Error: " . $update_sql . "<br>" . mysqli_error(connection());
    }
} else {
    if (isset($_GET["id"])) {
        $checkpoint_edit_query = "SELECT * FROM checkpoint WHERE id=" . $_GET["id"] . "";
        $checkpoint_edit = query($checkpoint_edit_query);
        $checkpoint_edit_output = "";

        if ($checkpoint_edit != false && num_rows($checkpoint_edit) === 1) {
            while($checkpoint = fetch_assoc($checkpoint_edit)) {
                echo "<strong>Edit</strong><br />" . Return_Edit_Checkpoint_Form($_GET["id"], $checkpoint["title"], $checkpoint["text"], $checkpoint["sort"]);
            }
        }
    } else {
        echo "Something went wrong: No ID specified";
    }
}
?>