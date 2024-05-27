<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: ../index.html");
    exit();
}

require_once "./conn/db_connection.php";
?>

<!DOCTYPE html>
<html lang="en" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SK E-Voting System - Candidate List</title>
    <link href="../frontend/css/output.css" rel="stylesheet">
</head>

<body class="bg-gray-100 p-8">
    <h1 class="text-2xl font-bold mb-4 text-black">Candidate List</h1>
    <hr><br>

    <div id="candidateListContainer">

    </div>

    <script>
        // Function to load candidate list
        function loadCandidateList() {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', './CdListLoader.php', true);
            xhr.onreadystatechange = function () {
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

        // Check if current URL has #candidate-list
        if (window.location.hash === '#candidate-list') {
            // Load candidate list
            loadCandidateList();
            // Set interval to reload candidate list every 6 seconds
            setInterval(loadCandidateList, 6000);
        }
    </script>

</body>

</html>