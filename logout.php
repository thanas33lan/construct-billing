<?php
// Initialize the session.
// If you are using session_name("something"), don't forget it now!
session_start();
ob_start();
include('./includes/MysqliDb.php');
    
// Unset all of the session variables.
$_SESSION = array();

// Finally, destroy the session.
session_destroy();
header("location:login.php");
?>
