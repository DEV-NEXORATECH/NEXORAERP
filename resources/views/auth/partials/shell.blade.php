<style>
    html:has(.nx-auth-wrapper),
    body:has(.nx-auth-wrapper) {
        height: 100%;
        overflow: hidden !important;
    }

    .nx-auth-wrapper {
        height: 100dvh;
        max-height: 100dvh;
    }
</style>

<main class="nx-auth-wrapper min-h-screen w-full flex flex-col lg:flex-row overflow-hidden bg-white text-[#0B2345] antialiased">
    <!-- Left Column: Realistic Laptop ERP Dashboard with Dark Blue Overlay (55% on desktop) -->
    <section class="nx-auth-visual hidden lg:flex lg:w-[55%] relative overflow-hidden flex-col justify-between p-12 xl:p-16 text-white select-none">
        <!-- Background Image: Realistic Laptop with ERP Dashboard -->
        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat transition-transform duration-700 ease-out scale-100 hover:scale-105" 
             style="background-image: url('{{ asset('images/erp-laptop-dashboard.jpg') }}');">
        </div>
        
        <!-- Dark Blue (#0B2345) Transparent Overlay -->
        <div class="absolute inset-0 bg-[#0B2345]/80 backdrop-blur-[1.5px]"></div>
        
        <!-- Subtle linear gradient overlay for depth -->
        <div class="absolute inset-0 bg-gradient-to-t from-[#0B2345]/95 via-[#0B2345]/60 to-[#0B2345]/40"></div>

        <!-- Top Header: NX Monogram Logo -->
        <div class="relative z-10 flex items-center gap-3.5">
            <img class="h-11 w-11 rounded-[10px] object-contain" src="{{ asset('images/nexora-login-logo.png') }}" alt="NEXORA360">
            <div class="flex flex-col">
                <span class="text-base font-bold tracking-wider text-white">NEXORA360</span>
                <span class="text-[11px] font-medium tracking-widest text-white/70 uppercase">Enterprise ERP</span>
            </div>
        </div>

        <!-- Bottom Content: Tagline -->
        <div class="relative z-10 max-w-lg mb-4">
            <div class="inline-block px-3 py-1 mb-4 rounded-full bg-white/10 text-xs font-semibold tracking-wider text-white/90 uppercase backdrop-blur-sm border border-white/15">
                Next-Gen ERP System
            </div>
            <h2 class="text-3xl xl:text-4xl font-extrabold text-white leading-tight tracking-tight">
                Kelola ERP,<br>Maksimalkan Potensi.
            </h2>
            <div class="h-1 w-12 bg-white/40 mt-5 rounded-full"></div>
        </div>
    </section>

    <!-- Right Column: Clean White Form (45% on desktop, 100% on mobile) -->
    <section class="nx-auth-form-panel w-full lg:w-[45%] min-h-screen flex flex-col justify-between bg-white px-6 py-8 sm:px-10 sm:py-12 md:px-14 lg:px-12 xl:px-16 overflow-hidden">
        <!-- Mobile Header (Hidden on Desktop) -->
        <div class="lg:hidden flex items-center justify-center gap-3 pt-2 pb-6">
            <img class="h-10 w-10 rounded-[10px] object-contain" src="{{ asset('images/nexora-login-logo.png') }}" alt="NEXORA360">
            <div class="flex flex-col">
                <span class="text-lg font-bold tracking-wider text-[#0B2345]">NEXORA360</span>
                <span class="text-[10px] font-semibold tracking-widest text-[#0B2345]/60 uppercase">Enterprise ERP</span>
            </div>
        </div>

        <!-- Form Center Container -->
        <div class="w-full max-w-[420px] mx-auto my-auto py-4">
            {{ $slot }}
        </div>

        <!-- Footer: Powered by NEXORA Technology -->
        <footer class="w-full pt-8 pb-2 text-center flex items-center justify-center gap-3 text-sm text-black select-none">
            <span class="font-semibold text-black">Powered by</span>
            <img class="h-[5.5rem] w-auto max-w-[460px] object-contain" src="{{ asset('images/nexora-technology.png') }}" alt="NEXORA Technology">
        </footer>
    </section>
</main>
