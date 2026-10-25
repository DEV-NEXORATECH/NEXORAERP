<?php

$manifest = json_decode(file_get_contents(__DIR__ . '/../public/build/manifest.json'), true);
$cssFile = __DIR__ . '/../public/build/' . $manifest['resources/css/app.css']['file'];
$css = file_get_contents($cssFile);

$pos = strpos($css, "action-modal");
while ($pos !== false) {
    echo substr($css, max(0, $pos - 20), 200) . "\n---\n";
    $pos = strpos($css, "action-modal", $pos + 150);
}
