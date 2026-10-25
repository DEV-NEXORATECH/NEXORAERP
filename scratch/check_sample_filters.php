<?php

$dir = dirname(__DIR__) . '/resources/views/erp';
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
$count = 0;

foreach ($it as $f) {
    if (!$f->isFile() || !str_ends_with($f->getFilename(), '.blade.php')) continue;
    $path = $f->getPathname();
    $content = file_get_contents($path);

    if (preg_match('/<form[^>]*class=["\']filter-toolbar["\'][^>]*>(.*?)<\/form>/is', $content, $m)) {
        echo "=== " . basename($path) . " ===\n";
        echo trim($m[1]) . "\n\n";
        if (++$count >= 3) break;
    }
}
