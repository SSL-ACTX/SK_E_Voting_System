<?php
session_start();

// Check if user is logged in, if not, redirect to login page
if (!isset($_SESSION["username"])) {
    header("Location: ../index.html");
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include database connection
    require_once "./conn/db_connection.php";

    // Retrieve form data
    $name = $_POST["name"];
    $password = $_POST["password"];
    $age = $_POST["age"];
    $birthday = $_POST["birthday"];
    $citizenship = $_POST["citizenship"];
    $contact_no = $_POST["contact_no"];
    $address = $_POST["address"];
    $occupation = $_POST["occupation"];

    // Check if the user already exists
    $check_query = "SELECT * FROM voters WHERE name = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("s", $name);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        echo "User already exists!";
    } else {
        // Generate voter ID
        $voter_id = "VT_" . uniqid();

        // Insert data into the database
        $sql = "INSERT INTO voters (voter_id, name, password, age, birthday, citizenship, contact_no, address, occupation)
                VALUES ('$voter_id', '$name', '$password', '$age', '$birthday', '$citizenship', '$contact_no', '$address', '$occupation')";

        if ($conn->query($sql) === TRUE) {
            // Delete guest account
            $delete_query = "DELETE FROM guest_users WHERE username = ?";
            $delete_stmt = $conn->prepare($delete_query);
            $delete_stmt->bind_param("s", $_SESSION['username']);
            $delete_stmt->execute();

            echo "Voter registered successfully!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    $delete_stmt->close();
    $conn->close();
}
?>
