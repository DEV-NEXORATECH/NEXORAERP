<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\Client;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProjectController extends Controller
{
    use LoadsErpData;

    public function index(): View
    {
        $projectsPage   = Project::with(['clientRecord'])->latest()->paginate(15);
        $projectReports = Project::with(['cashflows', 'salaries', 'reimbursements', 'invoices'])->get()->map(fn ($p) => [
            'project'             => $p,
            'summary'             => $this->cashflowSummary($p->cashflows),
            'profit_margin'       => $p->contract_value > 0 ? ($this->cashflowSummary($p->cashflows)['balance'] / $p->contract_value * 100) : 0,
            'salary_total'        => $p->salaries->where('status', 'paid')->sum('net_salary'),
            'reimbursement_total' => $p->reimbursements->where('status', 'paid')->sum('amount'),
            'invoice_total'       => $p->invoices->sum('amount'),
        ]);

        return view('erp.projects.index', compact('projectsPage', 'projectReports'));
    }

    public function create(): View
    {
        $clients = Client::orderBy('name')->get();
        return view('erp.projects.create', compact('clients'));
    }

    public function edit(Project $project): View
    {
        $clients = Client::orderBy('name')->get();
        return view('erp.projects.edit', compact('project', 'clients'));
    }

    public function show(Project $project): View
    {
        $project->load([
            'clientRecord',
            'cashflows',
            'proposals',
            'invoices.payments',
            'salaries.employee',
            'reimbursements.employee',
        ]);

        $summary      = $this->cashflowSummary($project->cashflows);
        $profitMargin = $summary['income'] > 0
            ? (($summary['balance'] / $summary['income']) * 100)
            : 0;

        $timeline = collect()
            ->merge($project->proposals->map(fn ($p) => ['date' => $p->created_at, 'label' => 'Proposal dibuat', 'desc' => $p->number . ' ' . $p->title]))
            ->merge($project->invoices->map(fn ($i) => ['date' => $i->created_at, 'label' => 'Invoice dibuat', 'desc' => $i->number]))
            ->merge($project->invoices->flatMap->payments->map(fn ($p) => ['date' => $p->created_at, 'label' => 'Payment masuk', 'desc' => $p->reference . ' Rp ' . number_format($p->amount, 0, ',', '.')]))
            ->merge($project->cashflows->map(fn ($c) => ['date' => $c->created_at, 'label' => 'Cost/cashflow ditambahkan', 'desc' => $c->category . ' ' . $c->description]))
            ->merge($project->reimbursements->where('status', 'paid')->map(fn ($r) => ['date' => $r->updated_at, 'label' => 'Reimbursement paid', 'desc' => $r->employee->name . ' ' . $r->category]))
            ->merge($project->salaries->where('status', 'paid')->map(fn ($s) => ['date' => $s->updated_at, 'label' => 'Salary paid', 'desc' => $s->employee->name . ' ' . $s->period]))
            ->sortByDesc('date')
            ->values();

        return view('erp.projects.show', compact('project', 'summary', 'profitMargin', 'timeline'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data           = $request->validate($this->rules());
        $data['code']   = $data['code'] ?: $this->nextNumber('NX-PRJ', Project::withTrashed()->count() + 1);
        $data['client'] = Client::find($data['client_id'])?->name ?? $data['client'];
        $data['contract_file_path'] = $this->storeUpload($request, 'contract_file', 'contracts');
        unset($data['contract_file']);

        $project = Project::create($data);
        $this->audit('created', $project, 'Project dibuat');

        return back()->with('status', 'Project berhasil dibuat.');
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $data           = $request->validate($this->rules($project));
        $data['client'] = Client::find($data['client_id'])?->name ?? $data['client'];
        $data['contract_file_path'] = $this->storeUpload($request, 'contract_file', 'contracts') ?? $project->contract_file_path;
        unset($data['contract_file']);

        $old = $project->toArray();
        $project->update($data);
        $this->audit('updated', $project, 'Project diedit', $old, $project->fresh()->toArray());

        return back()->with('status', 'Project berhasil diupdate.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $this->audit('deleted', $project, 'Project dihapus', $project->toArray());
        $project->delete();

        return back()->with('status', 'Project berhasil dihapus.');
    }

    private function rules(?Project $project = null): array
    {
        return [
            'code'          => ['nullable', 'max:50', Rule::unique('projects', 'code')->ignore($project)],
            'name'          => ['required', 'max:255'],
            'client_id'     => ['nullable', 'exists:clients,id'],
            'client'        => ['nullable', 'max:255'],
            'status'        => ['required', Rule::in(['planning', 'active', 'done', 'hold'])],
            'start_date'    => ['nullable', 'date'],
            'end_date'      => ['nullable', 'date', 'after_or_equal:start_date'],
            'budget'        => ['nullable', 'numeric', 'min:0'],
            'contract_value'  => ['nullable', 'numeric', 'min:0'],
            'contract_file' => ['nullable', 'file', 'max:4096'],
        ];
    }
}
