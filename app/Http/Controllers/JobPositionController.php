<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Http\Traits\AppliesListFilters;
use App\Models\JobPosition;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class JobPositionController extends Controller
{
    use LoadsErpData, AppliesListFilters;

    public function index(Request $request): View
    {
        $jobPositions = $this->applyListFilters(JobPosition::orderBy('name'), $request, ['name'])->paginate(15)->withQueryString();
        return view('erp.job-positions.index', compact('jobPositions'));
    }

    public function create(): View
    {
        return view('erp.job-positions.create');
    }

    public function edit(JobPosition $jobPosition): View
    {
        return view('erp.job-positions.edit', compact('jobPosition'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'max:255', Rule::unique('job_positions', 'name')],
        ]);

        $jobPosition = JobPosition::create($data);
        $this->audit('created', $jobPosition, 'Job position dibuat');

        return redirect()->route('job-positions.index')->with('status', 'Job position berhasil ditambahkan.');
    }

    public function update(Request $request, JobPosition $jobPosition): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'max:255', Rule::unique('job_positions', 'name')->ignore($jobPosition)],
        ]);

        $old = $jobPosition->toArray();
        $jobPosition->update($data);
        $this->audit('updated', $jobPosition, 'Job position diupdate', $old, $jobPosition->fresh()->toArray());

        return redirect()->route('job-positions.index')->with('status', 'Job position berhasil diupdate.');
    }

    public function destroy(JobPosition $jobPosition): RedirectResponse
    {
        $this->audit('deleted', $jobPosition, 'Job position dihapus', $jobPosition->toArray());
        $jobPosition->delete();

        return redirect()->route('job-positions.index')->with('status', 'Job position berhasil dihapus.');
    }
}
