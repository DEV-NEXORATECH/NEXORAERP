<?php

$dir = dirname(__DIR__) . '/resources/views/erp';
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
$badSyntax = [];
$missingToolbar = [];
$missingFilterCard = [];
$totalIndexViews = 0;

foreach ($it as $f) {
    if (!$f->isFile() || !str_ends_with($f->getFilename(), '.blade.php')) continue;
    $path = $f->getPathname();
    $content = file_get_contents($path);

    if (strpos($content, ':create-route="{{') !== false) {
        $badSyntax[] = $path;
    }

    if (str_contains($path, 'index.blade.php') || (str_contains($content, '<table') && !str_contains($path, 'show') && !str_contains($path, 'create') && !str_contains($path, 'edit') && !str_contains($path, 'partials'))) {
        $totalIndexViews++;
        if (!str_contains($content, 'x-erp.toolbar-actions')) {
            $missingToolbar[] = $path;
        }
        if (str_contains($content, '<form') && !str_contains($content, 'filter-card') && (str_contains($content, 'search') || str_contains($content, 'status') || str_contains($content, 'filter'))) {
            $missingFilterCard[] = $path;
        }
    }
}

echo "Total index/table views: $totalIndexViews\n";
echo "Bad Syntax count: " . count($badSyntax) . "\n";
echo "Missing Toolbar count: " . count($missingToolbar) . "\n";
foreach ($missingToolbar as $m) echo " - $m\n";
echo "Missing Filter Card count: " . count($missingFilterCard) . "\n";
foreach ($missingFilterCard as $m) echo " - $m\n";
