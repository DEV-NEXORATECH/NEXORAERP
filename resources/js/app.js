import './bootstrap';
import ApexCharts from 'apexcharts';

window.ApexCharts = ApexCharts;

window.formatRupiah = (val) => {
    if (val === null || val === undefined || isNaN(val)) return 'Rp 0';
    return 'Rp ' + Math.round(val).toLocaleString('id-ID');
};

window.initNexoraChart = (target, customOptions = {}) => {
    const el = typeof target === 'string' ? document.querySelector(target) : target;
    if (!el) return null;

    const baseDefaults = {
        chart: {
            fontFamily: 'Inter, ui-sans-serif, system-ui, sans-serif',
            toolbar: { show: false },
            zoom: { enabled: false },
            animations: {
                enabled: true,
                speed: 350,
                dynamicAnimation: { enabled: true, speed: 250 }
            }
        },
        colors: [
            '#0B2345', '#2563EB', '#16A34A', '#DC2626', '#EA580C',
            '#D97706', '#7C3AED', '#0891B2', '#0F766E', '#DB2777', '#4F46E5'
        ],
        grid: {
            borderColor: '#E2E8F0',
            strokeDashArray: 3,
            padding: { top: 6, right: 12, bottom: 6, left: 12 }
        },
        tooltip: {
            theme: 'light',
            style: { fontSize: '12px', fontFamily: 'Inter, sans-serif' },
            y: {
                formatter: (val) => {
                    if (val === null || val === undefined) return '-';
                    if (typeof val === 'number') {
                        if (Math.abs(val) >= 1000) {
                            return 'Rp ' + Math.round(val).toLocaleString('id-ID');
                        }
                        return val.toLocaleString('id-ID');
                    }
                    return val;
                }
            }
        },
        legend: {
            position: 'bottom',
            fontFamily: 'Inter, sans-serif',
            fontSize: '12px',
            fontWeight: 500,
            labels: { colors: '#475569' },
            markers: { radius: 3 }
        },
        stroke: { width: 2, curve: 'smooth' },
        dataLabels: { enabled: false }
    };

    // Deep merge shallow copy of chart and stroke
    const finalOptions = {
        ...baseDefaults,
        ...customOptions,
        chart: { ...baseDefaults.chart, ...(customOptions.chart || {}) },
        grid: { ...baseDefaults.grid, ...(customOptions.grid || {}) },
        tooltip: { ...baseDefaults.tooltip, ...(customOptions.tooltip || {}) },
        legend: { ...baseDefaults.legend, ...(customOptions.legend || {}) },
        stroke: { ...baseDefaults.stroke, ...(customOptions.stroke || {}) }
    };

    const chart = new ApexCharts(el, finalOptions);
    chart.render();
    return chart;
};

// Global dashboard date selector helper
document.addEventListener('change', (event) => {
    const periodSelect = event.target.closest('[data-dashboard-period]');
    if (!periodSelect) return;
    const form = periodSelect.closest('form');
    if (form) {
        if (periodSelect.value === 'custom') {
            const customDates = form.querySelector('[data-custom-dates]');
            if (customDates) customDates.classList.remove('hidden');
        } else {
            form.submit();
        }
    }
});

document.addEventListener('click', (event) => {
    const toggle = event.target.closest('[data-password-toggle]');

    if (!toggle) {
        return;
    }

    const input = document.getElementById(toggle.dataset.passwordToggle);

    if (!input) {
        return;
    }

    input.type = input.type === 'password' ? 'text' : 'password';
});

const ACTION_ICONS = {
    delete: `
        <svg class="h-5 w-5 text-[#DC2626]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 6h18"/>
            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
            <line x1="10" y1="11" x2="10" y2="17"/>
            <line x1="14" y1="11" x2="14" y2="17"/>
        </svg>`,
    restore: `
        <svg class="h-5 w-5 text-[#16A34A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
            <path d="M3 3v5h5"/>
        </svg>`,
    reset: `
        <svg class="h-5 w-5 text-[#D97706]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="3"/>
            <path d="M19.4 15a1.7 1.7 0 0 0 .34 1.88l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06A1.7 1.7 0 0 0 15 19.4a1.7 1.7 0 0 0-1 .6 1.7 1.7 0 0 0-.4 1.1V21a2 2 0 1 1-4 0v-.09A1.7 1.7 0 0 0 8.6 19.4a1.7 1.7 0 0 0-1.88.34l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.7 1.7 0 0 0 4.6 15a1.7 1.7 0 0 0-.6-1 1.7 1.7 0 0 0-1.1-.4H3a2 2 0 1 1 0-4h.09A1.7 1.7 0 0 0 4.6 8.6a1.7 1.7 0 0 0-.34-1.88l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.7 1.7 0 0 0 9 4.6a1.7 1.7 0 0 0 1-.6 1.7 1.7 0 0 0 .4-1.1V3a2 2 0 1 1 4 0v.09A1.7 1.7 0 0 0 15.4 4.6a1.7 1.7 0 0 0 1.88-.34l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06A1.7 1.7 0 0 0 19.4 9c.6.2 1 .7 1 1.3H21a2 2 0 1 1 0 4h-.09A1.7 1.7 0 0 0 19.4 15z"/>
        </svg>`,
    logout: `
        <svg class="h-5 w-5 text-[#64748B]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
            <polyline points="16 17 21 12 16 7"/>
            <line x1="21" y1="12" x2="9" y2="12"/>
        </svg>`,
    create: `
        <svg class="h-5 w-5 text-[#16A34A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 5v14M5 12h14"/>
        </svg>`,
    default: `
        <svg class="h-5 w-5 text-[#002875]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="16" x2="12" y2="12"/>
            <line x1="12" y1="8" x2="12.01" y2="8"/>
        </svg>`
};

const ensureActionModal = () => {
    let modal = document.getElementById('action-confirm-modal');

    if (modal) {
        return modal;
    }

    modal = document.createElement('div');
    modal.id = 'action-confirm-modal';
    modal.className = 'action-modal';
    modal.innerHTML = `
        <div class="action-modal-backdrop" data-action-cancel></div>
        <div class="action-modal-card" data-action-type="default" role="dialog" aria-modal="true" aria-labelledby="action-modal-title">
            <div class="action-modal-head">
                <div class="action-modal-header-left">
                    <div class="action-modal-icon" data-action-icon>
                        <svg class="h-5 w-5 text-[#DC2626]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 6h18"/>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                            <line x1="10" y1="11" x2="10" y2="17"/>
                            <line x1="14" y1="11" x2="14" y2="17"/>
                        </svg>
                    </div>
                    <div class="action-modal-text">
                        <h3 id="action-modal-title" class="action-modal-title">Konfirmasi Aksi</h3>
                        <p data-action-message class="action-modal-subtitle">Apakah Anda yakin ingin melanjutkan aksi ini?</p>
                    </div>
                </div>
                <button type="button" class="action-modal-close-btn" data-action-cancel title="Tutup">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="M18 6 6 18M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="action-modal-actions">
                <button type="button" class="action-modal-cancel" data-action-cancel>Batal</button>
                <button type="button" class="action-modal-confirm" data-action-confirm>Lanjutkan</button>
            </div>
        </div>
    `;
    document.body.appendChild(modal);

    return modal;
};

let pendingActionForm = null;
let pendingSubmitter = null;

const closeActionModal = () => {
    const modal = document.getElementById('action-confirm-modal');

    if (!modal) {
        return;
    }

    modal.classList.remove('is-open');
    pendingActionForm = null;
    pendingSubmitter = null;
};

const actionMeta = (form, submitter = null) => {
    const methodInput = form.querySelector('input[name="_method"]');
    const method = (methodInput?.value || form.method || 'post').toLowerCase();
    const button = submitter || form.querySelector('button[type="submit"], button:not([type]), input[type="submit"]');
    const buttonText = button?.innerText || button?.value || '';

    if (form.dataset.confirmMessage) {
        return {
            type: form.dataset.confirmType || 'default',
            title: form.dataset.confirmTitle || 'Konfirmasi action',
            message: form.dataset.confirmMessage,
            confirm: form.dataset.confirmButton || 'Lanjutkan',
            loading: form.dataset.loadingText || 'Memproses...',
        };
    }

    if (method === 'delete') {
        return {
            type: 'delete',
            title: 'Hapus data?',
            message: 'Data akan dihapus atau dipindahkan ke trash. Action ini perlu dikonfirmasi.',
            confirm: 'Hapus',
            loading: 'Menghapus...',
        };
    }

    if (method === 'patch' && /reset/i.test(buttonText)) {
        return {
            type: 'reset',
            title: 'Reset password?',
            message: 'Password akan direset dan password sementara akan dibuat.',
            confirm: 'Reset',
            loading: 'Mereset...',
        };
    }

    if (method === 'patch' && /restore/i.test(buttonText)) {
        return {
            type: 'restore',
            title: 'Restore data?',
            message: 'Data akan dikembalikan dari trash.',
            confirm: 'Restore',
            loading: 'Merestore...',
        };
    }

    if (method === 'patch') {
        return {
            type: 'update',
            title: 'Update status?',
            message: 'Status atau data akan diperbarui.',
            confirm: 'Update',
            loading: 'Mengupdate...',
        };
    }

    if (method === 'put') {
        return {
            type: 'update',
            title: 'Simpan perubahan?',
            message: 'Perubahan data akan disimpan ke sistem.',
            confirm: 'Simpan',
            loading: 'Menyimpan...',
        };
    }

    if (/logout/i.test(buttonText) || form.action.includes('/logout')) {
        return {
            type: 'logout',
            title: 'Logout?',
            message: 'Anda akan keluar dari aplikasi.',
            confirm: 'Logout',
            loading: 'Keluar...',
        };
    }

    return {
        type: 'create',
        title: 'Simpan data?',
        message: 'Data baru akan disimpan ke sistem.',
        confirm: 'Simpan',
        loading: 'Menyimpan...',
    };
};

const setFormLoading = (form, submitter, text) => {
    const button = submitter || form.querySelector('button[type="submit"], button:not([type]), input[type="submit"]');

    form.classList.add('is-submitting');

    if (!button) {
        return;
    }

    if (!button.dataset.originalText) {
        button.dataset.originalText = button.tagName === 'INPUT' ? button.value : button.innerHTML;
    }

    button.classList.add('is-loading');
    button.setAttribute('aria-busy', 'true');
    button.disabled = true;

    if (button.tagName === 'INPUT') {
        button.value = text;
    } else {
        button.textContent = text;
    }
};

document.addEventListener('submit', (event) => {
    const form = event.target;

    if (!(form instanceof HTMLFormElement) || form.dataset.confirmed === '1') {
        return;
    }

    if ((form.method || 'get').toLowerCase() !== 'post') {
        return;
    }

    event.preventDefault();

    pendingActionForm = form;
    pendingSubmitter = event.submitter || null;
    const meta = actionMeta(form, pendingSubmitter);
    const modal = ensureActionModal();
    const card = modal.querySelector('.action-modal-card');
    card.dataset.actionType = meta.type;
    const iconContainer = modal.querySelector('[data-action-icon]');
    if (iconContainer) {
        iconContainer.innerHTML = ACTION_ICONS[meta.type] || ACTION_ICONS.default;
    }
    modal.querySelector('#action-modal-title').textContent = meta.title;
    modal.querySelector('[data-action-message]').textContent = meta.message;
    modal.querySelector('[data-action-confirm]').textContent = meta.confirm;
    modal.dataset.loadingText = meta.loading;
    modal.classList.add('is-open');
});

document.addEventListener('click', (event) => {
    if (event.target.closest('[data-action-cancel]')) {
        closeActionModal();
        return;
    }

    if (event.target.closest('[data-action-confirm]') && pendingActionForm) {
        const modal = ensureActionModal();
        const loadingText = modal.dataset.loadingText || 'Memproses...';
        pendingActionForm.dataset.confirmed = '1';
        setFormLoading(pendingActionForm, pendingSubmitter, loadingText);
        pendingActionForm.submit();
    }
});

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        closeActionModal();
    }
});

const openUiModal = (id) => {
    const modal = document.getElementById(id);

    if (!modal) {
        return;
    }

    modal.classList.add('is-open');
    modal.setAttribute('aria-hidden', 'false');
};

const closeUiModal = (modal) => {
    if (!modal) {
        return;
    }

    modal.classList.remove('is-open');
    modal.setAttribute('aria-hidden', 'true');
};

document.addEventListener('click', (event) => {
    const opener = event.target.closest('[data-modal-open]');

    if (opener) {
        openUiModal(opener.dataset.modalOpen);
        return;
    }

    const closer = event.target.closest('[data-modal-close]');

    if (closer) {
        closeUiModal(closer.closest('.ui-modal'));
    }
});

document.addEventListener('keydown', (event) => {
    if (event.key !== 'Escape') {
        return;
    }

    document.querySelectorAll('.ui-modal.is-open').forEach(closeUiModal);
});
