@extends('layouts.erp', ['activePage' => 'expense-categories', 'pageTitle' => 'Expense Categories'])

@section('content')
<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5" id="expense-categories">
    <div class="section-head">
        <h2>Daftar Expense Category</h2>
        @if($can('admin', 'finance') || $can('admin'))
        <a href="{{ route('expense-categories.create-page') }}" class="button ghost">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>

    <form method="get" action="{{ route('expense-categories.index') }}" class="mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 placeholder:text-slate-400 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
        <select name="type" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
            <option value="">Semua Tipe</option>
            <option value="income" @selected(request('type') == 'income')>Income</option>
            <option value="expense" @selected(request('type') == 'expense')>Expense</option>
        </select>
        <div class="flex gap-2 items-end">
            <button class="rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-bold text-white hover:bg-[#003d78]">Filter</button>
            <a href="{{ route('expense-categories.index') }}" class="rounded-xl border border-[#d7e3ef] bg-white px-5 py-2.5 text-sm font-bold text-[#0059A7] hover:bg-[#f3f8fc]">Reset</a>
        </div>
    </form>

    <table>
        <thead><tr><th>Nama</th><th>Tipe</th><th>Aksi</th></tr></thead>
        <tbody>
            @foreach($expenseCategories as $category)
            <tr>
                <td class="font-bold">{{ $category->name }}</td>
                <td><span class="badge">{{ $category->type }}</span></td>
                <td class="actions">
                    <a href="{{ route('expense-categories.edit-page', $category) }}" class="mini ghost">Edit</a>
                    <form method="post" action="{{ route('expense-categories.destroy', $category) }}" class="inline">@csrf @method('delete')<button class="mini danger">Delete</button></form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($expenseCategories->hasPages())
    <div class="pager">
        <span>Menampilkan {{ $expenseCategories->firstItem() }}-{{ $expenseCategories->lastItem() }} dari {{ $expenseCategories->total() }}</span>
        {{ $expenseCategories->links() }}
    </div>
    @endif
</section>
@endsection
