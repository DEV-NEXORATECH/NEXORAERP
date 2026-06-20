<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\Department;
use App\Models\GoodsReceipt;
use App\Models\ProcurementContract;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequisition;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProcurementController extends Controller
{
    use LoadsErpData;

    public function index(): View
    {
        return view('erp.procurement.index', [
            'vendors' => Vendor::latest()->paginate(8, ['*'], 'vendor_page')->withQueryString(),
            'requisitions' => PurchaseRequisition::latest()->paginate(8, ['*'], 'pr_page')->withQueryString(),
            'orders' => PurchaseOrder::latest()->paginate(8, ['*'], 'po_page')->withQueryString(),
            'receipts' => GoodsReceipt::latest()->paginate(8, ['*'], 'receipt_page')->withQueryString(),
            'contracts' => ProcurementContract::latest()->paginate(8, ['*'], 'contract_page')->withQueryString(),
            'allVendors' => Vendor::orderBy('name')->get(['id', 'name']),
            'allRequisitions' => PurchaseRequisition::orderByDesc('id')->get(['id', 'number', 'title']),
            'allOrders' => PurchaseOrder::orderByDesc('id')->get(['id', 'number']),
            'users' => User::orderBy('name')->get(['id', 'name']),
            'departments' => Department::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function storeVendor(Request $request): RedirectResponse
    {
        Vendor::create($request->validate([
            'name' => ['required', 'max:255'],
            'category' => ['nullable', 'max:100'],
            'contact_name' => ['nullable', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'max:100'],
            'payment_terms' => ['nullable', 'max:100'],
            'status' => ['required', Rule::in(['active', 'inactive', 'blacklisted'])],
        ]));

        return back()->with('status', 'Vendor berhasil ditambahkan.');
    }

    public function storeRequisition(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'requester_id' => ['nullable', 'exists:users,id'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'title' => ['required', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'required_date' => ['nullable', 'date'],
            'status' => ['required', Rule::in(['draft', 'submitted', 'approved', 'rejected'])],
            'reason' => ['nullable'],
        ]);

        PurchaseRequisition::create($data + ['number' => $this->nextNumber('PR-NX', PurchaseRequisition::count() + 1)]);

        return back()->with('status', 'Purchase requisition berhasil dibuat.');
    }

    public function storeOrder(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'purchase_requisition_id' => ['nullable', 'exists:purchase_requisitions,id'],
            'vendor_id' => ['nullable', 'exists:vendors,id'],
            'order_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(['ordered', 'partial_received', 'received', 'cancelled'])],
            'notes' => ['nullable'],
        ]);

        PurchaseOrder::create($data + ['number' => $this->nextNumber('PO-NX', PurchaseOrder::count() + 1)]);

        return back()->with('status', 'Purchase order berhasil dibuat.');
    }

    public function storeReceipt(Request $request): RedirectResponse
    {
        GoodsReceipt::create($request->validate([
            'purchase_order_id' => ['nullable', 'exists:purchase_orders,id'],
            'receipt_date' => ['required', 'date'],
            'status' => ['required', Rule::in(['received', 'partial', 'rejected'])],
            'notes' => ['nullable'],
        ]));

        return back()->with('status', 'Receipt verification berhasil dicatat.');
    }

    public function storeContract(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'vendor_id' => ['nullable', 'exists:vendors,id'],
            'title' => ['required', 'max:255'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'renewal_reminder_date' => ['nullable', 'date'],
            'amount' => ['required', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(['draft', 'active', 'expired', 'terminated'])],
        ]);

        ProcurementContract::create($data + ['contract_number' => $this->nextNumber('VCTR-NX', ProcurementContract::count() + 1)]);

        return back()->with('status', 'Procurement contract berhasil dicatat.');
    }
}
