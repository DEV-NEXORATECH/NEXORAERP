<?php

namespace App\Http\Controllers;

class BackupController extends Controller
{
    public function download()
    {
        $path = database_path('database.sqlite');
        abort_unless(file_exists($path), 404);

        return response()->download($path, 'nexora-backup-' . now()->format('Ymd-His') . '.sqlite');
    }
}
