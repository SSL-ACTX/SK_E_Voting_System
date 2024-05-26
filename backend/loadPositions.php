<?php
session_start();
require_once "./conn/db_connection.php";

// Fetch positions and candidates
$position_sql = "SELECT DISTINCT position FROM candidates";
$position_result = $conn->query($position_sql);
$html = '';

if ($position_result->num_rows > 0) {
    while ($position_row = $position_result->fetch_assoc()) {
        $position = $position_row["position"];
        $candidate_sql = "SELECT * FROM candidates WHERE position='$position' AND approval_status = 1"; // Update query to include approval_status condition
        $candidate_result = $conn->query($candidate_sql);

        $html .= "<fieldset class='border border-gray-400 rounded-lg p-4 mb-8 bg-gray-100 hover:bg-gray-200'>";
        $html .= "<legend class='text-lg font-semibold mb-4 text-gray-800 nsofia'>$position</legend>";

        if ($candidate_result->num_rows > 0) {
            while ($row = $candidate_result->fetch_assoc()) {
                $candidate_id = $row["id"];
                $candidate_name = $row["name"];
                $party_affiliation = $row["party_affiliation"];
                $html .= "<label class='block mb-4 text-gray-800 flex items-center'><input type='radio' name='$position' value='$candidate_id' class='mr-2 custom-radio'>$candidate_name <span class='ml-2 text-sm text-gray-600'>$party_affiliation</span></label>";
            }
        } else {
            $html .= "<p class='text-gray-500'>No approved candidates available for $position</p>";
        }

        $html .= "</fieldset>";
    }
} else {
    $html .= "<p class='text-gray-500'>No positions available</p>";
}

echo $html;
$conn->close();
?>
