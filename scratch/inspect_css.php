<?php

$manifest = json_decode(file_get_contents(__DIR__ . '/../public/build/manifest.json'), true);
$cssFile = __DIR__ . '/../public/build/' . $manifest['resources/css/app.css']['file'];
$css = file_get_contents($cssFile);

$pos = strpos($css, "table-folk th");
if ($pos !== false) {
    echo "=== table-folk th ===\n";
    echo substr($css, $pos - 30, 400) . "\n\n";
}

$pos = strpos($css, "table-folk td");
if ($pos !== false) {
    echo "=== table-folk td ===\n";
    echo substr($css, $pos - 30, 400) . "\n\n";
}
