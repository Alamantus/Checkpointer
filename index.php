<?php
session_start();
$current_user = isset($_SESSION['user']) ? $_SESSION['user'] : 0;

$action = isset($_GET["action"]) ? $_GET["action"] : false;
$message = isset($_POST["message"]) ? $_POST["message"] : "";
$view_user = isset($_GET["user"]) ? $_GET["user"] : false;

if ($action == "add") {
    if (isset($_POST["title"])) {
        $title = htmlspecialchars($_POST["title"]);
        $text = isset($_POST["text"]) ? htmlspecialchars($_POST["text"]) : "";
        $parent = isset($_POST["parent"]) ? $_POST["parent"] : 0;
        $sort = isset($_POST["sort"]) ? $_POST["sort"] : 0;
        $status = isset($_POST["status"]) ? $_POST["status"] : 1;
        $owner = isset($_SESSION["user"]) ? $_SESSION["user"] : 0;
        
        $insert_sql = "INSERT INTO milestone (title, text, parent, sort, status, owner, created_date) ";
        $insert_sql .= "VALUES ('" . $title . "', '" . $text . "', " . $parent . ", " . $sort . ", " . $status . ", " . $owner . ", " . time() . ");";
        if (query($insert_sql)) {
            echo "New record created successfully";
            header('Location: .');
        } else {
            echo "Error: " . $insert_sql . "<br>" . mysqli_error(connection());
        }
    } else {
        echo "No Title!";
    }
}
elseif ($action == "edit") {
    if (isset($_POST["id"])) {
        $id = $_POST["id"];
        $title = isset($_POST["title"]) ? htmlspecialchars($_POST["title"]) : "";
        $text = isset($_POST["text"]) ? htmlspecialchars($_POST["text"]) : "";
        $sort = isset($_POST["sort"]) ? $_POST["sort"] : 0;
        
        $update_sql = "UPDATE milestone SET title='" . $title . "', text='" . $text . "', sort=" . $sort . " WHERE id=" . $id . ";";
        
        if (query($update_sql)) {
            echo "New record created successfully";
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
}
elseif ($action == "login") {
    if (isset($_POST['name'])) {
        $valid = Validate_User($_POST['name'], $_POST['pw']);
        if ($valid) {
            $_SESSION['user'] = Get_User_Id($_POST['name']);
            //echo "Logged in as " . Get_Username($_SESSION['user']);
            header('Location: .');
        } else {
            echo "Incorrect username/password combination";
            Show_Login_Form($_POST['name']);
        }
    }
    else {
        Show_Login_Form("");
    }
}
elseif ($action == "logout") {
    session_destroy();
    header('Location: .');
}
elseif (!isset($_SESSION['user'])) {
    echo "You are not logged in!<br /><a href='?action=login'>Log in</a> to see milestones.";
}
else {  //just show milestones
?>

<html>
<head>
    <title><?php echo Get_Username($_SESSION["user"]); ?>'s Milestones</title>
</head>
<body>
    <?php
        if ($current_user) {
            Output_User_Milestones($current_user);
        } else {
            echo "You are not logged in.";
        }
    ?>
    <p><a href="?action=logout">Log Out</a></p>
</body>
</html>

<?php
} //end else to show milestones

function Show_Login_Form ($name) {
    $login_form = '<h3>Log In</h3>';
    $login_form .= '<form method="post" action=".?action=login">';
    $login_form .= 'Username:<br>';
    $login_form .= '<input type="text" name="name" value="' . $name . '" onclick="this.select()" length="29">';
    $login_form .= '<br>';
    $login_form .= 'Password:<br>';
    $login_form .= '<input type="password" name="pw" value="" onclick="this.select()">';
    $login_form .= '<br><br>';
    $login_form .= '<input type="submit" value="Submit">';
    $login_form .= '</form>';
    
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
/*function Output_All_Milestones () {
    //Select 
    $milestones_query = "SELECT * FROM milestone WHERE parent=0 ORDER BY sort ASC";
    $milestones = query($milestones_query);
    $milestones_output = "";

    if ($milestones != false && num_rows($milestones) > 0) {
        echo "<ul>";
        // output data of each milestone as a list item
        while($milestone = fetch_assoc($milestones)) {
            $milestones_output .= "<li><strong>" . $milestone["title"]. "</strong><br />" . $milestone["text"]. "<br />User: " . Get_Username($milestone["owner"]) . "<br />";
            $milestones_output .= "<hr>" . Return_Add_Milestone_Form($milestone["id"]) . "<ul>";
            $milestones_output .= Get_Children($milestone["id"]);
            $milestones_output .= "</ul></li>";
        }
        echo $milestones_output . "</ul>";
        return;
    } else {
        echo "0 milestones";
        return;
    }
}
*/

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

///////////////////////////////////////////////////////////
//Replacements and Ailases
function connection() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "checkpointer";

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    return $conn;
}
function query ($query) {
    return mysqli_query(connection(), $query);
}
function num_rows ($query_results) {
    return mysqli_num_rows($query_results);
}
function fetch_assoc ($query_results) {
    return mysqli_fetch_assoc($query_results);
}
?>