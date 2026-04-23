<?php
// farmer/fcheck_otp.php
session_start();
require_once __DIR__ . '/../sql.php';

$email = isset($_SESSION['farmer_login_user']) ? $_SESSION['farmer_login_user'] : '';
$otp   = isset($_POST['otp']) ? trim($_POST['otp']) : '';

if ($email == '' || $otp == '') {
    echo 'missing';
    exit;
}

// SELECT using prepared statement
$stmt = mysqli_prepare($conn, "SELECT otp, otp_expires_at, otp_attempts FROM farmerlogin WHERE email = ? LIMIT 1");
if (!$stmt) {
    error_log('fcheck_otp: prepare failed: ' . mysqli_error($conn));
    echo 'db_error';
    exit;
}
mysqli_stmt_bind_param($stmt, 's', $email);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
mysqli_stmt_close($stmt);

if (!$res || mysqli_num_rows($res) == 0) {
    echo 'not_found';
    exit;
}

$row = mysqli_fetch_assoc($res);

$now = time();
$exp = $row['otp_expires_at'] ? strtotime($row['otp_expires_at']) : 0;

// too many attempts
if (intval($row['otp_attempts']) >= 5) {
    echo 'locked';
    exit;
}

// expired
if ($exp > 0 && $now > $exp) {
    echo 'expired';
    exit;
}

// match
if ((string)$row['otp'] === (string)$otp) {
    // success – clear otp fields and log in (prepared)
    $stmt2 = mysqli_prepare($conn, "UPDATE farmerlogin SET otp = '', otp_expires_at = NULL, otp_attempts = 0 WHERE email = ?");
    if ($stmt2) {
        mysqli_stmt_bind_param($stmt2, 's', $email);
        mysqli_stmt_execute($stmt2);
        mysqli_stmt_close($stmt2);
    } else {
        error_log('fcheck_otp: failed to prepare clear update: ' . mysqli_error($conn));
    }

    $_SESSION['IS_LOGIN'] = $email;
    echo 'yes';
} else {
    // wrong, increase attempts (prepared)
    $stmt3 = mysqli_prepare($conn, "UPDATE farmerlogin SET otp_attempts = otp_attempts + 1 WHERE email = ?");
    if ($stmt3) {
        mysqli_stmt_bind_param($stmt3, 's', $email);
        mysqli_stmt_execute($stmt3);
        mysqli_stmt_close($stmt3);
    } else {
        error_log('fcheck_otp: failed to prepare increment update: ' . mysqli_error($conn));
    }
    echo 'wrong';
}
