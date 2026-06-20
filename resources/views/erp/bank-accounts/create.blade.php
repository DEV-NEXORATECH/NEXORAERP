@extends('layouts.erp', ['activePage' => 'bank-accounts-create-page', 'pageTitle' => 'Tambah Bank Account'])

@section('content')
<section class="section" id="bank-account-create">
    <div class="card">
        <h2>Tambah Bank Account</h2>
        <form method="post" action="{{ route('bank-accounts.store') }}" class="grid">
            @csrf
            <input name="name" placeholder="Nama kas/bank" required>
            <input name="bank_name" placeholder="Bank">
            <input name="account_number" placeholder="No rekening">
            <input name="opening_balance" type="number" value="0">
            <button>Tambah Bank</button>
        </form>
        <a href="{{ route('bank-accounts.index') }}" class="mt-4 inline-block text-sm text-slate-500">&larr; Kembali</a>
    </div>
</section>
@endsection
