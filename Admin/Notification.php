<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer
require __DIR__ . '/PHPMailer-6.9.3/src/PHPMailer.php';
require __DIR__ . '/PHPMailer-6.9.3/src/Exception.php';
require __DIR__ . '/PHPMailer-6.9.3/src/SMTP.php';

function sendEmailNotification($toEmail, $subject, $message) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'insuredgeinsurance@gmail.com'; // Your email
        $mail->Password = 'InsurEdge123'; // Use App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('insuredgeinsurance@gmail.com', 'InsurEdge Insurance');
        $mail->addAddress($toEmail);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        // Send email
        if ($mail->send()) {
            return true; // Email sent successfully
        } else {
            return false; // Email not sent
        }
    } catch (Exception $e) {
        // Catch errors
        echo "Mailer Error: " . $mail->ErrorInfo; // This will display the error
        return false; // Email failed to send
    }
}

function logError($error) {
    file_put_contents('error_log.txt', date('Y-m-d H:i:s') . " - $error\n", FILE_APPEND);
}
?>
