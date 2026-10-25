<?php

$dir = 'c:/Users/raulm/Downloads/erpnexora/resources/views/erp';
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

$updated = 0;

foreach ($files as $file) {
    if (!$file->isFile() || $file->getExtension() !== 'php') {
        continue;
    }

    $path = $file->getRealPath();
    $content = file_get_contents($path);

    if (!str_contains($content, '->links()')) {
        continue;
    }

    // Skip if pagination-card is already used
    if (str_contains($content, 'pagination-card') || str_contains($content, '<x-erp.pagination')) {
        continue;
    }

    $modified = false;

    // Pattern A:
    // </table></div> followed by @if(...links...) ... @endif inside card
    // Replace by closing card first, then opening standalone pagination-card
    $patternA = '/(<\/table>\s*<\/div>)\s*(@if\([^>]+?\))\s*<div class="([^"]*?)">\s*(.*?)\s*<\/div>\s*(@endif)\s*<\/div>/s';

    if (preg_match($patternA, $content, $matches)) {
        $replacement = "$1\n    </div>\n\n    $2\n        <div class=\"pagination-card flex flex-col sm:flex-row sm:items-center sm:justify-between rounded-lg border border-[#E5E7EB] bg-white px-4 py-3 text-xs text-[#475569] shadow-xs gap-3\">\n            $4\n        </div>\n    $5";
        $content = preg_replace($patternA, $replacement, $content, 1);
        $modified = true;
    }

    // Pattern B:
    // @if(...hasPages()...) ... {{ ...->links() }} ... @endif
    $patternB = '/(<\/table>\s*<\/div>)\s*(@if\([^>]+?hasPages[^>]*?\))\s*<div class="([^"]*?)">\s*(.*?)\s*<\/div>\s*(@endif)/s';
    if (!$modified && preg_match($patternB, $content, $matches)) {
        $replacement = "$1\n    </div>\n\n    $2\n        <div class=\"pagination-card flex flex-col sm:flex-row sm:items-center sm:justify-between rounded-lg border border-[#E5E7EB] bg-white px-4 py-3 text-xs text-[#475569] shadow-xs gap-3\">\n            $4\n        </div>\n    $5";
        $content = preg_replace($patternB, $replacement, $content, 1);
        $modified = true;
    }

    if ($modified) {
        file_put_contents($path, $content);
        echo "Separated pagination in: " . basename(dirname($path)) . "/" . basename($path) . "\n";
        $updated++;
    }
}

echo "\nTotal views updated in pass 2: {$updated}\n";
