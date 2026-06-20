@extends('layouts.erp', ['activePage' => 'reports-balance-sheet', 'pageTitle' => 'Balance Sheet Report'])

@section('content')
<section class="report-hero">
    <div>
        <span>Financial Intelligence</span>
        <h1>Balance Sheet</h1>
        <p>Neraca aset, liabilities, dan equity dari seluruh jurnal.</p>
    </div>
</section>

<section class="grid three section">
    @foreach(['assets' => 'Assets', 'liabilities' => 'Liabilities', 'equity' => 'Equity'] as $key => $label)
        <div class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5">
            <h2 class="font-bold text-lg mb-4">{{ $label }}</h2>
            <table class="w-full">
                @forelse($balanceSheet[$key] as $row)
                    <tr><td class="py-1">{{ $row['account']->code }} - {{ $row['account']->name }}</td><td class="text-right font-bold py-1">{{ $rp($row['balance']) }}</td></tr>
                @empty
                    <tr><td colspan="2" class="py-8 text-center text-slate-500">Belum ada jurnal.</td></tr>
                @endforelse
            </table>
        </div>
    @endforeach
    <div class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5">
        <h2 class="font-bold text-lg mb-4">Balance Check</h2>
        <table class="w-full">
            <tr><td class="py-2">Total Assets</td><td class="text-right font-black py-2">{{ $rp($balanceSheet['total_assets']) }}</td></tr>
            <tr><td class="py-2">Liabilities + Equity</td><td class="text-right font-black py-2">{{ $rp($balanceSheet['total_liabilities'] + $balanceSheet['total_equity']) }}</td></tr>
        </table>
    </div>
</section>
@endsection
