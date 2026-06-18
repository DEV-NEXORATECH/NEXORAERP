@extends('layouts.erp', ['activePage' => 'trash', 'pageTitle' => 'Trash Restore'])

@section('content')
<section class="card section wide" id="trash">
    <div class="section-head">
        <h2>Trash / Restore</h2>
        <span class="badge">Soft-deleted records</span>
    </div>
    <table>
        <thead><tr><th>Tipe</th><th>Nama / ID</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($trash as $type => $items)
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
            @empty
                <tr><td colspan="3" class="py-8 text-center text-slate-500">Trash kosong.</td></tr>
            @endforelse
        </tbody>
    </table>
</section>
@endsection
