<?php

$dir = __DIR__ . '/../resources/views';
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
$all = [];
$notSeparated = [];

foreach ($files as $file) {
    if (!$file->isFile() || $file->getExtension() !== 'php') continue;
    $path = $file->getRealPath();
    $c = file_get_contents($path);
    if (str_contains($c, '->links()') || str_contains($c, 'firstItem(')) {
        $hasSeparate = str_contains($c, 'pagination-card') || str_contains($c, '<x-erp.pagination');
        $relPath = str_replace(realpath(__DIR__ . '/..') . DIRECTORY_SEPARATOR, '', $path);
        $all[] = $relPath;
        if (!$hasSeparate) {
            $notSeparated[] = $relPath;
        }
    }
}

echo "Total pagination views found: " . count($all) . "\n";
echo "Views without separated pagination card: " . count($notSeparated) . "\n";
foreach ($notSeparated as $f) {
    echo "  - $f\n";
}
