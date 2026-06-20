<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\EmployeeSkill;
use App\Models\LeaveRequest;
use App\Models\PayrollBenefit;
use App\Models\PerformanceReview;
use App\Models\Project;
use App\Models\Salary;
use App\Models\Timesheet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class HrisController extends Controller
{
    use LoadsErpData;

    public function index(): View
    {
        return view('erp.hris.index', [
            'skills' => EmployeeSkill::latest()->paginate(8, ['*'], 'skill_page')->withQueryString(),
            'attendances' => Attendance::latest()->paginate(8, ['*'], 'attendance_page')->withQueryString(),
            'timesheets' => Timesheet::latest()->paginate(8, ['*'], 'timesheet_page')->withQueryString(),
            'leaves' => LeaveRequest::latest()->paginate(8, ['*'], 'leave_page')->withQueryString(),
            'reviews' => PerformanceReview::latest()->paginate(8, ['*'], 'review_page')->withQueryString(),
            'benefits' => PayrollBenefit::latest()->paginate(8, ['*'], 'benefit_page')->withQueryString(),
            'employees' => Employee::orderBy('name')->get(['id', 'name']),
            'projects' => Project::orderBy('code')->get(['id', 'code', 'name']),
            'salaries' => Salary::latest()->get(['id', 'slip_number', 'employee_id', 'period']),
        ]);
    }

    public function storeSkill(Request $request): RedirectResponse
    {
        $row = EmployeeSkill::create($request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'skill' => ['required', 'max:255'],
            'level' => ['required', Rule::in(['basic', 'intermediate', 'advanced', 'expert'])],
            'notes' => ['nullable'],
        ]));
        $this->audit('created', $row, 'Employee skill dibuat');

        return back()->with('status', 'Skill karyawan berhasil disimpan.');
    }

    public function storeAttendance(Request $request): RedirectResponse
    {
        $row = Attendance::create($request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'work_date' => ['required', 'date'],
            'check_in' => ['nullable', 'date_format:H:i'],
            'check_out' => ['nullable', 'date_format:H:i'],
            'work_mode' => ['required', Rule::in(['office', 'remote', 'hybrid'])],
            'status' => ['required', Rule::in(['present', 'late', 'absent', 'leave'])],
            'notes' => ['nullable'],
        ]));
        $this->audit('created', $row, 'Attendance dibuat');

        return back()->with('status', 'Attendance berhasil dicatat.');
    }

    public function storeTimesheet(Request $request): RedirectResponse
    {
        $row = Timesheet::create($request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'project_id' => ['nullable', 'exists:projects,id'],
            'work_date' => ['required', 'date'],
            'hours' => ['required', 'numeric', 'min:0', 'max:24'],
            'status' => ['required', Rule::in(['submitted', 'approved', 'rejected'])],
            'description' => ['nullable'],
        ]));
        $this->audit('created', $row, 'Timesheet dibuat');

        return back()->with('status', 'Timesheet berhasil disubmit.');
    }

    public function storeLeave(Request $request): RedirectResponse
    {
        $row = LeaveRequest::create($request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'type' => ['required', Rule::in(['annual', 'sick', 'unpaid', 'special'])],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'status' => ['required', Rule::in(['pending', 'approved', 'rejected'])],
            'reason' => ['nullable'],
        ]));
        $this->audit('created', $row, 'Leave request dibuat');

        return back()->with('status', 'Leave request berhasil dibuat.');
    }

    public function storeReview(Request $request): RedirectResponse
    {
        $row = PerformanceReview::create($request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'period' => ['required', 'max:20'],
            'kpi_score' => ['required', 'integer', 'min:0', 'max:100'],
            'okr_score' => ['required', 'integer', 'min:0', 'max:100'],
            'rating' => ['required', Rule::in(['needs_improvement', 'meeting', 'exceeding', 'outstanding'])],
            'notes' => ['nullable'],
        ]));
        $this->audit('created', $row, 'Performance review dibuat');

        return back()->with('status', 'KPI/OKR berhasil dicatat.');
    }

    public function storeBenefit(Request $request): RedirectResponse
    {
        $row = PayrollBenefit::create($request->validate([
            'salary_id' => ['nullable', 'exists:salaries,id'],
            'employee_id' => ['required', 'exists:employees,id'],
            'period' => ['required', 'max:20'],
            'bpjs_health' => ['nullable', 'numeric', 'min:0'],
            'bpjs_employment' => ['nullable', 'numeric', 'min:0'],
            'pph21' => ['nullable', 'numeric', 'min:0'],
            'incentive' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(['draft', 'approved', 'paid'])],
        ]));
        $this->audit('created', $row, 'Payroll benefit dibuat');

        return back()->with('status', 'Payroll benefit berhasil disimpan.');
    }
}
