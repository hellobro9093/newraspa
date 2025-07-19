<?php
require_once 'database.php';

$database = new Database();
$db = $database->getConnection();

// Create users table
$query = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    document VARCHAR(20),
    balance DECIMAL(10,2) DEFAULT 0.00,
    total_deposited DECIMAL(10,2) DEFAULT 0.00,
    total_withdrawn DECIMAL(10,2) DEFAULT 0.00,
    cashback_earned DECIMAL(10,2) DEFAULT 0.00,
    affiliate_code VARCHAR(10) UNIQUE,
    referred_by VARCHAR(10),
    affiliate_earnings DECIMAL(10,2) DEFAULT 0.00,
    is_admin BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";
$db->exec($query);

// Create transactions table
$query = "CREATE TABLE IF NOT EXISTS transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type ENUM('deposit', 'withdrawal', 'game_win', 'game_loss', 'affiliate_bonus') NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";
$db->exec($query);

// Create games table
$query = "CREATE TABLE IF NOT EXISTS games (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    game_type VARCHAR(50) NOT NULL,
    bet_amount DECIMAL(10,2) NOT NULL,
    win_amount DECIMAL(10,2) DEFAULT 0.00,
    result ENUM('win', 'loss') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";
$db->exec($query);

// Create affiliate_referrals table
$query = "CREATE TABLE IF NOT EXISTS affiliate_referrals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    referrer_id INT NOT NULL,
    referred_id INT NOT NULL,
    commission_earned DECIMAL(10,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (referrer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (referred_id) REFERENCES users(id) ON DELETE CASCADE
)";
$db->exec($query);

// Create admin user if not exists
$admin_email = 'admin@raspadinha.com';
$admin_password = password_hash('admin123', PASSWORD_DEFAULT);
$admin_code = 'ADMIN001';

$check_admin = $db->prepare("SELECT id FROM users WHERE email = ?");
$check_admin->execute([$admin_email]);

if ($check_admin->rowCount() == 0) {
    $create_admin = $db->prepare("INSERT INTO users (username, email, password, affiliate_code, is_admin) VALUES (?, ?, ?, ?, ?)");
    $create_admin->execute(['admin', $admin_email, $admin_password, $admin_code, true]);
}

echo "Database initialized successfully!";
?>