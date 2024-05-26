<?php
$servername = getenv("MYSQL_ADDON_HOST");
$username = getenv("MYSQL_ADDON_USER"); 
$password = getenv("MYSQL_ADDON_PASSWORD"); 
$dbname = getenv("MYSQL_ADDON_DB");

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
