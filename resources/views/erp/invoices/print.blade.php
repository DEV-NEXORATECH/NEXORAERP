<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $invoice->number }}</title>
    <style>
        body { font-family: Arial, sans-serif; color:#002F59; margin:40px; line-height:1.5; }
        .top { display:flex; justify-content:space-between; gap:24px; align-items:flex-start; }
        .logo { width:88px; height:88px; object-fit:contain; }
        h1 { margin:0 0 8px; }
        table { width:100%; border-collapse:collapse; margin-top:24px; }
        td, th { border:1px solid #d7e3ef; padding:10px; text-align:left; }
        .right { text-align:right; }
        .muted { color:#637083; }
        .total { font-size:24px; font-weight:700; }
        .btn-print { margin-bottom:24px; padding:8px 20px; background:#0059A7; color:#fff; border:none; border-radius:6px; cursor:pointer; font-size:14px; }
        @media print { .btn-print { display:none; } body { margin:18mm; } }
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
            @if($companySetting->npwp)
                <div class="muted">NPWP: {{ $companySetting->npwp }}</div>
            @endif
        </div>
        <div style="text-align:right">
            <h1>INVOICE</h1>
            <div><strong>{{ $invoice->number }}</strong></div>
            <div>Issue: {{ $invoice->issue_date }}</div>
            <div>Due: {{ $invoice->due_date ?? '—' }}</div>
            <div>Status: <strong>{{ strtoupper($invoice->status) }}</strong></div>
        </div>
    </div>

    <table>
        <tr><th>Bill To</th><td>{{ $invoice->project->client }}</td></tr>
        <tr><th>Project</th><td>{{ $invoice->project->code }} — {{ $invoice->project->name }}</td></tr>
        @if($invoice->notes)
            <tr><th>Notes</th><td>{{ $invoice->notes }}</td></tr>
        @endif
    </table>

    @php
        $tax        = $invoice->amount * ($invoice->tax_rate / 100);
        $grandTotal = $invoice->amount + $tax;
        $remaining  = max(0, $grandTotal - $invoice->paid_amount);
    @endphp
    <table>
        <thead><tr><th>Deskripsi</th><th class="right">Jumlah</th></tr></thead>
        <tbody>
            <tr>
                <td>Project service</td>
                <td class="right">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
            </tr>
            @if($invoice->tax_rate > 0)
                <tr>
                    <td>Pajak {{ $invoice->tax_rate }}%</td>
                    <td class="right">Rp {{ number_format($tax, 0, ',', '.') }}</td>
                </tr>
            @endif
            <tr>
                <th>Grand Total</th>
                <th class="right total">Rp {{ number_format($grandTotal, 0, ',', '.') }}</th>
            </tr>
            <tr>
                <td>Sudah Dibayar</td>
                <td class="right">Rp {{ number_format($invoice->paid_amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>Sisa Tagihan</strong></td>
                <td class="right"><strong>Rp {{ number_format($remaining, 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>

    @if($invoice->payment_terms)
        <h2>Payment Terms</h2>
        <p>{!! nl2br(e($invoice->payment_terms)) !!}</p>
    @endif

    @if($companySetting->defaultBankAccount)
        <p>
            <strong>Rekening Pembayaran:</strong>
            {{ $companySetting->defaultBankAccount->bank_name }}
            {{ $companySetting->defaultBankAccount->account_number }}
            a/n {{ $companySetting->defaultBankAccount->name }}
        </p>
    @endif

    <div style="margin-top:64px; width:260px; text-align:center">
        <div>{{ $companySetting->signature_name ?: 'Authorized Signature' }}</div>
        <div style="border-top:1px solid #002F59; margin-top:72px; padding-top:8px">
            {{ $companySetting->company_name }}
        </div>
    </div>
</body>
</html>
