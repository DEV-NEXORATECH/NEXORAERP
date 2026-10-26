<?php

$dir = dirname(__DIR__) . '/resources/views/erp';
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

$doubleCardFilter = [];
$noToolbarActions = [];
$noFilterCard = [];
$tableWithoutFolk = [];
$allViews = [];

foreach ($it as $f) {
    if (!$f->isFile() || !str_ends_with($f->getFilename(), '.blade.php')) continue;
    $path = $f->getPathname();
    $rel = str_replace(dirname(__DIR__) . DIRECTORY_SEPARATOR, '', $path);
    $content = file_get_contents($path);

    if (str_contains($rel, 'print.blade.php') || str_contains($rel, 'partials') || str_contains($rel, 'layouts')) {
        continue;
    }

    $allViews[] = $rel;

    // 1. Check double nested filter card
    if (preg_match('/<div[^>]*class="[^"]*(?:bg-white|border|rounded)[^"]*"[^>]*>\s*{{--[^}]*--}}\s*<div class="filter-card"/is', $content)
        || preg_match('/<div[^>]*class="[^"]*(?:bg-white|border|rounded)[^"]*"[^>]*>\s*<div class="filter-card"/is', $content)) {
        $doubleCardFilter[] = $rel;
    }

    // 2. Check index/table views missing toolbar-actions
    $isIndexOrTable = str_contains($rel, 'index.blade.php') || (str_contains($content, '<table') && !str_contains($rel, 'show') && !str_contains($rel, 'create') && !str_contains($rel, 'edit'));
    if ($isIndexOrTable) {
        if (!str_contains($content, 'x-erp.toolbar-actions')) {
            $noToolbarActions[] = $rel;
        }
    }

    // 3. Check GET filter forms without filter-card
    if (preg_match('/<form[^>]*method=["\']get["\']/i', $content)) {
        if (!str_contains($content, 'filter-card')) {
            $noFilterCard[] = $rel;
        }
    }

    // 4. Check tables without table-folk
    if (str_contains($content, '<table') && !str_contains($content, 'table-folk')) {
        $tableWithoutFolk[] = $rel;
    }
}

echo "=== AUDIT RESULTS ===\n";
echo "Total Views Scanned: " . count($allViews) . "\n\n";

echo "1. Double Nested Filter Cards (" . count($doubleCardFilter) . "):\n";
foreach ($doubleCardFilter as $v) echo "  - $v\n";

echo "\n2. Views Missing Toolbar Actions (" . count($noToolbarActions) . "):\n";
foreach ($noToolbarActions as $v) echo "  - $v\n";

echo "\n3. GET Forms Missing Filter Card (" . count($noFilterCard) . "):\n";
foreach ($noFilterCard as $v) echo "  - $v\n";

echo "\n4. Tables Missing table-folk (" . count($tableWithoutFolk) . "):\n";
foreach ($tableWithoutFolk as $v) echo "  - $v\n";
