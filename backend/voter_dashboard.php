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
<html lang="en" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SK E-Voting System - Voter Dashboard</title>
    <link rel="stylesheet" href="../frontend/css/vdash.css">
    <link rel="stylesheet" href="../frontend/css/output.css">
    <!--<script src="./js/IPChecker.js" async></script>-->
</head>

<body class="bg-gray-100">
    <div class="flex h-screen">
        <div class="bg-gray-800 text-gray-100 flex-shrink-0 w-64 z-50 sidebar"><br>
            <div class="p-4">
                <img src="https://i.ibb.co/rsxxP8b/150px-Sangguniang-Kabataan-logo-svg.png" alt="Logo"
                    class="w-32 mx-auto mb-4">
                <div class="w-full px-2">
                    <div class="flex flex-col items-center w-full mt-3 border-t border-gray-700">
                        <a class="flex items-center w-full h-12 px-3 mt-2 rounded hover:bg-gray-700 hover:text-gray-300"
                            href="#home" onclick="loadPage('../frontend/home.html')">
                            <svg class="w-6 h-6 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <span class="ml-2 text-sm font-medium">Home</span>
                        </a>
                        <a class="flex items-center w-full h-12 px-3 mt-2 rounded hover:bg-gray-700 hover:text-gray-300"
                            href="#vote" onclick="loadPage('../frontend/vote.html')">
                            <svg class="w-6 h-6 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                            </svg>
                            <span class="ml-2 text-sm font-medium">Vote</span>
                        </a>
                        <a class="flex items-center w-full h-12 px-3 mt-2 rounded hover:bg-gray-700 hover:text-gray-300"
                            href="#candidacy" onclick="loadPage('../frontend/candidateReg.html')">
                            <svg class="w-6 h-6 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                            </svg>
                            <span class="ml-2 text-sm font-medium">Candidacy</span>
                        </a>
                        <a class="flex items-center w-full h-12 px-3 mt-2 rounded hover:bg-gray-700 hover:text-gray-300"
                            href="#candidate-list" onclick="loadPage('./candidate_list.php')">
                            <svg class="w-6 h-6 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
                            </svg>
                            <span class="ml-2 text-sm font-medium">Candidate List</span>
                        </a>
                    </div>
                    <div class="flex flex-col items-center w-full mt-2 border-t border-gray-700">
                        <a class="flex items-center w-full h-12 px-3 mt-2 rounded hover:bg-gray-700 hover:text-gray-300"
                            href="#results" onclick="loadPage('./results.php')">
                            <svg class="w-6 h-6 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                            </svg>
                            <span class="ml-2 text-sm font-medium">Results</span>
                        </a>
                        <a class="flex items-center w-full h-12 px-3 mt-2 rounded hover:bg-gray-700 hover:text-gray-300"
                            href="#about" onclick="loadPage('../frontend/about.html')">
                            <svg class="w-6 h-6 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                            </svg>
                            <span class="ml-2 text-sm font-medium">About</span>
                        </a>
                        <a class="relative flex items-center w-full h-12 px-3 mt-2 rounded hover:bg-gray-700 hover:text-gray-300"
                            href="./logout.php">
                            <svg class="w-6 h-6 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                            </svg>
                            <span class="ml-2 text-sm font-medium">Logout</span>
                        </a>
                    </div>

                </div>
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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script async>
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
            var hash = window.location.hash;
            if (hash) {
                var page = hash.substring(1);
                if (page.startsWith('./') && page.endsWith('.php')) {
                    loadPage(page);
                } else {
                    loadPage('../frontend/' + page + '.html');
                }
            } else {
                loadPage('../frontend/home.html');
            }
        });

        function toggleSidebar() {
            $('.sidebar').toggleClass('active');
        }
    </script>
    <script src="./js/RulesNotif.js"></script>
    <script src="./js/displayRules.js"></script>
    <!--<script src="./js/alrVoted.js" async></script>-->
    <script src="./js/voteForm.js"></script>
    <script src="./js/candStatus.js""></script>

</body>

</html>