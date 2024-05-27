<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: ../index.html");
    exit();
}

require_once './conn/db_connection.php';
require '../vendor/autoload.php'; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$voter_id = $_SESSION["voter_id"];
$voter_email = $_SESSION["email"];
$voted = false;
$notification = "";

// Check if the voter has already voted
$check_vote_sql = "SELECT * FROM votes WHERE voter_id='$voter_id'";
$check_vote_result = $conn->query($check_vote_sql);

if ($check_vote_result->num_rows > 0) {
    $voted = true;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && !$voted) {
    $votes = [];

    foreach ($_POST as $position => $candidate_id) {
        $vote_datetime = date("Y-m-d H:i:s");
        $sql = "INSERT INTO votes (voter_id, candidate_id, position, vote_datetime) 
                VALUES ('$voter_id', '$candidate_id', '$position', '$vote_datetime')";

        if ($conn->query($sql) !== TRUE) {
            $notification = "Error: " . $sql . "<br>" . $conn->error;
            $conn->close();
            exit();
        }

        // Collect vote data for email
        $votes[] = [
            'position' => $position,
            'candidate_id' => $candidate_id
        ];
    }

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
        $mail->Subject = 'Your Voting Receipt';

        $email_body = '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Your Voting Receipt</title>
            <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
            <style>
                .email-container {
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                    background-color: #fff;
                    border-radius: 8px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }
                .vote-list {
                    list-style-type: none;
                    padding: 0;
                }
                .vote-list li {
                    margin-bottom: 10px;
                }
            </style>
        </head>
        <body style="background-color: #f8f9fa; font-family: Arial, sans-serif;">
            <div class="email-container mt-5 p-4">
                <h1 class="text-center text-primary">Your Vote Receipt</h1>
                <p class="text-muted">Thank you for voting. Here are your choices:</p>
                <ul class="vote-list">';
                
        foreach ($votes as $vote) {
            $position = $vote['position'];
            $candidate_id = $vote['candidate_id'];
        
            $candidate_sql = "SELECT name FROM candidates WHERE id='$candidate_id'";
            $candidate_result = $conn->query($candidate_sql);
            $candidate_name = $candidate_result->fetch_assoc()['name'];
        
            $email_body .= "<li><strong>$position:</strong> $candidate_name</li>";
        }
        
        $email_body .= '
                </ul>
                <p class="text-muted">Date and Time of Voting: ' . date("Y-m-d H:i:s") . '</p>
            </div>
            <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        </body>
        </html>';
        
        $mail->Body = $email_body;        

        $mail->send();
        $notification = 'Votes submitted successfully! A receipt has been sent to your email.';
    } catch (Exception $e) {
        $notification = "Votes submitted successfully, but the email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

    $voted = true;
}
?>