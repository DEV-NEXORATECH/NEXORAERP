<?php

$dir = dirname(__DIR__) . '/resources/views/erp';
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
$formPatterns = [];

foreach ($it as $f) {
    if (!$f->isFile() || !str_ends_with($f->getFilename(), '.blade.php')) continue;
    $path = $f->getPathname();
    $content = file_get_contents($path);

    // Look for filter forms
    if (preg_match_all('/<form\b[^>]*>/i', $content, $matches)) {
        foreach ($matches[0] as $tag) {
            if (stripos($tag, 'get') !== false) {
                if (preg_match('/class=["\']([^"\']*)["\']/', $tag, $m)) {
                    $formPatterns[$m[1]] = ($formPatterns[$m[1]] ?? 0) + 1;
                } else {
                    $formPatterns['[NO_CLASS]'] = ($formPatterns['[NO_CLASS]'] ?? 0) + 1;
                }
            }
        }
    }
}

print_r($formPatterns);
