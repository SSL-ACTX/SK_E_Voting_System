<?php
session_start();
session_start();

require_once "./conn/db_connection.php";
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
    $name = $_POST["username"];
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Prepared statement for guest users
    $stmt = $conn->prepare("SELECT * FROM guest_users WHERE username=? AND password=?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $guest_result = $stmt->get_result();

    // Prepared statement for voters
    $stmt = $conn->prepare("SELECT * FROM voters WHERE name=? AND password=?");
    $stmt->bind_param("ss", $name, $password);
    $stmt->execute();
    $voter_result = $stmt->get_result();

    // Check if the user exists in either guest_users or voters
    if ($guest_result->num_rows > 0 || $voter_result->num_rows > 0) {
        $user_row = $guest_result->num_rows > 0 ? $guest_result->fetch_assoc() : $voter_result->fetch_assoc();
        $voter_email = $user_row['email'];

        $_SESSION["username"] = $username;
        $_SESSION["email"] = $voter_email;

        if ($guest_result->num_rows > 0) {
            $voter_check_stmt = $conn->prepare("SELECT * FROM voters WHERE name=?");
            $voter_check_stmt->bind_param("s", $username);
            $voter_check_stmt->execute();
            $voter_check_result = $voter_check_stmt->get_result();

            if ($voter_check_result->num_rows > 0) {
                $voter_row = $voter_check_result->fetch_assoc();
                $voter_id = $voter_row["id"];
                $v_id = $voter_row["voter_id"];
                $_SESSION["voter_id"] = $voter_id;
                $_SESSION["v_id"] = $v_id;
            }
        } else {
            $voter_id = $user_row["id"];
            $_SESSION["voter_id"] = $voter_id;
        }

        $verification_data = read_verification_data($json_file);

        if (isset($verification_data[$username]) && (time() - $verification_data[$username] < $cooldown_time)) {
            // User is within cooldown period, bypass OTP
            if (isset($_SESSION["voter_id"])) {
                header("Location: voter_dashboard.php#home");
            } else {
                header("Location: dashboard.php#homeFir");
            }
            exit();
        } else {
            // Generate OTP
            $otp = rand(100000, 999999);
            $_SESSION["otp"] = $otp;

            // Send OTP email
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; 
                $mail->SMTPAuth = true;
                $mail->Username = 'callixjeira@gmail.com';
                $mail->Password = 'cxbc vwec udzd ejzy';                      
                $mail->SMTPSecure = 'tls';                              
                $mail->Port       = 587; 
        
                $mail->setFrom('no-reply@example.com', 'SK E-Voting System');
                $mail->addAddress($voter_email);

                $mail->isHTML(true);
                $mail->Subject = 'OTP Verification';
                $mail->Body    = 'Your OTP is: ' . $otp;

                $mail->send();
                header("Location: otp_verification.php");
                exit();
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }
    } else {
        echo "Invalid username or password";
    }

    // Close prepared statements and result sets
    $stmt->close();
    $guest_result->close();
    $voter_result->close();
}

$conn->close();
?>
