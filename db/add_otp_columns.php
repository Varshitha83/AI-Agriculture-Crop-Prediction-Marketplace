<?php
/**
 * Migration script to add OTP-related columns to custlogin table
 * Run this script in your browser to add missing columns
 */

require_once __DIR__ . '/../sql.php';

// Check if columns already exist
$result = mysqli_query($conn, "SHOW COLUMNS FROM custlogin WHERE Field = 'otp_expires_at'");
if (mysqli_num_rows($result) > 0) {
    echo "<p style='color: green; font-size: 16px;'><strong>✓ Column 'otp_expires_at' already exists.</strong></p>";
} else {
    // Add otp_expires_at column
    $query1 = "ALTER TABLE custlogin ADD COLUMN otp_expires_at DATETIME NULL DEFAULT NULL AFTER otp";
    if (mysqli_query($conn, $query1)) {
        echo "<p style='color: green; font-size: 16px;'><strong>✓ Successfully added 'otp_expires_at' column.</strong></p>";
    } else {
        echo "<p style='color: red; font-size: 16px;'><strong>✗ Error adding 'otp_expires_at' column:</strong> " . mysqli_error($conn) . "</p>";
    }
}

// Check if otp_attempts column exists
$result = mysqli_query($conn, "SHOW COLUMNS FROM custlogin WHERE Field = 'otp_attempts'");
if (mysqli_num_rows($result) > 0) {
    echo "<p style='color: green; font-size: 16px;'><strong>✓ Column 'otp_attempts' already exists.</strong></p>";
} else {
    // Add otp_attempts column
    $query2 = "ALTER TABLE custlogin ADD COLUMN otp_attempts INT(11) DEFAULT 0 AFTER otp_expires_at";
    if (mysqli_query($conn, $query2)) {
        echo "<p style='color: green; font-size: 16px;'><strong>✓ Successfully added 'otp_attempts' column.</strong></p>";
    } else {
        echo "<p style='color: red; font-size: 16px;'><strong>✗ Error adding 'otp_attempts' column:</strong> " . mysqli_error($conn) . "</p>";
    }
}

// Verify final structure
echo "<hr />";
echo "<h3>Final Table Structure:</h3>";
$result = mysqli_query($conn, "SHOW COLUMNS FROM custlogin");
echo "<table border='1' cellpadding='10' cellspacing='0'>";
echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['Field']) . "</td>";
    echo "<td>" . htmlspecialchars($row['Type']) . "</td>";
    echo "<td>" . htmlspecialchars($row['Null']) . "</td>";
    echo "<td>" . htmlspecialchars($row['Key']) . "</td>";
    echo "<td>" . htmlspecialchars($row['Default'] ?? 'N/A') . "</td>";
    echo "</tr>";
}
echo "</table>";

echo "<hr />";
echo "<p style='color: blue; font-size: 14px;'><strong>Migration Complete!</strong> You can now delete this file or keep it for reference.</p>";

mysqli_close($conn);
?>
