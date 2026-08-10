@extends('layouts.erp', ['activePage' => 'reports-general-ledger', 'pageTitle' => 'General Ledger'])

@section('content')
<section class="report-hero">
    <div>
        <span>Financial Intelligence</span>
        <h1>General Ledger</h1>
        <p>Buku besar per akun dengan saldo berjalan untuk periode terpilih.</p>
    </div>
</section>

<section class="filter-panel report-filter section">
    <div class="filter-panel-header">
        <div class="filter-panel-icon">{!! $icon('journal') !!}</div>
        <div>
            <h3>Filter General Ledger</h3>
            <p class="muted">Pilih akun dan periode untuk melihat mutasi.</p>
        </div>
    </div>
    <form method="get" action="{{ route('reports.general-ledger') }}" class="filter-grid">
        <div class="filter-field"><label>Akun</label>
            <select name="chart_account_id">
                <option value="">Semua Akun</option>
                @foreach($accounts as $account)
                    <option value="{{ $account->id }}" @selected((int) request('chart_account_id') === $account->id)>{{ $account->code }} - {{ $account->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-field"><label>Dari</label><input name="date_from" type="date" value="{{ request('date_from') }}"></div>
        <div class="filter-field"><label>Sampai</label><input name="date_to" type="date" value="{{ request('date_to') }}"></div>
        <div class="filter-actions xl:col-span-6">
            <button>Apply Filter</button>
            <a class="button ghost" href="{{ route('reports.general-ledger') }}">Reset</a>
        </div>
    </form>
</section>

<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5 section">
    <h2 class="font-bold text-lg mb-4">General Ledger</h2>
    @if($paginated->isEmpty())
        <p class="muted">Tidak ada mutasi untuk filter tersebut.</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-[#d7e3ef]">
                        <th class="text-left py-2">Akun</th>
                        <th class="text-left py-2">Tanggal</th>
                        <th class="text-left py-2">No. Jurnal</th>
                        <th class="text-left py-2">Deskripsi</th>
                        <th class="text-right py-2">Debit</th>
                        <th class="text-right py-2">Kredit</th>
                        <th class="text-right py-2">Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($paginated as $row)
                        <tr class="border-b border-[#d7e3ef]/50">
                            <td class="py-2">
                                <span class="font-mono text-xs">{{ $row['account_code'] }}</span>
                                <span class="block text-xs muted">{{ $row['account_name'] }}</span>
                            </td>
                            <td class="py-2 whitespace-nowrap">{{ $row['date'] }}</td>
                            <td class="py-2 font-mono text-xs">{{ $row['number'] ?? '-' }}</td>
                            <td class="py-2">{{ $row['description'] ?? '-' }}</td>
                            <td class="py-2 text-right">{{ $rp($row['debit']) }}</td>
                            <td class="py-2 text-right">{{ $rp($row['credit']) }}</td>
                            <td class="py-2 text-right font-semibold">{{ $rp($row['balance']) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pager mt-4">{{ $paginated->links() }}</div>
    @endif
</section>
@endsection
