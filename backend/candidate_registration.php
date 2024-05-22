<?php
session_start();

// Check if user is logged in, if not, redirect to login page
if (!isset($_SESSION["username"])) {
    header("Location: ../index.html");
    exit();
}

// Include database connection
require_once "./conn/db_connection.php";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST["name"];
    $age = $_POST["age"];
    $residency = $_POST["residency"];
    $citizenship = $_POST["citizenship"];
    $party = $_POST["party_affiliation"];
    $position = $_POST["position"];
    
    // Set the default approval status to 0 (not approved)
    $approval_status = 0;

    $candidate_id = "CD_" . uniqid();

    // Insert data into the database, including the approval status
    $sql = "INSERT INTO candidates (candidate_id, name, age, residency, citizenship, party_affiliation, position, approval_status)
            VALUES ('$candidate_id', '$name', '$age', '$residency', '$citizenship', '$party', '$position', '$approval_status')";

    if ($conn->query($sql) === TRUE) {
        echo "Candidate registered successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
