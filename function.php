<?php
require_once __DIR__.'/connection.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
//require_once __DIR__.'/plugins/phpmailer2/vendor/autoload.php';
require_once(__DIR__."/plugins/phpmailer2/src/Exception.php");
require_once(__DIR__."/plugins/phpmailer2/src/PHPMailer.php");
require_once(__DIR__."/plugins/phpmailer2/src/SMTP.php");
require_once(__DIR__."/plugins/phpmailer2/language/phpmailer.lang-tr.php");

function mail_send($send_email, $subject, $message)
{
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->setLanguage('tr', __DIR__ . '"/plugins/phpmailer2/language/');
//        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
        $mail->isSMTP();                                            // Send using SMTP
        $mail->CharSet = "utf-8";
        $mail->Host = 'smtp.gmail.com';                       // Set the SMTP server to send through
        $mail->SMTPAuth = true;                                   // Enable SMTP authentication
        $mail->Username = 'isikkulupportali@gmail.com';           // SMTP username
        $mail->Password = 'bffsllokaiwfspwr';                     // SMTP password (remove spaces if any)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption
        $mail->Port = 587;                                    // TCP port to connect to

        // Recipients
        $mail->setFrom('isikkulupportali@gmail.com', 'Işık Kulüp Portalı');
        $mail->addAddress(trim($send_email), trim($send_email));          // Add a recipient

        // Content
        $mail->isHTML(true);                                        // Set email format to HTML
        $mail->Subject = mb_encode_mimeheader("$subject", "UTF-8");
        $mail->Body = $message;

        $mail->send();

        return true;
    } catch (Exception $e) {
        return false;
    }
}