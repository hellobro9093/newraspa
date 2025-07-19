<?php
require_once '../includes/session.php';
require_once '../config/database.php';
require_once '../includes/functions.php';

requireAdmin();

$database = new Database();
$db = $database->getConnection();

// Get affiliate statistics
$query = "SELECT 
    u.id,
    u.username,
    u.email,
    u.affiliate_code,
    u.affiliate_earnings,
    COUNT(r.id) as total_referrals,
    COALESCE(SUM(r.commission_earned), 0) as total_commission
FROM users u
LEFT JOIN affiliate_referrals r ON u.id = r.referrer_id
WHERE u.is_admin = FALSE
GROUP BY u.id
HAVING total_referrals > 0 OR u.affiliate_earnings > 0
ORDER BY u.affiliate_earnings DESC";

$stmt = $db->prepare($query);
$stmt->execute();
$affiliates = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get recent referrals
$query = "SELECT 
    r.*,
    referrer.username as referrer_name,
    referred.username as referred_name
FROM affiliate_referrals r
JOIN users referrer ON r.referrer_id = referrer.id
JOIN users referred ON r.referred_id = referred.id
ORDER BY r.created_at DESC
LIMIT 20";

$stmt = $db->prepare($query);
$stmt->execute();
$recent_referrals = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR" class="dark" style="--primary: #08FFB2;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Afiliados - Admin</title>
    <link rel="stylesheet" href="../assets/index-BSEjf_DK.css">
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    
    <div class="px-4 pt-[72px]">
        <main class="bg-surface rounded-b-xl w-full mb-4">
            <div class="mx-auto max-w-(--max-layout-width) min-h-[80lvh] w-full px-5 pt-6 md:pt-7 pb-12">
                <div class="mb-6">
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">Sistema de Afiliados</h1>
                    <p class="text-gray-600">Gerencie o programa de afiliados e comissões</p>
                </div>
                
                <!-- Top Affiliates -->
                <div class="bg-white rounded-lg shadow-sm border mb-8">
                    <div class="p-6 border-b">
                        <h2 class="text-xl font-semibold text-gray-800">Top Afiliados</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuário</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Indicações</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ganhos</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Link de Indicação</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($affiliates as $affiliate): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($affiliate['username']); ?></div>
                                                <div class="text-sm text-gray-500"><?php echo htmlspecialchars($affiliate['email']); ?></div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full">
                                                <?php echo htmlspecialchars($affiliate['affiliate_code']); ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php echo $affiliate['total_referrals']; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                                            <?php echo formatCurrency($affiliate['affiliate_earnings']); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <code class="bg-gray-100 px-2 py-1 rounded text-xs">
                                                <?php echo $_SERVER['HTTP_HOST']; ?>/auth/register?ref=<?php echo $affiliate['affiliate_code']; ?>
                                            </code>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Recent Referrals -->
                <div class="bg-white rounded-lg shadow-sm border">
                    <div class="p-6 border-b">
                        <h2 class="text-xl font-semibold text-gray-800">Indicações Recentes</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Afiliado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Indicado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Comissão</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($recent_referrals as $referral): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <?php echo htmlspecialchars($referral['referrer_name']); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php echo htmlspecialchars($referral['referred_name']); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                                            <?php echo formatCurrency($referral['commission_earned']); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo date('d/m/Y H:i', strtotime($referral['created_at'])); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <?php include '../includes/mobile_navbar.php'; ?>
</body>
</html>