<?php

namespace App\Http\Controllers;

use App\Http\Traits\AppliesListFilters;
use App\Http\Traits\LoadsErpData;
use App\Models\FiscalPeriod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FiscalPeriodController extends Controller
{
    use LoadsErpData, AppliesListFilters;

    public function index(Request $request): View
    {
        $periods = $this->applyListFilters(FiscalPeriod::orderBy('start_date', 'desc'), $request, ['name', 'code'])->paginate(8)->withQueryString();
        return view('erp.fiscal-periods.index', compact('periods'));
    }

    public function create(): View
    {
        return view('erp.fiscal-periods.create');
    }

    public function edit(FiscalPeriod $period): View
    {
        return view('erp.fiscal-periods.edit', compact('period'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $period = FiscalPeriod::create($data);
        $this->audit('created', $period, 'Periode fiskal dibuat');

        return redirect()->route('fiscal-periods.index')->with('status', 'Periode fiskal berhasil ditambahkan.');
    }

    public function update(Request $request, FiscalPeriod $period): RedirectResponse
    {
        $data = $this->validated($request, $period);
        $old = $period->toArray();
        $period->update($data);
        $this->audit('updated', $period, 'Periode fiskal diupdate', $old, $period->fresh()->toArray());

        return redirect()->route('fiscal-periods.index')->with('status', 'Periode fiskal berhasil diupdate.');
    }

    public function toggle(Request $request, FiscalPeriod $period): RedirectResponse
    {
        $period->update(['status' => $period->status === 'open' ? 'closed' : 'open']);
        $this->audit('updated', $period, 'Periode fiskal ' . $period->status);

        return back()->with('status', 'Status periode fiskal diubah.');
    }

    public function destroy(FiscalPeriod $period): RedirectResponse
    {
        $this->audit('deleted', $period, 'Periode fiskal dihapus', $period->toArray());
        $period->delete();

        return redirect()->route('fiscal-periods.index')->with('status', 'Periode fiskal berhasil dihapus.');
    }

    private function validated(Request $request, ?FiscalPeriod $period = null): array
    {
        $data = $request->validate([
            'code' => ['required', 'max:50', 'regex:/^[a-zA-Z0-9_.-]+$/', Rule::unique('fiscal_periods', 'code')->ignore($period?->id)],
            'name' => ['required', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'status' => ['required', Rule::in(['open', 'closed'])],
        ]);

        $data['start_date'] = \Carbon\Carbon::parse($data['start_date'])->toDateString();
        $data['end_date'] = \Carbon\Carbon::parse($data['end_date'])->toDateString();

        return $data;
    }
}

