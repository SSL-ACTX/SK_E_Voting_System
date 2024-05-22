<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Path to autoload.php of PHPMailer

function sendOTP($email, $otp) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();                                            
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true;
        $mail->Username = 'callixjeira@gmail.com';
        $mail->Password = 'cxbc vwec udzd ejzy';                      
        $mail->SMTPSecure = 'tls';                              
        $mail->Port       = 587; 

        // Recipients
        $mail->setFrom('your-email@example.com', 'SK E-Voting System');
        $mail->addAddress($email);                                

        // Content
        $mail->isHTML(true);                                       
        $mail->Subject = 'OTP for Email Verification';
        $mail->Body    = "Your OTP for email verification is: $otp";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>
