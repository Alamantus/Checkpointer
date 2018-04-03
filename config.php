<?php

define("SITE_NAME", "Checkpointer");
define("SITE_CATCHPHRASE", "Checkpoints on the way to your goal.");
define("SITE_KEYWORDS", "goals,checkpoint,milestone,checklist,manage");

// Encrypt the data in the backend to hamper data theft by making it unreadable.
define('ENCRYPT_DATA', true);
define('ENCRYPT_KEY', 'Replace With Random 32-Character String');
define('ENCRYPT_IV', 'Replace With Another Random 32-Character String');

define('TIMEZONE', 'UTC');

define('DATABASE_LOCATION', '');

function connection() {
    $sqlite_connection = new PDO('sqlite:' . DATABASE_LOCATION . 'checkpointer.db');
    // Set errormode to exceptions
    $sqlite_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sqlite_connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    return $sqlite_connection;
}
?>