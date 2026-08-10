@extends('layouts.erp', ['activePage' => 'product-categories', 'pageTitle' => 'Edit Kategori Produk'])

@section('content')
<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5" id="product-category-edit">
    <div class="section-head">
        <h2>Edit Kategori Produk: {{ $category->code }}</h2>
    </div>

    <form method="post" action="{{ route('product-categories.update', $category) }}" class="grid max-w-2xl gap-4">
        @csrf
        @method('put')
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Kode
                <input name="code" placeholder="CAT-001" required value="{{ old('code', $category->code) }}">
            </label>
            <label class="grid gap-1 text-sm font-semibold text-slate-700">
                Parent
                <select name="parent_id">
                    <option value="">-</option>
                    @foreach($parents as $parent)
                        <option value="{{ $parent->id }}" @selected((int) old('parent_id', $category->parent_id) === $parent->id)>{{ $parent->name }}</option>
                    @endforeach
                </select>
            </label>
        </div>
        <label class="grid gap-1 text-sm font-semibold text-slate-700">
            Nama
            <input name="name" placeholder="Nama kategori" required value="{{ old('name', $category->name) }}">
        </label>
        <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
            <input type="checkbox" name="is_active" value="1" {{ $category->is_active ? 'checked' : '' }} class="h-4 w-4">
            Aktif
        </label>
        <div class="flex gap-3">
            <button>Simpan Perubahan</button>
            <a href="{{ route('product-categories.index') }}" class="button ghost">Batal</a>
        </div>
    </form>
</section>
@endsection
