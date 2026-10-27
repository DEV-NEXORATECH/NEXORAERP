@props([
    'module' => 'data',
    'title'  => 'Impor Data',
])

<div id="import-modal-{{ $module }}" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity" onclick="document.getElementById('import-modal-{{ $module }}').classList.add('hidden')"></div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-[#E5E7EB]">
            <div class="flex items-center justify-between border-b border-[#E5E7EB] px-5 py-4 bg-[#F8FAFC]">
                <div class="flex items-center gap-2">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#EFF6FF] text-[#002875]">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-[#0F172A]">{{ $title }}</h3>
                        <p class="text-[11px] text-[#64748B]">Unggah file CSV sesuai format template</p>
                    </div>
                </div>
                <button type="button" onclick="document.getElementById('import-modal-{{ $module }}').classList.add('hidden')" class="text-[#94A3B8] hover:text-[#475569]">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
                </button>
            </div>

            <form action="{{ route('data-exchange.import', ['module' => $module]) }}" method="POST" enctype="multipart/form-data" class="p-5 space-y-4">
                @csrf
                <div class="rounded-lg border border-dashed border-[#CBD5E1] bg-[#F8FAFC] p-4 text-center">
                    <svg class="mx-auto h-8 w-8 text-[#94A3B8]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/></svg>
                    <label class="mt-2 block text-xs font-semibold text-[#002875] cursor-pointer hover:underline">
                        <span>Pilih file CSV</span>
                        <input type="file" name="file" accept=".csv,text/csv" required class="hidden" onchange="this.previousElementSibling.innerText = this.files[0] ? this.files[0].name : 'Pilih file CSV'">
                    </label>
                    <p class="text-[11px] text-[#94A3B8] mt-1">Maksimal 5MB (.csv)</p>
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <a href="{{ route('data-exchange.template', ['module' => $module]) }}" class="inline-flex items-center gap-1 text-[#002875] font-semibold hover:underline">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                        Unduh Template CSV
                    </a>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-[#E5E7EB]">
                    <button type="button" onclick="document.getElementById('import-modal-{{ $module }}').classList.add('hidden')" class="btn-import">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary inline-flex h-8 items-center px-4 text-xs font-semibold shadow-xs">
                        Mulai Impor
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
