<?php
// customer/csend_otp.php
session_start();
require_once __DIR__ . '/../sql.php';
require_once __DIR__ . '/../config.php';

// sanitize session email
$email = isset($_SESSION['customer_login_user']) ? mysqli_real_escape_string($conn, $_SESSION['customer_login_user']) : '';

if ($email == '') {
    echo 'no_session_email';
    exit;
}

// 1) Check that this customer exists (prepared)
$stmt = mysqli_prepare($conn, "SELECT cust_id FROM custlogin WHERE email = ? LIMIT 1");
if (!$stmt) {
    error_log('csend_otp: prepare failed: ' . mysqli_error($conn));
    echo 'db_error';
    exit;
}
mysqli_stmt_bind_param($stmt, 's', $email);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
mysqli_stmt_close($stmt);
if (!$res || mysqli_num_rows($res) == 0) {
    echo 'no_db_email';
    exit;
}

// 2) Generate OTP + expiry (5 minutes)
$otp        = rand(111111, 999999);
$expires_at = date('Y-m-d H:i:s', time() + 5 * 60);

// 3) Save to DB (and reset attempts) via prepared
$stmt2 = mysqli_prepare($conn, "UPDATE custlogin SET otp = ?, otp_expires_at = ?, otp_attempts = 0 WHERE email = ?");
if ($stmt2) {
    mysqli_stmt_bind_param($stmt2, 'iss', $otp, $expires_at, $email);
    mysqli_stmt_execute($stmt2);
    mysqli_stmt_close($stmt2);
} else {
    error_log('csend_otp: failed to prepare update: ' . mysqli_error($conn));
    echo 'db_error';
    exit;
}

// 4) Email content
$msg = "Your OTP verification code for Agriculture Portal is: $otp\n\n"
     . "This code is valid for 5 minutes.";

// 5) Send email via PHPMailer
if (smtp_mailer($email, 'OTP Verification', nl2br($msg))) {
    echo 'yes';
} else {
    echo 'mail_error';
}

// ============= Helper =============
function smtp_mailer($to, $subject, $msg){
    require_once __DIR__ . "/../smtp/class.phpmailer.php";
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->SMTPAuth   = true;
    $mail->SMTPSecure = 'ssl';
    $mail->Host       = $GLOBALS['SMTP_HOST'];
    $mail->Port       = $GLOBALS['SMTP_PORT'];
    $mail->IsHTML(true);
    $mail->CharSet    = 'UTF-8';
    $mail->Username   = $GLOBALS['SMTP_USERNAME'];
    $mail->Password   = $GLOBALS['SMTP_PASSWORD'];
    $mail->SetFrom($GLOBALS['SMTP_FROM']);
    $mail->Subject = $subject;
    $mail->Body    = $msg;
    $mail->AddAddress($to);
    return $mail->Send();
}
