<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\Client;
use App\Models\ClientContract;
use App\Models\Proposal;
use App\Models\SalesCommission;
use App\Models\SalesInquiry;
use App\Models\SalesLead;
use App\Models\SalesOrder;
use App\Models\SalesTarget;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SalesCrmController extends Controller
{
    use LoadsErpData;

    public function index(): View
    {
        return view('erp.sales-crm.index', [
            'inquiries' => SalesInquiry::latest()->paginate(8, ['*'], 'inquiry_page')->withQueryString(),
            'leads' => SalesLead::latest()->paginate(8, ['*'], 'lead_page')->withQueryString(),
            'orders' => SalesOrder::latest()->paginate(8, ['*'], 'order_page')->withQueryString(),
            'targets' => SalesTarget::latest()->paginate(8, ['*'], 'target_page')->withQueryString(),
            'commissions' => SalesCommission::latest()->paginate(8, ['*'], 'commission_page')->withQueryString(),
            'contracts' => ClientContract::latest()->paginate(8, ['*'], 'contract_page')->withQueryString(),
            'clients' => Client::orderBy('name')->get(['id', 'name']),
            'proposals' => Proposal::orderByDesc('id')->get(['id', 'number', 'title']),
            'users' => User::whereIn('role', ['admin', 'sales'])->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function storeInquiry(Request $request): RedirectResponse
    {
        $row = SalesInquiry::create($request->validate([
            'company_name' => ['required', 'max:255'],
            'contact_name' => ['nullable', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'max:100'],
            'source' => ['nullable', 'max:100'],
            'need' => ['nullable', 'max:255'],
            'status' => ['required', Rule::in(['new', 'contacted', 'qualified', 'lost'])],
            'owner_id' => ['nullable', 'exists:users,id'],
            'notes' => ['nullable'],
        ]));

        $this->audit('created', $row, 'Sales inquiry dibuat');

        return back()->with('status', 'Inquiry sales berhasil ditambahkan.');
    }

    public function storeLead(Request $request): RedirectResponse
    {
        $row = SalesLead::create($request->validate([
            'sales_inquiry_id' => ['nullable', 'exists:sales_inquiries,id'],
            'client_id' => ['nullable', 'exists:clients,id'],
            'owner_id' => ['nullable', 'exists:users,id'],
            'title' => ['required', 'max:255'],
            'stage' => ['required', Rule::in(['qualified', 'proposal', 'negotiation', 'won', 'lost'])],
            'value' => ['required', 'numeric', 'min:0'],
            'probability' => ['required', 'integer', 'min:0', 'max:100'],
            'expected_close_date' => ['nullable', 'date'],
            'notes' => ['nullable'],
        ]));

        $this->audit('created', $row, 'Sales lead pipeline dibuat');

        return back()->with('status', 'Lead pipeline berhasil disimpan.');
    }

    public function storeOrder(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'proposal_id' => ['nullable', 'exists:proposals,id'],
            'client_id' => ['nullable', 'exists:clients,id'],
            'title' => ['required', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'order_date' => ['required', 'date'],
            'status' => ['required', Rule::in(['draft', 'confirmed', 'delivered', 'cancelled'])],
            'notes' => ['nullable'],
        ]);

        $row = SalesOrder::create($data + ['number' => $this->nextNumber('SO-NX', SalesOrder::count() + 1)]);
        $this->audit('created', $row, 'Sales order dibuat');

        return back()->with('status', 'Sales order berhasil dibuat.');
    }

    public function storeTarget(Request $request): RedirectResponse
    {
        $row = SalesTarget::create($request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'period' => ['required', 'max:20'],
            'target_amount' => ['required', 'numeric', 'min:0'],
            'achieved_amount' => ['nullable', 'numeric', 'min:0'],
        ]) + ['achieved_amount' => $request->input('achieved_amount', 0)]);

        $this->audit('created', $row, 'Sales target dibuat');

        return back()->with('status', 'Sales target berhasil disimpan.');
    }

    public function storeCommission(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'period' => ['required', 'max:20'],
            'base_amount' => ['required', 'numeric', 'min:0'],
            'rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'status' => ['required', Rule::in(['draft', 'approved', 'paid'])],
        ]);
        $data['commission_amount'] = ((float) $data['base_amount']) * ((float) $data['rate'] / 100);

        $row = SalesCommission::create($data);
        $this->audit('created', $row, 'Sales commission dibuat');

        return back()->with('status', 'Komisi sales berhasil dihitung.');
    }

    public function storeContract(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'client_id' => ['nullable', 'exists:clients,id'],
            'title' => ['required', 'max:255'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'reminder_date' => ['nullable', 'date'],
            'amount' => ['required', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(['draft', 'active', 'expired', 'terminated'])],
            'notes' => ['nullable'],
        ]);

        $row = ClientContract::create($data + ['contract_number' => $this->nextNumber('CTR-NX', ClientContract::count() + 1)]);
        $this->audit('created', $row, 'Client contract dibuat');

        return back()->with('status', 'Contract lifecycle berhasil dicatat.');
    }
}
