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

    public function index(): View
    {
        $timesheets = Timesheet::with('employee', 'project')->latest()->paginate(20);
        return view('erp.timesheets.index', compact('timesheets'));
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
