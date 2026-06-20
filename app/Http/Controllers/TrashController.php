<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TrashController extends Controller
{
    use LoadsErpData;

    public function index(Request $request): View
    {
        $type = $request->input('type');
        $models = [
            'projects'  => \App\Models\Project::class,
            'proposals' => \App\Models\Proposal::class,
            'invoices'  => \App\Models\Invoice::class,
            'users'     => User::class,
        ];

        if ($type && isset($models[$type])) {
            $trash = [
                $type => $models[$type]::onlyTrashed()->latest()->paginate(8, ['*'], $type.'_page')->withQueryString(),
            ];
        } else {
            $trash = [
                'projects'  => \App\Models\Project::onlyTrashed()->latest()->paginate(8, ['*'], 'projects_page')->withQueryString(),
                'proposals' => \App\Models\Proposal::onlyTrashed()->latest()->paginate(8, ['*'], 'proposals_page')->withQueryString(),
                'invoices'  => \App\Models\Invoice::onlyTrashed()->latest()->paginate(8, ['*'], 'invoices_page')->withQueryString(),
                'users'     => User::onlyTrashed()->latest()->paginate(8, ['*'], 'users_page')->withQueryString(),
            ];
        }
        $hasTrash = collect($trash)->sum(fn ($items) => $items->total()) > 0;
        return view('erp.trash.index', compact('trash', 'hasTrash'));
    }

    public function restore(string $type, int $id): RedirectResponse
    {
        $models = [
            'projects'  => \App\Models\Project::class,
            'proposals' => \App\Models\Proposal::class,
            'invoices'  => \App\Models\Invoice::class,
            'users'     => User::class,
        ];

        abort_unless(isset($models[$type]), 404);
        $model = $models[$type]::onlyTrashed()->findOrFail($id);
        $model->restore();
        $this->audit('restored', $model, 'Data ' . $type . ' direstore');

        return back()->with('status', 'Data berhasil direstore.');
    }
}
