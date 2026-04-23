<?php
/**
 * Test & Verify OTP System
 * This page verifies everything is working correctly
 */
session_start();
require_once __DIR__ . '/../sql.php';

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>System Status - Agriculture Portal</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .container { background: white; padding: 40px; border-radius: 10px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); max-width: 800px; width: 100%; }
        h1 { color: #333; margin-bottom: 30px; text-align: center; }
        .check { display: flex; align-items: center; padding: 15px; margin-bottom: 10px; border-radius: 5px; background: #f8f9fa; }
        .check-icon { font-size: 24px; margin-right: 15px; }
        .check-content { flex: 1; }
        .check-title { font-weight: bold; color: #333; }
        .check-desc { font-size: 12px; color: #666; margin-top: 5px; }
        .success { border-left: 4px solid #28a745; background: #d4edda; }
        .success .check-title { color: #155724; }
        .error { border-left: 4px solid #dc3545; background: #f8d7da; }
        .error .check-title { color: #721c24; }
        .info { border-left: 4px solid #17a2b8; background: #d1ecf1; }
        .info .check-title { color: #0c5460; }
        .action-buttons { text-align: center; margin-top: 30px; }
        .btn { display: inline-block; padding: 12px 30px; margin: 10px; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; text-decoration: none; transition: all 0.3s; }
        .btn-success { background: #28a745; color: white; }
        .btn-success:hover { background: #218838; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3); }
        .btn-primary { background: #007bff; color: white; }
        .btn-primary:hover { background: #0056b3; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table th, table td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        table th { background: #007bff; color: white; }
        table tr:hover { background: #f5f5f5; }
    </style>
</head>
<body>
<div class="container">
    <h1>✅ Agriculture Portal - System Status</h1>
    
    <?php
    $status_ok = true;
    
    // 1. Database Connection
    if ($conn) {
        echo '<div class="check success">';
        echo '<div class="check-icon">✅</div>';
        echo '<div class="check-content">';
        echo '<div class="check-title">Database Connection</div>';
        echo '<div class="check-desc">Connected to agriculture_portal database</div>';
        echo '</div></div>';
    } else {
        echo '<div class="check error">';
        echo '<div class="check-icon">❌</div>';
        echo '<div class="check-content">';
        echo '<div class="check-title">Database Connection Failed</div>';
        echo '<div class="check-desc">' . mysqli_connect_error() . '</div>';
        echo '</div></div>';
        $status_ok = false;
    }
    
    // 2. Check custlogin columns
    $result = mysqli_query($conn, "SHOW COLUMNS FROM custlogin");
    $columns = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $columns[$row['Field']] = $row['Type'];
    }
    
    if (isset($columns['otp_expires_at']) && isset($columns['otp_attempts'])) {
        echo '<div class="check success">';
        echo '<div class="check-icon">✅</div>';
        echo '<div class="check-content">';
        echo '<div class="check-title">Customer Table (custlogin)</div>';
        echo '<div class="check-desc">All OTP columns present ✓</div>';
        echo '</div></div>';
    } else {
        echo '<div class="check error">';
        echo '<div class="check-icon">❌</div>';
        echo '<div class="check-content">';
        echo '<div class="check-title">Customer Table (custlogin)</div>';
        echo '<div class="check-desc">Missing OTP columns</div>';
        echo '</div></div>';
        $status_ok = false;
    }
    
    // 3. Check farmerlogin columns
    $result = mysqli_query($conn, "SHOW COLUMNS FROM farmerlogin");
    $columns = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $columns[$row['Field']] = $row['Type'];
    }
    
    if (isset($columns['otp_expires_at']) && isset($columns['otp_attempts'])) {
        echo '<div class="check success">';
        echo '<div class="check-icon">✅</div>';
        echo '<div class="check-content">';
        echo '<div class="check-title">Farmer Table (farmerlogin)</div>';
        echo '<div class="check-desc">All OTP columns present ✓</div>';
        echo '</div></div>';
    } else {
        echo '<div class="check error">';
        echo '<div class="check-icon">❌</div>';
        echo '<div class="check-content">';
        echo '<div class="check-title">Farmer Table (farmerlogin)</div>';
        echo '<div class="check-desc">Missing OTP columns</div>';
        echo '</div></div>';
        $status_ok = false;
    }
    
    // 4. Check SMTP Configuration
    require_once __DIR__ . '/../config.php';
    if (defined('SMTP_HOST') && SMTP_HOST !== '') {
        echo '<div class="check info">';
        echo '<div class="check-icon">ℹ️</div>';
        echo '<div class="check-content">';
        echo '<div class="check-title">Email Configuration</div>';
        echo '<div class="check-desc">SMTP configured for email delivery</div>';
        echo '</div></div>';
    } else {
        echo '<div class="check error">';
        echo '<div class="check-icon">⚠️</div>';
        echo '<div class="check-content">';
        echo '<div class="check-title">Email Configuration</div>';
        echo '<div class="check-desc">Check config.php for SMTP settings</div>';
        echo '</div></div>';
    }
    
    // 5. Overall Status
    echo '<div class="check ' . ($status_ok ? 'success' : 'error') . '">';
    echo '<div class="check-icon">' . ($status_ok ? '✅' : '❌') . '</div>';
    echo '<div class="check-content">';
    echo '<div class="check-title">Overall Status</div>';
    echo '<div class="check-desc">' . ($status_ok ? 'All systems ready for operation' : 'Some issues need attention') . '</div>';
    echo '</div></div>';
    
    // Sample data
    echo '<h2 style="margin-top: 40px; margin-bottom: 20px;">📝 Test Credentials</h2>';
    echo '<table>';
    echo '<tr><th>Type</th><th>Email/Username</th><th>Password</th></tr>';
    echo '<tr><td>Customer</td><td>agricultureportal01@gmail.com</td><td>password</td></tr>';
    echo '<tr><td>Farmer</td><td>agricultureportal01@gmail.com</td><td>password</td></tr>';
    echo '<tr><td>Admin</td><td>admin</td><td>password</td></tr>';
    echo '</table>';
    
    ?>
    
    <div class="action-buttons">
        <a href="../customer/clogin.php" class="btn btn-success">🔐 Go to Customer Login</a>
        <a href="../farmer/flogin.php" class="btn btn-primary">🚜 Go to Farmer Login</a>
        <a href="../admin/alogin.php" class="btn btn-primary">👨‍💼 Go to Admin Login</a>
    </div>
    
</div>
</body>
</html>
