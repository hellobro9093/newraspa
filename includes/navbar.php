<?php
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/functions.php';

$navData = getNavbarData();
?>

<header class="topbar bg-sidebar h-[72px] fixed z-50 right-0 left-0! text-[0.92rem]">
    <div class="mx-auto max-w-(--max-layout-width) px-4 w-full h-full">
        <nav class="flex justify-between items-center h-full gap-6">
            <div class="flex items-center gap-4">
                <a href="/" class="text-2xl font-semibold">
                    <img src="https://ik.imagekit.io/azx3nlpdu/logo.png?updatedAt=1752172431999" class="h-[42px] object-contain">
                </a>
            </div>
            
            <div class="md:flex items-center gap-6 font-medium hidden *:hover:bg-accent *:px-2 *:py-1 *:rounded-sm *:active:scale-90 *:transition-transform">
                <a href="/" class="">Inicio</a>
                <a href="/raspadinhas" class="">Raspadinhas</a>
                <a href="/indique" class="">Indique e Ganhe</a>
                <?php if (isAdmin()): ?>
                    <a href="/admin" class="">Admin</a>
                <?php endif; ?>
            </div>
            
            <div class="ml-auto flex items-center gap-1.5">
                <?php if (isLoggedIn()): ?>
                    <section class="flex items-center bg-accent h-9 rounded-md gap-3 px-3.5 has-[>svg]:px-3 cursor-pointer">
                        <span class="font-semibold text-sm"><?php echo formatCurrency($navData['balance']); ?></span>
                        <svg width="1em" height="1em" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="size-3.5 text-muted-foreground -ml-1">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m4 9 8 8 8-8"></path>
                        </svg>
                    </section>
                    
                    <div class="flex items-center gap-2 ml-1">
                        <button class="inline-flex items-center justify-center whitespace-nowrap text-sm transition-all disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg:not([class*='size-'])]:size-4 shrink-0 [&_svg]:shrink-0 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive bg-primary text-primary-foreground shadow-xs hover:bg-primary/90 h-8 rounded-md gap-1.5 px-3 has-[>svg]:px-2.5 font-semibold cursor-pointer">
                            <svg fill="none" viewBox="0 0 24 24" width="1em" height="1em" xmlns="http://www.w3.org/2000/svg" class="size-5">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 15v3m0 3v-3m0 0h-3m3 0h3"></path>
                                <path fill="currentColor" fill-rule="evenodd" d="M5 5a3 3 0 0 0-3 3v8a3 3 0 0 0 3 3h7.083A6 6 0 0 1 12 18c0-1.148.322-2.22.881-3.131A3 3 0 0 1 9 12a3 3 0 1 1 5.869.881A5.97 5.97 0 0 1 18 12c1.537 0 2.939.578 4 1.528V8a3 3 0 0 0-3-3zm7 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="sm:block hidden">Depositar</span>
                        </button>
                        
                        <button class="items-center justify-center whitespace-nowrap text-sm transition-all disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg:not([class*='size-'])]:size-4 shrink-0 [&_svg]:shrink-0 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive bg-primary text-primary-foreground shadow-xs hover:bg-primary/90 h-8 rounded-md gap-1.5 px-3 has-[>svg]:px-2.5 sm:flex hidden font-semibold cursor-pointer">
                            <svg viewBox="0 0 24 24" fill="currentColor" width="1em" height="1em" xmlns="http://www.w3.org/2000/svg" class="size-4">
                                <path d="M22 2H2a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h3v9a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-9h3a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1ZM7 20v-2a2 2 0 0 1 2 2Zm10 0h-2a2 2 0 0 1 2-2Zm0-4a4 4 0 0 0-4 4h-2a4 4 0 0 0-4-4V8h10Zm4-6h-2V7a1 1 0 0 0-1-1H6a1 1 0 0 0-1 1v3H3V4h18Zm-9 5a3 3 0 1 0-3-3 3 3 0 0 0 3 3Zm0-4a1 1 0 1 1-1 1 1 1 0 0 1 1-1Z"></path>
                            </svg>
                            Sacar
                        </button>
                    </div>
                    
                    <div class="flex items-center px-1.5 py-1 gap-1.5 rounded-sm cursor-pointer select-none group *:transition-all duration-200 ease-in-out relative">
                        <span class="relative flex size-8 shrink-0 overflow-hidden rounded-full">
                            <img role="img" src="<?php echo $navData['avatar']; ?>" class="aspect-square size-full" alt="@">
                        </span>
                        <div class="text-sm ml-0.5 font-medium overflow-hidden opacity-80 group-hover:opacity-100 text-nowrap text-ellipsis hidden sm:block w-17">
                            <?php echo htmlspecialchars($navData['username']); ?>
                        </div>
                        <svg width="1em" height="1em" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="size-4 mr-1 opacity-80 group-hover:opacity-100">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m4 9 8 8 8-8"></path>
                        </svg>
                        
                        <!-- Dropdown menu -->
                        <div class="absolute top-full right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden group-hover:block">
                            <a href="/perfil/conta" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Perfil</a>
                            <a href="/auth/logout" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sair</a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="flex items-center gap-2">
                        <a href="/auth/login" class="inline-flex items-center justify-center whitespace-nowrap text-sm transition-all bg-primary text-primary-foreground shadow-xs hover:bg-primary/90 h-8 rounded-md gap-1.5 px-3 font-semibold">
                            Entrar
                        </a>
                        <a href="/auth/register" class="inline-flex items-center justify-center whitespace-nowrap text-sm transition-all border border-primary text-primary hover:bg-primary hover:text-white h-8 rounded-md gap-1.5 px-3 font-semibold">
                            Cadastrar
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </nav>
    </div>
</header>