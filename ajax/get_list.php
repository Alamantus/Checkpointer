<?php
require_once('../config.php');
require_once('../funct.php');
require_once('../outputs.php');

echo Output_Checkpoints_Recursive($_GET["id"]);
?>