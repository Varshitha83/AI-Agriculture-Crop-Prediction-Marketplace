<?php
// test_mail.php – direct Gmail SMTP test

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "config.php";
require_once "smtp/class.phpmailer.php";

$mail = new PHPMailer();
$mail->IsSMTP();
$mail->SMTPAuth   = true;
$mail->SMTPSecure = "ssl";          // SSL
$mail->Host       = $SMTP_HOST;     // smtp.gmail.com
$mail->Port       = $SMTP_PORT;     // 465

$mail->SMTPDebug  = 2;              // <-- SHOW FULL ERROR DETAILS
$mail->Debugoutput = 'html';

$mail->IsHTML(true);
$mail->CharSet    = "UTF-8";

$mail->Username   = $SMTP_USERNAME; // your Gmail
$mail->Password   = $SMTP_PASSWORD; // your Google App Password
$mail->SetFrom($SMTP_FROM);

$mail->Subject = "Test mail from Agriculture portal";
$mail->Body    = "If you see this email, SMTP is working correctly.";
$mail->AddAddress($SMTP_USERNAME);  // send to yourself

if(!$mail->Send()){
    echo "<h3>Mail failed:</h3>";
    echo "<pre>" . htmlspecialchars($mail->ErrorInfo) . "</pre>";
} else {
    echo "<h3>Mail sent successfully!</h3>";
}
