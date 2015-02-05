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

function Return_Add_Milestone_Form ($parent_id) {
    $milestone_form = '<form method="post" action="?action=add" onsubmit="return validateCheckpoint(' . $parent_id . ')">';
    $milestone_form .= '<p>Title:<br />';
    // Using the $parent_id variable because each checkpoint has only one "add checkpoint" form.
    $milestone_form .= '<span id="checkpointTitleMessage' . $parent_id . '" class="hidden"><br /></span>';
    $milestone_form .= '<input id="checkpointTitleInput' . $parent_id . '" class="checkpointTitleInput" parentID="' . $parent_id . '" type="text" name="title" value="" length="199"></p>';
    $milestone_form .= '<p>Details:<br />';
    $milestone_form .= '<textarea rows="4" name="text"></textarea></p>';
    $milestone_form .= '<p>Sort Order:<br />';
    $milestone_form .= '<input type="text" name="sort" value="0" length="3"></p>';
    $milestone_form .= '<input type="hidden" name="parent" value="' . $parent_id .'">';
    $milestone_form .= '<p><input type="submit" value="Submit"></p>';
    $milestone_form .= '</form>';
    
    return $milestone_form;
}
function Return_Edit_Milestone_Form ($id, $title, $text, $sort) {
    $milestone_form = '<form method="post" action="?action=edit">';
    $milestone_form .= 'Title:<br>';
    $milestone_form .= '<input type="text" name="title" value="'. $title . '" length="199">';
    $milestone_form .= '<br>';
    $milestone_form .= 'Details:<br>';
    $milestone_form .= '<textarea name="text">'. $text . '</textarea>';
    $milestone_form .= '<br>';
    $milestone_form .= 'Sort Order:<br>';
    $milestone_form .= '<input type="text" name="sort" value="'. $sort . '" length="3">';
    $milestone_form .= '<br><input type="hidden" name="id" value="' . $id .'"><br>';
    $milestone_form .= '<input type="submit" value="Submit">';
    $milestone_form .= '</form>';
    
    return $milestone_form;
}

function Output_User_Milestones ($id) {
    //Select 
    $milestones_query = "SELECT * FROM milestone WHERE parent=0 AND owner=" . $id . " ORDER BY sort ASC";
    $milestones = query($milestones_query);
    $milestones_output = "";

    if ($milestones != false && num_rows($milestones) > 0) {
        echo "<div class='root_milestone'>";
        // output data of each milestone as a list item
        while($milestone = fetch_assoc($milestones)) {
            $milestones_output .= "<div class='milestone'><div id='milestone". $milestone["id"] ."' class='milestone_title clickable'><strong title='Created ". date("l, F j, Y \a\\t g:i a",$milestone["created_date"]) ."'>" . $milestone["title"]. "</strong></div><div id='milestone". $milestone["id"] ."_details' class='milestone_details'><span class='editButton'><a href='?action=edit&id=". $milestone["id"] ."'>Edit</a></span>" . $milestone["text"]. "</div>";
            $milestones_output .= "<div class='addCheckpointArea'><span class='addCheckpointButton clickable' id='addCheckpoint". $milestone["id"] ."'>Add Checkpoint to \"" . $milestone["title"]. "\"</span>";
            $milestones_output .= "<div id='addCheckpoint". $milestone["id"] ."_form' class='addCheckpointForm'>" . Return_Add_Milestone_Form($milestone["id"]) . "</div></div>";
            $milestones_output .= Get_Children($milestone["id"]);
            $milestones_output .= "</div>";
        }
        echo $milestones_output . "</div>";
    } else {
        echo "<strong>No goals yet!</strong><p>Add a new goal by clicking \"New Goal\" in the header and get started!";
    }
    //echo "<strong>Add New Goal</strong><br />" . Return_Add_Milestone_Form(0);
    return;
}
function Get_Children($id) {
    $children_query = "SELECT * FROM milestone WHERE parent=" . $id . " ORDER BY sort ASC";
    $children = query($children_query);
    $child_output = "";
    
    if ($children != false && num_rows($children) > 0) {
        // output data of each milestone as a list item
        while($child = fetch_assoc($children)) {
            $child_output .= "<div class='milestone'><div id='milestone". $child["id"] ."' class='milestone_title clickable'><strong title='Created ". date("l, F j, Y \a\\t g:i a",$child["created_date"]) ."'>" . $child["title"]. "</strong></div><div id='milestone". $child["id"] ."_details' class='milestone_details'><span class='editButton'><a href='?action=edit&id=". $child["id"] ."'>Edit</a></span>" . $child["text"]. "</div>";
            $child_output .= "<div class='addCheckpointArea'><span class='addCheckpointButton clickable' id='addCheckpoint". $child["id"] ."'>Add Checkpoint to \"" . $child["title"]. "\"</span>";
            $child_output .= "<div id='addCheckpoint". $child["id"] ."_form' class='addCheckpointForm'>" . Return_Add_Milestone_Form($child["id"]) . "</div></div>";
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