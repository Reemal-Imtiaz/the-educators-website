<?php
/**
 * PHPMailer se Gmail SMTP ke zariye email bhejane ki script.
 * Ye script aapke form data ko reemalimtiaz@gmail.com par bhejegi.
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// PHPMailer files ko include karein (File paths ko zarur check karein)
require 'phpmailer/Exception.php';
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';

// Form submit check karein
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    // Agar direct access hua hai toh wapis bhej dein
    header('Location: contact.html');
    exit;
}

// SMTP Credentials jo aapne diye hain
$smtp_host = "smtp.gmail.com";
$smtp_port = 587;
$smtp_user = "reemalimtiaz@gmail.com";
$smtp_pass = "pvxnekwljsbuvivh"; // Yeh App Password hona chahiye

// Woh email address jahan mail jayegi
$receiving_email_address = 'reemalimtiaz@gmail.com';

// Form Data variables
$name = filter_var($_POST['name'] ?? 'N/A', FILTER_SANITIZE_STRING);
$email = filter_var($_POST['email'] ?? 'N/A', FILTER_SANITIZE_EMAIL);
$subject = filter_var($_POST['subject'] ?? 'No Subject', FILTER_SANITIZE_STRING);
$message = filter_var($_POST['message'] ?? 'Empty Message', FILTER_SANITIZE_STRING);

// Email ka content
$email_subject = "The Educators Contact Form: " . $subject;
$email_body_html = "
    <h2>You got a New message (The Educators - Gulzar Campus)</h2>
    <p>You got a new new inquiry message.</p>
    <table style='border: 1px solid #ccc; padding: 10px;'>
        <tr>
            <td style='font-weight: bold;'>Naam:</td>
            <td>{$name}</td>
        </tr>
        <tr>
            <td style='font-weight: bold;'>Email:</td>
            <td>{$email}</td>
        </tr>
        <tr>
            <td style='font-weight: bold;'>Subject:</td>
            <td>{$subject}</td>
        </tr>
    </table>
    <h3 style='margin-top: 20px;'>Message:</h3>
    <p style='border: 1px solid #eee; padding: 10px; white-space: pre-wrap;'>{$message}</p>
";


$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();                                            // SMTP use karein
    $mail->Host       = $smtp_host;                             // Specify main SMTP server
    $mail->SMTPAuth   = true;                                   // SMTP authentication zaroori hai
    $mail->Username   = $smtp_user;                             // SMTP username (Gmail email)
    $mail->Password   = $smtp_pass;                             // SMTP password (Gmail App Password)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // TLS encryption zaroori hai, port 587 ke liye
    $mail->Port       = $smtp_port;                             // TCP port to connect to

    // Recipients
    $mail->setFrom($smtp_user, 'The Educators Contact Form'); // Kis ki taraf se mail jayegi
    $mail->addAddress($receiving_email_address);               // Admin ka receiving email

    // Content
    $mail->isHTML(true);                                      // Email format HTML set karein
    $mail->Subject = $email_subject;
    $mail->Body    = $email_body_html;
    $mail->AltBody = strip_tags($email_body_html);            // Non-HTML mail clients ke liye

    $mail->send();
    
    // Mail successfully bheja gaya
    header('Location: contact.html?mail_sent=1#contact');
    exit;
    
} catch (Exception $e) {
    // Error aane par
    // Error message ko debug karne ke liye use karein, production par disable kar dein
    // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    header('Location: contact.html?mail_sent=0#contact');
    exit;
}
?>