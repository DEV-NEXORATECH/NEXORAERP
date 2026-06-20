<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Http\Traits\AppliesListFilters;
use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    use LoadsErpData, AppliesListFilters;

    public function index(Request $request): View
    {
        $departments = $this->applyListFilters(Department::orderBy('name'), $request, ['name'])->paginate(15)->withQueryString();
        return view('erp.departments.index', compact('departments'));
    }

    public function create(): View
    {
        return view('erp.departments.create');
    }

    public function edit(Department $department): View
    {
        return view('erp.departments.edit', compact('department'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'max:255', Rule::unique('departments', 'name')],
        ]);

        $department = Department::create($data);
        $this->audit('created', $department, 'Department dibuat');

        return redirect()->route('departments.index')->with('status', 'Department berhasil ditambahkan.');
    }

    public function update(Request $request, Department $department): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'max:255', Rule::unique('departments', 'name')->ignore($department)],
        ]);

        $old = $department->toArray();
        $department->update($data);
        $this->audit('updated', $department, 'Department diupdate', $old, $department->fresh()->toArray());

        return redirect()->route('departments.index')->with('status', 'Department berhasil diupdate.');
    }

    public function destroy(Department $department): RedirectResponse
    {
        $this->audit('deleted', $department, 'Department dihapus', $department->toArray());
        $department->delete();

        return redirect()->route('departments.index')->with('status', 'Department berhasil dihapus.');
    }
}
