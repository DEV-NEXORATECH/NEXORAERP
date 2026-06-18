<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'NEXORA ERP' }}</title>
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
</body>
</html>
