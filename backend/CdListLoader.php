<?php
// Include database connection
require_once "./conn/db_connection.php";

// Fetch and display candidate list
$positions_sql = "SELECT DISTINCT position FROM candidates";
$positions_result = $conn->query($positions_sql);

if ($positions_result->num_rows > 0) {
    // Loop through each position
    ob_start(); // Start output buffering
    while ($position_row = $positions_result->fetch_assoc()) {
        $position = $position_row["position"];
?>
        <!-- Table for candidates running for <?php echo $position; ?> -->
        <div class="w-full mb-8">
            <h2 class="text-xl font-semibold mb-2"><?php echo $position; ?></h2>
            <table class="min-w-full divide-y divide-gray-200 bg-white shadow-lg overflow-hidden sm:rounded-lg">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Age</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Party</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php
                    // Retrieve approved candidates for the current position
                    $candidates_sql = "SELECT * FROM candidates WHERE position = '$position' AND approval_status = 1";
                    $candidates_result = $conn->query($candidates_sql);

                    if ($candidates_result->num_rows > 0) {
                        while ($row = $candidates_result->fetch_assoc()) {
                            echo "<tr>";
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
    $output = ob_get_clean(); // Get the buffered output
    echo $output; // Output the candidate list
} else {
    echo "<p>No positions available</p>";
}
?>
