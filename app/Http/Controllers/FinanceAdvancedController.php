<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\BankAccount;
use App\Models\BankReconciliationItem;
use App\Models\Cashflow;
use App\Models\CurrencyRate;
use App\Models\CurrencyVariance;
use App\Models\FixedAsset;
use App\Models\GoodsReceipt;
use App\Models\Invoice;
use App\Models\PurchaseMatch;
use App\Models\PurchaseOrder;
use App\Models\RevenueSchedule;
use App\Models\VendorBill;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FinanceAdvancedController extends Controller
{
    use LoadsErpData;

    public function index(): View
    {
        return view('erp.finance-advanced.index', [
            'assets' => FixedAsset::latest()->paginate(8, ['*'], 'asset_page')->withQueryString(),
            'rates' => CurrencyRate::latest('rate_date')->paginate(8, ['*'], 'rate_page')->withQueryString(),
            'variances' => CurrencyVariance::latest()->paginate(8, ['*'], 'variance_page')->withQueryString(),
            'schedules' => RevenueSchedule::latest()->paginate(8, ['*'], 'revenue_page')->withQueryString(),
            'reconciliations' => BankReconciliationItem::latest()->paginate(8, ['*'], 'recon_page')->withQueryString(),
            'matches' => PurchaseMatch::latest()->paginate(8, ['*'], 'match_page')->withQueryString(),
            'invoices' => Invoice::orderByDesc('id')->get(['id', 'number']),
            'banks' => BankAccount::orderBy('name')->get(['id', 'name']),
            'cashflows' => Cashflow::orderByDesc('id')->get(['id', 'description', 'amount']),
            'purchaseOrders' => PurchaseOrder::orderByDesc('id')->get(['id', 'number']),
            'receipts' => GoodsReceipt::orderByDesc('id')->get(['id']),
            'vendorBills' => VendorBill::orderByDesc('id')->get(['id', 'bill_number', 'vendor_name']),
        ]);
    }

    public function storeAsset(Request $request): RedirectResponse
    {
        $row = FixedAsset::create($request->validate([
            'name' => ['required', 'max:255'],
            'category' => ['nullable', 'max:100'],
            'acquisition_date' => ['nullable', 'date'],
            'acquisition_cost' => ['required', 'numeric', 'min:0'],
            'useful_life_months' => ['required', 'integer', 'min:1'],
            'accumulated_depreciation' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(['active', 'disposed', 'maintenance'])],
        ]) + ['accumulated_depreciation' => $request->input('accumulated_depreciation', 0)]);
        $this->audit('created', $row, 'Fixed asset dibuat');

        return back()->with('status', 'Fixed asset berhasil dicatat.');
    }

    public function storeRate(Request $request): RedirectResponse
    {
        $row = CurrencyRate::updateOrCreate(
            $request->validate([
                'currency' => ['required', 'size:3'],
                'rate_date' => ['required', 'date'],
            ]),
            $request->validate(['rate_to_idr' => ['required', 'numeric', 'min:0']])
        );
        $this->audit('updated', $row, 'Currency rate disimpan');

        return back()->with('status', 'Currency rate berhasil disimpan.');
    }

    public function storeVariance(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'invoice_id' => ['nullable', 'exists:invoices,id'],
            'currency' => ['required', 'size:3'],
            'foreign_amount' => ['required', 'numeric', 'min:0'],
            'invoice_rate' => ['required', 'numeric', 'min:0'],
            'payment_rate' => ['required', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(['draft', 'posted'])],
        ]);
        $data['variance_amount'] = ((float) $data['payment_rate'] - (float) $data['invoice_rate']) * (float) $data['foreign_amount'];

        $row = CurrencyVariance::create($data);
        $this->audit('created', $row, 'Currency variance dibuat');

        return back()->with('status', 'Currency variance berhasil dihitung.');
    }

    public function storeRevenue(Request $request): RedirectResponse
    {
        $row = RevenueSchedule::create($request->validate([
            'invoice_id' => ['nullable', 'exists:invoices,id'],
            'description' => ['required', 'max:255'],
            'recognition_month' => ['required', 'max:20'],
            'amount' => ['required', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(['scheduled', 'recognized', 'deferred'])],
        ]));
        $this->audit('created', $row, 'Revenue schedule dibuat');

        return back()->with('status', 'Revenue recognition schedule berhasil dibuat.');
    }

    public function storeReconciliation(Request $request): RedirectResponse
    {
        $row = BankReconciliationItem::create($request->validate([
            'bank_account_id' => ['nullable', 'exists:bank_accounts,id'],
            'cashflow_id' => ['nullable', 'exists:cashflows,id'],
            'statement_date' => ['required', 'date'],
            'statement_reference' => ['nullable', 'max:100'],
            'statement_amount' => ['required', 'numeric'],
            'match_status' => ['required', Rule::in(['unmatched', 'matched', 'variance'])],
        ]));
        $this->audit('created', $row, 'Bank reconciliation item dibuat');

        return back()->with('status', 'Bank reconciliation item berhasil dicatat.');
    }

    public function storeMatch(Request $request): RedirectResponse
    {
        $row = PurchaseMatch::create($request->validate([
            'purchase_order_id' => ['nullable', 'exists:purchase_orders,id'],
            'goods_receipt_id' => ['nullable', 'exists:goods_receipts,id'],
            'vendor_bill_id' => ['nullable', 'exists:vendor_bills,id'],
            'variance_amount' => ['nullable', 'numeric'],
            'status' => ['required', Rule::in(['matched', 'variance', 'blocked'])],
            'notes' => ['nullable'],
        ]) + ['variance_amount' => $request->input('variance_amount', 0)]);
        $this->audit('created', $row, 'Three-way matching dibuat');

        return back()->with('status', 'Three-way matching berhasil disimpan.');
    }
}
