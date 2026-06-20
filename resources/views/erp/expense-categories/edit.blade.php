@extends('layouts.erp', ['activePage' => 'expense-categories-edit-page', 'pageTitle' => 'Edit Expense Category'])

@section('content')
<section class="section" id="expense-category-edit">
    <div class="card">
        <h2>Edit Expense Category: {{ $expenseCategory->name }}</h2>
        <form method="post" action="{{ route('expense-categories.update', $expenseCategory) }}" class="grid">
            @csrf
            @method('put')
            <input name="name" value="{{ $expenseCategory->name }}" placeholder="Nama kategori" required>
            <input name="type" value="{{ $expenseCategory->type }}" placeholder="Tipe (cloud/tools/vendor)" required>
            <button>Update Category</button>
        </form>
        <a href="{{ route('expense-categories.index') }}" class="mt-4 inline-block text-sm text-slate-500">&larr; Kembali</a>
    </div>
</section>
@endsection
