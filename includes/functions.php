<?php
require_once __DIR__ . '/../config/database.php';

function addTransaction($user_id, $type, $amount, $description = '') {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "INSERT INTO transactions (user_id, type, amount, description, status) VALUES (?, ?, ?, ?, 'completed')";
    $stmt = $db->prepare($query);
    return $stmt->execute([$user_id, $type, $amount, $description]);
}

function updateUserBalance($user_id, $amount, $type = 'add') {
    $database = new Database();
    $db = $database->getConnection();
    
    if ($type == 'add') {
        $query = "UPDATE users SET balance = balance + ? WHERE id = ?";
    } else {
        $query = "UPDATE users SET balance = balance - ? WHERE id = ?";
    }
    
    $stmt = $db->prepare($query);
    return $stmt->execute([$amount, $user_id]);
}

function processAffiliateBonus($referred_user_id) {
    $database = new Database();
    $db = $database->getConnection();
    
    // Get referred user info
    $query = "SELECT referred_by FROM users WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$referred_user_id]);
    $referred_data = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($referred_data && $referred_data['referred_by']) {
        // Find referrer by affiliate code
        $query = "SELECT id FROM users WHERE affiliate_code = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$referred_data['referred_by']]);
        $referrer = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($referrer) {
            $bonus_amount = 0.50; // 50 centimos
            
            // Add bonus to referrer
            updateUserBalance($referrer['id'], $bonus_amount, 'add');
            
            // Update affiliate earnings
            $query = "UPDATE users SET affiliate_earnings = affiliate_earnings + ? WHERE id = ?";
            $stmt = $db->prepare($query);
            $stmt->execute([$bonus_amount, $referrer['id']]);
            
            // Add transaction record
            addTransaction($referrer['id'], 'affiliate_bonus', $bonus_amount, 'Bônus por indicação de novo usuário');
            
            // Record in affiliate_referrals table
            $query = "INSERT INTO affiliate_referrals (referrer_id, referred_id, commission_earned) VALUES (?, ?, ?)";
            $stmt = $db->prepare($query);
            $stmt->execute([$referrer['id'], $referred_user_id, $bonus_amount]);
        }
    }
}

function formatCurrency($amount) {
    return 'R$ ' . number_format($amount, 2, ',', '.');
}

function getNavbarData() {
    if (!isLoggedIn()) {
        return null;
    }
    
    $userData = getUserData();
    return [
        'username' => $userData['username'],
        'balance' => $userData['balance'],
        'avatar' => 'https://ik.imagekit.io/azx3nlpdu/flaticon_4140048.svg?updatedAt=1751619848402'
    ];
}
?>