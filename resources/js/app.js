import './bootstrap';

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
                <div class="action-modal-icon" data-action-icon>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3">
                        <path d="M12 9v4"/>
                        <path d="M12 17h.01"/>
                        <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 id="action-modal-title">Konfirmasi action</h3>
                    <p data-action-message>Yakin ingin melanjutkan action ini?</p>
                </div>
            </div>
            <div class="action-modal-actions">
                <button type="button" class="ghost" data-action-cancel>Batal</button>
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
