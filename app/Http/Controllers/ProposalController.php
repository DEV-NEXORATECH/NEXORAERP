<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\Proposal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProposalController extends Controller
{
    use LoadsErpData;

    public function index(Request $request): View
    {
        $query = Proposal::with(['project:id,code,name'])->latest();

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date('date_to'));
        }

        $proposalsPage = $query->paginate(20)->withQueryString();
        return view('erp.sales.index', compact('proposalsPage'));
    }

    public function create(): View
    {
        $projects = \App\Models\Project::orderBy('code')->get(['id', 'code', 'name']);
        return view('erp.sales.create', compact('projects'));
    }

    public function edit(Proposal $proposal): View
    {
        $projects = \App\Models\Project::orderBy('code')->get(['id', 'code', 'name']);
        return view('erp.sales.edit', compact('proposal', 'projects'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data           = $request->validate($this->rules());
        $data['number'] = $data['number'] ?: $this->nextNumber('PRP-NX', Proposal::withTrashed()->count() + 1);
        $data['signed_file_path'] = $this->storeUpload($request, 'signed_file', 'proposals');
        unset($data['signed_file']);

        $proposal = Proposal::create($data);
        $this->audit('created', $proposal, 'Proposal dibuat');

        return back()->with('status', 'Proposal berhasil dicatat.');
    }

    public function update(Request $request, Proposal $proposal): RedirectResponse
    {
        $old  = $proposal->toArray();
        $data = $request->validate($this->rules());
        $data['signed_file_path'] = $this->storeUpload($request, 'signed_file', 'proposals') ?? $proposal->signed_file_path;
        unset($data['signed_file']);

        $proposal->update($data);
        $this->audit('updated', $proposal, 'Proposal diedit', $old, $proposal->fresh()->toArray());

        return back()->with('status', 'Proposal berhasil diupdate.');
    }

    public function updateStatus(Request $request, Proposal $proposal): RedirectResponse
    {
        $data = $request->validate(['status' => ['required', Rule::in(['draft', 'sent', 'approved', 'rejected'])]]);

        if (in_array($data['status'], ['approved', 'rejected'], true) && ! in_array(auth()->user()->role, ['admin', 'sales'], true)) {
            abort(403, 'Hanya sales/admin yang bisa approve proposal.');
        }

        $old = $proposal->toArray();
        $proposal->update($data);
        $this->audit('status_updated', $proposal, 'Status proposal menjadi ' . $proposal->status, $old, $proposal->fresh()->toArray());

        return back()->with('status', 'Status proposal berhasil diupdate.');
    }

    public function destroy(Proposal $proposal): RedirectResponse
    {
        $this->audit('deleted', $proposal, 'Proposal dihapus', $proposal->toArray());
        $proposal->delete();

        return back()->with('status', 'Proposal berhasil dihapus.');
    }

    public function pdf(Proposal $proposal): View
    {
        $proposal->load('project.clientRecord');
        $companySetting = \App\Models\CompanySetting::with('defaultBankAccount')->firstOrCreate(['id' => 1], ['company_name' => 'NEXORA']);

        return view('erp.sales.print', compact('proposal', 'companySetting'));
    }

    private function rules(): array
    {
        return [
            'project_id'  => ['required', 'exists:projects,id'],
            'number'      => ['nullable', 'max:100', Rule::unique('proposals', 'number')->ignore(request()->route('proposal'))],
            'title'       => ['required', 'max:255'],
            'status'      => ['required', Rule::in(['draft', 'sent', 'approved', 'rejected'])],
            'amount'      => ['required', 'numeric', 'min:0'],
            'scope'       => ['nullable'],
            'valid_until' => ['nullable', 'date'],
            'signed_file' => ['nullable', 'file', 'max:4096'],
        ];
    }
}
