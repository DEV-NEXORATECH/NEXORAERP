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
        document.addEventListener('click', function (event) {
            const toggle = event.target.closest('[data-sidebar-toggle]');
            if (!toggle) return;
            const shell = document.querySelector('.app-shell');
            if (!shell) return;
            shell.classList.toggle('sidebar-collapsed');
            localStorage.setItem('nexora-sidebar-collapsed', shell.classList.contains('sidebar-collapsed') ? '1' : '0');
        });
        document.addEventListener('DOMContentLoaded', function () {
            const shell = document.querySelector('.app-shell');
            if (shell && localStorage.getItem('nexora-sidebar-collapsed') === '1') {
                shell.classList.add('sidebar-collapsed');
            }
        });
    </script>
</body>
</html>
