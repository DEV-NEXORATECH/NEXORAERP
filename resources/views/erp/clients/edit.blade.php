@extends('layouts.erp', ['activePage' => 'clients-edit-page', 'pageTitle' => 'Edit Client'])

@section('content')
<section class="section" id="client-edit">
    <div class="card">
        <h2>Edit Client: {{ $client->name }}</h2>
        <form method="post" action="{{ route('clients.update', $client) }}" class="grid">
            @csrf
            @method('put')
            <input name="name" value="{{ $client->name }}" placeholder="Nama client" required>
            <input name="contact_name" value="{{ $client->contact_name }}" placeholder="PIC">
            <input name="email" type="email" value="{{ $client->email }}" placeholder="Email">
            <input name="phone" value="{{ $client->phone }}" placeholder="Phone">
            <textarea name="address" placeholder="Alamat">{{ $client->address }}</textarea>
            <button>Update Client</button>
        </form>
        <a href="{{ route('clients.index') }}" class="mt-4 inline-block text-sm text-slate-500">&larr; Kembali</a>
    </div>
</section>
@endsection
