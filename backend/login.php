<?php
session_start();

require_once "./conn/db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Check if user exists in the database
    $sql = "SELECT * FROM guest_users WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user_row = $result->fetch_assoc();
        $voter_email = $user_row['email']; 

        $_SESSION["username"] = $username;
        $_SESSION["email"] = $voter_email;

        // Check if the user is a voter
        $voter_check_sql = "SELECT * FROM voters WHERE name='$username'";
        $voter_check_result = $conn->query($voter_check_sql);

        if ($voter_check_result->num_rows > 0) {
            // Get the voter ID
            $voter_row = $voter_check_result->fetch_assoc();
            $voter_id = $voter_row["id"];
            $_SESSION["voter_id"] = $voter_id;
        
            header("Location: voter_dashboard.php#home");
            exit();
        } else {
            header("Location: dashboard.php#homeFir");
            exit();
        }
    } else {
        echo "Invalid username or password";
    }
}

$conn->close();
?>
