<?php
require_once '../includes/session.php';
require_once '../config/database.php';
require_once '../includes/functions.php';

requireAdmin();

$database = new Database();
$db = $database->getConnection();

// Get statistics
$stats = [];

// Total users
$query = "SELECT COUNT(*) as total FROM users WHERE is_admin = FALSE";
$stmt = $db->prepare($query);
$stmt->execute();
$stats['total_users'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Total deposits
$query = "SELECT COALESCE(SUM(amount), 0) as total FROM transactions WHERE type = 'deposit' AND status = 'completed'";
$stmt = $db->prepare($query);
$stmt->execute();
$stats['total_deposits'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Total withdrawals
$query = "SELECT COALESCE(SUM(amount), 0) as total FROM transactions WHERE type = 'withdrawal' AND status = 'completed'";
$stmt = $db->prepare($query);
$stmt->execute();
$stats['total_withdrawals'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Total affiliate earnings
$query = "SELECT COALESCE(SUM(amount), 0) as total FROM transactions WHERE type = 'affiliate_bonus'";
$stmt = $db->prepare($query);
$stmt->execute();
$stats['total_affiliate_earnings'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Recent users
$query = "SELECT id, username, email, balance, created_at FROM users WHERE is_admin = FALSE ORDER BY created_at DESC LIMIT 10";
$stmt = $db->prepare($query);
$stmt->execute();
$recent_users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Recent transactions
$query = "SELECT t.*, u.username FROM transactions t 
          JOIN users u ON t.user_id = u.id 
          ORDER BY t.created_at DESC LIMIT 10";
$stmt = $db->prepare($query);
$stmt->execute();
$recent_transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR" class="dark" style="--primary: #08FFB2;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Raspadinha</title>
    <link rel="stylesheet" href="../assets/index-BSEjf_DK.css">
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    
    <div class="px-4 pt-[72px]">
        <main class="bg-surface rounded-b-xl w-full mb-4">
            <div class="mx-auto max-w-(--max-layout-width) min-h-[80lvh] w-full px-5 pt-6 md:pt-7 pb-12">
                <div class="mb-6">
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">Painel Administrativo</h1>
                    <p class="text-gray-600">Gerencie usuários, transações e estatísticas da plataforma</p>
                </div>
                
                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white p-6 rounded-lg shadow-sm border">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Total de Usuários</p>
                                <p class="text-2xl font-bold text-gray-800"><?php echo number_format($stats['total_users']); ?></p>
                            </div>
                            <div class="p-3 bg-blue-100 rounded-full">
                                <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white p-6 rounded-lg shadow-sm border">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Total Depositado</p>
                                <p class="text-2xl font-bold text-green-600"><?php echo formatCurrency($stats['total_deposits']); ?></p>
                            </div>
                            <div class="p-3 bg-green-100 rounded-full">
                                <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white p-6 rounded-lg shadow-sm border">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Total Sacado</p>
                                <p class="text-2xl font-bold text-red-600"><?php echo formatCurrency($stats['total_withdrawals']); ?></p>
                            </div>
                            <div class="p-3 bg-red-100 rounded-full">
                                <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white p-6 rounded-lg shadow-sm border">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Bônus Afiliados</p>
                                <p class="text-2xl font-bold text-purple-600"><?php echo formatCurrency($stats['total_affiliate_earnings']); ?></p>
                            </div>
                            <div class="p-3 bg-purple-100 rounded-full">
                                <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Users and Transactions -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Recent Users -->
                    <div class="bg-white rounded-lg shadow-sm border">
                        <div class="p-6 border-b">
                            <h2 class="text-xl font-semibold text-gray-800">Usuários Recentes</h2>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <?php foreach ($recent_users as $user): ?>
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="font-medium text-gray-800"><?php echo htmlspecialchars($user['username']); ?></p>
                                            <p class="text-sm text-gray-600"><?php echo htmlspecialchars($user['email']); ?></p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-medium text-gray-800"><?php echo formatCurrency($user['balance']); ?></p>
                                            <p class="text-sm text-gray-600"><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Recent Transactions -->
                    <div class="bg-white rounded-lg shadow-sm border">
                        <div class="p-6 border-b">
                            <h2 class="text-xl font-semibold text-gray-800">Transações Recentes</h2>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <?php foreach ($recent_transactions as $transaction): ?>
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="font-medium text-gray-800"><?php echo htmlspecialchars($transaction['username']); ?></p>
                                            <p class="text-sm text-gray-600"><?php echo ucfirst(str_replace('_', ' ', $transaction['type'])); ?></p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-medium <?php echo $transaction['type'] == 'deposit' || $transaction['type'] == 'affiliate_bonus' ? 'text-green-600' : 'text-red-600'; ?>">
                                                <?php echo formatCurrency($transaction['amount']); ?>
                                            </p>
                                            <p class="text-sm text-gray-600"><?php echo date('d/m/Y H:i', strtotime($transaction['created_at'])); ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Admin Actions -->
                <div class="mt-8 bg-white rounded-lg shadow-sm border p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Ações Administrativas</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="/admin/users.php" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition-colors text-center">
                            Gerenciar Usuários
                        </a>
                        <a href="/admin/transactions.php" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 transition-colors text-center">
                            Ver Transações
                        </a>
                        <a href="/admin/affiliates.php" class="bg-purple-500 text-white px-4 py-2 rounded-md hover:bg-purple-600 transition-colors text-center">
                            Sistema de Afiliados
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <?php include '../includes/mobile_navbar.php'; ?>
</body>
</html>