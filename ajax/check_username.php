<?php
require_once('../config.php');
require_once('../funct.php');
require_once('../outputs.php');
if (isset($_POST["username"])) {
    echo Get_User_Id($_POST["username"]);
} else {
    echo "No Username Specified!";
}
?>