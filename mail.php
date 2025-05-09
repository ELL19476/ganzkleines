<?php
require '/home/u937524310/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;

$mail = new PHPMailer;

function sendEmail($to, $subject, $body) {
    global $mail;
    //Server settings
    $mail->isSMTP();                                            // Use SMTP
    $mail->Host       = 'smtp.hostinger.com';                   // Set Hostinger SMTP server
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'verify@ganzkleines.de';                 // Your email address
    $mail->Password   = 'JA7[bWVaRrQamb&brv$X';                          // Your email password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            // Enable implicit SSL encryption
    $mail->Port       = 465;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('verify@ganzkleines.de', 'Ganz Kleines');
    $mail->addReplyTo('verify@ganzkleines.de', 'Ganz Kleines');
    $mail->addAddress($to);                                   // Add a recipient

    // Content
    $mail->isHTML(true);                                        // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $body;
    $mail->AltBody = strip_tags($body);
    $mail->CharSet = 'UTF-8';

    $mail->send();
    return true;
}
?>