<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Slip Gaji {{ $salary->employee->name }}</title>
    <style>
        body { font-family: Arial, sans-serif; color:#002F59; margin:40px; line-height:1.5; }
        table { width:100%; border-collapse:collapse; margin-top:20px; }
        td, th { border:1px solid #d7e3ef; padding:10px; text-align:left; }
        .total { font-size:24px; font-weight:700; }
        @media print { button { display:none; } body { margin:20mm; } }
    </style>
</head>
<body>
    <button onclick="window.print()">Print / Save as PDF</button>
    <h1>NEXORA Salary Slip</h1>
    <table>
        <tr><th>Employee</th><td>{{ $salary->employee->name }}</td></tr>
        <tr><th>Period</th><td>{{ $salary->period }}</td></tr>
        <tr><th>Project</th><td>{{ $salary->project?->code ?? 'Non Project' }}</td></tr>
        <tr><th>Status</th><td>{{ $salary->status }}</td></tr>
        <tr><th>Base Salary</th><td>Rp {{ number_format($salary->base_salary, 0, ',', '.') }}</td></tr>
        <tr><th>Allowance</th><td>Rp {{ number_format($salary->allowance, 0, ',', '.') }}</td></tr>
        <tr><th>Deduction</th><td>Rp {{ number_format($salary->deduction, 0, ',', '.') }}</td></tr>
        <tr><th>Net Salary</th><td class="total">Rp {{ number_format($salary->net_salary, 0, ',', '.') }}</td></tr>
    </table>
</body>
</html>
