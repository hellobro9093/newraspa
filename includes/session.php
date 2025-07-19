<?php
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == true;
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: /auth/login');
        exit();
    }
}

function requireAdmin() {
    if (!isAdmin()) {
        header('Location: /');
        exit();
    }
}

function getUserData() {
    if (!isLoggedIn()) {
        return null;
    }
    
    require_once __DIR__ . '/../config/database.php';
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "SELECT * FROM users WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$_SESSION['user_id']]);
    
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function generateAffiliateCode() {
    return strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
}
?>