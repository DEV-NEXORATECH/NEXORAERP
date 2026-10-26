<?php

$css = file_get_contents(dirname(__DIR__) . '/resources/css/app.css');

// Parse rules outside and inside layers/media
preg_match_all('/([^{}]+)\{([^{}]+)\}/s', $css, $matches);

$ruleMap = [];
$duplicates = [];

for ($i = 0; $i < count($matches[0]); $i++) {
    $rawSelector = trim($matches[1][$i]);
    // Skip @keyframes, @media
    if (str_starts_with($rawSelector, '@')) continue;
    // Normalize selector
    $normalized = preg_replace('/\s+/', ' ', $rawSelector);
    if (!isset($ruleMap[$normalized])) {
        $ruleMap[$normalized] = [];
    }
    $ruleMap[$normalized][] = $i;
}

foreach ($ruleMap as $sel => $indices) {
    if (count($indices) > 1) {
        $duplicates[$sel] = count($indices);
    }
}

echo "Duplicate rules found: " . count($duplicates) . "\n";
foreach ($duplicates as $sel => $count) {
    echo "  [$count x] $sel\n";
}
