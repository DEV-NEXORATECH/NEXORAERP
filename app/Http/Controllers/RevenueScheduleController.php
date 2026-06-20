<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Http\Traits\AppliesListFilters;
use App\Models\Project;
use App\Models\RevenueSchedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RevenueScheduleController extends Controller
{
    use LoadsErpData, AppliesListFilters;

    public function index(Request $request): View
    {
        $schedules = $this->applyListFilters(
            RevenueSchedule::with('project')->latest(),
            $request,
            ['title', 'notes']
        )->paginate(20)->withQueryString();
        $projects = Project::orderByDesc('id')->get(['id', 'code', 'name']);
        return view('erp.revenue-schedules.index', compact('schedules', 'projects'));
    }

    public function create(): View
    {
        $projects = Project::orderByDesc('id')->get(['id', 'code', 'name']);
        return view('erp.revenue-schedules.create', compact('projects'));
    }

    public function edit(RevenueSchedule $schedule): View
    {
        $projects = Project::orderByDesc('id')->get(['id', 'code', 'name']);
        return view('erp.revenue-schedules.edit', compact('schedule', 'projects'));
    }

    public function store(Request $request): RedirectResponse
    {
        $row = RevenueSchedule::create($request->validate([
            'project_id' => ['nullable', 'exists:projects,id'],
            'schedule_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(['pending', 'recognized', 'cancelled'])],
            'notes' => ['nullable'],
        ]));

        $this->audit('created', $row, 'Revenue schedule dibuat');

        return redirect()->route('revenue-schedules.index')->with('status', 'Revenue schedule berhasil ditambahkan.');
    }

    public function update(Request $request, RevenueSchedule $schedule): RedirectResponse
    {
        $old = $schedule->toArray();
        $schedule->update($request->validate([
            'project_id' => ['nullable', 'exists:projects,id'],
            'schedule_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(['pending', 'recognized', 'cancelled'])],
            'notes' => ['nullable'],
        ]));

        $this->audit('updated', $schedule, 'Revenue schedule diedit', $old, $schedule->fresh()->toArray());

        return redirect()->route('revenue-schedules.index')->with('status', 'Revenue schedule berhasil diupdate.');
    }

    public function destroy(RevenueSchedule $schedule): RedirectResponse
    {
        $this->audit('deleted', $schedule, 'Revenue schedule dihapus', $schedule->toArray());
        $schedule->delete();

        return redirect()->route('revenue-schedules.index')->with('status', 'Revenue schedule berhasil dihapus.');
    }
}
