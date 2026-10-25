<?php

$dir = __DIR__ . '/../resources/views/erp';
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
$count = 0;

foreach ($files as $file) {
    if (!$file->isFile() || $file->getExtension() !== 'php') continue;
    $filename = $file->getFilename();
    $path = $file->getRealPath();
    
    // Only target index.blade.php files (skip dashboard and crm overview)
    if ($filename !== 'index.blade.php') continue;
    $module = basename(dirname($path));
    if (in_array($module, ['dashboard', 'all-menu'])) continue;
    
    $content = file_get_contents($path);
    if (!str_contains($content, '<table')) continue;
    
    // Skip if already has toolbar-actions AND filter-card
    if (str_contains($content, '<x-erp.toolbar-actions') && str_contains($content, 'filter-card')) {
        continue;
    }
    
    $modified = false;
    
    // 1. Extract create route
    $createRouteExpr = null;
    $createLabel = '+ Tambah Data';
    if (preg_match('/<a[^>]+href=[\'"]({{\s*route\([\'"]([^\'"]+\.create[^\'"]*)[\'"](?:,\s*[^)]+)?\)\s*}})[\'"][^>]*>\s*(?:<svg[^>]*>.*?<\/svg>)?\s*([^<]+)<\/a>/s', $content, $mBtn)) {
        $createRouteExpr = $mBtn[1];
        $labelCandidate = trim(strip_tags($mBtn[3]));
        if (!empty($labelCandidate) && strlen($labelCandidate) < 40) {
            $createLabel = $labelCandidate;
            if (!str_starts_with($createLabel, '+')) {
                $createLabel = '+ ' . $createLabel;
            }
        }
    } elseif (preg_match('/route\([\'"]([^\'"]+\.create[^\'"]*)[\'"]\)/', $content, $mRoute)) {
        $createRouteExpr = "route('{$mRoute[1]}')";
    }
    
    // 2. Extract paginator variable
    $paginatorVar = null;
    if (preg_match('/\$([a-zA-Z0-9_]+)->links\(\)/', $content, $mPag)) {
        $paginatorVar = $mPag[1];
    } elseif (preg_match('/\$([a-zA-Z0-9_]+)\s+as\s+\$row/', $content, $mForeach)) {
        $paginatorVar = $mForeach[1];
    } elseif (preg_match('/\$([a-zA-Z0-9_]+)\s+as\s+\$item/', $content, $mForeach2)) {
        $paginatorVar = $mForeach2[1];
    }
    
    $titleModule = ucwords(str_replace('-', ' ', $module));
    
    // Construct toolbar-actions component
    $toolbarActions = "<x-erp.toolbar-actions module=\"{$module}\"";
    if ($createRouteExpr) {
        $toolbarActions .= " :create-route=\"{$createRouteExpr}\" create-label=\"" . addslashes($createLabel) . "\"";
    }
    $toolbarActions .= " />";
    
    // Replace header actions or add toolbar-actions
    if (!str_contains($content, '<x-erp.toolbar-actions')) {
        // Pattern 1: <x-erp.page-header ...> with or without <x-slot:actions>
        if (str_contains($content, '<x-erp.page-header')) {
            if (preg_match('/<x-slot:actions>(.*?)<\/x-slot:actions>/s', $content, $mSlot)) {
                $content = str_replace($mSlot[0], "<x-slot:actions>\n            {$toolbarActions}\n        </x-slot:actions>", $content);
                $modified = true;
            } elseif (preg_match('/(<x-erp\.page-header[^>]*\/>)/s', $content, $mHdr)) {
                $openHdr = rtrim(substr($mHdr[0], 0, -2)) . ">\n        <x-slot:actions>\n            {$toolbarActions}\n        </x-slot:actions>\n    </x-erp.page-header>";
                $content = str_replace($mHdr[0], $openHdr, $content);
                $modified = true;
            } elseif (preg_match('/(<x-erp\.page-header[^>]*>)/s', $content, $mHdrOpen)) {
                $content = str_replace($mHdrOpen[0], $mHdrOpen[0] . "\n        <x-slot:actions>\n            {$toolbarActions}\n        </x-slot:actions>", $content);
                $modified = true;
            }
        } else {
            // Pattern 2: Regular header card with a button or right container
            if (preg_match('/(<div[^>]*class="[^"]*flex[^"]*"[^>]*>\s*<div>.*?<\/div>)\s*(<a[^>]+href=[\'"][^\'"]*create[^\'"]*[\'"].*?<\/a>|\s*<div[^>]*class="[^"]*flex items-center[^"]*"[^>]*>.*?<\/div>)\s*(<\/div>)/s', $content, $mHead)) {
                $newHead = "{$mHead[1]}\n        {$toolbarActions}\n    {$mHead[3]}";
                $content = str_replace($mHead[0], $newHead, $content);
                $modified = true;
            }
        }
    }
    
    // 3. Separate Filter Card from Table Card
    // Look for form method="get"
    if (!str_contains($content, 'filter-card') && preg_match('/<form[^>]+method=[\'"]get[\'"][^>]*>(.*?)<\/form>/s', $content, $mForm)) {
        $fullFormTag = $mForm[0];
        $formInner = $mForm[1];
        
        // Extract action from form
        preg_match('/action=[\'"]([^\'"]*)[\'"]/', $fullFormTag, $mAction);
        $action = $mAction[1] ?? '';
        
        // Build clean 1-row filter-card
        // Ensure inputs are compact
        $cleanedFormInner = $formInner;
        // Make search input use filter-search !w-full
        $cleanedFormInner = preg_replace('/<input[^>]+name=[\'"]search[\'"][^>]*class=[\'"]([^\'"]*)[\'"]/i', '<input type="text" name="search" placeholder="Cari..." value="{{ request(\'search\') }}" class="filter-search !w-full pl-8 pr-3 text-xs"', $cleanedFormInner);
        
        $newFilterCard = "\n    {{-- Card 2 — Filter Toolbar (Terpisah & Tepat 1 Baris) --}}\n    <div class=\"filter-card\">\n        <form method=\"get\" action=\"{$action}\" class=\"filter-toolbar\">\n            {$cleanedFormInner}\n        </form>\n    </div>\n";
        
        // If the form was inside the table card:
        // E.g.: <div class="bg-white rounded-lg border ... overflow-hidden ...">\s*<form ...>...</form>\s*<div class="overflow-x-auto">
        $patternFormInside = '/(<div[^>]*class="[^"]*(?:bg-white|workspace-card)[^"]*"[^>]*>)\s*<form[^>]+method=[\'"]get[\'"][^>]*>.*?<\/form>\s*(<div class="overflow-x-auto">)/s';
        if (preg_match($patternFormInside, $content)) {
            $content = preg_replace($patternFormInside, "{$newFilterCard}\n    {{-- Card 3 — Workspace: Data Table --}}\n    <div class=\"workspace-card rounded-lg border border-slate-200 bg-white shadow-xs overflow-hidden\">\n        $2", $content);
            $modified = true;
        } else {
            // Form is outside, just wrap it in filter-card
            $content = str_replace($fullFormTag, $newFilterCard, $content);
            $modified = true;
        }
    }
    
    // 4. Ensure Table has class table-folk
    if (!str_contains($content, 'table-folk')) {
        $content = preg_replace('/<table\s+class=[\'"]([^\'"]*)[\'"]/', '<table class="table-folk w-full text-left border-collapse text-xs"', $content, 1);
        $modified = true;
    }
    
    // 5. Ensure Standalone Pagination Card
    if ($paginatorVar && !str_contains($content, '<x-erp.pagination')) {
        // Pattern: </table></div></div> followed by pagination or pagination inside
        // Close table card first
        $patternPag = '/(<\/table>\s*<\/div>)\s*(?:@if\([^>]+?\)\s*<div class="[^"]*">.*?<\/div>\s*@endif\s*<\/div>|<\/div>\s*@if\([^>]+?\)\s*<div class="[^"]*">.*?<\/div>\s*@endif)/s';
        if (preg_match($patternPag, $content)) {
            $replacement = "$1\n    </div>\n\n    {{-- Card 4 — Pagination (Card Terpisah) --}}\n    <x-erp.pagination :paginator=\"\${$paginatorVar}\" />";
            $content = preg_replace($patternPag, $replacement, $content, 1);
            $modified = true;
        }
    }
    
    // 6. Ensure Import Modal at end of file
    if (!str_contains($content, '<x-erp.import-modal')) {
        $importModal = "\n<x-erp.import-modal module=\"{$module}\" title=\"Impor Data {$titleModule}\" />\n@endsection";
        $content = preg_replace('/@endsection\s*$/', $importModal, trim($content));
        $modified = true;
    }
    
    if ($modified) {
        file_put_contents($path, $content);
        echo "Standardized 4-card layout in: {$module}\n";
        $count++;
    }
}

echo "\nTotal views successfully updated: {$count}\n";
