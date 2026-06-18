<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Proposal — {{ $proposal->title }}</title>
    <style>
        body { font-family: Arial, sans-serif; color:#002F59; margin:40px; line-height:1.5; }
        .top { display:flex; justify-content:space-between; gap:24px; align-items:flex-start; }
        .logo { width:88px; height:88px; object-fit:contain; }
        h1 { margin:0 0 8px; }
        .muted { color:#637083; }
        table { width:100%; border-collapse:collapse; margin-top:20px; }
        td, th { border:1px solid #d7e3ef; padding:10px; text-align:left; }
        .total { font-size:24px; font-weight:700; }
        .scope { white-space:pre-line; line-height:1.7; }
        .btn-print { margin-bottom:24px; padding:8px 20px; background:#0059A7; color:#fff; border:none; border-radius:6px; cursor:pointer; font-size:14px; }
        @media print { .btn-print { display:none; } body { margin:20mm; } }
    </style>
</head>
<body>
    <button class="btn-print" onclick="window.print()">Print / Save as PDF</button>

    <div class="top">
        <div>
            @if($companySetting->logo_path)
                <img class="logo" src="{{ asset('storage/'.$companySetting->logo_path) }}" alt="Logo">
            @endif
            <h1>{{ $companySetting->company_name }}</h1>
            @if($companySetting->address)
                <div class="muted">{{ $companySetting->address }}</div>
            @endif
            <div class="muted">
                {{ $companySetting->email }}
                {{ $companySetting->phone ? '| '.$companySetting->phone : '' }}
            </div>
        </div>
        <div style="text-align:right">
            <h1>PROPOSAL</h1>
            <div><strong>{{ $proposal->number }}</strong></div>
            <div>Status: <strong>{{ strtoupper($proposal->status) }}</strong></div>
            @if($proposal->valid_until)
                <div>Valid Until: {{ $proposal->valid_until }}</div>
            @endif
        </div>
    </div>

    <h2 style="margin-top:24px">{{ $proposal->title }}</h2>

    <table>
        <tr><th>Project</th><td>{{ $proposal->project->code }} — {{ $proposal->project->name }}</td></tr>
        <tr><th>Client</th><td>{{ $proposal->project->client }}</td></tr>
        <tr><th>Amount</th><td class="total">Rp {{ number_format($proposal->amount, 0, ',', '.') }}</td></tr>
    </table>

    @if($proposal->scope)
        <h2 style="margin-top:24px">Scope of Work</h2>
        <div class="scope">{!! nl2br(e($proposal->scope)) !!}</div>
    @endif

    <div style="margin-top:64px; width:260px; text-align:center">
        <div>{{ $companySetting->signature_name ?: 'Authorized Signature' }}</div>
        <div style="border-top:1px solid #002F59; margin-top:72px; padding-top:8px">
            {{ $companySetting->company_name }}
        </div>
    </div>
</body>
</html>
