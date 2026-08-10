@if (session('status'))
    <div class="ui-feedback ui-feedback-success" role="status">
        <span class="ui-feedback-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4">
                <path d="M20 6 9 17l-5-5"/>
            </svg>
        </span>
        <div>
            <strong>Berhasil</strong>
            <p>{{ session('status') }}</p>
        </div>
    </div>
@endif

@if (session('temporary_password'))
    <div class="ui-feedback ui-feedback-info" role="status">
        <span class="ui-feedback-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4">
                <path d="M12 17h.01"/>
                <path d="M12 7v6"/>
                <circle cx="12" cy="12" r="9"/>
            </svg>
        </span>
        <div>
            <strong>Password sementara</strong>
            <p>{{ session('temporary_password') }}</p>
        </div>
    </div>
@endif

@if ($errors->any())
    <div class="ui-feedback ui-feedback-error" role="alert">
        <span class="ui-feedback-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4">
                <path d="M12 9v4"/>
                <path d="M12 17h.01"/>
                <path d="M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0z"/>
            </svg>
        </span>
        <div>
            <strong>Perlu diperbaiki</strong>
            <p>{{ $errors->first() }}</p>
        </div>
    </div>
@endif
