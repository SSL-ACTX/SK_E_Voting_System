<?php
session_start();

require_once "./conn/db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["username"];
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Prepared statement for guest users
    $stmt = $conn->prepare("SELECT * FROM guest_users WHERE username=? AND password=?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $guest_result = $stmt->get_result();

    // Prepared statement for voters
    $stmt = $conn->prepare("SELECT * FROM voters WHERE name=? AND password=?");
    $stmt->bind_param("ss", $name, $password);
    $stmt->execute();
    $voter_result = $stmt->get_result();

    // Check if the user exists in either guest_users or voters
    if ($guest_result->num_rows > 0) {
        $user_row = $guest_result->fetch_assoc();
        $voter_email = $user_row['email'];

        $_SESSION["username"] = $username;
        $_SESSION["email"] = $voter_email;

        // Check if the user is a voter
        $voter_check_stmt = $conn->prepare("SELECT * FROM voters WHERE name=?");
        $voter_check_stmt->bind_param("s", $username);
        $voter_check_stmt->execute();
        $voter_check_result = $voter_check_stmt->get_result();

        if ($voter_check_result->num_rows > 0) {
            $voter_row = $voter_check_result->fetch_assoc();
            $voter_id = $voter_row["id"];
            $v_id = $voter_row["voter_id"];
            $_SESSION["voter_id"] = $voter_id;
            $_SESSION["v_id"] = $v_id;

            header("Location: voter_dashboard.php#home");
            exit();
        } else {
            header("Location: dashboard.php#homeFir");
            exit();
        }
    } elseif ($voter_result->num_rows > 0) {
        $user_row = $voter_result->fetch_assoc();
        $voter_email = $user_row['email'];

        $_SESSION["username"] = $username;
        $_SESSION["email"] = $voter_email;
        $voter_id = $user_row["id"];
        $_SESSION["voter_id"] = $voter_id;

        header("Location: voter_dashboard.php#home");
        exit();
    } else {
        echo "Invalid username or password";
    }

    // Close prepared statements and result sets
    $stmt->close();
    $guest_result->close();
    $voter_result->close();
}

$conn->close();
?>
