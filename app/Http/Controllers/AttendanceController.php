<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    use LoadsErpData;

    public function index(Request $request): View
    {
        $items = Attendance::with('employee')
            ->when($request->date_from, fn($q, $v) => $q->whereDate('work_date', '>=', $v))
            ->when($request->date_to, fn($q, $v) => $q->whereDate('work_date', '<=', $v))
            ->when($request->employee_id, fn($q, $v) => $q->where('employee_id', $v))
            ->when($request->mode, fn($q, $v) => $q->where('work_mode', $v))
            ->latest()->paginate(15)->withQueryString();
        $employees = \App\Models\Employee::orderBy('name')->get(['id', 'name']);
        return view('erp.attendances.index', compact('items', 'employees'));
    }

    public function create(): View
    {
        $employees = Employee::orderBy('name')->get(['id', 'name']);
        return view('erp.attendances.create', compact('employees'));
    }

    public function edit(Attendance $attendance): View
    {
        $employees = Employee::orderBy('name')->get(['id', 'name']);
        return view('erp.attendances.edit', compact('attendance', 'employees'));
    }

    public function store(Request $request): RedirectResponse
    {
        $row = Attendance::create($request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'work_date'   => ['required', 'date'],
            'check_in'    => ['nullable', 'date_format:H:i'],
            'check_out'   => ['nullable', 'date_format:H:i'],
            'work_mode'   => ['required', Rule::in(['office', 'remote', 'hybrid'])],
            'status'      => ['required', Rule::in(['present', 'late', 'absent', 'leave'])],
            'notes'       => ['nullable'],
        ]));
        $this->audit('created', $row, 'Attendance dibuat');

        return redirect()->route('attendances.index')->with('status', 'Attendance berhasil dicatat.');
    }

    public function update(Request $request, Attendance $attendance): RedirectResponse
    {
        $old = $attendance->toArray();
        $attendance->update($request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'work_date'   => ['required', 'date'],
            'check_in'    => ['nullable', 'date_format:H:i'],
            'check_out'   => ['nullable', 'date_format:H:i'],
            'work_mode'   => ['required', Rule::in(['office', 'remote', 'hybrid'])],
            'status'      => ['required', Rule::in(['present', 'late', 'absent', 'leave'])],
            'notes'       => ['nullable'],
        ]));
        $this->audit('updated', $attendance, 'Attendance diedit', $old, $attendance->fresh()->toArray());

        return redirect()->route('attendances.index')->with('status', 'Attendance berhasil diupdate.');
    }

    public function destroy(Attendance $attendance): RedirectResponse
    {
        $this->audit('deleted', $attendance, 'Attendance dihapus', $attendance->toArray());
        $attendance->delete();

        return redirect()->route('attendances.index')->with('status', 'Attendance berhasil dihapus.');
    }
}
