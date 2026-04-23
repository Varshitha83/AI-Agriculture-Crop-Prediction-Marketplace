<?php
// db/run_add_otp_columns.php
// Safe helper: backups `farmerlogin` and adds missing OTP columns.
// Usage: visit in browser: http://localhost/agriculture-portal-main/db/run_add_otp_columns.php

header('Content-Type: text/plain; charset=utf-8');
require_once __DIR__ . '/../sql.php';

if (!isset($conn) || !$conn) {
    echo "Database connection not available. Check sql.php configuration.\n";
    exit;
}

// Get current database name
$res = mysqli_query($conn, "SELECT DATABASE() AS db");
$row = mysqli_fetch_assoc($res);
$dbname = $row['db'] ?? '';
if ($dbname === '') {
    echo "Unable to determine current database.\n";
    exit;
}

echo "Database: $dbname\n";

// Tables to ensure OTP columns exist on
$tables = ['farmerlogin', 'custlogin'];
$now = date('Ymd_His');

foreach ($tables as $table) {
    // 1) Create backup table
    $backup_name = "{$table}_backup_{$now}";
    $create_backup_sql = "CREATE TABLE `{$backup_name}` AS SELECT * FROM `{$table}`";
    if (mysqli_query($conn, $create_backup_sql)) {
        echo "Backup created: {$backup_name}\n";
    } else {
        echo "Failed to create backup for {$table}: " . mysqli_error($conn) . "\n";
        // proceed anyway
    }

    $columns_to_add = [
        'otp_expires_at' => 'DATETIME DEFAULT NULL',
        'otp_attempts' => 'INT NOT NULL DEFAULT 0'
    ];

    foreach ($columns_to_add as $col => $definition) {
        $check_sql = "SELECT COUNT(*) AS c FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . mysqli_real_escape_string($conn, $dbname) . "' AND TABLE_NAME = '" . mysqli_real_escape_string($conn, $table) . "' AND COLUMN_NAME = '" . mysqli_real_escape_string($conn, $col) . "'";
        $r = mysqli_query($conn, $check_sql);
        if (!$r) {
            echo "Failed to check column {$col} on {$table}: " . mysqli_error($conn) . "\n";
            continue;
        }
        $rc = mysqli_fetch_assoc($r);
        $exists = intval($rc['c']) > 0;
        if ($exists) {
            echo "Column {$col} already exists on {$table}.\n";
            continue;
        }

        $alter_sql = "ALTER TABLE `{$table}` ADD COLUMN `{$col}` {$definition}";
        if (mysqli_query($conn, $alter_sql)) {
            echo "Added column {$col} to {$table}.\n";
        } else {
            echo "Failed to add column {$col} to {$table}: " . mysqli_error($conn) . "\n";
        }
    }

    // Final structure display (simple)
    $list = mysqli_query($conn, "DESCRIBE `{$table}`");
    if ($list) {
        echo "\nFinal {$table} structure:\n";
        while ($r = mysqli_fetch_assoc($list)) {
            echo $r['Field'] . ' ' . $r['Type'] . ' DEFAULT(' . ($r['Default'] ?? 'NULL') . ')\n';
        }
    } else {
        echo "Failed to describe {$table}: " . mysqli_error($conn) . "\n";
    }

    echo "\n";
}

echo "Done.\n";

?>
