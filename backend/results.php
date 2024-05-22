<?php
session_start();
error_reporting(E_ERROR | E_PARSE); // Show only errors and parse errors

// Check if user is logged in, if not, redirect to login page
if (!isset($_SESSION["username"])) {
    header("Location: ../index.html");
    exit();
}

// Include database connection
require_once "./conn/db_connection.php";

// Function to calculate total votes
function calculateTotalVotes($conn)
{
    $total_votes_sql = "SELECT COUNT(*) AS total_votes FROM votes";
    $total_votes_result = $conn->query($total_votes_sql);
    $total_votes = $total_votes_result->fetch_assoc()["total_votes"];
    return $total_votes;
}

// Function to retrieve candidates sorted by position and votes
function getCandidatesSorted($conn)
{
    $candidates_sorted_sql = "SELECT candidates.id, candidates.name, candidates.party_affiliation, 
                              candidates.position, COUNT(votes.candidate_id) AS vote_count 
                              FROM candidates 
                              LEFT JOIN votes ON candidates.id = votes.candidate_id 
                              WHERE candidates.approval_status = 1
                              GROUP BY candidates.id 
                              ORDER BY candidates.position ASC, vote_count DESC";
    $candidates_sorted_result = $conn->query($candidates_sorted_sql);
    $candidates_sorted = [];
    while ($row = $candidates_sorted_result->fetch_assoc()) {
        $candidates_sorted[] = $row;
    }
    return $candidates_sorted;
}

// Retrieve the top candidate with the most votes
$top_candidate_sql = "SELECT candidates.id, candidates.name, candidates.party_affiliation, 
                        candidates.position, COUNT(votes.candidate_id) AS vote_count 
                        FROM candidates 
                        LEFT JOIN votes ON candidates.id = votes.candidate_id 
                        WHERE candidates.approval_status = 1
                        GROUP BY candidates.id 
                        ORDER BY vote_count DESC
                        LIMIT 1";
$top_candidate_result = $conn->query($top_candidate_sql);
$top_candidate = $top_candidate_result->fetch_assoc();


// Calculate total votes
$total_votes = calculateTotalVotes($conn);

// Retrieve candidates sorted by position and votes
$candidates_sorted = getCandidatesSorted($conn);

// Check if winners need to be displayed
$status_sql = "SELECT voting_end FROM status";
$status_result = $conn->query($status_sql);
$status = $status_result->fetch_assoc()["voting_end"];

// Initialize winners array
$winners = [];

// Get the candidate with the most votes for each position
foreach ($candidates_sorted as $candidate) {
    if (!isset($winners[$candidate["position"]])) {
        $winners[$candidate["position"]] = $candidate;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SK E-Voting System - Results</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 p-8">
    <h1 class="text-2xl font-bold mb-4">Election Results</h1>

    <!-- Statistics -->
    <div class="grid grid-cols-3 gap-2 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-750">
            <h2 class="text-lg font-semibold mb-2">Total Votes Cast</h2>
            <p class="text-4xl font-bold"><?php echo $total_votes; ?></p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-750">
            <h2 class="text-lg font-semibold mb-2">Most Votes:</h2>
            <p class="text-lg font-semibold"><?php echo $top_candidate["name"]; ?></p>
            <p class="text-sm text-gray-500"><?php echo $top_candidate["party_affiliation"]; ?></p>
            <p class="text-lg font-bold"><?php echo $top_candidate["vote_count"]; ?> votes</p>
        </div>
    </div>

    <!-- Candidate List -->
    <div class="grid grid-cols-2 gap-4 mb-8">
        <div>
            <h2 class="text-xl font-semibold mb-2">Candidates</h2><br>
            <table class="min-w-full divide-y divide-gray-300 bg-white shadow-md overflow-hidden sm:rounded-xl mb-8">
                <thead class="bg-gray-50 border border-gray-750">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Name</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Position</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Party</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Votes</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-300 border border-gray-750">
                    <?php
                    foreach ($candidates_sorted as $candidate) {
                        $arrow_icon = '';
                        // Check if the candidate's position changed
                        if ($candidate['id'] === $winners[$candidate['position']]['id']) {
                            $arrow_icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm-.75-4.75a.75.75 0 0 0 1.5 0V8.66l1.95 2.1a.75.75 0 1 0 1.1-1.02l-3.25-3.5a.75.75 0 0 0-1.1 0L6.2 9.74a.75.75 0 1 0 1.1 1.02l1.95-2.1v4.59Z" clip-rule="evenodd" />
                  </svg>
                  ';
                        }
                        echo "<tr>";
                        echo "<td class='px-6 py-4 whitespace-nowrap'>" . $candidate["name"] . $arrow_icon . "</td>";
                        echo "<td class='px-6 py-4 whitespace-nowrap'>" . $candidate["position"] . "</td>";
                        echo "<td class='px-6 py-4 whitespace-nowrap'>" . $candidate["party_affiliation"] . "</td>";
                        echo "<td class='px-6 py-4 whitespace-nowrap'>" . $candidate["vote_count"] . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php if ($status): ?>
        <!-- Winners Table -->
        <div class="grid grid-cols-2 gap-4 mb-8">
            <div>
                <h2 class="text-xl font-semibold mb-2">Winners</h2><br>
                <table class="min-w-full divide-y divide-gray-300 bg-white shadow-md overflow-hidden sm:rounded-xl mb-8">
                    <thead class="bg-gray-50 border border-gray-750">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Name</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Position</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Party</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Votes</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-300 border border-gray-750">
                        <?php foreach ($winners as $winner): ?>
                            <tr>
                                <td class='px-6 py-4 whitespace-nowrap'><?php echo $winner["name"]; ?></td>
                                <td class='px-6 py-4 whitespace-nowrap'><?php echo $winner["position"]; ?></td>
                                <td class='px-6 py-4 whitespace-nowrap'><?php echo $winner["party_affiliation"]; ?></td>
                                <td class='px-6 py-4 whitespace-nowrap'><?php echo $winner["vote_count"]; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</body>

</html>