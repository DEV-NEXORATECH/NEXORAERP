<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\Employee;
use App\Models\PayrollBenefit;
use App\Models\Salary;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PayrollBenefitController extends Controller
{
    use LoadsErpData;

    public function index(Request $request): View
    {
        $items = PayrollBenefit::with('employee', 'salary')
            ->when($request->search, fn($q, $v) => $q->where('period', 'like', "%{$v}%"))
            ->when($request->employee_id, fn($q, $v) => $q->where('employee_id', $v))
            ->latest()->paginate(15)->withQueryString();
        $employees = \App\Models\Employee::orderBy('name')->get(['id', 'name']);
        return view('erp.payroll-benefits.index', compact('items', 'employees'));
    }

    public function create(): View
    {
        $employees = Employee::orderBy('name')->get(['id', 'name']);
        $salaries = Salary::latest()->get(['id', 'slip_number', 'employee_id', 'period']);
        return view('erp.payroll-benefits.create', compact('employees', 'salaries'));
    }

    public function edit(PayrollBenefit $benefit): View
    {
        $employees = Employee::orderBy('name')->get(['id', 'name']);
        $salaries = Salary::latest()->get(['id', 'slip_number', 'employee_id', 'period']);
        return view('erp.payroll-benefits.edit', compact('benefit', 'employees', 'salaries'));
    }

    public function store(Request $request): RedirectResponse
    {
        $row = PayrollBenefit::create($request->validate([
            'salary_id'      => ['nullable', 'exists:salaries,id'],
            'employee_id'    => ['required', 'exists:employees,id'],
            'period'         => ['required', 'max:20'],
            'bpjs_health'    => ['nullable', 'numeric', 'min:0'],
            'bpjs_employment' => ['nullable', 'numeric', 'min:0'],
            'pph21'          => ['nullable', 'numeric', 'min:0'],
            'incentive'      => ['nullable', 'numeric', 'min:0'],
            'status'         => ['required', Rule::in(['draft', 'approved', 'paid'])],
        ]));
        $this->audit('created', $row, 'Payroll benefit dibuat');

        return redirect()->route('payroll-benefits.index')->with('status', 'Payroll benefit berhasil ditambahkan.');
    }

    public function update(Request $request, PayrollBenefit $benefit): RedirectResponse
    {
        $old = $benefit->toArray();
        $benefit->update($request->validate([
            'salary_id'      => ['nullable', 'exists:salaries,id'],
            'employee_id'    => ['required', 'exists:employees,id'],
            'period'         => ['required', 'max:20'],
            'bpjs_health'    => ['nullable', 'numeric', 'min:0'],
            'bpjs_employment' => ['nullable', 'numeric', 'min:0'],
            'pph21'          => ['nullable', 'numeric', 'min:0'],
            'incentive'      => ['nullable', 'numeric', 'min:0'],
            'status'         => ['required', Rule::in(['draft', 'approved', 'paid'])],
        ]));
        $this->audit('updated', $benefit, 'Payroll benefit diedit', $old, $benefit->fresh()->toArray());

        return redirect()->route('payroll-benefits.index')->with('status', 'Payroll benefit berhasil diupdate.');
    }

    public function destroy(PayrollBenefit $benefit): RedirectResponse
    {
        $this->audit('deleted', $benefit, 'Payroll benefit dihapus', $benefit->toArray());
        $benefit->delete();

        return redirect()->route('payroll-benefits.index')->with('status', 'Payroll benefit berhasil dihapus.');
    }
}
