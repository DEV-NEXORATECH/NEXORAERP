<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\SalesCommission;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SalesCommissionController extends Controller
{
    use LoadsErpData;

    public function index(): View
    {
        $commissions = SalesCommission::latest()->paginate(20);
        return view('erp.sales-commissions.index', compact('commissions'));
    }

    public function create(): View
    {
        $users = User::whereIn('role', ['admin', 'sales'])->orderBy('name')->get(['id', 'name']);
        return view('erp.sales-commissions.create', compact('users'));
    }

    public function edit(SalesCommission $commission): View
    {
        $users = User::whereIn('role', ['admin', 'sales'])->orderBy('name')->get(['id', 'name']);
        return view('erp.sales-commissions.edit', compact('commission', 'users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'user_id'     => ['nullable', 'exists:users,id'],
            'period'      => ['required', 'max:20'],
            'base_amount' => ['required', 'numeric', 'min:0'],
            'rate'        => ['required', 'numeric', 'min:0', 'max:100'],
            'status'      => ['required', Rule::in(['draft', 'approved', 'paid'])],
        ]);
        $data['commission_amount'] = ((float) $data['base_amount']) * ((float) $data['rate'] / 100);

        $row = SalesCommission::create($data);
        $this->audit('created', $row, 'Sales commission dibuat');

        return redirect()->route('sales-commissions.index')->with('status', 'Komisi sales berhasil dihitung.');
    }

    public function update(Request $request, SalesCommission $commission): RedirectResponse
    {
        $old = $commission->toArray();
        $data = $request->validate([
            'user_id'     => ['nullable', 'exists:users,id'],
            'period'      => ['required', 'max:20'],
            'base_amount' => ['required', 'numeric', 'min:0'],
            'rate'        => ['required', 'numeric', 'min:0', 'max:100'],
            'status'      => ['required', Rule::in(['draft', 'approved', 'paid'])],
        ]);
        $data['commission_amount'] = ((float) $data['base_amount']) * ((float) $data['rate'] / 100);

        $commission->update($data);
        $this->audit('updated', $commission, 'Sales commission diedit', $old, $commission->fresh()->toArray());

        return redirect()->route('sales-commissions.index')->with('status', 'Komisi sales berhasil diupdate.');
    }

    public function destroy(SalesCommission $commission): RedirectResponse
    {
        $this->audit('deleted', $commission, 'Sales commission dihapus', $commission->toArray());
        $commission->delete();

        return redirect()->route('sales-commissions.index')->with('status', 'Komisi sales berhasil dihapus.');
    }
}
