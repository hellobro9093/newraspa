<?php
require_once __DIR__ . '/../config/database.php';

function addTransaction($user_id, $type, $amount, $description = '', $status = 'completed') {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "INSERT INTO transactions (user_id, type, amount, description, status) VALUES (?, ?, ?, ?, ?)";
    $stmt = $db->prepare($query);
    return $stmt->execute([$user_id, $type, $amount, $description, $status]);
}

function updateUserBalance($user_id, $amount, $type = 'add') {
    $database = new Database();
    $db = $database->getConnection();
    
    if ($type == 'add') {
        $query = "UPDATE users SET balance = balance + ? WHERE id = ?";
    } else {
        $query = "UPDATE users SET balance = balance - ? WHERE id = ? AND balance >= ?";
        $stmt = $db->prepare($query);
        return $stmt->execute([$amount, $user_id, $amount]);
    }
    
    $stmt = $db->prepare($query);
    return $stmt->execute([$amount, $user_id]);
}

function processAffiliateBonus($referred_user_id) {
    $database = new Database();
    $db = $database->getConnection();
    
    // Buscar dados do usuário indicado
    $query = "SELECT referred_by FROM users WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$referred_user_id]);
    $referred_data = $stmt->fetch();
    
    if ($referred_data && $referred_data['referred_by']) {
        // Encontrar o indicador pelo código de afiliado
        $query = "SELECT id FROM users WHERE affiliate_code = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$referred_data['referred_by']]);
        $referrer = $stmt->fetch();
        
        if ($referrer) {
            // Buscar valor do bônus nas configurações
            $query = "SELECT setting_value FROM system_settings WHERE setting_key = 'affiliate_bonus'";
            $stmt = $db->prepare($query);
            $stmt->execute();
            $bonus_setting = $stmt->fetch();
            $bonus_amount = $bonus_setting ? floatval($bonus_setting['setting_value']) : 0.50;
            
            // Adicionar bônus ao indicador
            updateUserBalance($referrer['id'], $bonus_amount, 'add');
            
            // Atualizar ganhos de afiliado
            $query = "UPDATE users SET affiliate_earnings = affiliate_earnings + ? WHERE id = ?";
            $stmt = $db->prepare($query);
            $stmt->execute([$bonus_amount, $referrer['id']]);
            
            // Adicionar registro de transação
            addTransaction($referrer['id'], 'affiliate_bonus', $bonus_amount, 'Bônus por indicação de novo usuário');
            
            // Registrar na tabela de indicações
            $query = "INSERT INTO affiliate_referrals (referrer_id, referred_id, commission_earned) VALUES (?, ?, ?)";
            $stmt = $db->prepare($query);
            $stmt->execute([$referrer['id'], $referred_user_id, $bonus_amount]);
            
            return true;
        }
    }
    return false;
}

function formatCurrency($amount) {
    return 'R$ ' . number_format($amount, 2, ',', '.');
}

function getNavbarData() {
    if (!isLoggedIn()) {
        return null;
    }
    
    $userData = getUserData();
    if (!$userData) return null;
    
    return [
        'username' => $userData['username'],
        'balance' => $userData['balance'],
        'avatar' => 'https://ik.imagekit.io/azx3nlpdu/flaticon_4140048.svg?updatedAt=1751619848402'
    ];
}

function getSystemSetting($key, $default = null) {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "SELECT setting_value FROM system_settings WHERE setting_key = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$key]);
    $result = $stmt->fetch();
    
    return $result ? $result['setting_value'] : $default;
}

function updateSystemSetting($key, $value) {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "UPDATE system_settings SET setting_value = ? WHERE setting_key = ?";
    $stmt = $db->prepare($query);
    return $stmt->execute([$value, $key]);
}

function getUserStats($user_id) {
    $database = new Database();
    $db = $database->getConnection();
    
    // Total de indicações
    $query = "SELECT COUNT(*) as total_referrals FROM affiliate_referrals WHERE referrer_id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$user_id]);
    $referrals = $stmt->fetch();
    
    // Ganhos de afiliado
    $query = "SELECT affiliate_earnings FROM users WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$user_id]);
    $earnings = $stmt->fetch();
    
    return [
        'total_referrals' => $referrals['total_referrals'],
        'affiliate_earnings' => $earnings['affiliate_earnings']
    ];
}

function getRecentReferrals($user_id, $limit = 10) {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "SELECT r.*, u.username as referred_name 
              FROM affiliate_referrals r
              JOIN users u ON r.referred_id = u.id
              WHERE r.referrer_id = ?
              ORDER BY r.created_at DESC
              LIMIT ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$user_id, $limit]);
    
    return $stmt->fetchAll();
}
?>