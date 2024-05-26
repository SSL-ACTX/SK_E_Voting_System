<?php

// Retrieve IP address from POST request
$data = json_decode(file_get_contents("php://input"));

// Validate and sanitize IP address
$ipAddress = filter_var($data->ip, FILTER_VALIDATE_IP);

if ($ipAddress) {
    // Write IP address to JSON file
    $jsonFilePath = './json/ip_address.json';
    $jsonData = json_encode(['ip' => $ipAddress], JSON_PRETTY_PRINT);

    if (file_put_contents($jsonFilePath, $jsonData) !== false) {
        echo 'IP Address saved successfully';
    } else {
        http_response_code(500);
        echo 'Error saving IP address to file';
    }
} else {
    http_response_code(400);
    echo 'Invalid IP address';
}
?>
