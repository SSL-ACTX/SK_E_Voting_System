<?php
session_start();
error_reporting(E_ERROR | E_PARSE);

if (!isset($_SESSION["username"])) {
    header("Location: ../index.html");
    exit();
}

require_once "./conn/db_connection.php";

function calculateTotalVotes($conn)
{
    $total_votes_sql = "SELECT COUNT(*) AS total_votes FROM votes";
    $total_votes_result = $conn->query($total_votes_sql);
    $total_votes = $total_votes_result->fetch_assoc()["total_votes"];
    return $total_votes;
}

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


$total_votes = calculateTotalVotes($conn);
$candidates_sorted = getCandidatesSorted($conn);

$status_sql = "SELECT voting_end FROM status";
$status_result = $conn->query($status_sql);
$status = $status_result->fetch_assoc()["voting_end"];

$winners = [];

foreach ($candidates_sorted as $candidate) {
    if (!isset($winners[$candidate["position"]])) {
        $winners[$candidate["position"]] = $candidate;
    }
}

$sql = "SELECT COUNT(voter_id) as user_count FROM voters";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_count = $row['user_count'];
} else {
    $user_count = 0;
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SK E-Voting System - Results</title>
    <link rel="stylesheet" href="../frontend/css/results.css">
    <link rel="stylesheet" href="../frontend/css/output.css">
</head>

<body class="bg-gray-400 p-8">
    <h1 class="text-2xl font-bold mb-4 text-black">Election Results</h1>

    <!-- Statistics -->
    <div class="grid grid-cols-3 gap-2 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-750 totVotes">
            <h2 class="text-sm uppercase text-gray-700 leading-tight">Total Votes Cast</h2>
            <p class="text-4xl font-bold text-black"><?php echo $total_votes; ?></p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-750 mostVotes">
            <h2 class="text-md uppercase text-gray-700 leading-tight">Most Votes:</h2>
            <p class="text-lg font-semibold text-black"><?php echo $top_candidate["name"]; ?></p>
            <p class="text-sm text-gray-500"><?php echo $top_candidate["party_affiliation"]; ?></p>
            <p class="text-lg font-bold text-black"><?php echo $top_candidate["vote_count"]; ?> votes</p>
        </div>
        <div class="rounded-lg bg-white shadow-md md:shadow-mdrelative overflow-hidden border border-gray-750">
            <div class="px-3 pt-8 pb-10 text-center relative z-10">
                <h4 class="text-sm uppercase text-gray-700 leading-tight">Users</h4>
                <h3 id="user-count" class="text-3xl text-gray-700 font-semibold leading-tight my-3">
                    <?php echo $user_count; ?></h3>
            </div>
        </div>
    </div>

    <!-- Candidate and Winners Tables -->
    <div class="grid grid-cols-1 gap-4 mb-8 md:grid-cols-2">
        <div>
            <h2 class="text-xl font-semibold mb-2 text-black">Candidates</h2><br>
            <table class="w-full divide-y divide-gray-300 bg-white shadow-md overflow-hidden sm:rounded-xl mb-8">
                <thead class="bg-gray-800 border border-gray-750">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                            Name</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                            Position</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                            Party</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
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
                        echo "<tr class='text-black'>";
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
        <?php if ($status): ?>
            <div>
                <h2 class="text-xl font-semibold mb-2 text-black">Winners</h2><br>
                <table class="w-full divide-y divide-gray-300 bg-white shadow-md overflow-hidden sm:rounded-xl mb-8">
                    <thead class="bg-gray-800 border border-gray-750">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                                Name</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                                Position</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                                Party</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                                Votes</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-300 border border-gray-750">
                        <?php foreach ($winners as $winner): ?>
                            <tr class="text-black">
                                <td class='px-6 py-4 whitespace-nowrap'><?php echo $winner["name"]; ?></td>
                                <td class='px-6 py-4 whitespace-nowrap'><?php echo $winner["position"]; ?></td>
                                <td class='px-6 py-4 whitespace-nowrap'><?php echo $winner["party_affiliation"]; ?></td>
                                <td class='px-6 py-4 whitespace-nowrap'><?php echo $winner["vote_count"]; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

</body>

</html>