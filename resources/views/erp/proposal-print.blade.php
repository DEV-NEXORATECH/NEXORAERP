<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $proposal->title }}</title>
    <style>
        body { font-family: Arial, sans-serif; color:#002F59; margin:40px; line-height:1.5; }
        h1 { margin-bottom:4px; }
        .muted { color:#637083; }
        table { width:100%; border-collapse:collapse; margin-top:20px; }
        td, th { border:1px solid #d7e3ef; padding:10px; text-align:left; }
        .total { font-size:24px; font-weight:700; }
        @media print { button { display:none; } body { margin:20mm; } }
    </style>
</head>
<body>
    <button onclick="window.print()">Print / Save as PDF</button>
    <h1>NEXORA Proposal</h1>
    <div class="muted">{{ $proposal->title }}</div>
    <table>
        <tr><th>Project</th><td>{{ $proposal->project->code }} - {{ $proposal->project->name }}</td></tr>
        <tr><th>Client</th><td>{{ $proposal->project->client }}</td></tr>
        <tr><th>Status</th><td>{{ $proposal->status }}</td></tr>
        <tr><th>Valid Until</th><td>{{ $proposal->valid_until }}</td></tr>
        <tr><th>Amount</th><td class="total">Rp {{ number_format($proposal->amount, 0, ',', '.') }}</td></tr>
    </table>
    <h2>Scope</h2>
    <p>{!! nl2br(e($proposal->scope)) !!}</p>
</body>
</html>
