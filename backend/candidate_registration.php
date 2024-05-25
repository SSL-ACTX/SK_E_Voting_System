<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: ../index.html");
    exit();
}

require_once "./conn/db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $age = $_POST["age"];
    $residency = $_POST["residency"];
    $citizenship = $_POST["citizenship"];
    $party = $_POST["party_affiliation"];
    $position = $_POST["position"];
    
    $errors = [];

    if (empty($name)) $errors[] = "Name is required.";
    if (empty($age)) $errors[] = "Age is required.";
    if (empty($residency)) $errors[] = "Residency is required.";
    if (empty($citizenship)) $errors[] = "Citizenship is required.";
    if (empty($party)) $errors[] = "Party affiliation is required.";
    if (empty($position)) $errors[] = "Position is required.";

    if (!empty($errors)) {
        echo implode('<br>', $errors);
    } else {
        $approval_status = 0;
        $candidate_id = "CD_" . uniqid();

        $stmt = $conn->prepare("INSERT INTO candidates (candidate_id, name, age, residency, citizenship, party_affiliation, position, approval_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssissssi", $candidate_id, $name, $age, $residency, $citizenship, $party, $position, $approval_status);

        if ($stmt->execute()) {
            echo "Candidate registered successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    $conn->close();
}
?>
