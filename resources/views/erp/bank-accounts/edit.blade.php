@extends('layouts.erp', ['activePage' => 'bank-accounts-edit-page', 'pageTitle' => 'Edit Bank Account'])

@section('content')
<section class="section" id="bank-account-edit">
    <div class="card">
        <h2>Edit Bank Account: {{ $bankAccount->name }}</h2>
        <form method="post" action="{{ route('bank-accounts.update', $bankAccount) }}" class="grid">
            @csrf
            @method('put')
            <input name="name" value="{{ $bankAccount->name }}" placeholder="Nama kas/bank" required>
            <input name="bank_name" value="{{ $bankAccount->bank_name }}" placeholder="Bank">
            <input name="account_number" value="{{ $bankAccount->account_number }}" placeholder="No rekening">
            <input name="opening_balance" type="number" value="{{ $bankAccount->opening_balance }}">
            <button>Update Bank</button>
        </form>
        <a href="{{ route('bank-accounts.index') }}" class="mt-4 inline-block text-sm text-slate-500">&larr; Kembali</a>
    </div>
</section>
@endsection
