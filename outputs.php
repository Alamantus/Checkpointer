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

function Output_Checkpoints_Recursive($user_id, $parent_id = 0, $public_only = false) {
    $checkpoints_query = "SELECT c.*, s.name AS 'status_name' FROM checkpoint c LEFT JOIN status s ON c.status=s.id WHERE parent=? AND owner=?";
    if ($public_only) {
        $checkpoints_query .= " AND is_public = 1";
    }
    $checkpoints_query .= " ORDER BY sort ASC";
    $checkpoints = query($checkpoints_query, array($parent_id, $user_id));
    $type = $parent_id == 0 ? 'goal' : 'checkpoint';
    $output = "";
    
    if ($checkpoints && count($checkpoints) > 0) {
        if ($parent_id == 0) {
            $output .= "<ul class='root_checkpoints' parentid='$parent_id'>";
        }
        // output data of each checkpoint as a list item
        foreach($checkpoints as $checkpoint) {
            // $output .= '<script>console.log(' . json_encode($checkpoint) . ')</script>';
            $title = $checkpoint['title'];
            $text = $checkpoint['text'];
            if (ENCRYPT_DATA) {
                $title = easy_crypt('decrypt', $title);
                $text = easy_crypt('decrypt', $text);
            }
            $title = htmlspecialchars_decode($title);
            $text = htmlspecialchars_decode($text);
            
            $statuses = getStatuses();
            
            $output .= "<li class='" . $type . "' cpid='". $checkpoint["id"] ."'>";
            $output .= "<div id='" . $type . $checkpoint["id"] ."' class='" . $type . "_title'>";
            if (!$public_only) {
            $output .= "<span class='handle'>&#8645;</span>";
                $output .= "<select id='" . $type . $checkpoint["id"] ."_status' class='" . $type . "_status'>";
                if ($statuses && count($statuses) > 0) {
                    foreach($statuses as $status) {
                        $output .= "<option value='". $status["id"] ."' title='". $status["name"] ."'";
                        if ($checkpoint["status"] == $status["id"]) {
                            $output .= " selected='selected'";
                        }
                        $output .= ">". $status["html_display"] ."</option>";
                    }
                }
            } else {
                if ($statuses != false && count($statuses) > 0) {
                    foreach($statuses as $status) {
                        if ($checkpoint["status"] == $status["id"])
                            $output .= "<div class='public_status' title='". $status["name"] ."' value='". $status["id"] ."'>". $status["html_display"] ."</div>";
                    }
                }
            }
            $output .= "</select>";
            $output .= "<strong class='title' title='Created ". date("l, F j, Y \a\\t g:i a",$checkpoint["created_date"]) ."'>";
            $output .= $title;
            $output .= "</strong>";
            if (!$public_only && $checkpoint["status"] != 2 && $checkpoint["status"] != 3) {
                $output .= "<strong id='" . $type . $checkpoint["id"] ."_edit' class='editButton clickable' user='". $user_id . "'";
                if ($parent_id == 0) {
                    $output .= " privacy='". $checkpoint["is_public"] ."'";
                }
                $output .= ">Edit</strong>";
                $output .= "<strong class='addCheckpointButton clickable' title='Add Checkpoint to \"" . $title. "\"' id='addCheckpoint". $checkpoint["id"] ."'>";
                $output .= "+";
                $output .= "</strong>";
            }
            $output .= "</div>";
            $output .= "<div id='" . $type . $checkpoint["id"] ."_details' class='" . $type . "_details'";
            if ($text == '') {
                $output .= ' style="display:none;"';
            }
            $output .= ">";
            $output .= "<div id='" . $type . $checkpoint["id"] ."_details_text' class='" . $type . "_details_text'>";
            $output .= $text;
            $output .= "</div>";
            $output .= "</div>";
            if (!$public_only) {
                $output .= "<div class='addCheckpointArea'>";
                $output .= "<div id='addCheckpoint". $checkpoint["id"] ."_form' class='addCheckpointForm' style='display:none;'>";
                $output .= "<h3>Add Checkpoint to \"" . $title. "\"</h3>";
                $output .= Return_Add_Checkpoint_Form($checkpoint["id"], $type);
                $output .= "</div>";
                $output .= "</div>";
            }
            $count_suffix = " " . ($parent_id == 0 ? '' : 'Sub-') . "Checkpoints";
            $count_children = Count_Children($checkpoint["id"], $type, $count_suffix, $public_only);  //Inserts its own div section.
            $output .= $count_children;
            $children_id = $type . $checkpoint["id"] . '_children';
            $output .= "<div id='" . $children_id . "' class='children'";
            if (!isset($_COOKIE[$children_id]) || $checkpoint["status"] == 2 || $checkpoint["status"] == 3) {
                $output .= " style='display:none;'";
            }
            $output .= ">";
            $output .= "<span id='" . $type . $checkpoint["id"] ."_children_hide' class='hideChildrenButton clickable'>";
            $output .= "Hide" . $count_suffix;
            $output .= "</span>";
            $output .= "<ul id='" . $type . $checkpoint["id"] ."_children_list' class='childrenList' parentid='". $checkpoint["id"] ."'>";
            $output .= Output_Checkpoints_Recursive($user_id, $checkpoint["id"], $public_only);
            $output .= "</ul>";
            $output .= "</div>";
            $output .= "</li>";
        }
        if ($parent_id == 0) {
            $output .= "</ul>";
        }
    }
    else {
        if ($parent_id == 0) {
            $output = "<div class='infoPage'><strong>No goals yet!</strong>";
            if (!$public_only) {
                $output .= "<p>Add a new goal by clicking \"New Goal\" in the header and get started!";
            }
            $output .= "</div>";
        }
    }
    return $output;
}
function Count_Children($id, $type, $suffix, $public_only = false) {
    $children_id = $type . $id . '_children';
    $children_query = "SELECT * FROM checkpoint WHERE parent=? ORDER BY sort ASC";
    $children = query($children_query, array($id));
    $output = "";
    
    if (!$public_only) {
        $has_children = $children != false && count($children) > 0;
        $should_hide = isset($_COOKIE[$children_id]);
        $output .= "<div id='". $children_id . "_count' class='childCount clickable'";
        if ($has_children && $should_hide) {
            $output .= " style='display:none;'";
        }
        $output .= ">";
        if ($has_children) {
            $output .= "Show ". count($children) . $suffix;
        } else {
            $output .= "Open ". $suffix . " area for sorting";
        }
        $output .= "</div>";
    } else {
        if ($children != false && count($children) > 0) {
            $output .= "<div id='". $children_id . "_count' class='childCount clickable'>";
            $output .= "Show ". count($children) . $suffix;
            $output .= "</div>";
        } else {
            $output .= '<script>$("#'. $type . $id .'_details, #'. $type . $id .'_details_snip").addClass("noborder");</script>';
        }
    }
    return $output;
}
?>