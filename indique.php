<?php
require_once 'includes/session.php';
require_once 'includes/functions.php';

requireLogin();

$userData = getUserData();
$affiliate_link = $_SERVER['HTTP_HOST'] . '/auth/register?ref=' . $userData['affiliate_code'];

// Get referral statistics
require_once 'config/database.php';
$database = new Database();
$db = $database->getConnection();

$query = "SELECT COUNT(*) as total_referrals FROM affiliate_referrals WHERE referrer_id = ?";
$stmt = $db->prepare($query);
$stmt->execute([$_SESSION['user_id']]);
$stats = $stmt->fetch(PDO::FETCH_ASSOC);

// Get recent referrals
$query = "SELECT 
    r.*,
    u.username as referred_name
FROM affiliate_referrals r
JOIN users u ON r.referred_id = u.id
WHERE r.referrer_id = ?
ORDER BY r.created_at DESC
LIMIT 10";

$stmt = $db->prepare($query);
$stmt->execute([$_SESSION['user_id']]);
$recent_referrals = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR" class="dark" style="--primary: #08FFB2;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Indique e Ganhe - Raspadinha</title>
    <link rel="stylesheet" href="assets/index-BSEjf_DK.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    
    <div class="px-4 pt-[72px]">
        <main class="bg-surface rounded-b-xl w-full mb-4">
            <div class="mx-auto max-w-(--max-layout-width) min-h-[80lvh] w-full px-5 pt-6 md:pt-7 pb-[calc(68px+2.4rem)] md:pb-12">
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">Indique e Ganhe</h1>
                    <p class="text-gray-600">Ganhe R$ 0,50 para cada amigo que você indicar!</p>
                </div>
                
                <!-- Statistics -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white p-6 rounded-lg shadow-sm border">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Total de Indicações</p>
                                <p class="text-2xl font-bold text-gray-800"><?php echo $stats['total_referrals']; ?></p>
                            </div>
                            <div class="p-3 bg-blue-100 rounded-full">
                                <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white p-6 rounded-lg shadow-sm border">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Ganhos Totais</p>
                                <p class="text-2xl font-bold text-green-600"><?php echo formatCurrency($userData['affiliate_earnings']); ?></p>
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
                                <p class="text-sm text-gray-600">Seu Código</p>
                                <p class="text-2xl font-bold text-purple-600"><?php echo $userData['affiliate_code']; ?></p>
                            </div>
                            <div class="p-3 bg-purple-100 rounded-full">
                                <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Share Link -->
                <div class="bg-white p-6 rounded-lg shadow-sm border mb-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Seu Link de Indicação</h2>
                    <div class="flex items-center gap-4">
                        <input type="text" 
                               value="https://<?php echo $affiliate_link; ?>" 
                               readonly 
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-md bg-gray-50"
                               id="affiliate-link">
                        <button onclick="copyLink()" 
                                class="bg-primary text-white px-4 py-2 rounded-md hover:bg-primary/90 transition-colors">
                            Copiar
                        </button>
                    </div>
                    <p class="text-sm text-gray-600 mt-2">
                        Compartilhe este link com seus amigos. Quando eles se cadastrarem usando seu link, você ganha R$ 0,50!
                    </p>
                </div>
                
                <!-- How it works -->
                <div class="bg-white p-6 rounded-lg shadow-sm border mb-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Como Funciona</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <span class="text-blue-600 font-bold">1</span>
                            </div>
                            <h3 class="font-semibold text-gray-800 mb-2">Compartilhe</h3>
                            <p class="text-sm text-gray-600">Envie seu link de indicação para amigos e familiares</p>
                        </div>
                        
                        <div class="text-center">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <span class="text-green-600 font-bold">2</span>
                            </div>
                            <h3 class="font-semibold text-gray-800 mb-2">Cadastro</h3>
                            <p class="text-sm text-gray-600">Seus amigos se cadastram usando seu link</p>
                        </div>
                        
                        <div class="text-center">
                            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <span class="text-purple-600 font-bold">3</span>
                            </div>
                            <h3 class="font-semibold text-gray-800 mb-2">Ganhe</h3>
                            <p class="text-sm text-gray-600">Receba R$ 0,50 automaticamente em sua conta</p>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Referrals -->
                <?php if (!empty($recent_referrals)): ?>
                <div class="bg-white rounded-lg shadow-sm border">
                    <div class="p-6 border-b">
                        <h2 class="text-xl font-semibold text-gray-800">Suas Indicações Recentes</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <?php foreach ($recent_referrals as $referral): ?>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-medium text-gray-800"><?php echo htmlspecialchars($referral['referred_name']); ?></p>
                                        <p class="text-sm text-gray-600"><?php echo date('d/m/Y H:i', strtotime($referral['created_at'])); ?></p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-medium text-green-600"><?php echo formatCurrency($referral['commission_earned']); ?></p>
                                        <p class="text-sm text-gray-600">Comissão</p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
    
    <?php include 'includes/mobile_navbar.php'; ?>
    
    <script>
        function copyLink() {
            const linkInput = document.getElementById('affiliate-link');
            linkInput.select();
            linkInput.setSelectionRange(0, 99999);
            document.execCommand('copy');
            
            // Show feedback
            const button = event.target;
            const originalText = button.textContent;
            button.textContent = 'Copiado!';
            button.classList.add('bg-green-500');
            button.classList.remove('bg-primary');
            
            setTimeout(() => {
                button.textContent = originalText;
                button.classList.remove('bg-green-500');
                button.classList.add('bg-primary');
            }, 2000);
        }
    </script>
</body>
</html>