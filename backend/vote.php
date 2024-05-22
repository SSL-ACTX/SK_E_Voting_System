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

        $mail->setFrom('no-reply@example.com', 'Voting System');
        $mail->addAddress($voter_email);

        $mail->isHTML(true);
        $mail->Subject = 'Your Voting Receipt';
        $email_body = '<h1>Your Vote Receipt</h1>';
        $email_body .= '<p>Thank you for voting. Here are your choices:</p><ul>';

        foreach ($votes as $vote) {
            $position = $vote['position'];
            $candidate_id = $vote['candidate_id'];

            $candidate_sql = "SELECT name FROM candidates WHERE id='$candidate_id'";
            $candidate_result = $conn->query($candidate_sql);
            $candidate_name = $candidate_result->fetch_assoc()['name'];

            $email_body .= "<li><b>$position:</b> $candidate_name</li>";
        }

        $email_body .= '</ul><p>Date and Time of Voting: ' . date("Y-m-d H:i:s") . '</p>';
        $mail->Body = $email_body;

        $mail->send();
        $notification = 'Votes submitted successfully! A receipt has been sent to your email.';
    } catch (Exception $e) {
        $notification = "Votes submitted successfully, but the email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

    $voted = true;
}
?>