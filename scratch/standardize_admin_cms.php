<?php

// 1. admin/audit.blade.php
$adminAudit = file_get_contents(dirname(__DIR__) . '/resources/views/erp/admin/audit.blade.php');
$adminAudit = str_replace(
    '<div class="inline-flex h-9 items-center gap-1.5 self-start rounded-md border border-[#E5E7EB] bg-[#F8FAFC] px-3.5 text-xs font-semibold text-[#0F172A] md:self-auto">
            <span class="text-[#475569]">Total Log:</span>
            <span class="font-bold text-[#002875]">{{ $auditLogs->total() }} Entri</span>
        </div>',
    '<x-erp.toolbar-actions module="audit_logs" :can-create="false" />',
    $adminAudit
);
$adminAudit = str_replace('<table class="w-full text-left text-xs">', '<table class="table-folk w-full text-left border-collapse text-xs">', $adminAudit);
file_put_contents(dirname(__DIR__) . '/resources/views/erp/admin/audit.blade.php', $adminAudit);

// 2. admin/trash.blade.php
$adminTrash = file_get_contents(dirname(__DIR__) . '/resources/views/erp/admin/trash.blade.php');
$adminTrash = str_replace(
    '        </div>
    </div>

    {{-- Trash Table Container --}}',
    '        </div>
        <x-erp.toolbar-actions module="trash" :can-create="false" />
    </div>

    {{-- Trash Table Container --}}',
    $adminTrash
);
$adminTrash = str_replace('<table class="w-full text-left text-xs">', '<table class="table-folk w-full text-left border-collapse text-xs">', $adminTrash);
file_put_contents(dirname(__DIR__) . '/resources/views/erp/admin/trash.blade.php', $adminTrash);

// 3. admin/users.blade.php
$adminUsers = file_get_contents(dirname(__DIR__) . '/resources/views/erp/admin/users.blade.php');
$adminUsers = str_replace(
    '<div class="inline-flex h-9 items-center gap-1.5 self-start rounded-md border border-[#E5E7EB] bg-[#F8FAFC] px-3.5 text-xs font-semibold text-[#0F172A] md:self-auto">
            <span class="text-[#475569]">Total Akun:</span>
            <span class="font-bold text-[#002875]">{{ $users->total() }} User</span>
        </div>',
    '<x-erp.toolbar-actions module="users" :can-create="false" />',
    $adminUsers
);
$adminUsers = str_replace('<table class="w-full text-left text-xs">', '<table class="table-folk w-full text-left border-collapse text-xs">', $adminUsers);
file_put_contents(dirname(__DIR__) . '/resources/views/erp/admin/users.blade.php', $adminUsers);

// 4. cms/blog/index.blade.php
$cmsBlog = file_get_contents(dirname(__DIR__) . '/resources/views/erp/cms/blog/index.blade.php');
$cmsBlog = preg_replace(
    '/<div class="flex items-center gap-3 self-start md:self-auto">.*?<\/div>\s*<\/div>/s',
    '<x-erp.toolbar-actions module="cms_posts" :create-route="route(\'cms.blog.create\')" create-label="+ Tulis Artikel Baru" />
    </div>',
    $cmsBlog
);
file_put_contents(dirname(__DIR__) . '/resources/views/erp/cms/blog/index.blade.php', $cmsBlog);

// 5. feature-flags/index.blade.php
$ff = file_get_contents(dirname(__DIR__) . '/resources/views/erp/feature-flags/index.blade.php');
$ff = preg_replace(
    '/<div class="section-head">.*?<\/div>\s*<\/div>/s',
    '<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-gray-900 tracking-tight">Pengaturan Fitur</h1>
            <p class="text-xs text-gray-500">Kelola status aktif/nonaktif fitur ERP untuk kontrol modul sistem.</p>
        </div>
        <x-erp.toolbar-actions module="feature_flags" :create-route="route(\'feature-flags.create-page\')" create-label="+ Tambah Fitur" />
    </div>',
    $ff
);
file_put_contents(dirname(__DIR__) . '/resources/views/erp/feature-flags/index.blade.php', $ff);

// 6. cms/sections/index.blade.php
if (file_exists(dirname(__DIR__) . '/resources/views/erp/cms/sections/index.blade.php')) {
    $sec = file_get_contents(dirname(__DIR__) . '/resources/views/erp/cms/sections/index.blade.php');
    if (!str_contains($sec, 'x-erp.toolbar-actions')) {
        $sec = str_replace(
            '</x-erp.page-header>',
            '    <x-erp.toolbar-actions module="cms_sections" :can-create="false" />
</x-erp.page-header>',
            $sec
        );
        file_put_contents(dirname(__DIR__) . '/resources/views/erp/cms/sections/index.blade.php', $sec);
    }
}

echo "Standardized admin, cms, and feature flags!\n";
