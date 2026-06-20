<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\Employee;
use App\Models\Project;
use App\Models\Timesheet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TimesheetController extends Controller
{
    use LoadsErpData;

    public function index(Request $request): View
    {
        $items = Timesheet::with('employee', 'project')
            ->when($request->date_from, fn($q, $v) => $q->whereDate('work_date', '>=', $v))
            ->when($request->date_to, fn($q, $v) => $q->whereDate('work_date', '<=', $v))
            ->when($request->employee_id, fn($q, $v) => $q->where('employee_id', $v))
            ->when($request->status, fn($q, $v) => $q->where('status', $v))
            ->latest()->paginate(15)->withQueryString();
        $employees = \App\Models\Employee::orderBy('name')->get(['id', 'name']);
        return view('erp.timesheets.index', compact('items', 'employees'));
    }

    public function create(): View
    {
        $employees = Employee::orderBy('name')->get(['id', 'name']);
        $projects = Project::orderBy('code')->get(['id', 'code', 'name']);
        return view('erp.timesheets.create', compact('employees', 'projects'));
    }

    public function edit(Timesheet $timesheet): View
    {
        $employees = Employee::orderBy('name')->get(['id', 'name']);
        $projects = Project::orderBy('code')->get(['id', 'code', 'name']);
        return view('erp.timesheets.edit', compact('timesheet', 'employees', 'projects'));
    }

    public function store(Request $request): RedirectResponse
    {
        $row = Timesheet::create($request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'project_id'  => ['nullable', 'exists:projects,id'],
            'work_date'   => ['required', 'date'],
            'hours'       => ['required', 'numeric', 'min:0', 'max:24'],
            'status'      => ['required', Rule::in(['submitted', 'approved', 'rejected'])],
            'description' => ['nullable'],
        ]));
        $this->audit('created', $row, 'Timesheet dibuat');

        return redirect()->route('timesheets.index')->with('status', 'Timesheet berhasil disubmit.');
    }

    public function update(Request $request, Timesheet $timesheet): RedirectResponse
    {
        $old = $timesheet->toArray();
        $timesheet->update($request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'project_id'  => ['nullable', 'exists:projects,id'],
            'work_date'   => ['required', 'date'],
            'hours'       => ['required', 'numeric', 'min:0', 'max:24'],
            'status'      => ['required', Rule::in(['submitted', 'approved', 'rejected'])],
            'description' => ['nullable'],
        ]));
        $this->audit('updated', $timesheet, 'Timesheet diedit', $old, $timesheet->fresh()->toArray());

        return redirect()->route('timesheets.index')->with('status', 'Timesheet berhasil diupdate.');
    }

    public function destroy(Timesheet $timesheet): RedirectResponse
    {
        $this->audit('deleted', $timesheet, 'Timesheet dihapus', $timesheet->toArray());
        $timesheet->delete();

        return redirect()->route('timesheets.index')->with('status', 'Timesheet berhasil dihapus.');
    }
}
