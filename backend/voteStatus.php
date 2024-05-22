<?php
require_once "./conn/db_connection.php";

$sql = "SELECT voting FROM status LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo $row["voting"];
} else {
    echo "0"; // Default to closed if no status is found
}
$conn->close();
?>
