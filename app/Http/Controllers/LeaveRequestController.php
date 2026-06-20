<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\Employee;
use App\Models\LeaveRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LeaveRequestController extends Controller
{
    use LoadsErpData;

    public function index(Request $request): View
    {
        $items = LeaveRequest::with('employee')
            ->when($request->status, fn($q, $v) => $q->where('status', $v))
            ->when($request->type, fn($q, $v) => $q->where('type', $v))
            ->when($request->employee_id, fn($q, $v) => $q->where('employee_id', $v))
            ->latest()->paginate(15)->withQueryString();
        $employees = \App\Models\Employee::orderBy('name')->get(['id', 'name']);
        return view('erp.leave-requests.index', compact('items', 'employees'));
    }

    public function create(): View
    {
        $employees = Employee::orderBy('name')->get(['id', 'name']);
        return view('erp.leave-requests.create', compact('employees'));
    }

    public function edit(LeaveRequest $leave): View
    {
        $employees = Employee::orderBy('name')->get(['id', 'name']);
        return view('erp.leave-requests.edit', compact('leave', 'employees'));
    }

    public function store(Request $request): RedirectResponse
    {
        $row = LeaveRequest::create($request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'type'        => ['required', Rule::in(['annual', 'sick', 'unpaid', 'special'])],
            'start_date'  => ['required', 'date'],
            'end_date'    => ['required', 'date', 'after_or_equal:start_date'],
            'status'      => ['required', Rule::in(['pending', 'approved', 'rejected'])],
            'reason'      => ['nullable'],
        ]));
        $this->audit('created', $row, 'Leave request dibuat');

        return redirect()->route('leave-requests.index')->with('status', 'Leave request berhasil ditambahkan.');
    }

    public function update(Request $request, LeaveRequest $leave): RedirectResponse
    {
        $old = $leave->toArray();
        $leave->update($request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'type'        => ['required', Rule::in(['annual', 'sick', 'unpaid', 'special'])],
            'start_date'  => ['required', 'date'],
            'end_date'    => ['required', 'date', 'after_or_equal:start_date'],
            'status'      => ['required', Rule::in(['pending', 'approved', 'rejected'])],
            'reason'      => ['nullable'],
        ]));
        $this->audit('updated', $leave, 'Leave request diedit', $old, $leave->fresh()->toArray());

        return redirect()->route('leave-requests.index')->with('status', 'Leave request berhasil diupdate.');
    }

    public function destroy(LeaveRequest $leave): RedirectResponse
    {
        $this->audit('deleted', $leave, 'Leave request dihapus', $leave->toArray());
        $leave->delete();

        return redirect()->route('leave-requests.index')->with('status', 'Leave request berhasil dihapus.');
    }
}
