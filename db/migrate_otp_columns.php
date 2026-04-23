<?php
/**
 * Comprehensive Database Migration Script
 * Adds OTP-related columns to both custlogin and farmerlogin tables
 * 
 * Instructions:
 * 1. Place this file in the db/ directory
 * 2. Access via browser: http://localhost/agriculture-portal-main/db/migrate_otp_columns.php
 * 3. Check the output for success/failure
 * 4. Delete this file after migration
 */

require_once __DIR__ . '/../sql.php';

echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>OTP Migration</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 900px; margin: 0 auto; background: white; padding: 30px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
        .success { color: #155724; background: #d4edda; padding: 10px; border-radius: 3px; margin: 10px 0; border-left: 4px solid #28a745; }
        .error { color: #721c24; background: #f8d7da; padding: 10px; border-radius: 3px; margin: 10px 0; border-left: 4px solid #f5c6cb; }
        .info { color: #0c5460; background: #d1ecf1; padding: 10px; border-radius: 3px; margin: 10px 0; border-left: 4px solid #17a2b8; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        table th { background: #007bff; color: white; padding: 10px; text-align: left; }
        table td { padding: 10px; border-bottom: 1px solid #ddd; }
        table tr:hover { background: #f9f9f9; }
        .hr { margin: 30px 0; border: 0; border-top: 1px solid #ddd; }
    </style>
</head>
<body>
<div class='container'>
    <h1>🔧 OTP Database Migration</h1>";

// Function to add columns to a table
function addColumnsToTable($conn, $tableName, $columns) {
    foreach ($columns as $columnName => $columnDef) {
        // Check if column already exists
        $result = mysqli_query($conn, "SHOW COLUMNS FROM $tableName WHERE Field = '$columnName'");
        if (mysqli_num_rows($result) > 0) {
            echo "<div class='info'>✓ Column '$columnName' already exists in '$tableName' table.</div>";
        } else {
            // Add column
            $query = "ALTER TABLE $tableName ADD COLUMN $columnName $columnDef";
            if (mysqli_query($conn, $query)) {
                echo "<div class='success'>✓ Successfully added '$columnName' column to '$tableName' table.</div>";
            } else {
                echo "<div class='error'>✗ Error adding '$columnName' column to '$tableName' table: " . mysqli_error($conn) . "</div>";
            }
        }
    }
}

// Define columns to add
$otp_columns = [
    'otp_expires_at' => 'DATETIME NULL DEFAULT NULL',
    'otp_attempts' => 'INT(11) DEFAULT 0'
];

echo "<h2>Processing custlogin table...</h2>";
addColumnsToTable($conn, 'custlogin', $otp_columns);

echo "<div class='hr'></div>";
echo "<h2>Processing farmerlogin table...</h2>";
addColumnsToTable($conn, 'farmerlogin', $otp_columns);

echo "<div class='hr'></div>";
echo "<h2>Final Table Structures:</h2>";

// Show custlogin structure
echo "<h3>custlogin table:</h3>";
$result = mysqli_query($conn, "SHOW COLUMNS FROM custlogin");
echo "<table>
    <tr>
        <th>Field</th>
        <th>Type</th>
        <th>Null</th>
        <th>Key</th>
        <th>Default</th>
    </tr>";
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
        <td><strong>" . htmlspecialchars($row['Field']) . "</strong></td>
        <td>" . htmlspecialchars($row['Type']) . "</td>
        <td>" . htmlspecialchars($row['Null']) . "</td>
        <td>" . htmlspecialchars($row['Key']) . "</td>
        <td>" . htmlspecialchars($row['Default'] ?? 'N/A') . "</td>
    </tr>";
}
echo "</table>";

// Show farmerlogin structure
echo "<h3>farmerlogin table:</h3>";
$result = mysqli_query($conn, "SHOW COLUMNS FROM farmerlogin");
echo "<table>
    <tr>
        <th>Field</th>
        <th>Type</th>
        <th>Null</th>
        <th>Key</th>
        <th>Default</th>
    </tr>";
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
        <td><strong>" . htmlspecialchars($row['Field']) . "</strong></td>
        <td>" . htmlspecialchars($row['Type']) . "</td>
        <td>" . htmlspecialchars($row['Null']) . "</td>
        <td>" . htmlspecialchars($row['Key']) . "</td>
        <td>" . htmlspecialchars($row['Default'] ?? 'N/A') . "</td>
    </tr>";
}
echo "</table>";

echo "<div class='hr'></div>";
echo "<div class='success'><h3>✓ Migration Complete!</h3>
<p>The OTP system should now work properly for both customers and farmers.</p>
<p><strong>Next Steps:</strong></p>
<ul>
    <li>Delete this migration file</li>
    <li>Test the login flow</li>
    <li>Verify OTP sending and verification works</li>
</ul>
</div>";

echo "</div>
</body>
</html>";

mysqli_close($conn);
?>
