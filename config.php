<?php
function connection() {
    $servername = "localhost";
    $username = "";
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

define("SITE_NAME", "See.All.My Dreams");
?>