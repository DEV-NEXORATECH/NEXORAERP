@extends('layouts.erp', ['activePage' => 'company', 'pageTitle' => 'Company Setting'])

@section('content')
<section class="section" id="settings">
    <div class="card">
        <h2>Setting Perusahaan</h2>
        <form method="post" action="{{ route('company-setting.update') }}" enctype="multipart/form-data" class="grid">
            @csrf
            <input name="company_name" value="{{ $companySetting->company_name }}" required placeholder="Nama Perusahaan">
            <input name="email" type="email" value="{{ $companySetting->email }}" placeholder="Email">
            <input name="phone" value="{{ $companySetting->phone }}" placeholder="Phone">
            <input name="npwp" value="{{ $companySetting->npwp }}" placeholder="NPWP">
            <textarea name="address" placeholder="Alamat">{{ $companySetting->address }}</textarea>
            <input name="signature_name" value="{{ $companySetting->signature_name }}" placeholder="Nama tanda tangan">
            <select name="default_bank_account_id">
                <option value="">Bank default</option>
                @foreach($bankAccounts as $bank)
                    <option value="{{ $bank->id }}" @selected($companySetting->default_bank_account_id===$bank->id)>{{ $bank->name }}</option>
                @endforeach
            </select>
            <input name="logo" type="file">
            <button>Simpan Setting</button>
        </form>
    </div>
</section>
@endsection
