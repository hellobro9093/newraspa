<?php
require_once 'includes/session.php';
require_once 'includes/functions.php';

requireLogin();

$userData = getUserData();
$stats = getUserStats($_SESSION['user_id']);
$recent_referrals = getRecentReferrals($_SESSION['user_id']);

// Gerar link de afiliado
$affiliate_link = $_SERVER['HTTP_HOST'] . '/auth/register.php?ref=' . $userData['affiliate_code'];
?>

<!DOCTYPE html>
<html lang="pt-BR" class="dark" style="--primary: #08FFB2;">
<head>
  <meta charset="UTF-8">
  <link rel="icon" type="image/x-icon" href="https://ik.imagekit.io/azx3nlpdu/">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, viewport-fit=cover, user-scalable=no, shrink-to-fit=no">
  <meta name="darkreader-lock">
  <title>Raspadinha</title>
  <link rel="stylesheet" crossorigin="" href="/assets/index-BSEjf_DK.css">
</head>

<body>
  <?php include 'includes/navbar.php'; ?>

  <div class="px-4 pt-[72px]">
    <main class="bg-surface rounded-b-xl w-full mb-4">
      <div class="mx-auto max-w-(--max-layout-width) min-h-[80lvh] w-full px-5 pt-6 md:pt-7 pb-[calc(68px+2.4rem)] md:pb-12">
        <div>
          <section class="grid grid-cols-1 md:grid-cols-3 gap-6 w-full">
            <div class="flex flex-col gap-4 sm:col-span-1">
              <div data-slot="card" class="bg-card text-card-foreground flex flex-col rounded-xl border py-6 shadow-sm w-full !p-7 gap-3">
                <span data-slot="avatar" class="relative flex shrink-0 overflow-hidden rounded-full size-17 sm:size-14 self-center md:self-start">
                  <img role="img" src="https://ik.imagekit.io/azx3nlpdu/flaticon_4140048.svg?updatedAt=1751619848402" data-slot="avatar-image" class="aspect-square size-full" alt="@">
                </span>
                <div class="flex gap-3 items-center mb-0">
                  <h1><?php echo htmlspecialchars($userData['username']); ?></h1>
                  <div class="flex items-center gap-2 py-1 px-1.5 rounded-sm border">
                    <img class="size-5 sm:size-6 object-contain" src="/assets/level_1-BnAWvPcq.png" alt="level">
                    <span class="text-sm text-blue-300 font-medium">Nível 1</span>
                  </div>
                </div>
                <div class="flex gap-2">
                  <div class="py-0.5 px-1.5 bg-secondary border rounded-sm w-fit text-sm mb-1">
                    <span class="opacity-70">Comissão R$ 0,50</span>
                  </div>
                </div>
                <div class="flex flex-col gap-2">
                  <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="<?php echo min(100, ($stats['total_referrals'] / 10) * 100); ?>" aria-label="<?php echo min(100, ($stats['total_referrals'] / 10) * 100); ?>%" role="progressbar" data-state="loading" data-value="<?php echo min(100, ($stats['total_referrals'] / 10) * 100); ?>" data-max="100" data-slot="progress" class="bg-primary/20 relative h-2 w-full overflow-hidden rounded-full">
                    <div data-state="loading" data-value="<?php echo min(100, ($stats['total_referrals'] / 10) * 100); ?>" data-max="100" data-slot="progress-indicator" class="bg-primary h-full w-full flex-1 transition-all" style="transform: translateX(-<?php echo 100 - min(100, ($stats['total_referrals'] / 10) * 100); ?>%);"></div>
                  </div>
                </div>
                <div class="flex justify-between items-center mb-3">
                  <span class="text-sm opacity-60"><?php echo $stats['total_referrals']; ?> / 10 Indicações</span>
                  <span class="text-sm opacity-60">Nível 1</span>
                </div>
                <button data-slot="button" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-all disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg:not([class*='size-'])]:size-4 shrink-0 [&_svg]:shrink-0 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive bg-primary text-primary-foreground shadow-xs hover:bg-primary/90 h-9 px-4 py-2 has-[>svg]:px-3">
                  Ver níveis
                </button>
              </div>
            </div>
            
            <div class="flex flex-col md:col-span-2 gap-3">
              <div class="flex flex-col">
                <h1 class="mb-2">Link de referência</h1>
                <div class="flex">
                  <div data-slot="card" class="bg-card text-card-foreground flex flex-col gap-6 rounded-xl border py-6 shadow-sm !flex-row w-full !justify-between !p-6 !items-center">
                    <div class="flex flex-col gap-2 w-full">
                      <div class="flex justify-between items-center">
                        <div class="flex flex-col gap-1">
                          <span class="opacity-60">Seu Código</span>
                          <div class="text-2xl font-semibold">
                            <span class="opacity-60">r/</span><?php echo htmlspecialchars($userData['affiliate_code']); ?>
                          </div>
                        </div>
                        <button onclick="copyCode()" data-slot="button" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-all disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg:not([class*='size-'])]:size-4 shrink-0 [&_svg]:shrink-0 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive bg-primary text-primary-foreground shadow-xs hover:bg-primary/90 h-10 rounded-md px-6 has-[>svg]:px-4 cursor-pointer">
                          Copiar Código
                        </button>
                      </div>
                      <div class="flex justify-between items-center mt-2 border-t pt-3">
                        <span class="text-sm opacity-60">https://<?php echo $affiliate_link; ?></span>
                        <button onclick="copyLink()" data-slot="button" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium transition-all disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg:not([class*='size-'])]:size-4 shrink-0 [&_svg]:shrink-0 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive h-8 rounded-md gap-1.5 px-3 has-[>svg]:px-2.5 cursor-pointer">
                          <svg width="1em" height="1em" viewBox="0 0 512 512" fill="currentColor" xmlns="http://www.w3.org/2000/svg" class="size-5.5 text-card-foreground">
                            <path d="M336 192h40a40 40 0 0 1 40 40v192a40 40 0 0 1-40 40H136a40 40 0 0 1-40-40V232a40 40 0 0 1 40-40h40M336 128l-80-80-80 80M256 321V48" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32px"></path>
                          </svg>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Estatísticas -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div data-slot="card" class="bg-card text-card-foreground rounded-xl border shadow-sm p-4">
                  <div class="flex items-center justify-between">
                    <div>
                      <p class="text-sm text-muted-foreground">Total de Indicações</p>
                      <p class="text-2xl font-bold text-gray-800"><?php echo $stats['total_referrals']; ?></p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-full">
                      <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                      </svg>
                    </div>
                  </div>
                </div>
                
                <div data-slot="card" class="bg-card text-card-foreground rounded-xl border shadow-sm p-4">
                  <div class="flex items-center justify-between">
                    <div>
                      <p class="text-sm text-muted-foreground">Ganhos Totais</p>
                      <p class="text-2xl font-bold text-green-600"><?php echo formatCurrency($stats['affiliate_earnings']); ?></p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-full">
                      <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"></path>
                      </svg>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Indicações Recentes -->
              <?php if (!empty($recent_referrals)): ?>
              <div class="mt-4">
                <h2 class="text-lg font-semibold mb-3">Suas Indicações Recentes</h2>
                <div data-slot="card" class="bg-card text-card-foreground rounded-xl border shadow-sm p-4">
                  <div class="space-y-3">
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
          </section>
        </div>
      </div>
      
      <div class="flex justify-between">
        <div class="bg-sidebar fixed z-1 top-[72px]">
          <div class="bg-surface rounded-tl-xl size-4"></div>
        </div>
        <div class="bg-sidebar fixed z-1 top-[72px] right-4">
          <div class="bg-surface rounded-tr-xl size-4"></div>
        </div>
      </div>
    </main>
  </div>
  
  <footer class="footer bg-sidebar w-full text-sm mt-8">
    <div class="flex flex-col flex-wrap md:flex-row gap-5 md:gap-0 mx-auto max-w-(--max-layout-width) px-4 w-full h-full">
      <div class="flex flex-col w-full md:w-[35%]">
        <div class="flex flex-col gap-4 h-full">
          <a href="/" class="">
            <img src="https://ik.imagekit.io/azx3nlpdu/logo.png?updatedAt=1752172431999" class="footer-logo w-[140px] min-w-[140px] block" alt="">
          </a>
          <div class="text-xxs opacity-65">
            © 2025 raspadinha.dancsolutions.com. Todos os direitos reservados.
          </div>
          <div class="text-xxs opacity-50">
            Raspadinhas e outros jogos de azar são regulamentados e cobertos pela nossa licença de jogos. Jogue com responsabilidade.
          </div>
        </div>
      </div>
      <div class="w-full md:w-[65%] md:pl-12 flex flex-col justify-between gap-12 mt-3 sm:mt-0">
        <div class="flex flex-wrap sm:justify-end gap-32 w-full">
          <div class="flex flex-col gap-y-0.5">
            <strong class="text-left mb-2.5">Regulamentos</strong>
            <div class="flex flex-col justify-center md:justify-start gap-3 flex-wrap [&>*]:text-sm [&>*]:opacity-70">
              <a href="#" class="footer_link">Jogo responsável</a>
              <a href="#" class="footer_link">Política de Privacidade</a>
              <a href="#" class="footer_link">Termos de Uso</a>
            </div>
          </div>
          <div class="flex flex-col gap-y-0.5">
            <strong class="text-left mb-2.5">Ajuda</strong>
            <div class="flex flex-col justify-center md:justify-start gap-3 flex-wrap [&>*]:text-sm [&>*]:opacity-70">
              <a href="#" class="footer_link">Perguntas Frequentes</a>
              <a href="#" class="footer_link">Como Jogar</a>
              <a href="#" class="footer_link">Suporte Técnico</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </footer>

  <?php include 'includes/mobile_navbar.php'; ?>

  <script>
    function copyCode() {
      const code = '<?php echo $userData['affiliate_code']; ?>';
      navigator.clipboard.writeText(code).then(function() {
        showToast('Código copiado!');
      });
    }
    
    function copyLink() {
      const link = 'https://<?php echo $affiliate_link; ?>';
      navigator.clipboard.writeText(link).then(function() {
        showToast('Link copiado!');
      });
    }
    
    function showToast(message) {
      // Criar toast notification
      const toast = document.createElement('div');
      toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-md z-50';
      toast.textContent = message;
      document.body.appendChild(toast);
      
      setTimeout(() => {
        document.body.removeChild(toast);
      }, 3000);
    }
  </script>
</body>
</html>