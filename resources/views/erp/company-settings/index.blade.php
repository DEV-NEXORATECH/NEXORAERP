@extends('layouts.erp', ['activePage' => 'company-settings', 'pageTitle' => 'Company Setting'])

@section('content')
<section class="module-detail-page">
    <div class="module-detail-hero">
        <div class="module-detail-copy">
            <a class="module-back-link" href="{{ route('admin.index') }}">Back to Admin</a>
            <div class="module-title-row">
                <span class="module-title-icon">{!! $icon('settings') !!}</span>
                <div>
                    <span class="module-eyebrow">Settings Admin</span>
                    <h1>Company Setting</h1>
                </div>
            </div>
            <p>Kelola identitas perusahaan, rekening default, logo, dan informasi legal untuk dokumen ERP.</p>
        </div>
        <div class="module-count">
            <strong>6</strong>
            <span>Field</span>
        </div>
    </div>
</section>

<section class="section" id="settings">
    <div class="card">
        <h2>Setting Perusahaan</h2>
        <form method="post" action="{{ route('company-settings.update') }}" enctype="multipart/form-data" class="grid">
            @csrf
            @method('put')
            <input name="company_name" value="{{ $companySetting->company_name }}" required placeholder="Nama Perusahaan">
            <input name="email" type="email" value="{{ $companySetting->email }}" placeholder="Email">
            <input name="phone" value="{{ $companySetting->phone }}" placeholder="Phone">
            <input name="npwp" value="{{ $companySetting->npwp }}" placeholder="NPWP">
            <textarea name="address" placeholder="Alamat">{{ $companySetting->address }}</textarea>
            <input name="signature_name" value="{{ $companySetting->signature_name }}" placeholder="Nama tanda tangan">
            <select name="default_bank_account_id">
                <option value="">Bank default</option>
                @foreach($bankAccounts as $bank)
                    <option value="{{ $bank->id }}" @selected($companySetting->default_bank_account_id === $bank->id)>{{ $bank->name }}</option>
                @endforeach
            </select>
            <input name="logo" type="file">
            <button>Simpan Setting</button>
        </form>
    </div>
</section>
@endsection
