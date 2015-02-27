<?php
require_once('config.php');
require_once('funct.php');
require_once('outputs.php');
session_start();
$current_user = isset($_SESSION['user']) ? $_SESSION['user'] : 0;

$action = isset($_GET["action"]) ? $_GET["action"] : false;
$message = isset($_POST["message"]) ? $_POST["message"] : "";
$view_user = isset($_GET["user"]) ? $_GET["user"] : false;

include('header.php');

if ($action == "about") {
    include_once('templates/about.php');
}
if ($action == "add") {
    include_once('templates/add.php');
}
elseif ($action == "edit") {
    include_once('templates/edit.php');
}
elseif ($action == "login") {
    include_once('templates/login.php');
}
elseif ($action == "logout") {
    include_once('templates/logout.php');
}
elseif ($action == "createaccount") {
    include_once('templates/create_account.php');
}
elseif (!$action) {
    if (!isset($_SESSION['user'])) {
?>
        <strong>You are not logged in!</strong>
<?php
        Show_Login_Form("");
    }
    else {
        // Update Last Active
        $update_sql = "UPDATE user SET last_active= " . time() . " WHERE id=" . $current_user . ";";
        if (query($update_sql)) {
            //success!
        } else {
            echo "Error: " . $update_sql . "<br>" . mysqli_error(connection());
        }
        
        // And show checkpoints
        Output_User_Checkpoints($current_user);
    } //end else to show checkpoints
}

include('footer.php');
?>