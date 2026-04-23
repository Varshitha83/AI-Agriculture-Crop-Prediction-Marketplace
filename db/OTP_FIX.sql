-- Direct SQL Fix for OTP Columns
-- Copy and paste these commands into phpMyAdmin SQL tab
-- Or run each ALTER TABLE command separately

USE agriculture_portal;

-- Check if columns exist before adding (run each separately if needed)

ALTER TABLE custlogin ADD COLUMN IF NOT EXISTS otp_expires_at DATETIME NULL DEFAULT NULL;
ALTER TABLE custlogin ADD COLUMN IF NOT EXISTS otp_attempts INT(11) DEFAULT 0;

ALTER TABLE farmerlogin ADD COLUMN IF NOT EXISTS otp_expires_at DATETIME NULL DEFAULT NULL;
ALTER TABLE farmerlogin ADD COLUMN IF NOT EXISTS otp_attempts INT(11) DEFAULT 0;

-- Verify the changes
SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='custlogin' AND COLUMN_NAME IN ('otp_expires_at', 'otp_attempts');
SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='farmerlogin' AND COLUMN_NAME IN ('otp_expires_at', 'otp_attempts');
