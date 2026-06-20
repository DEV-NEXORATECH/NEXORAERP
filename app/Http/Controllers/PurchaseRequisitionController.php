<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\Department;
use App\Models\PurchaseRequisition;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PurchaseRequisitionController extends Controller
{
    use LoadsErpData;

    public function index(): View
    {
        $requisitions = PurchaseRequisition::latest()->paginate(20);
        return view('erp.purchase-requisitions.index', compact('requisitions'));
    }

    public function create(): View
    {
        $users       = User::orderBy('name')->get(['id', 'name']);
        $departments = Department::orderBy('name')->get(['id', 'name']);
        return view('erp.purchase-requisitions.create', compact('users', 'departments'));
    }

    public function edit(PurchaseRequisition $requisition): View
    {
        $users       = User::orderBy('name')->get(['id', 'name']);
        $departments = Department::orderBy('name')->get(['id', 'name']);
        return view('erp.purchase-requisitions.edit', compact('requisition', 'users', 'departments'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'requester_id'  => ['nullable', 'exists:users,id'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'title'         => ['required', 'max:255'],
            'amount'        => ['required', 'numeric', 'min:0'],
            'required_date' => ['nullable', 'date'],
            'status'        => ['required', Rule::in(['draft', 'submitted', 'approved', 'rejected'])],
            'reason'        => ['nullable'],
        ]);

        $row = PurchaseRequisition::create($data + ['number' => $this->nextNumber('PR-NX', PurchaseRequisition::withTrashed()->count() + 1)]);
        $this->audit('created', $row, 'Purchase requisition dibuat');

        return redirect()->route('purchase-requisitions.index')->with('status', 'Purchase requisition berhasil dibuat.');
    }

    public function update(Request $request, PurchaseRequisition $requisition): RedirectResponse
    {
        $old = $requisition->toArray();
        $requisition->update($request->validate([
            'requester_id'  => ['nullable', 'exists:users,id'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'title'         => ['required', 'max:255'],
            'amount'        => ['required', 'numeric', 'min:0'],
            'required_date' => ['nullable', 'date'],
            'status'        => ['required', Rule::in(['draft', 'submitted', 'approved', 'rejected'])],
            'reason'        => ['nullable'],
        ]));
        $this->audit('updated', $requisition, 'Purchase requisition diedit', $old, $requisition->fresh()->toArray());

        return redirect()->route('purchase-requisitions.index')->with('status', 'Purchase requisition berhasil diupdate.');
    }

    public function destroy(PurchaseRequisition $requisition): RedirectResponse
    {
        $this->audit('deleted', $requisition, 'Purchase requisition dihapus', $requisition->toArray());
        $requisition->delete();

        return redirect()->route('purchase-requisitions.index')->with('status', 'Purchase requisition berhasil dihapus.');
    }
}
