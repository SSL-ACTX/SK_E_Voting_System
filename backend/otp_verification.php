<?php
session_start();

require_once "./conn/db_connection.php";

$cooldown_time = 30 * 60; // 30 minutes in seconds
$json_file = 'verification_data.json';

// Function to read verification data from JSON file
function read_verification_data($file) {
    if (file_exists($file)) {
        $data = file_get_contents($file);
        return json_decode($data, true);
    } else {
        return [];
    }
}

// Function to write verification data to JSON file
function write_verification_data($file, $data) {
    file_put_contents($file, json_encode($data));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_otp = $_POST["otp"];
    if ($entered_otp == $_SESSION["otp"]) {
        unset($_SESSION["otp"]);
        
        // Read current verification data
        $verification_data = read_verification_data($json_file);
        
        // Store the current time as last verification time
        $verification_data[$_SESSION["username"]] = time();
        
        // Write the updated verification data back to the JSON file
        write_verification_data($json_file, $verification_data);

        if (isset($_SESSION["voter_id"])) {
            header("Location: voter_dashboard.php#home");
        } else {
            header("Location: dashboard.php#homeFir");
        }
        exit();
    } else {
        echo "Invalid OTP. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>OTP Verification</title>
    <link rel="stylesheet" href="../frontend/css/fonts.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<style>
    body { background-image: url("https://wsrv.nl/?url=https://i.ibb.co/3FxkhJb/wallpapersden-com-bloom-edges-blue-windows-11-3840x2160.jpg&maxage=31d&l=6"); background-position: center; background-repeat: no-repeat; background-size: cover; backdrop-filter: blur(5px); } .FormOTP {  }
</style>
<body class="bg-gray-800 flex items-center justify-center h-screen nsofia">
    <form method="post" class="bg-gray-900 p-8 rounded-xl shadow-md w-full max-w-sm border border-gray-700 FormOTP">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-200">OTP Verification</h2>
        <div class="mb-4">
            <label for="otp" class="block text-gray-300 text-sm font-bold mb-2">Enter OTP:</label>
            <input type="text" id="otp" name="otp" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-300 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        <div class="flex items-center justify-center">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Verify</button>
        </div>
    </form>

    <script src="./js/skInjectLogo.js"></script>
</body>
</html>

