@extends('layouts.erp', ['activePage' => 'vendor-bill-edit', 'pageTitle' => 'Edit Vendor Bill'])

@section('content')
<div class="max-w-4xl mx-auto space-y-4">
    <!-- Header -->
    <div class="flex items-center justify-between bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-gray-900 tracking-tight">Edit Vendor Bill</h1>
            <p class="text-xs text-gray-500">Lengkapi formulir di bawah ini dengan data yang valid</p>
        </div>
        <a href="{{ route('vendor-bills.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
        <form method="post" action="{{ route('vendor-bills.update', $vendorBill) }}" class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2" class="space-y-4">
            
            @csrf @method('put')
            <div class="grid gap-1.5"><label>Vendor</label><input name="vendor_name" value="{{ $vendorBill->vendor_name }}" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900"></div>
            <div class="grid gap-1.5"><label>Bill Number</label><input name="bill_number" value="{{ $vendorBill->bill_number }}" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900"></div>
            <div class="grid gap-1.5"><label>Project</label><select name="project_id" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900"><option value="">Company</option>@foreach($projects as $project)<option value="{{ $project->id }}" @selected($vendorBill->project_id===$project->id)>{{ $project->code }}</option>@endforeach</select></div>
            <div class="grid gap-1.5"><label>Bank/Kas</label><select name="bank_account_id" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900"><option value="">-</option>@foreach($bankAccounts as $bank)<option value="{{ $bank->id }}" @selected($vendorBill->bank_account_id===$bank->id)>{{ $bank->name }}</option>@endforeach</select></div>
            <div class="grid gap-1.5"><label>Bill Date</label><input name="bill_date" type="date" value="{{ $vendorBill->bill_date }}" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900"></div>
            <div class="grid gap-1.5"><label>Due Date</label><input name="due_date" type="date" value="{{ $vendorBill->due_date }}" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900"></div>
            <div class="grid gap-1.5"><label>Amount</label><input name="amount" type="number" min="0" value="{{ $vendorBill->amount }}" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900"></div>
            <div class="grid gap-1.5"><label>Tax %</label><input name="tax_rate" type="number" min="0" value="{{ $vendorBill->tax_rate }}" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900"></div>
            <div class="grid gap-1.5"><label>Status</label><select name="status" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900">@foreach(['unpaid','partial','paid','void'] as $st)<option @selected($vendorBill->status===$st)>{{ $st }}</option>@endforeach</select></div>
            <div class="grid gap-1.5 md:col-span-2"><label>Notes</label><input name="notes" value="{{ $vendorBill->notes }}" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white text-gray-900"></div>
            <div class="mt-4 flex items-center gap-3 md:col-span-2">
                <button class="btn">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Update
                </button>
                <a class="button ghost" href="{{ route('vendor-bills.index') }}">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Kembali
                </a>
            </div>
        
            <div class="pt-4 border-t border-gray-200 flex items-center justify-end gap-2">
                <a href="{{ route('vendor-bills.index') }}" class="px-3.5 py-2 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">Batal</a>
                <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition shadow-sm">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection
