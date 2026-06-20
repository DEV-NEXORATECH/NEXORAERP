<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Http\Traits\AppliesListFilters;
use App\Models\ExpenseCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ExpenseCategoryController extends Controller
{
    use LoadsErpData, AppliesListFilters;

    public function index(Request $request): View
    {
        $expenseCategories = $this->applyListFilters(ExpenseCategory::orderBy('name'), $request, ['name'])->paginate(15)->withQueryString();
        return view('erp.expense-categories.index', compact('expenseCategories'));
    }

    public function create(): View
    {
        return view('erp.expense-categories.create');
    }

    public function edit(ExpenseCategory $expenseCategory): View
    {
        return view('erp.expense-categories.edit', compact('expenseCategory'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'max:255', Rule::unique('expense_categories', 'name')],
            'type' => ['required', 'max:50'],
        ]);

        $expenseCategory = ExpenseCategory::create($data);
        $this->audit('created', $expenseCategory, 'Expense category dibuat');

        return redirect()->route('expense-categories.index')->with('status', 'Expense category berhasil ditambahkan.');
    }

    public function update(Request $request, ExpenseCategory $expenseCategory): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'max:255', Rule::unique('expense_categories', 'name')->ignore($expenseCategory)],
            'type' => ['required', 'max:50'],
        ]);

        $old = $expenseCategory->toArray();
        $expenseCategory->update($data);
        $this->audit('updated', $expenseCategory, 'Expense category diupdate', $old, $expenseCategory->fresh()->toArray());

        return redirect()->route('expense-categories.index')->with('status', 'Expense category berhasil diupdate.');
    }

    public function destroy(ExpenseCategory $expenseCategory): RedirectResponse
    {
        $this->audit('deleted', $expenseCategory, 'Expense category dihapus', $expenseCategory->toArray());
        $expenseCategory->delete();

        return redirect()->route('expense-categories.index')->with('status', 'Expense category berhasil dihapus.');
    }
}
