@extends('layouts.erp', ['activePage' => 'clients-create-page', 'pageTitle' => 'Tambah Client'])

@section('content')
<section class="section" id="client-create">
    <div class="card">
        <h2>Tambah Client</h2>
        <form method="post" action="{{ route('clients.store') }}" class="grid">
            @csrf
            <input name="name" placeholder="Nama client" required>
            <input name="contact_name" placeholder="PIC">
            <input name="email" type="email" placeholder="Email">
            <input name="phone" placeholder="Phone">
            <textarea name="address" placeholder="Alamat"></textarea>
            <button>Tambah Client</button>
        </form>
        <a href="{{ route('clients.index') }}" class="mt-4 inline-block text-sm text-slate-500">&larr; Kembali</a>
    </div>
</section>
@endsection
