<?php
// Include database connection
require_once "./conn/db_connection.php";

// Fetch and display candidate list
$positions_sql = "SELECT DISTINCT position FROM candidates";
$positions_result = $conn->query($positions_sql);

// Predefined order for the positions
$position_order = [
    "SK Chairperson",
    "SK Secretary",
    "SK Treasurer",
    "SK Auditor",
    "SK Kagawad"
];

$positions = [];

if ($positions_result->num_rows > 0) {
    // Store positions in an array
    while ($position_row = $positions_result->fetch_assoc()) {
        $positions[] = $position_row["position"];
    }

    // Sort positions based on the predefined order
    usort($positions, function($a, $b) use ($position_order) {
        $pos_a = array_search($a, $position_order);
        $pos_b = array_search($b, $position_order);

        if ($pos_a === false) $pos_a = count($position_order);
        if ($pos_b === false) $pos_b = count($position_order);

        return $pos_a - $pos_b;
    });

    // Loop through each position in the sorted order
    ob_start(); // Start output buffering
    echo '<div class="grid grid-cols-1 md:grid-cols-2 gap-8">'; // Start the grid container
    foreach ($positions as $position) {
?>
        <!-- Table for candidates running for <?php echo $position; ?> -->
        <div class="bg-white p-4 shadow-lg rounded-lg">
            <h2 class="text-xl font-semibold mb-2 text-black"><?php echo $position; ?></h2>
            <table class="min-w-full divide-y divide-gray-300 bg-white shadow overflow-hidden sm:rounded-lg border border-gray-700">
                <thead class="bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Age</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Party</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-300">
                    <?php
                    // Retrieve approved candidates for the current position
                    $candidates_sql = "SELECT * FROM candidates WHERE position = '$position' AND approval_status = 1";
                    $candidates_result = $conn->query($candidates_sql);

                    if ($candidates_result->num_rows > 0) {
                        while ($row = $candidates_result->fetch_assoc()) {
                            echo "<tr class='text-black'>";
                            echo "<td class='px-6 py-4 whitespace-nowrap'>" . $row["name"] . "</td>";
                            echo "<td class='px-6 py-4 whitespace-nowrap'>" . $row["age"] . "</td>";
                            echo "<td class='px-6 py-4 whitespace-nowrap'>" . $row["party_affiliation"] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td class='px-6 py-4 whitespace-nowrap' colspan='3'>No candidates available</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
<?php
    }
    echo '</div>'; // End the grid container
    $output = ob_get_clean(); // Get the buffered output
    echo $output; // Output the candidate list
} else {
    echo "<p>No positions available</p>";
}
?>
