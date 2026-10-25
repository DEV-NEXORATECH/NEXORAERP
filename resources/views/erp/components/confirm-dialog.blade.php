@props([
    'id'          => 'confirm-modal',
    'title'       => 'Konfirmasi Tindakan',
    'message'     => 'Apakah Anda yakin ingin memproses tindakan ini? Tindakan ini tidak dapat dibatalkan.',
    'confirmText' => 'Ya, Lanjutkan',
    'cancelText'  => 'Batal',
    'action'      => '',
    'method'      => 'POST',
    'isDanger'    => false,
])

<dialog id="{{ $id }}" class="fixed inset-0 z-50 m-auto w-full max-w-md rounded-[12px] border border-[#D1D5DB] bg-white p-0 shadow-xl backdrop:bg-[#001B50]/40 backdrop:backdrop-blur-xs open:flex open:flex-col">
    <div class="flex items-center justify-between border-b border-[#E5E7EB] px-5 py-4">
        <div class="flex items-center gap-2.5">
            @if($isDanger)
                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-[#DC2626]/10 text-[#DC2626]">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 9v4m0 4h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                </div>
            @else
                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-[#002875]/10 text-[#002875]">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4m0-4h.01"/></svg>
                </div>
            @endif
            <h3 class="text-base font-bold text-[#111827] m-0">{{ $title }}</h3>
        </div>
        <button type="button" onclick="document.getElementById('{{ $id }}').close()" class="rounded-md p-1 text-[#6B7280] hover:bg-[#F3F4F6] hover:text-[#111827] transition">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
        </button>
    </div>

    <div class="p-5 text-sm text-[#475569] leading-relaxed">
        <p class="m-0">{{ $message }}</p>
        {{ $slot ?? '' }}
    </div>

    <div class="flex items-center justify-end gap-2.5 border-t border-[#E5E7EB] bg-[#F9FAFB] px-5 py-3.5 rounded-b-[12px]">
        <button type="button" onclick="document.getElementById('{{ $id }}').close()" class="inline-flex items-center justify-center rounded-[8px] border border-[#D1D5DB] bg-white px-4 py-2 text-xs font-semibold text-[#374151] hover:bg-[#F3F4F6] transition shadow-2xs">
            {{ $cancelText }}
        </button>

        @if($action)
            <form method="POST" action="{{ $action }}" class="m-0 inline">
                @csrf
                @if(in_array(strtoupper($method), ['PUT', 'PATCH', 'DELETE']))
                    @method($method)
                @endif
                <button type="submit" class="inline-flex items-center justify-center rounded-[8px] px-4 py-2 text-xs font-semibold text-white transition shadow-2xs {{ $isDanger ? 'bg-[#DC2626] hover:bg-[#B91C1C]' : 'bg-[#002875] hover:bg-[#001B50]' }}">
                    {{ $confirmText }}
                </button>
            </form>
        @else
            <button type="button" data-confirm-action class="inline-flex items-center justify-center rounded-[8px] px-4 py-2 text-xs font-semibold text-white transition shadow-2xs {{ $isDanger ? 'bg-[#DC2626] hover:bg-[#B91C1C]' : 'bg-[#002875] hover:bg-[#001B50]' }}">
                {{ $confirmText }}
            </button>
        @endif
    </div>
</dialog>
