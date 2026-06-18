<nav class="navbar">
    <div class="navbar-left">
        <button type="button" class="sidebar-toggle" data-sidebar-toggle>
            <span class="side-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </span>
            <span class="side-label">Menu</span>
        </button>
        <div>
            <strong>{{ $pageTitle ?? 'Dashboard' }}</strong>
            <div class="muted">NEXORA ERP</div>
        </div>
    </div>

    <div class="navbar-right">
        <span class="badge">{{ strtoupper($role) }}</span>
    </div>
</nav>
