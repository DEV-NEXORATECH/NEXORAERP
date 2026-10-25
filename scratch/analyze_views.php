<?php

$dir = __DIR__ . '/../resources/views/erp';
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
$list = [];

foreach ($files as $file) {
    if (!$file->isFile() || $file->getExtension() !== 'php') continue;
    $filename = $file->getFilename();
    $path = $file->getRealPath();
    
    if ($filename === 'index.blade.php') {
        $content = file_get_contents($path);
        if (str_contains($content, '<table') && !str_contains($content, '<x-erp.toolbar-actions')) {
            $rel = str_replace(realpath(__DIR__ . '/..') . DIRECTORY_SEPARATOR, '', $path);
            $module = basename(dirname($path));
            
            // Extract paginator variable
            preg_match('/\$([a-zA-Z0-9_]+)->links\(\)/', $content, $pMatch);
            $paginator = $pMatch[1] ?? null;
            
            // Extract create route
            preg_match('/route\([\'"]([^\'"]+\.create[^\'"]*)[\'"]\)/', $content, $rMatch);
            $createRoute = $rMatch[1] ?? null;
            
            // Extract filter form
            $hasForm = preg_match('/<form[^>]+method=[\'"]get[\'"][^>]*>/i', $content);
            
            $list[] = [
                'file' => $rel,
                'module' => $module,
                'paginator' => $paginator,
                'create_route' => $createRoute,
                'has_form' => $hasForm,
            ];
        }
    }
}

echo "Found " . count($list) . " index files needing standardization:\n";
foreach ($list as $item) {
    echo "  - {$item['module']} (paginator: " . ($item['paginator'] ?? 'none') . ", create: " . ($item['create_route'] ?? 'none') . ", form: " . ($item['has_form'] ? 'yes' : 'no') . ")\n";
}
