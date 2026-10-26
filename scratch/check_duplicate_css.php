<?php

$css = file_get_contents(dirname(__DIR__) . '/resources/css/app.css');

// Find all top-level selectors and check for duplicates or duplicate block definitions
// Also let's inspect the file structure around line 4500 to 4900 where recent additions were made
$lines = explode("\n", $css);
echo "Total lines in app.css: " . count($lines) . "\n";

// Look for duplicate comment headers or duplicate selector blocks
preg_match_all('/^([^{@\/][^{]*)\{/m', $css, $matches, PREG_OFFSET_CAPTURE);
$selectors = [];
$duplicates = [];

foreach ($matches[1] as $match) {
    $sel = trim($match[0]);
    if (empty($sel)) continue;
    if (isset($selectors[$sel])) {
        $duplicates[$sel][] = substr_count(substr($css, 0, $match[1]), "\n") + 1;
    } else {
        $selectors[$sel] = [substr_count(substr($css, 0, $match[1]), "\n") + 1];
    }
}

echo "Found " . count($duplicates) . " duplicate selector blocks:\n";
foreach ($duplicates as $sel => $lineNums) {
    echo "Selector: " . str_replace("\n", " ", $sel) . "\n";
    echo "  Appears on lines: " . implode(', ', array_merge($selectors[$sel], $lineNums)) . "\n";
}
