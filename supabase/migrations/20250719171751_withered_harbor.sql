-- Criação do banco de dados
CREATE DATABASE IF NOT EXISTS raspadinha_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE raspadinha_db;

-- Tabela de usuários
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NULL,
    document VARCHAR(20) NULL,
    balance DECIMAL(10,2) DEFAULT 0.00,
    total_deposited DECIMAL(10,2) DEFAULT 0.00,
    total_withdrawn DECIMAL(10,2) DEFAULT 0.00,
    cashback_earned DECIMAL(10,2) DEFAULT 0.00,
    affiliate_code VARCHAR(10) UNIQUE NOT NULL,
    referred_by VARCHAR(10) NULL,
    affiliate_earnings DECIMAL(10,2) DEFAULT 0.00,
    is_admin BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_affiliate_code (affiliate_code),
    INDEX idx_referred_by (referred_by)
);

-- Tabela de transações
CREATE TABLE transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type ENUM('deposit', 'withdrawal', 'game_win', 'game_loss', 'affiliate_bonus', 'cashback') NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'completed', 'failed', 'cancelled') DEFAULT 'pending',
    description TEXT NULL,
    reference_id VARCHAR(100) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_type (type),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
);

-- Tabela de jogos
CREATE TABLE games (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    game_type VARCHAR(50) NOT NULL,
    bet_amount DECIMAL(10,2) NOT NULL,
    win_amount DECIMAL(10,2) DEFAULT 0.00,
    result ENUM('win', 'loss') NOT NULL,
    game_data JSON NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_game_type (game_type),
    INDEX idx_created_at (created_at)
);

-- Tabela de indicações/afiliados
CREATE TABLE affiliate_referrals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    referrer_id INT NOT NULL,
    referred_id INT NOT NULL,
    commission_earned DECIMAL(10,2) DEFAULT 0.50,
    status ENUM('pending', 'paid') DEFAULT 'paid',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (referrer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (referred_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_referral (referrer_id, referred_id),
    INDEX idx_referrer_id (referrer_id),
    INDEX idx_referred_id (referred_id)
);

-- Tabela de configurações do sistema
CREATE TABLE system_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT NOT NULL,
    description TEXT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Inserir configurações padrão
INSERT INTO system_settings (setting_key, setting_value, description) VALUES
('affiliate_bonus', '0.50', 'Valor do bônus por indicação em reais'),
('min_withdrawal', '10.00', 'Valor mínimo para saque'),
('max_withdrawal', '5000.00', 'Valor máximo para saque'),
('site_maintenance', '0', 'Site em manutenção (0=não, 1=sim)'),
('registration_enabled', '1', 'Registro habilitado (0=não, 1=sim)');

-- Criar usuário admin padrão
INSERT INTO users (username, email, password, affiliate_code, is_admin) VALUES 
('admin', 'admin@raspadinha.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ADMIN001', TRUE);

-- Função para gerar código de afiliado único
DELIMITER //
CREATE FUNCTION generate_affiliate_code() RETURNS VARCHAR(10)
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE code VARCHAR(10);
    DECLARE done INT DEFAULT 0;
    
    REPEAT
        SET code = UPPER(SUBSTRING(MD5(RAND()), 1, 8));
        SELECT COUNT(*) INTO done FROM users WHERE affiliate_code = code;
    UNTIL done = 0 END REPEAT;
    
    RETURN code;
END//
DELIMITER ;