<?php
session_start();

// Check if user is logged in and is a voter, if not, redirect to login page
if (!isset($_SESSION["username"])) {
    header("Location: ../index.html");
    exit();
}

// Include database connection
require_once "./conn/db_connection.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SK E-Voting System - Candidate List</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-8">
    <h1 class="text-2xl font-bold mb-4">Candidate List</h1><hr><br>

    <!-- Candidate list container -->
    <div id="candidateListContainer">
        <?php include 'CdListLoader.php'; ?>
    </div>

    <script>
        // Function to load candidate list using AJAX
        function loadCandidateList() {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', './CdListLoader.php', true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // Update the candidate list container with new data
                        document.getElementById('candidateListContainer').innerHTML = xhr.responseText;
                        console.log("Candidate list updated -->")
                    } else {
                        console.error('Error loading candidate list');
                    }
                }
            };
            xhr.send();
        }

        // Load candidate list immediately and every 5 seconds
        loadCandidateList();
        setInterval(loadCandidateList, 5000);
    </script>
</body>
</html>
