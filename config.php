<?php

define("SITE_NAME", "Checkpointer");
define("SITE_CATCHPHRASE", "Checkpoints on the way to your goal.");
define("SITE_KEYWORDS", "goals,checkpoint,milestone,checklist,manage");

function connection() {
//    $servername = "localhost";
//    $username = "";
//    $password = "";
//    $dbname = "checkpointer";
//
//    // Create connection
//    $conn = mysqli_connect($servername, $username, $password, $dbname);
//    // Check connection
//    if (!$conn) {
//        die("Connection failed: " . mysqli_connect_error());
//    }

    $sqlite_connection = new PDO('sqlite:checkpointer.db');
    // Set errormode to exceptions
    $sqlite_connection->setAttribute(PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);
    
//    return $conn;
    return $sqlite_connection;
}
?>