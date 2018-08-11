<?php
session_name('Admin');
session_start(); //Start the current session

include_once('config.php'); //Initiate the MySQL connection


session_destroy(); //Destroy it! So we are logged out now
header("location:../?rdr=home&msg=Successfully-Logged-out"); // Move back to login.php with a logout message
?>