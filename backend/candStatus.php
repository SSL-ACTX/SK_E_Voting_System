<?php
require_once "./conn/db_connection.php";

$sql = "SELECT candidacy FROM status LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo $row["candidacy"];
} else {
    echo "0"; 
}

$conn->close();
?>
