@extends('layouts.erp', ['activePage' => 'trash', 'pageTitle' => 'Trash Restore'])

@section('content')
<section class="card section wide" id="trash">
    <div class="section-head">
        <h2>Trash / Restore</h2>
        <span class="badge">Soft-deleted records</span>
    </div>
    <form method="get" action="{{ route('trash.index') }}" class="mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <select name="type" class="rounded-xl border border-[#d7e3ef] bg-white/80 px-4 py-2.5 text-sm font-semibold text-slate-700 focus:border-[#7ec8f8] focus:ring-2 focus:ring-[#7ec8f8]/20">
            <option value="">Semua Tipe</option>
            <option value="projects" @selected(request('type')==='projects')>Projects</option>
            <option value="proposals" @selected(request('type')==='proposals')>Proposals</option>
            <option value="invoices" @selected(request('type')==='invoices')>Invoices</option>
            <option value="users" @selected(request('type')==='users')>Users</option>
        </select>
        <div class="flex gap-2 items-end">
            <button class="rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-bold text-white hover:bg-[#003d78]">Filter</button>
            <a href="{{ route('trash.index') }}" class="rounded-xl border border-[#d7e3ef] bg-white px-5 py-2.5 text-sm font-bold text-[#0059A7] hover:bg-[#f3f8fc]">Reset</a>
        </div>
    </form>
    <table>
        <thead><tr><th>Tipe</th><th>Nama / ID</th><th>Aksi</th></tr></thead>
        <tbody>
            @if($hasTrash)
            @foreach($trash as $type => $items)
                @foreach($items as $item)
                    <tr>
                        <td><span class="badge">{{ $type }}</span></td>
                        <td class="font-bold">{{ $item->name ?? $item->title ?? $item->number ?? $item->code }}</td>
                        <td>
                            <form method="post" action="{{ route('trash.restore', [$type, $item->id]) }}">
                                @csrf @method('patch')
                                <button class="mini ghost">Restore</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            @endforeach
            @else
                <tr><td colspan="3" class="py-8 text-center text-slate-500">Trash kosong.</td></tr>
            @endif
        </tbody>
    </table>
    @foreach($trash as $type => $items)
        @if($items->total() > 0)
            <div class="pager">
                <span>Menampilkan {{ $items->firstItem() }}-{{ $items->lastItem() }} dari {{ $items->total() }} {{ $type }}</span>
                {{ $items->links() }}
            </div>
        @endif
    @endforeach
</section>
@endsection
