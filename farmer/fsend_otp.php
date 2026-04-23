<?php
// farmer/fsend_otp.php
session_start();
require_once __DIR__ . '/../sql.php';
require_once __DIR__ . '/../config.php';

// sanitize session email
$email = isset($_SESSION['farmer_login_user']) ? mysqli_real_escape_string($conn, $_SESSION['farmer_login_user']) : '';

if ($email == '') {
    echo 'no_session_email';
    exit;
}

// check farmer exists
$res = mysqli_query($conn, "SELECT * FROM farmerlogin WHERE email='$email' LIMIT 1");
if (mysqli_num_rows($res) == 0) {
    echo 'no_db_email';
    exit;
}

// generate OTP + expiry (5 minutes)
$otp        = rand(111111, 999999);
$expires_at = date('Y-m-d H:i:s', time() + 5 * 60);

// store OTP
// store OTP using prepared statement
$stmt = mysqli_prepare($conn, "UPDATE `farmerlogin` SET otp = ?, otp_expires_at = ?, otp_attempts = 0 WHERE email = ?");
if ($stmt) {
    mysqli_stmt_bind_param($stmt, 'iss', $otp, $expires_at, $email);
    mysqli_stmt_execute($stmt);
    $affected = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);
    if ($affected === 0) {
        // Could be that email not found or no change — check existence
        $r = mysqli_query($conn, "SELECT COUNT(*) as c FROM `farmerlogin` WHERE email='$email'");
        $row = mysqli_fetch_assoc($r);
        if (empty($row) || intval($row['c']) === 0) {
            error_log('farmer/fsend_otp.php: No matching email when updating OTP for: ' . $email);
            echo 'no_db_email';
            exit;
        }
        // email exists but nothing updated — likely missing columns or permission issue
        error_log('farmer/fsend_otp.php: OTP update affected 0 rows for existing email: ' . $email);
        echo 'db_error';
        exit;
    }
} else {
    error_log('farmer/fsend_otp.php: Failed to prepare OTP update statement: ' . mysqli_error($conn));
    echo 'db_error';
    exit;
}

$msg = "Your OTP verification code for Agriculture Portal is: $otp\n\n"
     . "This code is valid for 5 minutes.";

if (smtp_mailer($email, 'OTP Verification', nl2br($msg))) {
    echo 'yes';
} else {
    echo 'mail_error';
}

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
