@php
    $hasAlert = session('status') || session('success') || session('error') || session('danger') || session('warning') || session('temporary_password') || $errors->any();
@endphp

@if ($hasAlert)
<div id="erp-toast-container" class="fixed top-5 right-5 z-[9999] flex flex-col gap-2.5 max-w-[360px] w-full pointer-events-none transition-all duration-300">
    {{-- Success Toast --}}
    @if (session('status') || session('success'))
        <div class="erp-toast-item pointer-events-auto flex items-start gap-3 rounded-xl border border-emerald-200 bg-white p-3.5 shadow-xl shadow-slate-900/10 transition-all duration-300 ease-out" role="status">
            <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-emerald-50 border border-emerald-200 text-emerald-600 mt-0.5">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 6 9 17l-5-5"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0 pr-1">
                <div class="text-xs font-bold text-[#0F172A]">Berhasil</div>
                <div class="text-xs text-[#475569] leading-snug mt-0.5 break-words">{{ session('status') ?? session('success') }}</div>
            </div>
            <button type="button" onclick="dismissErpToast(this)" class="shrink-0 text-[#94A3B8] hover:text-[#0F172A] p-0.5 rounded transition cursor-pointer" title="Tutup">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    {{-- Temporary Password Toast --}}
    @if (session('temporary_password'))
        <div class="erp-toast-item pointer-events-auto flex items-start gap-3 rounded-xl border border-blue-200 bg-white p-3.5 shadow-xl shadow-slate-900/10 transition-all duration-300 ease-out" role="status">
            <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-blue-50 border border-blue-200 text-[#002875] mt-0.5">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0 pr-1">
                <div class="text-xs font-bold text-[#0F172A]">Password Sementara</div>
                <div class="text-xs text-[#475569] leading-snug mt-0.5 font-mono bg-slate-50 p-1.5 rounded border border-slate-200 break-all">{{ session('temporary_password') }}</div>
            </div>
            <button type="button" onclick="dismissErpToast(this)" class="shrink-0 text-[#94A3B8] hover:text-[#0F172A] p-0.5 rounded transition cursor-pointer" title="Tutup">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    {{-- Warning Toast --}}
    @if (session('warning'))
        <div class="erp-toast-item pointer-events-auto flex items-start gap-3 rounded-xl border border-amber-200 bg-white p-3.5 shadow-xl shadow-slate-900/10 transition-all duration-300 ease-out" role="status">
            <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-amber-50 border border-amber-200 text-amber-600 mt-0.5">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0 pr-1">
                <div class="text-xs font-bold text-[#0F172A]">Peringatan</div>
                <div class="text-xs text-[#475569] leading-snug mt-0.5 break-words">{{ session('warning') }}</div>
            </div>
            <button type="button" onclick="dismissErpToast(this)" class="shrink-0 text-[#94A3B8] hover:text-[#0F172A] p-0.5 rounded transition cursor-pointer" title="Tutup">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    {{-- Error Toast --}}
    @if (session('error') || session('danger') || $errors->any())
        <div class="erp-toast-item pointer-events-auto flex items-start gap-3 rounded-xl border border-red-200 bg-white p-3.5 shadow-xl shadow-slate-900/10 transition-all duration-300 ease-out" role="alert">
            <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-red-50 border border-red-200 text-red-600 mt-0.5">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0 pr-1">
                <div class="text-xs font-bold text-[#DC2626]">Perlu Diperbaiki</div>
                <div class="text-xs text-[#475569] leading-snug mt-0.5 break-words">
                    {{ session('error') ?? session('danger') ?? $errors->first() }}
                </div>
            </div>
            <button type="button" onclick="dismissErpToast(this)" class="shrink-0 text-[#94A3B8] hover:text-[#0F172A] p-0.5 rounded transition cursor-pointer" title="Tutup">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
        </div>
    @endif
</div>

<script>
    function dismissErpToast(btn) {
        const item = btn.closest('.erp-toast-item');
        if (!item) return;
        item.style.opacity = '0';
        item.style.transform = 'translateX(20px)';
        setTimeout(() => item.remove(), 250);
    }

    document.addEventListener('DOMContentLoaded', function () {
        setTimeout(function () {
            document.querySelectorAll('.erp-toast-item').forEach(function (toast) {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(20px)';
                setTimeout(() => toast.remove(), 250);
            });
        }, 4000);
    });
</script>
@endif
