<?php
require_once '../includes/session.php';
require_once '../config/database.php';
require_once '../includes/functions.php';

requireAdmin();

$database = new Database();
$db = $database->getConnection();

// Handle user actions
if ($_POST) {
    $user_id = $_POST['user_id'] ?? 0;
    $action = $_POST['action'] ?? '';
    
    if ($action == 'toggle_status' && $user_id) {
        $query = "UPDATE users SET is_active = NOT is_active WHERE id = ? AND is_admin = FALSE";
        $stmt = $db->prepare($query);
        $stmt->execute([$user_id]);
        $success_message = 'Status do usuário atualizado!';
    } elseif ($action == 'add_balance' && $user_id) {
        $amount = floatval($_POST['amount'] ?? 0);
        if ($amount > 0) {
            updateUserBalance($user_id, $amount, 'add');
            addTransaction($user_id, 'deposit', $amount, 'Adicionado pelo admin');
            $success_message = 'Saldo adicionado com sucesso!';
        }
    } elseif ($action == 'remove_balance' && $user_id) {
        $amount = floatval($_POST['amount'] ?? 0);
        if ($amount > 0) {
            updateUserBalance($user_id, $amount, 'subtract');
            addTransaction($user_id, 'withdrawal', $amount, 'Removido pelo admin');
            $success_message = 'Saldo removido com sucesso!';
        }
    }
}

// Get all users
$search = $_GET['search'] ?? '';
$page = max(1, intval($_GET['page'] ?? 1));
$limit = 20;
$offset = ($page - 1) * $limit;

$where_clause = "WHERE is_admin = FALSE";
$params = [];

if (!empty($search)) {
    $where_clause .= " AND (username LIKE ? OR email LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$query = "SELECT * FROM users $where_clause ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
$stmt = $db->prepare($query);
$stmt->execute($params);
$users = $stmt->fetchAll();

// Get total count for pagination
$count_query = "SELECT COUNT(*) as total FROM users $where_clause";
$stmt = $db->prepare($count_query);
$stmt->execute($params);
$total_users = $stmt->fetch()['total'];
$total_pages = ceil($total_users / $limit);
?>

<!DOCTYPE html>
<html lang="pt-BR" class="dark" style="--primary: #08FFB2;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Usuários - Admin</title>
    <link rel="stylesheet" href="../assets/index-BSEjf_DK.css">
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    
    <div class="px-4 pt-[72px]">
        <main class="bg-surface rounded-b-xl w-full mb-4">
            <div class="mx-auto max-w-(--max-layout-width) min-h-[80lvh] w-full px-5 pt-6 md:pt-7 pb-12">
                <div class="mb-6">
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">Gerenciar Usuários</h1>
                    <p class="text-gray-600">Visualize e gerencie todos os usuários da plataforma</p>
                </div>
                
                <?php if (isset($success_message)): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        <?php echo $success_message; ?>
                    </div>
                <?php endif; ?>
                
                <!-- Search -->
                <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
                    <form method="GET" class="flex gap-4">
                        <input type="text" name="search" placeholder="Buscar por nome ou email..." 
                               value="<?php echo htmlspecialchars($search); ?>"
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-md">
                        <button type="submit" class="bg-primary text-white px-4 py-2 rounded-md hover:bg-primary/90">
                            Buscar
                        </button>
                        <?php if ($search): ?>
                            <a href="/admin/users.php" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                                Limpar
                            </a>
                        <?php endif; ?>
                    </form>
                </div>
                
                <!-- Users Table -->
                <div class="bg-white rounded-lg shadow-sm border">
                    <div class="p-6 border-b">
                        <h2 class="text-xl font-semibold text-gray-800">
                            Usuários (<?php echo number_format($total_users); ?> total)
                        </h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuário</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Saldo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Afiliado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cadastro</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($user['username']); ?></div>
                                                <div class="text-sm text-gray-500"><?php echo htmlspecialchars($user['email']); ?></div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                                            <?php echo formatCurrency($user['balance']); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full">
                                                <?php echo htmlspecialchars($user['affiliate_code']); ?>
                                            </span>
                                            <?php if ($user['referred_by']): ?>
                                                <div class="text-xs text-gray-500 mt-1">Por: <?php echo htmlspecialchars($user['referred_by']); ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full <?php echo $user['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                                <?php echo $user['is_active'] ? 'Ativo' : 'Inativo'; ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo date('d/m/Y', strtotime($user['created_at'])); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex gap-2">
                                                <!-- Toggle Status -->
                                                <form method="POST" class="inline">
                                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                    <input type="hidden" name="action" value="toggle_status">
                                                    <button type="submit" class="text-blue-600 hover:text-blue-900">
                                                        <?php echo $user['is_active'] ? 'Desativar' : 'Ativar'; ?>
                                                    </button>
                                                </form>
                                                
                                                <!-- Add Balance -->
                                                <button onclick="showBalanceModal(<?php echo $user['id']; ?>, 'add')" 
                                                        class="text-green-600 hover:text-green-900">
                                                    + Saldo
                                                </button>
                                                
                                                <!-- Remove Balance -->
                                                <button onclick="showBalanceModal(<?php echo $user['id']; ?>, 'remove')" 
                                                        class="text-red-600 hover:text-red-900">
                                                    - Saldo
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                        <div class="px-6 py-3 border-t">
                            <div class="flex justify-between items-center">
                                <div class="text-sm text-gray-700">
                                    Página <?php echo $page; ?> de <?php echo $total_pages; ?>
                                </div>
                                <div class="flex gap-2">
                                    <?php if ($page > 1): ?>
                                        <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>" 
                                           class="px-3 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                                            Anterior
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($page < $total_pages): ?>
                                        <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>" 
                                           class="px-3 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                                            Próxima
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
    
    <!-- Balance Modal -->
    <div id="balanceModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg p-6 w-full max-w-md">
                <h3 id="modalTitle" class="text-lg font-semibold mb-4"></h3>
                <form method="POST">
                    <input type="hidden" id="modalUserId" name="user_id">
                    <input type="hidden" id="modalAction" name="action">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Valor (R$)</label>
                        <input type="number" step="0.01" name="amount" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>
                    <div class="flex gap-4">
                        <button type="submit" class="flex-1 bg-primary text-white py-2 rounded-md hover:bg-primary/90">
                            Confirmar
                        </button>
                        <button type="button" onclick="hideBalanceModal()" 
                                class="flex-1 bg-gray-500 text-white py-2 rounded-md hover:bg-gray-600">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        function showBalanceModal(userId, action) {
            document.getElementById('modalUserId').value = userId;
            document.getElementById('modalAction').value = action + '_balance';
            document.getElementById('modalTitle').textContent = action === 'add' ? 'Adicionar Saldo' : 'Remover Saldo';
            document.getElementById('balanceModal').classList.remove('hidden');
        }
        
        function hideBalanceModal() {
            document.getElementById('balanceModal').classList.add('hidden');
        }
    </script>
    
    <?php include '../includes/mobile_navbar.php'; ?>
</body>
</html>