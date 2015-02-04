<?php
function query ($query) {
    return mysqli_query(connection(), $query);
}
function num_rows ($query_results) {
    return mysqli_num_rows($query_results);
}
function fetch_assoc ($query_results) {
    return mysqli_fetch_assoc($query_results);
}

function Show_Login_Form ($name) {
    $login_form = '<div id="loginForm"><h3>Log In</h3>';
    $login_form .= '<form method="post" action=".?action=login">';
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
    $milestone_form = '<form method="post" action=".?action=add">';
    $milestone_form .= 'Title:<br>';
    $milestone_form .= '<input type="text" name="title" value="" length="199">';
    $milestone_form .= '<br>';
    $milestone_form .= 'Details:<br>';
    $milestone_form .= '<textarea name="text"></textarea>';
    $milestone_form .= '<br>';
    $milestone_form .= 'Sort Order:<br>';
    $milestone_form .= '<input type="text" name="sort" value="0" length="3">';
    $milestone_form .= '<br><input type="hidden" name="parent" value="' . $parent_id .'"><br>';
    $milestone_form .= '<input type="submit" value="Submit">';
    $milestone_form .= '</form>';
    
    return $milestone_form;
}
function Return_Edit_Milestone_Form ($id, $title, $text, $sort) {
    $milestone_form = '<form method="post" action=".?action=edit">';
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
        echo "<ul>";
        // output data of each milestone as a list item
        while($milestone = fetch_assoc($milestones)) {
            $milestones_output .= "<li><strong title='Created ". date("l, F j, Y \a\\t g:i a",$milestone["created_date"]) ."'>" . $milestone["title"]. "</strong><span style='margin-left:50px;font-size:10px;'><a href='?action=edit&id=". $milestone["id"] ."'>Edit</a></span><br />" . $milestone["text"]. "<br />User: " . Get_Username($milestone["owner"]) . "<br />";
            $milestones_output .= "<hr><strong>Add Milestone Below \"" . $milestone["title"]. "\"</strong><br />" . Return_Add_Milestone_Form($milestone["id"]) . "<ul>";
            $milestones_output .= Get_Children($milestone["id"]);
            $milestones_output .= "</ul></li>";
        }
        echo $milestones_output . "</ul><strong>Add New Milestone</strong><br />" . Return_Add_Milestone_Form(0);
        return;
    } else {
        echo "<strong>Add New Milestone</strong><br />" . Return_Add_Milestone_Form(0);
        return;
    }
}
function Get_Children($id) {
    $children_query = "SELECT * FROM milestone WHERE parent=" . $id . " ORDER BY sort ASC";
    $children = query($children_query);
    $child_output = "";
    
    if ($children != false && num_rows($children) > 0) {
        // output data of each milestone as a list item
        while($child = fetch_assoc($children)) {
            $child_output .= "<li><strong title='Created ". date("l, F j, Y \a\\t g:i a",$child["created_date"]) ."'>" . $child["title"]. "</strong><span style='margin-left:50px;font-size:10px;'><a href='?action=edit&id=". $child["id"] ."'>Edit</a></span><br />" . $child["text"] . "<br />";
            $child_output .= "<hr><strong>Add Milestone Below \"" . $child["title"]. "\"</strong><br />" . Return_Add_Milestone_Form($child["id"]) . "<ul>";
            $child_output .= Get_Children($child["id"]);
            $child_output .= "</ul></li>";
        }
        return $child_output;
    }
    else {
        return;
    }
}

function Validate_User($name, $password) {
    $hashed_pw = crypt($password, $name);
    $query = "SELECT * FROM user WHERE name='" . $name . "' AND password='" . $hashed_pw . "'";
    $users = query($query);
    
    if (num_rows($users) === 1) {
        return true;
    } else {
        return false;
    }
}
function Get_Username($id) {
    $query = "SELECT name FROM user WHERE id=" . $id;
    $users = query($query);
    
    if (num_rows($users) > 0) {
        if (num_rows($users) === 1) {
            while($user = fetch_assoc($users)) {
                return $user["name"];
            }
        } else {
            return "More than one username returned!";
        }
    } else {
        return "No User";
    }
}
function Get_User_Id($username) {
    $query = "SELECT id FROM user WHERE name='" . $username . "'";
    $users = query($query);
    
    if (num_rows($users) > 0) {
        if (num_rows($users) === 1) {
            while($user = fetch_assoc($users)) {
                return $user["id"];
            }
        } else {
            return "More than one username returned!";
        }
    } else {
        return "No User";
    }
}

?>