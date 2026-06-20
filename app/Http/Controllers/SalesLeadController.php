<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\Client;
use App\Models\SalesInquiry;
use App\Models\SalesLead;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SalesLeadController extends Controller
{
    use LoadsErpData;

    public function index(): View
    {
        $leads = SalesLead::latest()->paginate(20);
        return view('erp.sales-leads.index', compact('leads'));
    }

    public function create(): View
    {
        $salesInquiries = SalesInquiry::orderByDesc('id')->get(['id', 'company_name']);
        $clients = Client::orderBy('name')->get(['id', 'name']);
        $users = User::whereIn('role', ['admin', 'sales'])->orderBy('name')->get(['id', 'name']);
        return view('erp.sales-leads.create', compact('salesInquiries', 'clients', 'users'));
    }

    public function edit(SalesLead $lead): View
    {
        $salesInquiries = SalesInquiry::orderByDesc('id')->get(['id', 'company_name']);
        $clients = Client::orderBy('name')->get(['id', 'name']);
        $users = User::whereIn('role', ['admin', 'sales'])->orderBy('name')->get(['id', 'name']);
        return view('erp.sales-leads.edit', compact('lead', 'salesInquiries', 'clients', 'users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $row = SalesLead::create($request->validate([
            'sales_inquiry_id'   => ['nullable', 'exists:sales_inquiries,id'],
            'client_id'          => ['nullable', 'exists:clients,id'],
            'owner_id'           => ['nullable', 'exists:users,id'],
            'title'              => ['required', 'max:255'],
            'stage'              => ['required', Rule::in(['qualified', 'proposal', 'negotiation', 'won', 'lost'])],
            'value'              => ['required', 'numeric', 'min:0'],
            'probability'        => ['required', 'integer', 'min:0', 'max:100'],
            'expected_close_date' => ['nullable', 'date'],
            'notes'              => ['nullable'],
        ]));

        $this->audit('created', $row, 'Sales lead pipeline dibuat');

        return redirect()->route('sales-leads.index')->with('status', 'Lead pipeline berhasil disimpan.');
    }

    public function update(Request $request, SalesLead $lead): RedirectResponse
    {
        $old = $lead->toArray();
        $lead->update($request->validate([
            'sales_inquiry_id'   => ['nullable', 'exists:sales_inquiries,id'],
            'client_id'          => ['nullable', 'exists:clients,id'],
            'owner_id'           => ['nullable', 'exists:users,id'],
            'title'              => ['required', 'max:255'],
            'stage'              => ['required', Rule::in(['qualified', 'proposal', 'negotiation', 'won', 'lost'])],
            'value'              => ['required', 'numeric', 'min:0'],
            'probability'        => ['required', 'integer', 'min:0', 'max:100'],
            'expected_close_date' => ['nullable', 'date'],
            'notes'              => ['nullable'],
        ]));

        $this->audit('updated', $lead, 'Sales lead pipeline diedit', $old, $lead->fresh()->toArray());

        return redirect()->route('sales-leads.index')->with('status', 'Lead pipeline berhasil diupdate.');
    }

    public function destroy(SalesLead $lead): RedirectResponse
    {
        $this->audit('deleted', $lead, 'Sales lead pipeline dihapus', $lead->toArray());
        $lead->delete();

        return redirect()->route('sales-leads.index')->with('status', 'Lead pipeline berhasil dihapus.');
    }
}
