<?php
session_start();
require_once "./conn/db_connection.php"; // Include your database configuration

// Check if user is logged in and get their voter ID (assuming it's stored in session)
if (isset($_SESSION['voter_id'])) {
    $voter_id = $_SESSION['voter_id'];

    // Check if the voter has already voted
    $check_vote_sql = "SELECT * FROM votes WHERE voter_id='$voter_id'";
    $check_vote_result = $conn->query($check_vote_sql);

    if ($check_vote_result->num_rows > 0) {
        // User has already voted
        echo json_encode(array("voted" => true));
    } else {
        // User has not voted
        echo json_encode(array("voted" => false));
    }
} else {
    // User is not logged in
    echo json_encode(array("error" => "User not logged in"));
}
?>
