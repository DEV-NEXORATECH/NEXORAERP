@extends('layouts.erp', ['activePage' => 'expense-categories-create-page', 'pageTitle' => 'Tambah Expense Category'])

@section('content')
<section class="section" id="expense-category-create">
    <div class="card">
        <h2>Tambah Expense Category</h2>
        <form method="post" action="{{ route('expense-categories.store') }}" class="grid">
            @csrf
            <input name="name" placeholder="Nama kategori" required>
            <input name="type" placeholder="Tipe (cloud/tools/vendor)" required>
            <button>Tambah Category</button>
        </form>
        <a href="{{ route('expense-categories.index') }}" class="mt-4 inline-block text-sm text-slate-500">&larr; Kembali</a>
    </div>
</section>
@endsection
