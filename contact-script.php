<?php
// contact-script.php
// Handles form submission from contact.php

// Only need DB connection here
require_once __DIR__ . '/sql.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get form values safely
    $user_name    = trim($_POST['user_name']    ?? '');
    $user_mobile  = trim($_POST['user_mobile']  ?? '');
    $user_email   = trim($_POST['user_email']   ?? '');
    $user_address = trim($_POST['user_address'] ?? '');
    $user_message = trim($_POST['user_message'] ?? '');

    // 1) Insert into DB (using prepared statement is safer)
    $stmt = $conn->prepare("
        INSERT INTO contactus (c_name, c_mobile, c_email, c_address, c_message)
        VALUES (?, ?, ?, ?, ?)
    ");

    if ($stmt) {
        $stmt->bind_param("sssss",
            $user_name,
            $user_mobile,
            $user_email,
            $user_address,
            $user_message
        );

        $ok = $stmt->execute();
        $stmt->close();
    } else {
        $ok = false;
    }

    // 2) (Optional) send email to you
    //    If you don't want email, you can comment this whole block.
    if ($ok) {
        $to      = "santhoshamsricharan28@gmail.com";   // ← your email
        $subject = "New Contact Message - Agriculture Portal";

        $body  = "You have received a new contact message.\r\n\r\n";
        $body .= "Name:    $user_name\r\n";
        $body .= "Mobile:  $user_mobile\r\n";
        $body .= "Email:   $user_email\r\n";
        $body .= "Address: $user_address\r\n\r\n";
        $body .= "Message:\r\n$user_message\r\n";

        $headers  = "From: ".$user_email."\r\n";
        $headers .= "Reply-To: ".$user_email."\r\n";

        // Suppress warning on localhost if mail() not configured
        @mail($to, $subject, $body, $headers);
    }

    // 3) Redirect back with status for modal
    if ($ok) {
        header("Location: contact.php?status=success");
    } else {
        header("Location: contact.php?status=error");
    }
    exit;
}

// If someone opens this file directly in browser, just go back.
header("Location: contact.php");
exit;
