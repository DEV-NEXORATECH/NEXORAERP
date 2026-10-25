<?php

$dir = __DIR__ . '/../resources/views/erp';
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
$indexFiles = [];

foreach ($files as $file) {
    if (!$file->isFile() || $file->getExtension() !== 'php') continue;
    $filename = $file->getFilename();
    $path = $file->getRealPath();
    
    // Look for index views or views that render lists/tables
    if ($filename === 'index.blade.php' || str_contains($filename, 'list') || str_contains($path, 'masters.blade.php') || str_contains($path, 'trash.blade.php')) {
        $rel = str_replace(realpath(__DIR__ . '/..') . DIRECTORY_SEPARATOR, '', $path);
        $content = file_get_contents($path);
        
        $hasToolbarActions = str_contains($content, '<x-erp.toolbar-actions') || str_contains($content, 'btn-export');
        $hasSeparateFilter = str_contains($content, 'filter-card') || str_contains($content, 'filter-toolbar');
        $hasImportModal = str_contains($content, '<x-erp.import-modal');
        
        $indexFiles[] = [
            'file' => $rel,
            'has_actions' => $hasToolbarActions,
            'has_filter' => $hasSeparateFilter,
            'has_import' => $hasImportModal,
            'has_table' => str_contains($content, '<table')
        ];
    }
}

echo "Total index/table views: " . count($indexFiles) . "\n";
$missingActions = array_filter($indexFiles, fn($i) => $i['has_table'] && !$i['has_actions']);
$missingFilter = array_filter($indexFiles, fn($i) => $i['has_table'] && !$i['has_filter']);

echo "Missing toolbar actions (Export/Import/PDF): " . count($missingActions) . "\n";
foreach ($missingActions as $m) {
    echo "  - " . $m['file'] . "\n";
}

echo "\nMissing separate filter card: " . count($missingFilter) . "\n";
foreach ($missingFilter as $m) {
    echo "  - " . $m['file'] . "\n";
}
