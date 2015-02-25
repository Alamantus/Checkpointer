<?php
function Show_Login_Form ($name) {
    $login_form = '<div><h3>Log In</h3>';
    $login_form .= '<form method="post" action="?action=login">';
    $login_form .= 'Username:<br>';
    $login_form .= '<input type="text" name="name" value="' . $name . '" onclick="this.select()" length="29">';
    $login_form .= '<br>';
    $login_form .= 'Password:<br>';
    $login_form .= '<input type="password" name="pw" value="" onclick="this.select()">';
    $login_form .= '<br><br>';
    $login_form .= '<input type="submit" value="Submit">';
    $login_form .= '</form></div>';
    
    echo $login_form;
}

function Return_Add_Checkpoint_Form ($parent_id) {
    $checkpoint_form = '<form method="post" action="?action=add" onsubmit="return validateCheckpoint(' . $parent_id . ')">';
    $checkpoint_form .= '<p>Title:<br />';
    // Using the $parent_id variable because each checkpoint has only one "add checkpoint" form.
    $checkpoint_form .= '<span id="checkpointTitleMessage' . $parent_id . '" class="hidden"><br /></span>';
    $checkpoint_form .= '<input id="checkpointTitleInput' . $parent_id . '" class="checkpointTitleInput" parentID="' . $parent_id . '" type="text" name="title" value="" length="199"></p>';
    $checkpoint_form .= '<p>Details:<br />';
    $checkpoint_form .= '<textarea rows="4" name="text"></textarea></p>';
    $checkpoint_form .= '<p>Sort Order:<br />';
    $checkpoint_form .= '<input type="text" name="sort" value="0" length="3"></p>';
    $checkpoint_form .= '<input type="hidden" name="parent" value="' . $parent_id .'">';
    $checkpoint_form .= '<p><input type="submit" value="Submit"></p>';
    $checkpoint_form .= '</form>';
    
    return $checkpoint_form;
}
function Return_Edit_Checkpoint_Form ($id, $title, $text, $sort) {
    $checkpoint_form = '<form method="post" action="?action=edit">';
    $checkpoint_form .= 'Title:<br>';
    $checkpoint_form .= '<input type="text" name="title" value="'. $title . '" length="199">';
    $checkpoint_form .= '<br>';
    $checkpoint_form .= 'Details:<br>';
    $checkpoint_form .= '<textarea name="text">'. $text . '</textarea>';
    $checkpoint_form .= '<br>';
    $checkpoint_form .= 'Sort Order:<br>';
    $checkpoint_form .= '<input type="text" name="sort" value="'. $sort . '" length="3">';
    $checkpoint_form .= '<br><input type="hidden" name="id" value="' . $id .'"><br>';
    $checkpoint_form .= '<input type="submit" value="Submit">';
    $checkpoint_form .= '</form>';
    
    return $checkpoint_form;
}

function Output_User_Checkpoints ($id) {
    //Select 
    $checkpoints_query = "SELECT * FROM checkpoint WHERE parent=0 AND owner=" . $id . " ORDER BY sort ASC";
    $checkpoints = query($checkpoints_query);
    $checkpoints_output = "";

    if ($checkpoints != false && num_rows($checkpoints) > 0) {
        echo "<div class='root_checkpoint'>";
        // output data of each checkpoint as a list item
        while($checkpoint = fetch_assoc($checkpoints)) {
            $checkpoints_output .= "<div class='checkpoint'>";
            $checkpoints_output .= "<div id='checkpoint". $checkpoint["id"] ."' class='checkpoint_title clickable'>";
            $checkpoints_output .= "<strong title='Created ". date("l, F j, Y \a\\t g:i a",$checkpoint["created_date"]) ."'>" . $checkpoint["title"]. "</strong>";
            $checkpoints_output .= "</div>";
            $checkpoints_output .= "<div id='checkpoint". $checkpoint["id"] ."_details' class='checkpoint_details'>";
            $checkpoints_output .= "<span class='editButton'><a href='?action=edit&id=". $checkpoint["id"] ."'>Edit</a></span>";
            $checkpoints_output .= $checkpoint["text"];
            $checkpoints_output .= "</div>";
            $checkpoints_output .= "<div class='addCheckpointArea'>";
            $checkpoints_output .= "<span class='addCheckpointButton clickable' id='addCheckpoint". $checkpoint["id"] ."'>Add Checkpoint to \"" . $checkpoint["title"]. "\"</span>";
            $checkpoints_output .= "<div id='addCheckpoint". $checkpoint["id"] ."_form' class='addCheckpointForm'>";
            $checkpoints_output .= Return_Add_Checkpoint_Form($checkpoint["id"]);
            $checkpoints_output .= "</div>";
            $checkpoints_output .= "</div>";
            $checkpoints_output .= Get_Children($checkpoint["id"]);
            $checkpoints_output .= "</div>";
        }
        echo $checkpoints_output . "</div>";
    } else {
        echo "<strong>No goals yet!</strong><p>Add a new goal by clicking \"New Goal\" in the header and get started!";
    }
    //echo "<strong>Add New Goal</strong><br />" . Return_Add_Checkpoint_Form(0);
    return;
}
function Get_Children($id) {
    $children_query = "SELECT * FROM checkpoint WHERE parent=" . $id . " ORDER BY sort ASC";
    $children = query($children_query);
    $child_output = "";
    
    if ($children != false && num_rows($children) > 0) {
        // output data of each checkpoint as a list item
        while($child = fetch_assoc($children)) {
            $child_output .= "<div class='checkpoint'><div id='checkpoint". $child["id"] ."' class='checkpoint_title clickable'><strong title='Created ". date("l, F j, Y \a\\t g:i a",$child["created_date"]) ."'>" . $child["title"]. "</strong></div><div id='checkpoint". $child["id"] ."_details' class='checkpoint_details'><span class='editButton'><a href='?action=edit&id=". $child["id"] ."'>Edit</a></span>" . $child["text"]. "</div>";
            $child_output .= "<div class='addCheckpointArea'><span class='addCheckpointButton clickable' id='addCheckpoint". $child["id"] ."'>Add Checkpoint to \"" . $child["title"]. "\"</span>";
            $child_output .= "<div id='addCheckpoint". $child["id"] ."_form' class='addCheckpointForm'>" . Return_Add_Checkpoint_Form($child["id"]) . "</div></div>";
            $child_output .= Get_Children($child["id"]);
            $child_output .= "</div>";
        }
        return $child_output;
    }
    else {
        return;
    }
}
?>