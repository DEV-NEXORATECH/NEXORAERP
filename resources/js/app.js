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
        <div class="action-modal-card" role="dialog" aria-modal="true" aria-labelledby="action-modal-title">
            <div class="action-modal-icon">
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

const closeActionModal = () => {
    const modal = document.getElementById('action-confirm-modal');

    if (!modal) {
        return;
    }

    modal.classList.remove('is-open');
    pendingActionForm = null;
};

const actionLabel = (form) => {
    const methodInput = form.querySelector('input[name="_method"]');
    const method = (methodInput?.value || form.method || 'post').toLowerCase();
    const submitter = form.querySelector('button[type="submit"], button:not([type]), input[type="submit"]');
    const buttonText = submitter?.innerText || submitter?.value || '';

    if (form.dataset.confirmMessage) {
        return form.dataset.confirmMessage;
    }

    if (method === 'delete') {
        return 'Data akan dihapus atau dipindahkan ke trash. Lanjutkan?';
    }

    if (method === 'patch' && /reset/i.test(buttonText)) {
        return 'Password akan direset dan password sementara akan dibuat. Lanjutkan?';
    }

    if (method === 'patch' && /restore/i.test(buttonText)) {
        return 'Data akan direstore dari trash. Lanjutkan?';
    }

    if (method === 'patch') {
        return 'Status/data akan diperbarui. Lanjutkan?';
    }

    if (method === 'put') {
        return 'Perubahan data akan disimpan. Lanjutkan?';
    }

    if (/logout/i.test(buttonText) || form.action.includes('/logout')) {
        return 'Anda akan keluar dari aplikasi. Lanjutkan?';
    }

    return 'Data akan disimpan ke sistem. Lanjutkan?';
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
    const modal = ensureActionModal();
    modal.querySelector('[data-action-message]').textContent = actionLabel(form);
    modal.classList.add('is-open');
});

document.addEventListener('click', (event) => {
    if (event.target.closest('[data-action-cancel]')) {
        closeActionModal();
        return;
    }

    if (event.target.closest('[data-action-confirm]') && pendingActionForm) {
        pendingActionForm.dataset.confirmed = '1';
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
