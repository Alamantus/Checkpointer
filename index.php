<?php
require_once('config.php');
require_once('funct.php');
session_start();
$current_user = isset($_SESSION['user']) ? $_SESSION['user'] : 0;

$action = isset($_GET["action"]) ? $_GET["action"] : false;
$message = isset($_POST["message"]) ? $_POST["message"] : "";
$view_user = isset($_GET["user"]) ? $_GET["user"] : false;

include('header.php');

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

<?php
    if ($current_user) {
        Output_User_Milestones($current_user);
    } else {
        echo "You are not logged in.";
    }
?>

<?php
} //end else to show milestones

include('footer.php');
?>