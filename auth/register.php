<?php
require_once '../includes/session.php';
require_once '../config/database.php';
require_once '../includes/functions.php';

if (isLoggedIn()) {
    header('Location: /');
    exit();
}

$error = '';
$success = '';

if ($_POST) {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $affiliate_code = $_POST['affiliate_code'] ?? '';
    
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = 'Por favor, preencha todos os campos obrigatórios.';
    } elseif ($password !== $confirm_password) {
        $error = 'As senhas não coincidem.';
    } elseif (strlen($password) < 6) {
        $error = 'A senha deve ter pelo menos 6 caracteres.';
    } else {
        $database = new Database();
        $db = $database->getConnection();
        
        // Check if username or email already exists
        $query = "SELECT id FROM users WHERE username = ? OR email = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$username, $email]);
        
        if ($stmt->rowCount() > 0) {
            $error = 'Nome de usuário ou email já existe.';
        } else {
            // Validate affiliate code if provided
            $referred_by = null;
            if (!empty($affiliate_code)) {
                $query = "SELECT id FROM users WHERE affiliate_code = ?";
                $stmt = $db->prepare($query);
                $stmt->execute([$affiliate_code]);
                
                if ($stmt->rowCount() == 0) {
                    $error = 'Código de afiliado inválido.';
                } else {
                    $referred_by = $affiliate_code;
                }
            }
            
            if (empty($error)) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $user_affiliate_code = generateAffiliateCode();
                
                $query = "INSERT INTO users (username, email, password, affiliate_code, referred_by) VALUES (?, ?, ?, ?, ?)";
                $stmt = $db->prepare($query);
                
                if ($stmt->execute([$username, $email, $hashed_password, $user_affiliate_code, $referred_by])) {
                    $user_id = $db->lastInsertId();
                    
                    // Process affiliate bonus if user was referred
                    if ($referred_by) {
                        processAffiliateBonus($user_id);
                    }
                    
                    $success = 'Conta criada com sucesso! Você pode fazer login agora.';
                } else {
                    $error = 'Erro ao criar conta. Tente novamente.';
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR" class="dark" style="--primary: #08FFB2;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Raspadinha</title>
    <link rel="stylesheet" href="../assets/index-BSEjf_DK.css">
</head>
<body>
    <div class="min-h-screen bg-gray-100 flex items-center justify-center py-8">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
            <div class="text-center mb-6">
                <img src="https://ik.imagekit.io/azx3nlpdu/logo.png?updatedAt=1752172431999" class="h-12 mx-auto mb-4">
                <h1 class="text-2xl font-bold text-gray-800">Cadastrar</h1>
            </div>
            
            <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Nome de usuário</label>
                    <input type="text" id="username" name="username" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                           value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
                </div>
                
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" id="email" name="email" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                </div>
                
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Senha</label>
                    <input type="password" id="password" name="password" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                </div>
                
                <div class="mb-4">
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">Confirmar senha</label>
                    <input type="password" id="confirm_password" name="confirm_password" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                </div>
                
                <div class="mb-6">
                    <label for="affiliate_code" class="block text-sm font-medium text-gray-700 mb-2">Código de afiliado (opcional)</label>
                    <input type="text" id="affiliate_code" name="affiliate_code" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                           value="<?php echo htmlspecialchars($_POST['affiliate_code'] ?? $_GET['ref'] ?? ''); ?>">
                </div>
                
                <button type="submit" 
                        class="w-full bg-primary text-white py-2 px-4 rounded-md hover:bg-primary/90 transition-colors">
                    Cadastrar
                </button>
            </form>
            
            <div class="text-center mt-4">
                <p class="text-gray-600">
                    Já tem uma conta? 
                    <a href="/auth/login" class="text-primary hover:underline">Faça login</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>