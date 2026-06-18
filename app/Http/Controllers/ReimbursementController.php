<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\Cashflow;
use App\Models\Reimbursement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ReimbursementController extends Controller
{
    use LoadsErpData;

    public function index(): View
    {
        $reimbursementsPage = Reimbursement::with(['employee:id,name', 'project:id,code'])->latest()->paginate(20);
        return view('erp.reimbursements.index', compact('reimbursementsPage'));
    }

    public function create(): View
    {
        $employees = \App\Models\Employee::orderBy('name')->get(['id', 'name']);
        $projects  = \App\Models\Project::orderBy('code')->get(['id', 'code']);
        return view('erp.reimbursements.create', compact('employees', 'projects'));
    }

    public function edit(Reimbursement $reimbursement): View
    {
        $employees = \App\Models\Employee::orderBy('name')->get(['id', 'name']);
        $projects  = \App\Models\Project::orderBy('code')->get(['id', 'code']);
        return view('erp.reimbursements.edit', compact('reimbursement', 'employees', 'projects'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->rules());
        $data['receipt_file_path'] = $this->storeUpload($request, 'receipt_file', 'reimbursements');
        unset($data['receipt_file']);

        $reimbursement = Reimbursement::create($data);

        if ($reimbursement->status === 'paid') {
            $this->ensureReimbursementCashflow($reimbursement);
        }

        $this->audit('created', $reimbursement, 'Reimbursement dibuat');

        return back()->with('status', 'Reimbursement berhasil dicatat.');
    }

    public function update(Request $request, Reimbursement $reimbursement): RedirectResponse
    {
        $old  = $reimbursement->toArray();
        $data = $request->validate($this->rules());
        $data['receipt_file_path'] = $this->storeUpload($request, 'receipt_file', 'reimbursements') ?? $reimbursement->receipt_file_path;
        unset($data['receipt_file']);

        $reimbursement->update($data);

        if ($reimbursement->status === 'paid') {
            $this->ensureReimbursementCashflow($reimbursement);
        }

        $this->audit('updated', $reimbursement, 'Reimbursement diedit', $old, $reimbursement->fresh()->toArray());

        return back()->with('status', 'Reimbursement berhasil diupdate.');
    }

    public function updateStatus(Request $request, Reimbursement $reimbursement): RedirectResponse
    {
        $data = $request->validate(['status' => ['required', Rule::in(['pending', 'approved', 'paid', 'rejected'])]]);

        if (in_array($data['status'], ['approved', 'paid'], true) && ! in_array(auth()->user()->role, ['admin', 'finance'], true)) {
            abort(403, 'Hanya finance/admin yang bisa approve/paid reimbursement.');
        }

        $old = $reimbursement->toArray();
        $reimbursement->update($data);

        if ($reimbursement->status === 'paid') {
            $this->ensureReimbursementCashflow($reimbursement);
        }

        $this->audit('status_updated', $reimbursement, 'Status reimbursement menjadi ' . $reimbursement->status, $old, $reimbursement->fresh()->toArray());

        return back()->with('status', 'Status reimbursement berhasil diupdate.');
    }

    public function destroy(Reimbursement $reimbursement): RedirectResponse
    {
        $this->audit('deleted', $reimbursement, 'Reimbursement dihapus', $reimbursement->toArray());
        $reimbursement->delete();

        return back()->with('status', 'Reimbursement berhasil dihapus.');
    }

    private function ensureReimbursementCashflow(Reimbursement $reimbursement): void
    {
        if ($reimbursement->cashflow_id) {
            return;
        }

        $reimbursement->load('employee');
        $cashflow = Cashflow::create([
            'project_id'       => $reimbursement->project_id,
            'type'             => 'expense',
            'category'         => 'Reimbursement',
            'cost_type'        => 'reimbursement',
            'description'      => $reimbursement->category . ' - ' . $reimbursement->employee->name,
            'amount'           => $reimbursement->amount,
            'transaction_date' => $reimbursement->expense_date,
        ]);
        $reimbursement->update(['cashflow_id' => $cashflow->id]);
    }

    private function rules(): array
    {
        return [
            'employee_id'  => ['required', 'exists:employees,id'],
            'project_id'   => ['nullable', 'exists:projects,id'],
            'category'     => ['required', 'max:100'],
            'description'  => ['nullable'],
            'amount'       => ['required', 'numeric', 'min:0'],
            'status'       => ['required', Rule::in(['pending', 'approved', 'paid', 'rejected'])],
            'expense_date' => ['required', 'date'],
            'receipt_file' => ['nullable', 'file', 'max:4096'],
        ];
    }
}
