<?php
// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require_once __DIR__.'/plugins/phpmailer2/vendor/autoload.php';

// Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                       // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'isikkulupportali@gmail.com';           // SMTP username
    $mail->Password   = 'bffsllokaiwfspwr';                     // SMTP password (remove spaces if any)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption
    $mail->Port       = 587;                                    // TCP port to connect to

    // Recipients
    $mail->setFrom('isikkulupportali@gmail.com', 'Işık Üniversitesi Kulüp Portalı');
    $mail->addAddress('19YOBI1012@isik.edu.tr', '19YOBI1012@isik.edu.tr');          // Add a recipient
//    $mail->addCC('mervenyilddiz@gmail.com');
    //$mail->addBCC('mervenyilddiz@gmail.com');

    // Content
    $mail->isHTML(true);                                        // Set email format to HTML
    $mail->Subject = 'Merhabalar';
    $mail->Body    = 'Bu bir test mailidir.';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
