<?php
// Database configuration
$dbHost = 'localhost:3325';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'athenaCC';

// Create database connection
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($db->connect_error) {
    die('Connection failed: ' . $db->connect_error);
} else {
    echo "<script>console.log('Succesful connection');</script>";
}
?>
