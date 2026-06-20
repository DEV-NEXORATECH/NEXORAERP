<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\Vendor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class VendorController extends Controller
{
    use LoadsErpData;

    public function index(): View
    {
        $vendors = Vendor::latest()->paginate(20);
        return view('erp.vendors.index', compact('vendors'));
    }

    public function create(): View
    {
        return view('erp.vendors.create');
    }

    public function edit(Vendor $vendor): View
    {
        return view('erp.vendors.edit', compact('vendor'));
    }

    public function store(Request $request): RedirectResponse
    {
        $row = Vendor::create($request->validate([
            'name'          => ['required', 'max:255'],
            'category'      => ['nullable', 'max:100'],
            'contact_name'  => ['nullable', 'max:255'],
            'email'         => ['nullable', 'email', 'max:255'],
            'phone'         => ['nullable', 'max:100'],
            'payment_terms' => ['nullable', 'max:100'],
            'status'        => ['required', Rule::in(['active', 'inactive', 'blacklisted'])],
        ]));
        $this->audit('created', $row, 'Vendor dibuat');

        return redirect()->route('vendors.index')->with('status', 'Vendor berhasil ditambahkan.');
    }

    public function update(Request $request, Vendor $vendor): RedirectResponse
    {
        $old = $vendor->toArray();
        $vendor->update($request->validate([
            'name'          => ['required', 'max:255'],
            'category'      => ['nullable', 'max:100'],
            'contact_name'  => ['nullable', 'max:255'],
            'email'         => ['nullable', 'email', 'max:255'],
            'phone'         => ['nullable', 'max:100'],
            'payment_terms' => ['nullable', 'max:100'],
            'status'        => ['required', Rule::in(['active', 'inactive', 'blacklisted'])],
        ]));
        $this->audit('updated', $vendor, 'Vendor diedit', $old, $vendor->fresh()->toArray());

        return redirect()->route('vendors.index')->with('status', 'Vendor berhasil diupdate.');
    }

    public function destroy(Vendor $vendor): RedirectResponse
    {
        $this->audit('deleted', $vendor, 'Vendor dihapus', $vendor->toArray());
        $vendor->delete();

        return redirect()->route('vendors.index')->with('status', 'Vendor berhasil dihapus.');
    }
}
