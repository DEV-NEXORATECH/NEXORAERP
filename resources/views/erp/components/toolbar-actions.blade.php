@props([
    'module'      => 'data',
    'exportRoute' => null,
    'importRoute' => null,
    'pdfRoute'    => null,
    'createRoute' => null,
    'createLabel' => '+ Tambah Data',
    'canCreate'   => true,
])

<div class="flex items-center gap-2">
    {{-- Export Button --}}
    <a href="{{ $exportRoute ?? route('data-exchange.export', ['module' => $module]) . (request()->getQueryString() ? '?' . request()->getQueryString() : '') }}" class="btn-export" title="Ekspor data ke file CSV">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
        <span>Ekspor</span>
    </a>

    {{-- Import Button --}}
    <button type="button" onclick="document.getElementById('import-modal-{{ $module }}').classList.remove('hidden')" class="btn-import" title="Impor data dari file CSV">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg>
        <span>Impor</span>
    </button>

    {{-- PDF Button --}}
    @if($pdfRoute)
        <a href="{{ $pdfRoute }}" target="_blank" class="btn-pdf" title="Cetak / Buka PDF">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
            <span>PDF</span>
        </a>
    @else
        <button type="button" onclick="window.print()" class="btn-pdf no-print" title="Cetak / PDF Halaman">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
            <span>PDF</span>
        </button>
    @endif

    {{-- Primary Create Button --}}
    @if($createRoute && $canCreate)
        <a href="{{ $createRoute }}" class="btn btn-primary inline-flex h-[34px] items-center gap-1.5 px-3.5 text-xs font-semibold shadow-xs">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>{{ ltrim($createLabel, '+ ') }}</span>
        </a>
    @endif
</div>

<x-erp.import-modal :module="$module" />
