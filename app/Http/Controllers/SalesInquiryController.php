<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\SalesInquiry;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SalesInquiryController extends Controller
{
    use LoadsErpData;

    public function index(): View
    {
        $inquiries = SalesInquiry::latest()->paginate(20);
        return view('erp.sales-inquiries.index', compact('inquiries'));
    }

    public function create(): View
    {
        $users = User::whereIn('role', ['admin', 'sales'])->orderBy('name')->get(['id', 'name']);
        return view('erp.sales-inquiries.create', compact('users'));
    }

    public function edit(SalesInquiry $inquiry): View
    {
        $users = User::whereIn('role', ['admin', 'sales'])->orderBy('name')->get(['id', 'name']);
        return view('erp.sales-inquiries.edit', compact('inquiry', 'users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $row = SalesInquiry::create($request->validate([
            'company_name' => ['required', 'max:255'],
            'contact_name' => ['nullable', 'max:255'],
            'email'        => ['nullable', 'email', 'max:255'],
            'phone'        => ['nullable', 'max:100'],
            'source'       => ['nullable', 'max:100'],
            'need'         => ['nullable', 'max:255'],
            'status'       => ['required', Rule::in(['new', 'contacted', 'qualified', 'lost'])],
            'owner_id'     => ['nullable', 'exists:users,id'],
            'notes'        => ['nullable'],
        ]));

        $this->audit('created', $row, 'Sales inquiry dibuat');

        return redirect()->route('sales-inquiries.index')->with('status', 'Inquiry sales berhasil ditambahkan.');
    }

    public function update(Request $request, SalesInquiry $inquiry): RedirectResponse
    {
        $old = $inquiry->toArray();
        $inquiry->update($request->validate([
            'company_name' => ['required', 'max:255'],
            'contact_name' => ['nullable', 'max:255'],
            'email'        => ['nullable', 'email', 'max:255'],
            'phone'        => ['nullable', 'max:100'],
            'source'       => ['nullable', 'max:100'],
            'need'         => ['nullable', 'max:255'],
            'status'       => ['required', Rule::in(['new', 'contacted', 'qualified', 'lost'])],
            'owner_id'     => ['nullable', 'exists:users,id'],
            'notes'        => ['nullable'],
        ]));

        $this->audit('updated', $inquiry, 'Sales inquiry diedit', $old, $inquiry->fresh()->toArray());

        return redirect()->route('sales-inquiries.index')->with('status', 'Inquiry sales berhasil diupdate.');
    }

    public function destroy(SalesInquiry $inquiry): RedirectResponse
    {
        $this->audit('deleted', $inquiry, 'Sales inquiry dihapus', $inquiry->toArray());
        $inquiry->delete();

        return redirect()->route('sales-inquiries.index')->with('status', 'Inquiry sales berhasil dihapus.');
    }
}
