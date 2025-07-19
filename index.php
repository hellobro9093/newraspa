<?php
require_once 'includes/session.php';
require_once 'includes/functions.php';
?>

<!DOCTYPE html>
<html lang="pt-BR" class="dark" style="--primary: #08FFB2;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raspadinha - Jogue e Ganhe Prêmios Incríveis</title>
    <link rel="stylesheet" href="assets/index-BSEjf_DK.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    
    <div class="px-4 pt-[72px]">
        <main class="bg-surface rounded-b-xl w-full mb-4">
            <div class="mx-auto max-w-(--max-layout-width) min-h-[80lvh] w-full px-5 pt-6 md:pt-7 pb-[calc(68px+2.4rem)] md:pb-12">
                
                <!-- Hero Section -->
                <div class="text-center py-12 mb-12">
                    <h1 class="text-4xl md:text-6xl font-bold text-gray-800 mb-4">
                        Raspadinha Online
                    </h1>
                    <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                        Jogue raspadinhas digitais e ganhe prêmios incríveis! Diversão garantida com chances reais de ganhar.
                    </p>
                    
                    <?php if (isLoggedIn()): ?>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <a href="/raspadinhas.php" class="bg-primary text-white px-8 py-3 rounded-lg font-semibold hover:bg-primary/90 transition-colors">
                                Jogar Agora
                            </a>
                            <a href="/indique.php" class="border border-primary text-primary px-8 py-3 rounded-lg font-semibold hover:bg-primary hover:text-white transition-colors">
                                Indique e Ganhe
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <a href="/auth/register.php" class="bg-primary text-white px-8 py-3 rounded-lg font-semibold hover:bg-primary/90 transition-colors">
                                Cadastre-se Grátis
                            </a>
                            <a href="/auth/login.php" class="border border-primary text-primary px-8 py-3 rounded-lg font-semibold hover:bg-primary hover:text-white transition-colors">
                                Fazer Login
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Features -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                    <div class="text-center p-6 bg-white rounded-lg shadow-sm border">
                        <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Jogo Justo</h3>
                        <p class="text-gray-600">Sistema transparente e auditável para garantir jogos justos e seguros.</p>
                    </div>
                    
                    <div class="text-center p-6 bg-white rounded-lg shadow-sm border">
                        <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Prêmios Reais</h3>
                        <p class="text-gray-600">Ganhe dinheiro real que pode ser sacado diretamente para sua conta.</p>
                    </div>
                    
                    <div class="text-center p-6 bg-white rounded-lg shadow-sm border">
                        <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Indique e Ganhe</h3>
                        <p class="text-gray-600">Ganhe R$ 0,50 para cada amigo que você indicar para a plataforma.</p>
                    </div>
                </div>
                
                <!-- Popular Games -->
                <div class="mb-12">
                    <h2 class="text-3xl font-bold text-gray-800 text-center mb-8">Jogos Populares</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="bg-white rounded-lg shadow-sm border overflow-hidden hover:shadow-md transition-shadow">
                            <img src="https://images.pexels.com/photos/6664189/pexels-photo-6664189.jpeg?auto=compress&cs=tinysrgb&w=400" 
                                 alt="Raspadinha Clássica" class="w-full h-48 object-cover">
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-800 mb-2">Raspadinha Clássica</h3>
                                <p class="text-sm text-gray-600 mb-3">O jogo tradicional que todos amam</p>
                                <button class="w-full bg-primary text-white py-2 rounded-md hover:bg-primary/90 transition-colors">
                                    Jogar
                                </button>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg shadow-sm border overflow-hidden hover:shadow-md transition-shadow">
                            <img src="https://images.pexels.com/photos/6664189/pexels-photo-6664189.jpeg?auto=compress&cs=tinysrgb&w=400" 
                                 alt="Super Prêmio" class="w-full h-48 object-cover">
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-800 mb-2">Super Prêmio</h3>
                                <p class="text-sm text-gray-600 mb-3">Prêmios maiores, mais emoção</p>
                                <button class="w-full bg-primary text-white py-2 rounded-md hover:bg-primary/90 transition-colors">
                                    Jogar
                                </button>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg shadow-sm border overflow-hidden hover:shadow-md transition-shadow">
                            <img src="https://images.pexels.com/photos/6664189/pexels-photo-6664189.jpeg?auto=compress&cs=tinysrgb&w=400" 
                                 alt="Mega Sorte" class="w-full h-48 object-cover">
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-800 mb-2">Mega Sorte</h3>
                                <p class="text-sm text-gray-600 mb-3">Jackpots incríveis te esperam</p>
                                <button class="w-full bg-primary text-white py-2 rounded-md hover:bg-primary/90 transition-colors">
                                    Jogar
                                </button>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg shadow-sm border overflow-hidden hover:shadow-md transition-shadow">
                            <img src="https://images.pexels.com/photos/6664189/pexels-photo-6664189.jpeg?auto=compress&cs=tinysrgb&w=400" 
                                 alt="Eletrodomésticos" class="w-full h-48 object-cover">
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-800 mb-2">Eletrodomésticos</h3>
                                <p class="text-sm text-gray-600 mb-3">Ganhe produtos incríveis</p>
                                <button class="w-full bg-primary text-white py-2 rounded-md hover:bg-primary/90 transition-colors">
                                    Jogar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- CTA Section -->
                <?php if (!isLoggedIn()): ?>
                <div class="bg-primary text-white rounded-lg p-8 text-center">
                    <h2 class="text-3xl font-bold mb-4">Pronto para Começar?</h2>
                    <p class="text-xl mb-6 opacity-90">
                        Cadastre-se agora e comece a jogar! É grátis e você pode ganhar prêmios reais.
                    </p>
                    <a href="/auth/register.php" class="bg-white text-primary px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                        Cadastrar Agora
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
    
    <?php include 'includes/mobile_navbar.php'; ?>
</body>
</html>