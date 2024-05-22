<?php
session_start();

if (!isset($_SESSION["username"]) && !isset($_SESSION["email"])) {
    header("Location: ../index.html");
    exit();
}

$username = $_SESSION["username"];
$voter_email = $_SESSION["email"];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SK E-Voting System - Voter Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="./js/RulesNotif.js" async></script>
    <script src="./js/displayRules.js" async></script>
    <!--<script src="./js/alrVoted.js" async></script>-->
    <script src="./js/voteForm.js" async></script>
    <script src="./js/candStatus.js" async"></script>
    <link rel="stylesheet" href="../frontend/css/vdash.css">
</head>

<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="bg-gray-800 text-gray-100 flex-shrink-0 w-64 z-50 sidebar">
            <div class="p-4">
                <!-- Logo -->
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b2/Sangguniang_Kabataan_logo.svg/150px-Sangguniang_Kabataan_logo.svg.png"
                    alt="Logo" class="w-32 mx-auto mb-4">
                <!-- Navigation Links -->
                <ul>
                    <li class="mb-2">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205 3 1m1.5.5-1.5-.5M6.75 7.364V3h-3v18m3-13.636 10.5-3.819" />
                            </svg>

                            <a href="#home" onclick="loadPage('../frontend/home.html')"
                                class="block px-4 py-2 rounded-md hover:bg-gray-700 flex-grow">Home</a>
                        </div>
                    </li>
                    <li class="mb-2">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                            </svg>
                            <a href="#vote" onclick="loadPage('../frontend/vote.html')"
                                class="block px-4 py-2 rounded-md hover:bg-gray-700 flex-grow votec">Vote</a>
                        </div>
                    </li>
                    <li class="mb-2">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z" />
                            </svg>
                            <a href="#candidacy" onclick="loadPage('../frontend/candidateReg.html')"
                                class="block px-4 py-2 rounded-md hover:bg-gray-700 flex-grow">Candidacy</a>
                        </div>
                    </li>
                    <li class="mb-2">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                            </svg>
                            <a href="#candidate-list" onclick="loadPage('candidate_list.php')"
                                class="block px-4 py-2 rounded-md hover:bg-gray-700 flex-grow">Candidate List</a>
                        </div>
                    </li>
                    <li class="mb-2">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                            </svg>
                            <a href="#results" onclick="loadPage('results.php')"
                                class="block px-4 py-2 rounded-md hover:bg-gray-700 flex-grow">Results</a>
                        </div>
                    </li>
                    <li class="mb-2">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                            </svg>
                            <a href="./logout.php"
                                class="block px-4 py-2 rounded-md hover:bg-gray-700 flex-grow">Logout</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <div class="z-50 shadow-lg">
            <button onclick="toggleSidebar()"
                class="block md:hidden bg-gray-800 text-gray-100 p-2 rounded-md absolute top-4 right-4 sidebar-toggle">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7">
                    </path>
                </svg>
            </button>
        </div>

        <div class="flex-grow p-6 content-container">
            <div id="content"></div>
        </div>

    </div>
    <script>
        function loadPage(page) {
            $.ajax({
                url: page,
                type: 'GET',
                success: function (data) {
                    $('#content').html(data);
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }

        $(document).ready(function () {
            loadPage('../frontend/home.html');
        });

        // Function to toggle sidebar
        function toggleSidebar() {
            $('.sidebar').toggleClass('active');
        }
    </script>
</body>

</html>