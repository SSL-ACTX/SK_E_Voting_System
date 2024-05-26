<?php
session_start();

header('Content-Type: application/json');

if (isset($_SESSION["username"]) && isset($_SESSION["email"])) {
    $accountInfo = [
        "username" => $_SESSION["username"],
        "email" => $_SESSION["email"],
        "voter_id" => isset($_SESSION["voter_id"]) ? $_SESSION["voter_id"] : null
    ];
    echo json_encode($accountInfo);
} else {
    echo json_encode(["error" => "No session data found."]);
}
?>
