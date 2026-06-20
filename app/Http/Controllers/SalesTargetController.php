<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\SalesTarget;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SalesTargetController extends Controller
{
    use LoadsErpData;

    public function index(Request $request): View
    {
        $items = SalesTarget::latest()
            ->when($request->search, fn($q, $v) => $q->where('period', 'like', "%{$v}%"))
            ->when($request->user_id, fn($q, $v) => $q->where('user_id', $v))
            ->paginate(15)->withQueryString();
        $users = \App\Models\User::orderBy('name')->get(['id', 'name']);
        return view('erp.sales-targets.index', compact('items', 'users'));
    }

    public function create(): View
    {
        $users = User::whereIn('role', ['admin', 'sales'])->orderBy('name')->get(['id', 'name']);
        return view('erp.sales-targets.create', compact('users'));
    }

    public function edit(SalesTarget $target): View
    {
        $users = User::whereIn('role', ['admin', 'sales'])->orderBy('name')->get(['id', 'name']);
        return view('erp.sales-targets.edit', compact('target', 'users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $row = SalesTarget::create($request->validate([
            'user_id'         => ['nullable', 'exists:users,id'],
            'period'          => ['required', 'max:20'],
            'target_amount'   => ['required', 'numeric', 'min:0'],
            'achieved_amount' => ['nullable', 'numeric', 'min:0'],
        ]) + ['achieved_amount' => $request->input('achieved_amount', 0)]);

        $this->audit('created', $row, 'Sales target dibuat');

        return redirect()->route('sales-targets.index')->with('status', 'Sales target berhasil disimpan.');
    }

    public function update(Request $request, SalesTarget $target): RedirectResponse
    {
        $old = $target->toArray();
        $target->update($request->validate([
            'user_id'         => ['nullable', 'exists:users,id'],
            'period'          => ['required', 'max:20'],
            'target_amount'   => ['required', 'numeric', 'min:0'],
            'achieved_amount' => ['nullable', 'numeric', 'min:0'],
        ]) + ['achieved_amount' => $request->input('achieved_amount', 0)]);

        $this->audit('updated', $target, 'Sales target diedit', $old, $target->fresh()->toArray());

        return redirect()->route('sales-targets.index')->with('status', 'Sales target berhasil diupdate.');
    }

    public function destroy(SalesTarget $target): RedirectResponse
    {
        $this->audit('deleted', $target, 'Sales target dihapus', $target->toArray());
        $target->delete();

        return redirect()->route('sales-targets.index')->with('status', 'Sales target berhasil dihapus.');
    }
}
