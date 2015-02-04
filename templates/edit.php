<?php
if (isset($_POST["id"])) {
    $id = $_POST["id"];
    $title = isset($_POST["title"]) ? htmlspecialchars($_POST["title"]) : "";
    $text = isset($_POST["text"]) ? htmlspecialchars($_POST["text"]) : "";
    $sort = isset($_POST["sort"]) ? $_POST["sort"] : 0;
    
    $update_sql = "UPDATE milestone SET title='" . $title . "', text='" . $text . "', sort=" . $sort . " WHERE id=" . $id . ";";
    
    if (query($update_sql)) {
        echo "Record edited successfully";
        header('Location: .');
    } else {
        echo "Error: " . $update_sql . "<br>" . mysqli_error(connection());
    }
} else {
    if (isset($_GET["id"])) {
        $milestone_edit_query = "SELECT * FROM milestone WHERE id=" . $_GET["id"] . "";
        $milestone_edit = query($milestone_edit_query);
        $milestone_edit_output = "";

        if ($milestone_edit != false && num_rows($milestone_edit) === 1) {
            while($milestone = fetch_assoc($milestone_edit)) {
                echo "<strong>Edit</strong><br />" . Return_Edit_Milestone_Form($_GET["id"], $milestone["title"], $milestone["text"], $milestone["sort"]);
            }
        }
    } else {
        echo "Something went wrong: No ID specified";
    }
}
?>