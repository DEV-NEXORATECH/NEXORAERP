@extends('layouts.erp', ['activePage' => 'clients', 'pageTitle' => 'Clients'])

@section('content')
<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#0B2345]/5" id="clients">
    <div class="section-head">
        <h2>Daftar Client</h2>
        @if($can('admin', 'finance') || $can('admin'))
        <a href="{{ route('clients.create-page') }}" class="button ghost">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M12 5v14M5 12h14"/></svg>
            Tambah
        </a>
        @endif
    </div>

    <form method="get" action="{{ route('clients.index') }}" class="mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}" class="field-input">
        <div class="flex gap-2 items-end">
            <button class="filter-btn">Filter</button>
            <a href="{{ route('clients.index') }}" class="btn-outline">Reset</a>
        </div>
    </form>

    <table>
        <thead><tr><th>Nama</th><th>Kontak</th><th>Aksi</th></tr></thead>
        <tbody>
            @foreach($clients as $client)
            <tr>
                <td>
                    <div class="font-bold">{{ $client->name }}</div>
                    <div class="muted">{{ $client->address }}</div>
                </td>
                <td>
                    <div>{{ $client->contact_name }}</div>
                    <div class="muted">{{ $client->email }} / {{ $client->phone }}</div>
                </td>
                <td class="actions">
                    <a href="{{ route('clients.edit-page', $client) }}" class="mini ghost">Edit</a>
                    <form method="post" action="{{ route('clients.destroy', $client) }}" class="inline">@csrf @method('delete')<button class="mini danger">Delete</button></form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($clients->hasPages())
    <div class="pager">
        <span>Menampilkan {{ $clients->firstItem() }}-{{ $clients->lastItem() }} dari {{ $clients->total() }}</span>
        {{ $clients->links() }}
    </div>
    @endif
</section>
@endsection
