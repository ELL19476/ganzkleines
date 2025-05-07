<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;

$mail = new PHPMailer;

function sendEmail($to, $subject, $body) {
    global $mail;
    //Server settings
    $mail->isSMTP();                                            // Use SMTP
    $mail->Host       = 'smtp.hostinger.com';                   // Set Hostinger SMTP server
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'hallo@ganzkleines.de';            // Your email address
    $mail->Password   = 'Fk!v[rZ$KY8';                  // Your email password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            // Enable implicit SSL encryption
    $mail->Port       = 465;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('hallo@ganzkleines.de', 'Ganz Kleines');
    $mail->addAddress($to);                                   // Add a recipient

    // Content
    $mail->isHTML(true);                                        // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $body;
    $mail->AltBody = strip_tags($body);

    $mail->send();
    return true;
}
?>