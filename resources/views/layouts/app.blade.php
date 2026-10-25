<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'NEXORA360 ERP' }}</title>
    <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('images/nexora-login-logo.png') }}?v=5">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @yield('body')
    <script>
        const isMobile = () => window.innerWidth < 1100;

        document.addEventListener('click', function (event) {
            const shell = document.querySelector('.app-shell');
            if (!shell) return;

            const toggle = event.target.closest('[data-sidebar-toggle]');
            if (toggle) {
                if (isMobile()) {
                    shell.classList.toggle('sidebar-open');
                } else {
                    shell.classList.toggle('sidebar-collapsed');
                    localStorage.setItem('nexora-sidebar-collapsed', shell.classList.contains('sidebar-collapsed') ? '1' : '0');
                }
                return;
            }

            const backdrop = event.target.closest('[data-sidebar-backdrop]');
            if (backdrop && isMobile()) {
                shell.classList.remove('sidebar-open');
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            const shell = document.querySelector('.app-shell');
            if (shell && !isMobile() && localStorage.getItem('nexora-sidebar-collapsed') === '1') {
                shell.classList.add('sidebar-collapsed');
            }
        });

        window.addEventListener('resize', function () {
            const shell = document.querySelector('.app-shell');
            if (!shell) return;
            if (!isMobile() && shell.classList.contains('sidebar-open')) {
                shell.classList.remove('sidebar-open');
            }
            if (isMobile() && shell.classList.contains('sidebar-collapsed')) {
                shell.classList.remove('sidebar-collapsed');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
