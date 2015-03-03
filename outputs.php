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

function Return_Add_Checkpoint_Form ($parent_id, $parent_type) {
    /*
     * $parent_id = the checkpoint id.
     * $parent_type = goal or checkpoint
     */
    $checkpoint_form = '<form method="post" action="?action=add" onsubmit="return validateCheckpoint(' . $parent_id . ')">';
    $checkpoint_form .= '<p>Title:<br />';
    // Using the $parent_id variable because each checkpoint has only one "add checkpoint" form.
    $checkpoint_form .= '<span id="checkpointTitleMessage' . $parent_id . '" class="hidden"><br /></span>';
    $checkpoint_form .= '<input id="checkpointTitleInput' . $parent_id . '" class="checkpointTitleInput titleAddBox" parentID="' . $parent_id . '" type="text" name="title" value="" length="199" autocomplete="off"></p>';
    $checkpoint_form .= '<p>Details:<br />';
    $checkpoint_form .= '<textarea rows="4" name="text" class="detailsAddBox"></textarea></p>';
    $checkpoint_form .= '<input type="hidden" name="parent" value="' . $parent_id .'">';
    $checkpoint_form .= '<input type="hidden" name="parentType" value="' . $parent_type .'">';
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
    $checkpoint_form .= '<br><input type="hidden" name="id" value="' . $id .'"><br>';
    $checkpoint_form .= '<input type="submit" value="Submit">';
    $checkpoint_form .= '</form>';
    
    return $checkpoint_form;
}

function Output_User_Checkpoints ($id) {
    //Select 
    $checkpoints_query = "SELECT c.*, s.name AS 'status_name' FROM checkpoint c LEFT JOIN status s ON c.status=s.id WHERE parent=0 AND owner=" . $id . " ORDER BY sort ASC";
    $checkpoints = query($checkpoints_query);
    $checkpoints_output = "";

    if ($checkpoints != false && num_rows($checkpoints) > 0) {
        echo "<ul class='root_checkpoints' parentid='0'>";
        // output data of each checkpoint as a list item
        while($checkpoint = fetch_assoc($checkpoints)) {
            $title = easy_crypt('decrypt', $checkpoint['title']);
            $text = easy_crypt('decrypt', $checkpoint['text']);
            
            $status_query = "SELECT * FROM status WHERE id > 0 ORDER BY id ASC";
            $statuses = query($status_query);
            
            $checkpoints_output .= "<li class='goal' cpid='". $checkpoint["id"] ."'>";
            $checkpoints_output .= "<div id='goal". $checkpoint["id"] ."' class='goal_title'>";
            $checkpoints_output .= "<span class='handle'>&#8645;</span>";
            $checkpoints_output .= "<select id='goal". $checkpoint["id"] ."_status' class='checkpoint_status'>";
            if ($statuses != false && num_rows($statuses) > 0) {
                while($status = fetch_assoc($statuses)) {
                    $checkpoints_output .= "<option value='". $status["id"] ."' title='". $status["name"] ."'";
                    if ($checkpoint["status"] == $status["id"]) {
                        $checkpoints_output .= " selected='selected'";
                    }
                    $checkpoints_output .= ">". $status["html_display"] ."</option>";
                }
            }
            $checkpoints_output .= "</select>";
            $checkpoints_output .= "<strong class='title' title='Created ". date("l, F j, Y \a\\t g:i a",$checkpoint["created_date"]) ."'>";
            $checkpoints_output .= $title;
            $checkpoints_output .= "</strong>";
            $checkpoints_output .= "<strong id='goal". $checkpoint["id"] ."_edit' class='editButton clickable' user='". $id ."' sorder='". $checkpoint["sort"] ."'>Edit</strong>";
            $checkpoints_output .= "<strong class='addCheckpointButton clickable' title='Add Checkpoint to \"" . $title. "\"' id='addCheckpoint". $checkpoint["id"] ."'>";
            $checkpoints_output .= "+";
            $checkpoints_output .= "</strong>";
            $checkpoints_output .= "</div>";
            $checkpoints_output .= "<div id='goal". $checkpoint["id"] ."_details' class='goal_details'>";
            $checkpoints_output .= "<div id='goal". $checkpoint["id"] ."_details_text' class='goal_details_text'>";
            $checkpoints_output .= $text;
            $checkpoints_output .= "</div>";
            $checkpoints_output .= "</div>";
            $checkpoints_output .= "<div class='addCheckpointArea'>";
            $checkpoints_output .= "<div id='addCheckpoint". $checkpoint["id"] ."_form' class='addCheckpointForm'>";
            $checkpoints_output .= "<h3>Add Checkpoint to \"" . $title. "\"</h3>";
            $checkpoints_output .= Return_Add_Checkpoint_Form($checkpoint["id"], "goal");
            $checkpoints_output .= "</div>";
            $checkpoints_output .= "</div>";
            $checkpoints_output .= Count_Children($checkpoint["id"], "goal", " Checkpoints");   //Inserts its own div section.
            $checkpoints_output .= "<div id='goal". $checkpoint["id"] ."_children' class='children'>";
            $checkpoints_output .= "<span id='goal". $checkpoint["id"] ."_children_hide' class='hideChildrenButton clickable'>Hide Checkpoints</span>";
            $checkpoints_output .= "<ul id='goal". $checkpoint["id"] ."_children_list' class='childrenList' parentid='". $checkpoint["id"] ."'>";
            $checkpoints_output .= Get_Children($id, $checkpoint["id"]);
            $checkpoints_output .= "</ul>";
            $checkpoints_output .= "</div>";
            $checkpoints_output .= "</li>";
        }
        echo $checkpoints_output . "</ul>";
    } else {
        echo "<strong>No goals yet!</strong><p>Add a new goal by clicking \"New Goal\" in the header and get started!";
    }
    return;
}
function Get_Children($user_id, $parent_id) {
    $children_query = "SELECT c.*, s.name AS 'status_name' FROM checkpoint c LEFT JOIN status s ON c.status=s.id WHERE parent=" . $parent_id . " ORDER BY sort ASC";
    $children = query($children_query);//Get Statuses
    $child_output = "";
    
    if ($children != false && num_rows($children) > 0) {
        // output data of each checkpoint as a list item
        while($child = fetch_assoc($children)) {
            $title = easy_crypt('decrypt', $child['title']);
            $text = easy_crypt('decrypt', $child['text']);
            
            $status_query = "SELECT * FROM status WHERE id > 0 ORDER BY id ASC";
            $statuses = query($status_query);
            
            $child_output .= "<li class='checkpoint' cpid='". $child["id"] ."'>";
            $child_output .= "<div id='checkpoint". $child["id"] ."' class='checkpoint_title'>";
            $child_output .= "<span class='handle'>&#8645;</span>";
            $child_output .= "<select id='checkpoint". $child["id"] ."_status' class='checkpoint_status'>";
            if ($statuses != false && num_rows($statuses) > 0) {
                while($status = fetch_assoc($statuses)) {
                    $child_output .= "<option value='". $status["id"] ."' title='". $status["name"] ."'";
                    if ($child["status_name"] == $status["name"]) {
                        $child_output .= " selected='selected'";
                    }
                    $child_output .= ">". $status["html_display"] ."</option>";
                }
            }
            $child_output .= "</select>";
            $child_output .= "<strong class='title' title='Created ". date("l, F j, Y \a\\t g:i a",$child["created_date"]) ."'>";
            $child_output .= $title;
            $child_output .= "</strong>";
            $child_output .= "<strong id='checkpoint". $child["id"] ."_edit' class='editButton clickable' user='". $user_id ."' sorder='". $child["sort"] ."'>Edit</strong>";
            $child_output .= "<strong class='addCheckpointButton clickable' title='Add Checkpoint to \"" . $title. "\"' id='addCheckpoint". $child["id"] ."'>";
            $child_output .= "+";
            $child_output .= "</strong>";
            $child_output .= "</div>";
            if ($text != "") {
            $child_output .= "<div id='checkpoint". $child["id"] ."_details_snip' title='Expand Details' class='checkpoint_details_snip clickable'>";
                $child_output .= substr($text, 0, 30);
                if (strlen($text) > 30) {
                    $child_output .= "...";
                }
            $child_output .= "</div>";
            }
            $child_output .= "<div id='checkpoint". $child["id"] ."_details' class='checkpoint_details'>";
            $child_output .= "<div class='checkpoint_actions'>";
            $child_output .= "<span id='checkpoint". $child["id"] ."_details_hide' class='hideDetailsButton clickable'>";
            $child_output .= "Collapse";
            $child_output .= "</span>";
            $child_output .= "</div>";
            $child_output .= "<div id='checkpoint". $child["id"] ."_details_text' class='checkpoint_details_text'>";
            $child_output .= $text;
            $child_output .= "</div>";
            $child_output .= "</div>";
            $child_output .= "<div class='addCheckpointArea'>";
            $child_output .= "<div id='addCheckpoint". $child["id"] ."_form' class='addCheckpointForm'>";
            $child_output .= "<h3>Add Checkpoint to \"" . $title. "\"</h3>";
            $child_output .= Return_Add_Checkpoint_Form($child["id"], "checkpoint");
            $child_output .= "</div>";
            $child_output .= "</div>";
            $child_output .= Count_Children($child["id"], "checkpoint", " Sub-Checkpoints");  //Inserts its own div section.
            $child_output .= "<div id='checkpoint". $child["id"] ."_children' class='children'>";
            $child_output .= "<span id='checkpoint". $child["id"] ."_children_hide' class='hideChildrenButton clickable'>";
            $child_output .= "Hide Sub-Checkpoints";
            $child_output .= "</span>";
            $child_output .= "<ul id='goal". $child["id"] ."_children_list' class='childrenList' parentid='". $child["id"] ."'>";
            $child_output .= Get_Children($user_id, $child["id"]);
            $child_output .= "</ul>";
            $child_output .= "</div>";
            $child_output .= "</li>";
        }
        return $child_output;
    }
    else {
        return;
    }
}
function Count_Children($id, $id_prefix, $suffix) {
    $children_query = "SELECT * FROM checkpoint WHERE parent=" . $id . " ORDER BY sort ASC";
    $children = query($children_query);
    $output = "";
    
    $output .= "<div id='". $id_prefix . $id ."_children_count' class='childCount clickable'>";
    if ($children != false && num_rows($children) > 0) {
        $output .= "Show ". num_rows($children) . $suffix;
    } else {
        $output .= "Open ". $suffix . " area for sorting";
    }
    $output .= "</div>";
    return $output;
}
?>