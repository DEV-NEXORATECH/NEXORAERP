<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\CompanySetting;
use App\Models\Invoice;
use App\Models\Proposal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    use LoadsErpData;

    public function index(): View
    {
        $invoicesPage = Invoice::with(['project:id,code'])->latest()->paginate(20);
        $payments     = \App\Models\Payment::with(['invoice:id,number', 'bankAccount:id,name'])->latest()->paginate(20);
        return view('erp.invoices.index', compact('invoicesPage', 'payments'));
    }

    public function create(): View
    {
        $projects  = \App\Models\Project::orderBy('code')->get(['id', 'code']);
        $proposals = Proposal::whereIn('status', ['approved', 'sent'])->get(['id', 'title', 'status']);
        return view('erp.invoices.create', compact('projects', 'proposals'));
    }

    public function edit(Invoice $invoice): View
    {
        $projects  = \App\Models\Project::orderBy('code')->get(['id', 'code']);
        $proposals = Proposal::whereIn('status', ['approved', 'sent'])->get(['id', 'title', 'status']);
        return view('erp.invoices.edit', compact('invoice', 'projects', 'proposals'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data           = $request->validate($this->rules());
        $data['number'] = $data['number'] ?: $this->nextNumber('INV-NX', Invoice::withTrashed()->count() + 1);

        if (! empty($data['proposal_id'])) {
            $proposal = Proposal::findOrFail($data['proposal_id']);
            if ($proposal->status !== 'approved') {
                return back()->withErrors(['proposal_id' => 'Invoice hanya bisa dibuat dari proposal approved.'])->withInput();
            }
        }

        $invoice = Invoice::create($data);
        $this->audit('created', $invoice, 'Invoice dibuat');

        return back()->with('status', 'Invoice berhasil dibuat.');
    }

    public function update(Request $request, Invoice $invoice): RedirectResponse
    {
        $old = $invoice->toArray();
        $invoice->update($request->validate($this->rules($invoice)));
        $this->audit('updated', $invoice, 'Invoice diedit', $old, $invoice->fresh()->toArray());

        return back()->with('status', 'Invoice berhasil diupdate.');
    }

    public function destroy(Invoice $invoice): RedirectResponse
    {
        $this->audit('deleted', $invoice, 'Invoice dihapus', $invoice->toArray());
        $invoice->delete();

        return back()->with('status', 'Invoice berhasil dihapus.');
    }

    public function pdf(Invoice $invoice): View
    {
        $invoice->load(['project.clientRecord', 'payments.bankAccount']);
        $companySetting = CompanySetting::with('defaultBankAccount')->firstOrCreate(['id' => 1], ['company_name' => 'NEXORA']);

        return view('erp.invoices.print', compact('invoice', 'companySetting'));
    }

    private function rules(?Invoice $invoice = null): array
    {
        return [
            'project_id'    => ['required', 'exists:projects,id'],
            'proposal_id'   => ['nullable', 'exists:proposals,id'],
            'number'        => ['nullable', 'max:100', Rule::unique('invoices', 'number')->ignore($invoice)],
            'status'        => ['required', Rule::in(['draft', 'sent', 'partial', 'paid', 'void'])],
            'issue_date'    => ['required', 'date'],
            'due_date'      => ['nullable', 'date', 'after_or_equal:issue_date'],
            'amount'        => ['required', 'numeric', 'min:0'],
            'tax_rate'      => ['nullable', 'numeric', 'min:0', 'max:100'],
            'notes'         => ['nullable'],
            'payment_terms' => ['nullable'],
        ];
    }
}
