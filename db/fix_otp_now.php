<?php
/**
 * Simple OTP Column Fix - Direct Database Update
 * Just open this file in browser and it will fix everything
 */

// Database connection
$conn = mysqli_connect("localhost", "root", "", "agriculture_portal");

if (!$conn) {
    die("Connection Error: " . mysqli_connect_error());
}

echo "<h2>🔧 Fixing OTP Columns...</h2>";

// Fix custlogin table
$sql1 = "ALTER TABLE custlogin ADD COLUMN otp_expires_at DATETIME NULL DEFAULT NULL";
$sql2 = "ALTER TABLE custlogin ADD COLUMN otp_attempts INT(11) DEFAULT 0";

// Fix farmerlogin table
$sql3 = "ALTER TABLE farmerlogin ADD COLUMN otp_expires_at DATETIME NULL DEFAULT NULL";
$sql4 = "ALTER TABLE farmerlogin ADD COLUMN otp_attempts INT(11) DEFAULT 0";

$queries = array(
    "custlogin - otp_expires_at" => $sql1,
    "custlogin - otp_attempts" => $sql2,
    "farmerlogin - otp_expires_at" => $sql3,
    "farmerlogin - otp_attempts" => $sql4
);

foreach ($queries as $name => $sql) {
    if (mysqli_query($conn, $sql)) {
        echo "<p style='color: green;'>✅ $name - Added successfully</p>";
    } else {
        $error = mysqli_error($conn);
        if (strpos($error, "Duplicate column") !== false) {
            echo "<p style='color: blue;'>ℹ️ $name - Already exists</p>";
        } else {
            echo "<p style='color: red;'>❌ $name - Error: $error</p>";
        }
    }
}

echo "<hr>";
echo "<h2>✅ Database Fixed! You can now log in.</h2>";
echo "<p><a href='../customer/clogin.php'>Go to Customer Login</a></p>";
echo "<p><a href='../farmer/flogin.php'>Go to Farmer Login</a></p>";

mysqli_close($conn);
?>
