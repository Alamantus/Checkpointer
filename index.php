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
elseif (!isset($_SESSION['user'])) {
    echo "You are not logged in!<br /><a href='?action=login'>Log in</a> to see milestones.";
}
else {  //just show milestones
    if ($current_user) {
        Output_User_Milestones($current_user);
    } else {
        echo "You are not logged in.";
    }
} //end else to show milestones

include('footer.php');
?>