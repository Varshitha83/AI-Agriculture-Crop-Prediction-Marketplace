<?php
// =========================
// BASIC SITE DETAILS
// =========================
$SITE_NAME      = "Agriculture portal";
$OWNER_NAME     = "Agriculture portal Admin";
$SUPPORT_EMAIL  = "support@example.com";

// =========================
// DATABASE SETTINGS
// =========================
// Adjust if your MySQL settings differ
$DB_HOST        = "localhost";
$DB_USERNAME    = "root";
$DB_PASSWORD    = "";
$DB_NAME        = "agriculture_portal";

// =========================
// OPENAI (ChatGPT)
// Used in index.php and farmer/fchatgpt.php
// =========================
$OPENAI_API_KEY = "YOUR_OPENAI_API_KEY"; 
// ^ Put your real OpenAI key here

// =========================
// STRIPE PAYMENT GATEWAY
// (Card / wallet payments via Stripe)
// =========================
$STRIPE_SECRET_KEY      = "YOUR_STRIPE_SECRET_KEY";
$STRIPE_PUBLISHABLE_KEY = "YOUR_STRIPE_PUBLISHABLE_KEY";
// =========================
// UPI / WALLET PAYMENTS
// PhonePe, Google Pay, Paytm via UPI
// (You will usually connect this using a gateway like Razorpay/Paytm)
// =========================
$ENABLE_UPI_PAYMENTS = true;

// Your business UPI ID (VPA) that works with PhonePe/GPay/Paytm
$UPI_MERCHANT_VPA    = "YOUR_UPI_ID";
$UPI_BUSINESS_NAME   = "Agriculture portal";     // this name appears in UPI apps

// If you later use Razorpay / Paytm as API, fill these:
$UPI_GATEWAY_NAME    = "razorpay";               // e.g. 'razorpay', 'paytm'
$UPI_GATEWAY_API_KEY = "YOUR_UPI_API_KEY";
// =========================
// SMTP / EMAIL SETTINGS
// Gmail for OTP emails
// =========================
$SMTP_HOST      = "smtp.gmail.com";
$SMTP_PORT      = 465;
$SMTP_USERNAME  = "YOUR_EMAIL";
$SMTP_PASSWORD  = "YOUR_APP_PASSWORD";
$SMTP_FROM      = "support@example.com";

// =========================
// LANGUAGE SETTINGS
// English + Telugu
// =========================
$DEFAULT_LANGUAGE = "en"; // 'en' = English, 'te' = Telugu

// Reusable text in multiple languages
$LANG_STRINGS = [
    'en' => [
        'site_title'     => 'Agriculture portal',
        'welcome'        => 'Welcome to Agriculture portal',
        'farmer_login'   => 'Farmer Login',
        'customer_login' => 'Customer Login',
        'otp_message'    => 'Your OTP verification code for Agriculture portal is',
        'pay_now'        => 'Pay Now',
        'pay_with_upi'   => 'Pay with UPI (PhonePe / Google Pay / Paytm)',
    ],
    'te' => [
        'site_title'     => 'వ్యవసాయ పోర్టల్',
        'welcome'        => 'వ్యవసాయ పోర్టల్‌కి స్వాగతం',
        'farmer_login'   => 'రైతు లాగిన్',
        'customer_login' => 'కస్టమర్ లాగిన్',
        'otp_message'    => 'వ్యవసాయ పోర్టల్ కోసం మీ OTP ధృవీకరణ కోడ్',
        'pay_now'        => 'ఇప్పుడే చెల్లించండి',
        'pay_with_upi'   => 'యూపీఐ ద్వారా చెల్లించండి (ఫోన్‌పే / గూగుల్ పే / పేటీఎం)',
    ],
];
?>
