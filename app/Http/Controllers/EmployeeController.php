<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\Department;
use App\Models\Employee;
use App\Models\JobPosition;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    use LoadsErpData;

    public function index(): View
    {
        $employeesPage = Employee::latest()->paginate(20);
        return view('erp.employees.index', compact('employeesPage'));
    }

    public function create(): View
    {
        $jobPositions = JobPosition::orderBy('name')->get();
        $departments  = Department::orderBy('name')->get();
        return view('erp.employees.create', compact('jobPositions', 'departments'));
    }

    public function edit(Employee $employee): View
    {
        $jobPositions = JobPosition::orderBy('name')->get();
        $departments  = Department::orderBy('name')->get();
        return view('erp.employees.edit', compact('employee', 'jobPositions', 'departments'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data     = $request->validate($this->rules());
        $data     = $this->hydrateLabels($data);
        $employee = Employee::create($data);
        $this->audit('created', $employee, 'Karyawan dibuat');

        return back()->with('status', 'Karyawan berhasil ditambahkan.');
    }

    public function update(Request $request, Employee $employee): RedirectResponse
    {
        $old = $employee->toArray();
        $employee->update($this->hydrateLabels($request->validate($this->rules())));
        $this->audit('updated', $employee, 'Karyawan diedit', $old, $employee->fresh()->toArray());

        return back()->with('status', 'Karyawan berhasil diupdate.');
    }

    public function destroy(Employee $employee): RedirectResponse
    {
        $this->audit('deleted', $employee, 'Karyawan dihapus', $employee->toArray());
        $employee->delete();

        return back()->with('status', 'Karyawan berhasil dihapus.');
    }

    private function hydrateLabels(array $data): array
    {
        $data['department'] = Department::find($data['department_id'] ?? null)?->name ?? ($data['department'] ?? 'IT');
        $data['position']   = JobPosition::find($data['job_position_id'] ?? null)?->name ?? ($data['position'] ?? 'Staff');

        return $data;
    }

    private function rules(): array
    {
        return [
            'name'            => ['required', 'max:255'],
            'position'        => ['nullable', 'max:255'],
            'job_position_id' => ['nullable', 'exists:job_positions,id'],
            'department'      => ['nullable', 'max:100'],
            'department_id'   => ['nullable', 'exists:departments,id'],
            'base_salary'     => ['required', 'numeric', 'min:0'],
        ];
    }
}
