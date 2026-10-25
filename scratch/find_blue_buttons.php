<?php

$manifest = json_decode(file_get_contents(__DIR__ . '/../public/build/manifest.json'), true);
$cssFile = __DIR__ . '/../public/build/' . $manifest['resources/css/app.css']['file'];
$css = file_get_contents($cssFile);

preg_match_all('/([^{}]+)\{[^{}]*#0b2345[^{}]*\}/i', $css, $matches);
foreach ($matches[1] as $selector) {
    if (str_contains($selector, 'button') || str_contains($selector, 'btn') || str_contains($selector, 'modal')) {
        echo trim($selector) . "\n";
    }
}
