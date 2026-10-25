<?php

$dir = dirname(__DIR__) . '/resources/views/erp';
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
$fixedFiles = 0;

foreach ($it as $f) {
    if (!$f->isFile() || !str_ends_with($f->getFilename(), '.blade.php')) continue;
    $path = $f->getPathname();
    $content = file_get_contents($path);

    // Fix :create-route="{{ route(...) }}" -> :create-route="route(...)"
    $newContent = preg_replace_callback(
        '/:create-route="{{\s*(route\([^)]+\))\s*}}"/',
        function ($matches) {
            return ':create-route="' . $matches[1] . '"';
        },
        $content
    );

    // Also check for any :export-route="{{ ... }}" or :pdf-route="{{ ... }}"
    $newContent = preg_replace_callback(
        '/:(export-route|pdf-route|import-route)="{{\s*(route\([^)]+\))\s*}}"/',
        function ($matches) {
            return ':' . $matches[1] . '="' . $matches[2] . '"';
        },
        $newContent
    );

    if ($newContent !== $content) {
        file_put_contents($path, $newContent);
        $fixedFiles++;
        echo "Fixed syntax in: " . basename($path) . "\n";
    }
}

echo "Total files fixed: $fixedFiles\n";
