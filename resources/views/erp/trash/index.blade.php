@extends('layouts.erp', ['activePage' => 'trash', 'pageTitle' => 'Trash Restore'])

@section('content')
<section class="card section wide" id="trash">
    <div class="section-head">
        <h2>Trash / Restore</h2>
        <span class="badge">Soft-deleted records</span>
    </div>
    <form method="get" action="{{ route('trash.index') }}" class="mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <select name="type" class="field-input">
            <option value="">Semua Tipe</option>
            <option value="projects" @selected(request('type')==='projects')>Projects</option>
            <option value="proposals" @selected(request('type')==='proposals')>Proposals</option>
            <option value="invoices" @selected(request('type')==='invoices')>Invoices</option>
            <option value="users" @selected(request('type')==='users')>Users</option>
        </select>
        <div class="flex gap-2 items-end">
            <button class="filter-btn">Filter</button>
            <a href="{{ route('trash.index') }}" class="btn-outline">Reset</a>
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
