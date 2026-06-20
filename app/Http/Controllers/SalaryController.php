<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\Cashflow;
use App\Models\Salary;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SalaryController extends Controller
{
    use LoadsErpData;

    public function index(Request $request): View
    {
        $query = Salary::with(['employee:id,name', 'project:id,code'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('period')) {
            $query->where('period', 'like', '%'.$request->input('period').'%');
        }
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->input('employee_id'));
        }

        $salariesPage = $query->paginate(20)->withQueryString();
        $employees = \App\Models\Employee::orderBy('name')->get(['id', 'name']);
        return view('erp.salaries.index', compact('salariesPage', 'employees'));
    }

    public function create(): View
    {
        $employees = \App\Models\Employee::orderBy('name')->get(['id', 'name']);
        $projects  = \App\Models\Project::orderBy('code')->get(['id', 'code']);
        return view('erp.salaries.create', compact('employees', 'projects'));
    }

    public function edit(Salary $salary): View
    {
        $employees = \App\Models\Employee::orderBy('name')->get(['id', 'name']);
        $projects  = \App\Models\Project::orderBy('code')->get(['id', 'code']);
        return view('erp.salaries.edit', compact('salary', 'employees', 'projects'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data              = $request->validate($this->rules());
        $data['allowance'] = $data['allowance'] ?? 0;
        $data['deduction'] = $data['deduction'] ?? 0;
        $data['net_salary']   = $data['base_salary'] + $data['allowance'] - $data['deduction'];
        $data['slip_number']  = $this->nextNumber('SLP-NX', Salary::withTrashed()->count() + 1);

        $salary = Salary::create($data);
        $this->audit('created', $salary, 'Salary dibuat');

        return back()->with('status', 'Salary berhasil disimpan sebagai draft.');
    }

    public function update(Request $request, Salary $salary): RedirectResponse
    {
        $data              = $request->validate($this->rules($salary));
        $data['allowance'] = $data['allowance'] ?? 0;
        $data['deduction'] = $data['deduction'] ?? 0;
        $data['net_salary'] = $data['base_salary'] + $data['allowance'] - $data['deduction'];

        $old = $salary->toArray();
        $salary->update($data);
        $this->audit('updated', $salary, 'Salary diedit', $old, $salary->fresh()->toArray());

        return back()->with('status', 'Salary berhasil diupdate.');
    }

    public function updateStatus(Request $request, Salary $salary): RedirectResponse
    {
        $data = $request->validate(['status' => ['required', Rule::in(['draft', 'approved', 'paid'])]]);

        if ($data['status'] === 'approved' && ! in_array(auth()->user()->role, ['admin', 'hr'], true)) {
            abort(403, 'Hanya HR/admin yang bisa approve salary.');
        }
        if ($data['status'] === 'paid' && ! in_array(auth()->user()->role, ['admin', 'finance'], true)) {
            abort(403, 'Hanya finance/admin yang bisa paid salary.');
        }

        $old = $salary->toArray();
        $salary->update($data);

        if ($salary->status === 'paid') {
            $this->ensureSalaryCashflow($salary);
        }

        $this->audit('status_updated', $salary, 'Status salary menjadi ' . $salary->status, $old, $salary->fresh()->toArray());

        return back()->with('status', 'Status salary berhasil diupdate.');
    }

    public function destroy(Salary $salary): RedirectResponse
    {
        $this->audit('deleted', $salary, 'Salary dihapus', $salary->toArray());
        $salary->delete();

        return back()->with('status', 'Salary berhasil dihapus.');
    }

    public function pdf(Salary $salary): View
    {
        $salary->load(['employee.jobPosition', 'employee.departmentRecord', 'project']);
        $companySetting = \App\Models\CompanySetting::firstOrCreate(['id' => 1], ['company_name' => 'NEXORA']);

        return view('erp.salaries.print', compact('salary', 'companySetting'));
    }

    private function ensureSalaryCashflow(Salary $salary): void
    {
        if ($salary->cashflow_id) {
            return;
        }

        $salary->load('employee');
        $cashflow = Cashflow::create([
            'project_id'       => $salary->project_id,
            'type'             => 'expense',
            'category'         => 'Payroll',
            'cost_type'        => 'salary',
            'description'      => 'Gaji ' . $salary->employee->name . ' periode ' . $salary->period,
            'amount'           => $salary->net_salary,
            'transaction_date' => now()->toDateString(),
        ]);
        $salary->update(['cashflow_id' => $cashflow->id]);
    }

    private function rules(?Salary $salary = null): array
    {
        return [
            'employee_id' => ['required', 'exists:employees,id'],
            'project_id'  => ['nullable', 'exists:projects,id'],
            'period'      => ['required', 'max:20', Rule::unique('salaries')->where(fn ($q) => $q->where('employee_id', request('employee_id')))->ignore($salary)],
            'base_salary' => ['required', 'numeric', 'min:0'],
            'allowance'   => ['nullable', 'numeric', 'min:0'],
            'deduction'   => ['nullable', 'numeric', 'min:0'],
            'status'      => ['nullable', Rule::in(['draft', 'approved', 'paid'])],
        ];
    }
}
