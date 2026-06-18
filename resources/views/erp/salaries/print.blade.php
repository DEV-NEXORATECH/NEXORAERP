<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Slip Gaji — {{ $salary->employee->name }}</title>
    <style>
        body { font-family: Arial, sans-serif; color:#002F59; margin:40px; line-height:1.5; }
        .top { display:flex; justify-content:space-between; gap:24px; align-items:flex-start; }
        .logo { width:88px; height:88px; object-fit:contain; }
        h1 { margin:0 0 8px; }
        .muted { color:#637083; }
        table { width:100%; border-collapse:collapse; margin-top:20px; }
        td, th { border:1px solid #d7e3ef; padding:10px; text-align:left; }
        .right { text-align:right; }
        .total { font-size:24px; font-weight:700; }
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
            <h1>SLIP GAJI</h1>
            <div><strong>{{ $salary->slip_number }}</strong></div>
            <div>Periode: <strong>{{ $salary->period }}</strong></div>
            <div>Status: <strong>{{ strtoupper($salary->status) }}</strong></div>
        </div>
    </div>

    <table style="margin-top:32px">
        <tr><th>Karyawan</th><td>{{ $salary->employee->name }}</td></tr>
        <tr><th>Jabatan</th><td>{{ $salary->employee->jobPosition?->name ?? '—' }}</td></tr>
        <tr><th>Departemen</th><td>{{ $salary->employee->departmentRecord?->name ?? '—' }}</td></tr>
        <tr><th>Project</th><td>{{ $salary->project?->code ?? 'Non Project' }}</td></tr>
    </table>

    <table>
        <thead><tr><th>Komponen</th><th class="right">Jumlah</th></tr></thead>
        <tbody>
            <tr>
                <td>Gaji Pokok</td>
                <td class="right">Rp {{ number_format($salary->base_salary, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Tunjangan</td>
                <td class="right">Rp {{ number_format($salary->allowance, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Potongan</td>
                <td class="right">— Rp {{ number_format($salary->deduction, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Gaji Bersih</th>
                <th class="right total">Rp {{ number_format($salary->net_salary, 0, ',', '.') }}</th>
            </tr>
        </tbody>
    </table>

    @if($salary->notes)
        <p style="margin-top:16px"><strong>Catatan:</strong> {{ $salary->notes }}</p>
    @endif

    <div style="margin-top:64px; display:flex; justify-content:space-between">
        <div style="width:220px; text-align:center">
            <div class="muted">Karyawan</div>
            <div style="border-top:1px solid #002F59; margin-top:56px; padding-top:8px">{{ $salary->employee->name }}</div>
        </div>
        <div style="width:220px; text-align:center">
            <div class="muted">{{ $companySetting->signature_name ?: 'HR / Finance' }}</div>
            <div style="border-top:1px solid #002F59; margin-top:56px; padding-top:8px">{{ $companySetting->company_name }}</div>
        </div>
    </div>
</body>
</html>
