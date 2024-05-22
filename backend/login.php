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
        $voter_email = $user_row['email']; // Fetch the email from the result

        $_SESSION["username"] = $username;
        $_SESSION["email"] = $voter_email;

        // Check if the user is a voter
        $voter_check_sql = "SELECT * FROM voters WHERE name='$username'";
        $voter_check_result = $conn->query($voter_check_sql);

        if ($voter_check_result->num_rows > 0) {
            // Get the voter ID
            $voter_row = $voter_check_result->fetch_assoc();
            $voter_id = $voter_row["id"];
        
            // Store voter_id in the session
            $_SESSION["voter_id"] = $voter_id;
        
            // Redirect to voter dashboard
            header("Location: voter_dashboard.php");
            exit();
        } else {
            // Redirect to dashboard for other users
            header("Location: dashboard.php");
            exit();
        }
    } else {
        echo "Invalid username or password";
    }
}

$conn->close();
?>
