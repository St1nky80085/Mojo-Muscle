<?php
// =============================================
// MOJO MUSCLE - Mail Configuration
// Uses Gmail SMTP via PHPMailer
// =============================================

define('SITE_URL',      'http://localhost/New%20folder/Mojo-Muscle/');  // ← your live URL
define('MAIL_FROM',     'mojo.musclee@gmail.com');          // ← your Gmail
define('MAIL_PASSWORD', 'baki nyrb ppzx ldik');           // ← Gmail App Password
define('MAIL_NAME',     'Mojo Muscle Gym');

function sendMail($to_email, $to_name, $subject, $html_body) {
    // Load PHPMailer (we use the simple include method — no Composer needed)
    require_once __DIR__ . '/../PHPMailer/PHPMailer.php';
    require_once __DIR__ . '/../PHPMailer/SMTP.php';
    require_once __DIR__ . '/../PHPMailer/Exception.php';

    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = MAIL_FROM;
        $mail->Password   = MAIL_PASSWORD;
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom(MAIL_FROM, MAIL_NAME);
        $mail->addAddress($to_email, $to_name);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $html_body;
        $mail->AltBody = strip_tags($html_body);

        $mail->send();
        return true;
    } catch (Exception $e) {
        return $mail->ErrorInfo;
    }
}
?>
