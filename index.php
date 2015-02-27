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
elseif (!isset($_SESSION['user'])) {
?>
    <strong>You are not logged in!</strong>
    <!--<p><span id="loginButton" class="clickable">Log in</span> to see checkpoints.</p>
    <div id="loginForm">
        <form name="logIn" method="post" action="?action=login" onsubmit="return validateLogin()">
        Username:<br />
        <span id="nameMessage" class="hidden"><br /></span>
        <input type="text" id="nameInput" name="name" value="" onclick="this.select()" length="29">
        <br />
        Password:<br />
        <span id="pwMessage" class="hidden"><br /></span>
        <input type="password" id="pwInput" name="pw" value="" onclick="this.select()">
        <br /><br />
        <input type="submit" value="Submit">
        </form>
        <span id="cancelLoginButton" class="clickable">Cancel</span>
    </div>-->
<?php
    //include("templates/lorem.php");
}
else {
    if ($current_user) {
        // Update Last Active
        $update_sql = "UPDATE user SET last_active= " . time() . " WHERE id=" . $current_user . ";";
        if (query($update_sql)) {
            //success!
        } else {
            echo "Error: " . $update_sql . "<br>" . mysqli_error(connection());
        }
        
        // And show checkpoints
        Output_User_Checkpoints($current_user);
    } else {
        echo "You are not logged in.";
    }
} //end else to show checkpoints

include('footer.php');
?>