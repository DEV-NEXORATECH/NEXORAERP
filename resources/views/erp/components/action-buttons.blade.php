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

@php
    $actionId = 'erp-act-' . uniqid();
@endphp

<div class="inline-flex items-center gap-1.5">
    {{-- Detail / Show --}}
    @if($showUrl)
        <a href="{{ $showUrl }}" class="erp-action-button inline-flex h-7 w-7 items-center justify-center rounded border border-[#BAE6FD] bg-[#F0F9FF] text-[#0369A1] hover:bg-[#E0F2FE] transition shadow-2xs" title="Lihat Detail">
            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
            <span class="sr-only">Lihat Detail</span>
        </a>
    @endif

    {{-- Edit --}}
    @if($editUrl && $canEdit)
        <a href="{{ $editUrl }}" class="erp-action-button inline-flex h-7 w-7 items-center justify-center rounded border border-[#D1D5DB] bg-[#F9FAFB] text-[#475569] hover:bg-[#F3F4F6] transition shadow-2xs" title="Edit">
            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
            <span class="sr-only">Edit</span>
        </a>
    @endif

    {{-- Approve --}}
    @if($approveUrl && $canApprove)
        <form method="POST" action="{{ $approveUrl }}" class="m-0 inline erp-action-form" data-confirm-type="default" data-confirm-title="Setujui {{ $recordName }}" data-confirm-text="Apakah Anda yakin ingin menyetujui {{ $recordName }}?">
            @csrf
            <button type="submit" class="erp-action-button inline-flex h-7 w-7 items-center justify-center rounded border border-[#86EFAC] bg-[#F0FDF4] text-[#15803D] hover:bg-[#15803D] hover:text-white transition shadow-2xs cursor-pointer" title="Approve">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                <span class="sr-only">Approve</span>
            </button>
        </form>
    @endif

    {{-- Reject --}}
    @if($rejectUrl && $canApprove)
        <form method="POST" action="{{ $rejectUrl }}" class="m-0 inline erp-action-form" data-confirm-type="delete" data-confirm-title="Tolak {{ $recordName }}" data-confirm-text="Apakah Anda yakin ingin menolak {{ $recordName }}?">
            @csrf
            <button type="submit" class="erp-action-button inline-flex h-7 w-7 items-center justify-center rounded border border-[#FECACA] bg-[#FEF2F2] text-[#DC2626] hover:bg-[#DC2626] hover:text-white transition shadow-2xs cursor-pointer" title="Reject">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6 6 18M6 6l12 12"/></svg>
                <span class="sr-only">Reject</span>
            </button>
        </form>
    @endif

    {{-- Print / PDF --}}
    @if($printUrl)
        <a href="{{ $printUrl }}" target="_blank" class="erp-action-button inline-flex h-7 w-7 items-center justify-center rounded border border-[#D1D5DB] bg-[#F9FAFB] text-[#475569] hover:bg-[#F3F4F6] transition shadow-2xs" title="Cetak / PDF">
            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
            <span class="sr-only">Cetak / PDF</span>
        </a>
    @endif

    {{-- Custom Extra Actions Slot --}}
    {{ $slot ?? '' }}

    {{-- Delete with confirmation dialog --}}
    @if($deleteUrl && $canDelete)
        <form method="POST" action="{{ $deleteUrl }}" class="m-0 inline erp-action-form" data-confirm-type="delete" data-confirm-title="Hapus {{ $recordName }}" data-confirm-text="Apakah Anda yakin ingin menghapus {{ $recordName }}? Tindakan ini tidak dapat dibatalkan.">
            @csrf
            @method('DELETE')
            <button type="submit" class="erp-action-button inline-flex h-7 w-7 items-center justify-center rounded border border-[#FECACA] bg-[#FEF2F2] text-[#DC2626] hover:bg-[#FEE2E2] transition shadow-2xs cursor-pointer" title="Hapus">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                <span class="sr-only">Hapus</span>
            </button>
        </form>
    @endif
</div>

<div id="erp-action-modal-{{ $actionId }}" class="action-modal" data-action-modal>
    <div class="action-modal-backdrop" data-action-modal-close></div>
    <div class="action-modal-card" data-action-type="delete">
        <div class="action-modal-head">
            <div class="action-modal-header-left">
                <div class="action-modal-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 9v4M12 17h.01M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                    </svg>
                </div>
                <div class="action-modal-text">
                    <h3 class="action-modal-title" data-action-modal-title>Hapus data</h3>
                    <p class="action-modal-subtitle" data-action-modal-subtitle>Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.</p>
                </div>
            </div>
            <button type="button" class="action-modal-close-btn" data-action-modal-close title="Tutup">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 6 6 18M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="action-modal-actions">
            <button type="button" class="action-modal-cancel" data-action-modal-close>Batal</button>
            <button type="button" class="action-modal-confirm" data-action-modal-confirm>Hapus</button>
        </div>
    </div>
</div>

<script>
(function() {
    const modal = document.getElementById('erp-action-modal-{{ $actionId }}');
    if (!modal) return;
    const card = modal.querySelector('.action-modal-card');
    const titleEl = modal.querySelector('[data-action-modal-title]');
    const textEl = modal.querySelector('[data-action-modal-subtitle]');
    const confirmBtn = modal.querySelector('[data-action-modal-confirm]');
    const closeEls = modal.querySelectorAll('[data-action-modal-close]');
    let pendingForm = null;

    document.querySelectorAll('.erp-action-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (form.dataset.erpConfirmed === '1') {
                form.dataset.erpConfirmed = '0';
                return true;
            }
            e.preventDefault();
            pendingForm = form;
            const type = form.dataset.confirmType || 'delete';
            card.setAttribute('data-action-type', type);
            if (type === 'delete') {
                titleEl.textContent = form.dataset.confirmTitle || 'Hapus data';
                textEl.textContent = form.dataset.confirmText || 'Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.';
                confirmBtn.textContent = 'Hapus';
            } else if (type === 'default') {
                titleEl.textContent = form.dataset.confirmTitle || 'Konfirmasi';
                textEl.textContent = form.dataset.confirmText || 'Apakah Anda yakin ingin melanjutkan tindakan ini?';
                confirmBtn.textContent = 'Ya, Lanjutkan';
            } else {
                titleEl.textContent = form.dataset.confirmTitle || 'Konfirmasi';
                textEl.textContent = form.dataset.confirmText || 'Apakah Anda yakin ingin melanjutkan tindakan ini?';
                confirmBtn.textContent = 'Konfirmasi';
            }
            modal.classList.add('is-open');
        });
    });

    closeEls.forEach(el => el.addEventListener('click', () => {
        modal.classList.remove('is-open');
        pendingForm = null;
    }));

    confirmBtn.addEventListener('click', () => {
        if (pendingForm) {
            pendingForm.dataset.erpConfirmed = '1';
            pendingForm.submit();
        }
        modal.classList.remove('is-open');
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            modal.classList.remove('is-open');
            pendingForm = null;
        }
    });
})();
</script>
