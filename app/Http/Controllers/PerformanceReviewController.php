<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\Employee;
use App\Models\PerformanceReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PerformanceReviewController extends Controller
{
    use LoadsErpData;

    public function index(): View
    {
        $reviews = PerformanceReview::with('employee')->latest()->paginate(20);
        return view('erp.performance-reviews.index', compact('reviews'));
    }

    public function create(): View
    {
        $employees = Employee::orderBy('name')->get(['id', 'name']);
        return view('erp.performance-reviews.create', compact('employees'));
    }

    public function edit(PerformanceReview $review): View
    {
        $employees = Employee::orderBy('name')->get(['id', 'name']);
        return view('erp.performance-reviews.edit', compact('review', 'employees'));
    }

    public function store(Request $request): RedirectResponse
    {
        $row = PerformanceReview::create($request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'period'      => ['required', 'max:20'],
            'kpi_score'   => ['required', 'integer', 'min:0', 'max:100'],
            'okr_score'   => ['required', 'integer', 'min:0', 'max:100'],
            'rating'      => ['required', Rule::in(['needs_improvement', 'meeting', 'exceeding', 'outstanding'])],
            'notes'       => ['nullable'],
        ]));
        $this->audit('created', $row, 'Performance review dibuat');

        return redirect()->route('performance-reviews.index')->with('status', 'KPI/OKR berhasil ditambahkan.');
    }

    public function update(Request $request, PerformanceReview $review): RedirectResponse
    {
        $old = $review->toArray();
        $review->update($request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'period'      => ['required', 'max:20'],
            'kpi_score'   => ['required', 'integer', 'min:0', 'max:100'],
            'okr_score'   => ['required', 'integer', 'min:0', 'max:100'],
            'rating'      => ['required', Rule::in(['needs_improvement', 'meeting', 'exceeding', 'outstanding'])],
            'notes'       => ['nullable'],
        ]));
        $this->audit('updated', $review, 'Performance review diedit', $old, $review->fresh()->toArray());

        return redirect()->route('performance-reviews.index')->with('status', 'KPI/OKR berhasil diupdate.');
    }

    public function destroy(PerformanceReview $review): RedirectResponse
    {
        $this->audit('deleted', $review, 'Performance review dihapus', $review->toArray());
        $review->delete();

        return redirect()->route('performance-reviews.index')->with('status', 'KPI/OKR berhasil dihapus.');
    }
}
