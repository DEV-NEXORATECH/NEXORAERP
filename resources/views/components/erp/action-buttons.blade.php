@props([
    'showUrl'     => null,
    'editUrl'     => null,
    'deleteUrl'   => null,
    'deleteId'    => null,
    'approveUrl'  => null,
    'rejectUrl'   => null,
    'printUrl'    => null,
    'downloadUrl' => null,
    'recordName'  => 'data ini',
    'canEdit'     => true,
    'canDelete'   => true,
    'canApprove'  => false,
])

<div class="inline-flex items-center gap-1.5">
    {{-- Detail / Show --}}
    @if($showUrl)
        <a href="{{ $showUrl }}" class="inline-flex h-7 w-7 items-center justify-center rounded-[6px] border border-[#D1D5DB] bg-white text-[#002875] hover:bg-[#F3F4F6] transition shadow-2xs" title="Lihat Detail">
            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
            <span class="sr-only">Lihat Detail</span>
        </a>
    @endif

    {{-- Edit --}}
    @if($editUrl && $canEdit)
        <a href="{{ $editUrl }}" class="inline-flex h-7 w-7 items-center justify-center rounded-[6px] border border-[#D1D5DB] bg-white text-[#0369A1] hover:bg-[#F3F4F6] transition shadow-2xs" title="Edit Data">
            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
            <span class="sr-only">Edit</span>
        </a>
    @endif

    {{-- Approve --}}
    @if($approveUrl && $canApprove)
        <form method="POST" action="{{ $approveUrl }}" class="m-0 inline" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui (Approve) {{ $recordName }}?');">
            @csrf
            <button type="submit" class="inline-flex h-7 w-7 items-center justify-center rounded-[6px] border border-[#15803D]/40 bg-[#15803D]/10 text-[#15803D] hover:bg-[#15803D] hover:text-white transition shadow-2xs cursor-pointer" title="Approve">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                <span class="sr-only">Approve</span>
            </button>
        </form>
    @endif

    {{-- Reject --}}
    @if($rejectUrl && $canApprove)
        <form method="POST" action="{{ $rejectUrl }}" class="m-0 inline" onsubmit="return confirm('Apakah Anda yakin ingin menolak (Reject) {{ $recordName }}?');">
            @csrf
            <button type="submit" class="inline-flex h-7 w-7 items-center justify-center rounded-[6px] border border-[#DC2626]/40 bg-[#DC2626]/10 text-[#DC2626] hover:bg-[#DC2626] hover:text-white transition shadow-2xs cursor-pointer" title="Reject">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6 6 18M6 6l12 12"/></svg>
                <span class="sr-only">Reject</span>
            </button>
        </form>
    @endif

    {{-- Print / PDF --}}
    @if($printUrl)
        <a href="{{ $printUrl }}" target="_blank" class="inline-flex h-7 w-7 items-center justify-center rounded-[6px] border border-[#D1D5DB] bg-white text-[#475569] hover:bg-[#F3F4F6] hover:text-[#002875] transition shadow-2xs" title="Cetak / PDF">
            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
            <span class="sr-only">Cetak / PDF</span>
        </a>
    @endif

    {{-- Custom Extra Actions Slot --}}
    {{ $slot ?? '' }}

    {{-- Delete with confirmation dialog --}}
    @if($deleteUrl && $canDelete)
        <form method="POST" action="{{ $deleteUrl }}" class="m-0 inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus {{ $recordName }}? Tindakan ini tidak dapat dibatalkan.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex h-7 w-7 items-center justify-center rounded-[6px] border border-[#DC2626]/40 bg-white text-[#DC2626] hover:bg-[#DC2626] hover:text-white transition shadow-2xs cursor-pointer" title="Hapus Data">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                <span class="sr-only">Hapus</span>
            </button>
        </form>
    @endif
</div>
