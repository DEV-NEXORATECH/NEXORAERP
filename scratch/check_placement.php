<?php

$dir = __DIR__ . '/../resources/views';
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
$issues = [];

foreach ($files as $file) {
    if (!$file->isFile() || $file->getExtension() !== 'php') continue;
    $path = $file->getRealPath();
    $c = file_get_contents($path);
    $rel = str_replace(realpath(__DIR__ . '/..') . DIRECTORY_SEPARATOR, '', $path);
    
    // Check if pagination appears inside workspace-card before workspace-card is closed
    if (preg_match('/<div[^>]*class="[^"]*workspace-card[^"]*"[^>]*>(.*?)(pagination-card|<x-erp\.pagination)/s', $c, $m)) {
        // Count opening and closing divs between workspace-card and pagination
        $inner = $m[1];
        $opens = substr_count($inner, '<div');
        $closes = substr_count($inner, '</div>');
        // If closes <= opens, pagination is INSIDE workspace-card!
        if ($closes <= $opens) {
            $issues[] = [
                'file' => $rel,
                'reason' => 'Pagination is INSIDE workspace-card (opens: ' . ($opens+1) . ', closes: ' . $closes . ')'
            ];
        }
    }
}

echo "Total pagination placement issues: " . count($issues) . "\n";
foreach ($issues as $i) {
    echo "  - {$i['file']}: {$i['reason']}\n";
}
