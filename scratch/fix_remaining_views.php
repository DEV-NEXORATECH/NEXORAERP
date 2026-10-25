<?php

$dir = __DIR__ . '/../resources/views/erp';
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
$updated = 0;

foreach ($files as $file) {
    if (!$file->isFile() || $file->getExtension() !== 'php') continue;
    $filename = $file->getFilename();
    $path = $file->getRealPath();
    
    if ($filename !== 'index.blade.php') continue;
    $module = basename(dirname($path));
    if (in_array($module, ['dashboard', 'all-menu'])) continue;
    
    $content = file_get_contents($path);
    if (!str_contains($content, '<table')) continue;
    
    $modified = false;
    
    // Fix broken action attribute: action="{{ route(" -> action="{{ route('{$module}.index') }}"
    if (str_contains($content, 'action="{{ route("')) {
        $content = str_replace('action="{{ route("', 'action="{{ route(\'' . $module . '.index\') }}"', $content);
        $modified = true;
    }
    
    // Add toolbar-actions if missing
    if (!str_contains($content, '<x-erp.toolbar-actions')) {
        // Find create route if exists
        $createRouteExpr = null;
        $createLabel = '+ Tambah Data';
        
        if (preg_match('/<a[^>]+href=[\'"](\{\{\s*route\([\'"][^\'"]+\.create[^\'"]*[\'"][^)]*\)\s*\}\})[\'"][^>]*>\s*(?:<svg[^>]*>.*?<\/svg>)?\s*([^<]+)<\/a>/s', $content, $mBtn)) {
            $createRouteExpr = $mBtn[1];
            $label = trim(strip_tags($mBtn[2]));
            if (!empty($label) && strlen($label) < 40) {
                $createLabel = str_starts_with($label, '+') ? $label : '+ ' . $label;
            }
            // Replace this <a> button directly with <x-erp.toolbar-actions>
            $replacement = "<x-erp.toolbar-actions module=\"{$module}\" :create-route=\"{$createRouteExpr}\" create-label=\"" . addslashes($createLabel) . "\" />";
            $content = str_replace($mBtn[0], $replacement, $content);
            $modified = true;
        } else {
            // Check if there's a header div where we can insert toolbar-actions
            $replacement = "<x-erp.toolbar-actions module=\"{$module}\" />";
            // Header with flex justify-between
            if (preg_match('/(<div[^>]*class="[^"]*flex[^"]*justify-between[^"]*"[^>]*>\s*<div>.*?<\/div>)\s*(<\/div>)/s', $content, $mH)) {
                $content = str_replace($mH[0], "{$mH[1]}\n        {$replacement}\n    {$mH[2]}", $content);
                $modified = true;
            } elseif (preg_match('/(<x-erp\.page-header[^>]*\/>)/s', $content, $mHdr)) {
                $openHdr = rtrim(substr($mHdr[0], 0, -2)) . ">\n        <x-slot:actions>\n            {$replacement}\n        </x-slot:actions>\n    </x-erp.page-header>";
                $content = str_replace($mHdr[0], $openHdr, $content);
                $modified = true;
            }
        }
    }
    
    // Ensure separate filter-card exists if there is any search/filter form or inputs
    if (!str_contains($content, 'filter-card')) {
        if (preg_match('/<form[^>]+method=[\'"]get[\'"][^>]*>(.*?)<\/form>/s', $content, $mForm)) {
            $cleaned = $mForm[0];
            $wrapped = "\n    {{-- Card 2 — Filter Toolbar (Terpisah & Tepat 1 Baris) --}}\n    <div class=\"filter-card\">\n        " . trim($cleaned) . "\n    </div>\n";
            $content = str_replace($mForm[0], $wrapped, $content);
            $modified = true;
        }
    }
    
    // Ensure form has class filter-toolbar
    $content = preg_replace('/<form\s+method=[\'"]get[\'"]\s+action=([^\s>]+)\s+class=[\'"][^"\']*[\'"]>/i', '<form method="get" action=$1 class="filter-toolbar">', $content);
    
    if ($modified) {
        file_put_contents($path, $content);
        echo "Fixed: {$module}\n";
        $updated++;
    }
}

echo "\nUpdated in fix pass: {$updated}\n";
