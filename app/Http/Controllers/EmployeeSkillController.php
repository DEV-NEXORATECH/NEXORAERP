<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\Employee;
use App\Models\EmployeeSkill;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EmployeeSkillController extends Controller
{
    use LoadsErpData;

    public function index(Request $request): View
    {
        $items = EmployeeSkill::with('employee')
            ->when($request->search, fn($q, $v) => $q->where('skill', 'like', "%{$v}%"))
            ->when($request->level, fn($q, $v) => $q->where('level', $v))
            ->latest()->paginate(15)->withQueryString();
        $employees = \App\Models\Employee::orderBy('name')->get(['id', 'name']);
        return view('erp.employee-skills.index', compact('items', 'employees'));
    }

    public function create(): View
    {
        $employees = Employee::orderBy('name')->get(['id', 'name']);
        return view('erp.employee-skills.create', compact('employees'));
    }

    public function edit(EmployeeSkill $skill): View
    {
        $employees = Employee::orderBy('name')->get(['id', 'name']);
        return view('erp.employee-skills.edit', compact('skill', 'employees'));
    }

    public function store(Request $request): RedirectResponse
    {
        $row = EmployeeSkill::create($request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'skill'       => ['required', 'max:255'],
            'level'       => ['required', Rule::in(['basic', 'intermediate', 'advanced', 'expert'])],
            'notes'       => ['nullable'],
        ]));
        $this->audit('created', $row, 'Employee skill dibuat');

        return redirect()->route('employee-skills.index')->with('status', 'Skill karyawan berhasil ditambahkan.');
    }

    public function update(Request $request, EmployeeSkill $skill): RedirectResponse
    {
        $old = $skill->toArray();
        $skill->update($request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'skill'       => ['required', 'max:255'],
            'level'       => ['required', Rule::in(['basic', 'intermediate', 'advanced', 'expert'])],
            'notes'       => ['nullable'],
        ]));
        $this->audit('updated', $skill, 'Employee skill diedit', $old, $skill->fresh()->toArray());

        return redirect()->route('employee-skills.index')->with('status', 'Skill karyawan berhasil diupdate.');
    }

    public function destroy(EmployeeSkill $skill): RedirectResponse
    {
        $this->audit('deleted', $skill, 'Employee skill dihapus', $skill->toArray());
        $skill->delete();

        return redirect()->route('employee-skills.index')->with('status', 'Skill karyawan berhasil dihapus.');
    }
}
