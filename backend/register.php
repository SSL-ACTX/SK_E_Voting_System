<?php
require_once "./conn/db_connection.php";

header('Content-Type: application/json');

// Form submission handling
$response = ["success" => false, "message" => ""];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Basic validation
    if (empty($username) || empty($email) || empty($password)) {
        $response["message"] = "All fields are required.";
        echo json_encode($response);
        exit();
    }

    // Check if username or email already exists
    $check_sql = "SELECT * FROM guest_users WHERE username='$username' OR email='$email'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        // Username or email already exists
        $response["message"] = "Username or email already exists. Please choose a different one.";
        echo json_encode($response);
        exit();
    }

    // Insert user into the database
    $sql = "INSERT INTO guest_users (username, email, password) VALUES ('$username', '$email', '$password')";

    if ($conn->query($sql) === TRUE) {
        $response["success"] = true;
        $response["message"] = "Registration successful!";
    } else {
        $response["message"] = "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    $response["message"] = "Invalid request method.";
}

echo json_encode($response);
$conn->close();
?>
