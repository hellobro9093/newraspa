<?php
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/functions.php';
?>

<div class="bg-surface rounded-xl border-t shadow-lg z-10 fixed bottom-2 left-2 right-2 px-2 h-[72px] flex items-center gap-x-2.5 md:hidden">
    <a href="/" class="group flex flex-col items-center justify-center gap-1 text-center text-inherit select-none flex-1 transition-transform active:scale-90">
        <div>
            <svg width="1em" height="1em" viewBox="0 0 512 512" fill="currentColor" xmlns="http://www.w3.org/2000/svg" class="size-5">
                <path d="M416 174.74V48h-80v58.45L256 32 0 272h64v208h144V320h96v160h144V272h64z"></path>
            </svg>
        </div>
        <span class="text-[0.7rem] font-medium">Início</span>
    </a>
    
    <a href="/raspadinhas" class="group flex flex-col items-center justify-center gap-1 text-center text-inherit select-none flex-1 transition-transform active:scale-90">
        <div>
            <svg width="1em" height="1em" fill="currentColor" class="bi bi-ticket-perforated-fill size-5" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 4.5A1.5 1.5 0 0 1 1.5 3h13A1.5 1.5 0 0 1 16 4.5V6a.5.5 0 0 1-.5.5 1.5 1.5 0 0 0 0 3 .5.5 0 0 1 .5.5v1.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 11.5V10a.5.5 0 0 1 .5-.5 1.5 1.5 0 1 0 0-3A.5.5 0 0 1 0 6zm4-1v1h1v-1zm1 3v-1H4v1zm7 0v-1h-1v1zm-1-2h1v-1h-1zm-6 3H4v1h1zm7 1v-1h-1v1zm-7 1H4v1h1zm7 1v-1h-1v1zm-8 1v1h1v-1zm7 1h1v-1h-1z"></path>
            </svg>
        </div>
        <span class="text-[0.7rem] font-medium">Raspadinhas</span>
    </a>
    
    <?php if (isLoggedIn()): ?>
        <button class="group flex flex-col items-center justify-center gap-1 text-center text-inherit select-none -translate-y-[1.25rem]">
            <div class="bg-primary rounded-full border-4 border-surface text-primary-contrast p-3 transition-transform group-active:scale-90">
                <svg fill="none" viewBox="0 0 24 24" width="1em" height="1em" xmlns="http://www.w3.org/2000/svg" class="size-[1.6rem]">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 15v3m0 3v-3m0 0h-3m3 0h3"></path>
                    <path fill="currentColor" fill-rule="evenodd" d="M5 5a3 3 0 0 0-3 3v8a3 3 0 0 0 3 3h7.083A6 6 0 0 1 12 18c0-1.148.322-2.22.881-3.131A3 3 0 0 1 9 12a3 3 0 1 1 5.869.881A5.97 5.97 0 0 1 18 12c1.537 0 2.939.578 4 1.528V8a3 3 0 0 0-3-3zm7 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <span class="text-[0.7rem] font-medium">Depósitar</span>
        </button>
        
        <a href="/indique" class="group flex flex-col items-center justify-center gap-1 text-center text-inherit select-none flex-1 transition-transform active:scale-90">
            <div>
                <svg viewBox="0 0 640 512" fill="currentColor" width="1em" height="1em" xmlns="http://www.w3.org/2000/svg" class="size-5">
                    <path d="M96 128a128 128 0 1 1 256 0 128 128 0 1 1-256 0zM0 482.3C0 383.8 79.8 304 178.3 304h91.4c98.5 0 178.3 79.8 178.3 178.3 0 16.4-13.3 29.7-29.7 29.7H29.7C13.3 512 0 498.7 0 482.3zM504 312v-64h-64c-13.3 0-24-10.7-24-24s10.7-24 24-24h64v-64c0-13.3 10.7-24 24-24s24 10.7 24 24v64h64c13.3 0 24 10.7 24 24s-10.7 24-24 24h-64v64c0 13.3-10.7 24-24 24s-24-10.7-24-24z"></path>
                </svg>
            </div>
            <span class="text-[0.7rem] font-medium">Indique</span>
        </a>
        
        <a href="/perfil/conta" class="group flex flex-col items-center justify-center gap-1 text-center text-inherit select-none flex-1 transition-transform active:scale-90">
            <div>
                <svg viewBox="0 0 448 512" fill="currentColor" width="1em" height="1em" xmlns="http://www.w3.org/2000/svg" class="size-5">
                    <path d="M224 256a128 128 0 1 0 0-256 128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3 0 498.7 13.3 512 29.7 512h388.6c16.4 0 29.7-13.3 29.7-29.7 0-98.5-79.8-178.3-178.3-178.3z"></path>
                </svg>
            </div>
            <span class="text-[0.7rem] font-medium">Perfil</span>
        </a>
    <?php else: ?>
        <a href="/auth/login" class="group flex flex-col items-center justify-center gap-1 text-center text-inherit select-none flex-1 transition-transform active:scale-90">
            <div>
                <svg viewBox="0 0 448 512" fill="currentColor" width="1em" height="1em" xmlns="http://www.w3.org/2000/svg" class="size-5">
                    <path d="M224 256a128 128 0 1 0 0-256 128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3 0 498.7 13.3 512 29.7 512h388.6c16.4 0 29.7-13.3 29.7-29.7 0-98.5-79.8-178.3-178.3-178.3z"></path>
                </svg>
            </div>
            <span class="text-[0.7rem] font-medium">Entrar</span>
        </a>
        
        <a href="/auth/register" class="group flex flex-col items-center justify-center gap-1 text-center text-inherit select-none flex-1 transition-transform active:scale-90">
            <div>
                <svg viewBox="0 0 640 512" fill="currentColor" width="1em" height="1em" xmlns="http://www.w3.org/2000/svg" class="size-5">
                    <path d="M96 128a128 128 0 1 1 256 0 128 128 0 1 1-256 0zM0 482.3C0 383.8 79.8 304 178.3 304h91.4c98.5 0 178.3 79.8 178.3 178.3 0 16.4-13.3 29.7-29.7 29.7H29.7C13.3 512 0 498.7 0 482.3zM504 312v-64h-64c-13.3 0-24-10.7-24-24s10.7-24 24-24h64v-64c0-13.3 10.7-24 24-24s24 10.7 24 24v64h64c13.3 0 24 10.7 24 24s-10.7 24-24 24h-64v64c0 13.3-10.7 24-24 24s-24-10.7-24-24z"></path>
                </svg>
            </div>
            <span class="text-[0.7rem] font-medium">Cadastrar</span>
        </a>
    <?php endif; ?>
</div>