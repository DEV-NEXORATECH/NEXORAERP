@extends('layouts.erp', ['activePage' => 'reports-trial-balance', 'pageTitle' => 'Trial Balance'])

@section('content')
<section class="bg-white rounded-lg border border-gray-200 p-5 shadow-sm mb-4">
    <div>
        <span>Financial Intelligence</span>
        <h1>Trial Balance</h1>
        <p>Neraca saldo per akun berdasarkan jurnal pada periode terpilih.</p>
    </div>
</section>

<section class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm mb-4 report-filter section">
    <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm mb-4-header">
        <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm mb-4-icon">{!! $icon('reports') !!}</div>
        <div>
            <h3>Filter Trial Balance</h3>
            <p class="muted">Filter berdasarkan periode dan jenis akun.</p>
        </div>
    </div>
    <form method="get" action="{{ route('reports.trial-balance') }}" class="filter-grid">
        <div class="filter-field"><label>Dari</label><input name="date_from" type="date" value="{{ request('date_from') }}"></div>
        <div class="filter-field"><label>Sampai</label><input name="date_to" type="date" value="{{ request('date_to') }}"></div>
        <div class="filter-field"><label>Jenis Akun</label>
            <select name="type">
                <option value="">Semua</option>
                @foreach($types as $type)
                    <option value="{{ $type }}" @selected(request('type') === $type)>{{ ucfirst($type) }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-actions xl:col-span-6">
            <button>Apply Filter</button>
            <a class="button ghost" href="{{ route('reports.trial-balance') }}">Reset</a>
        </div>
    </form>
</section>

<section class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm section">
    <h2 class="font-bold text-lg mb-4">Trial Balance</h2>
    @if($accounts->isEmpty())
        <p class="muted">Tidak ada jurnal pada periode tersebut.</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-[#d7e3ef]">
                        <th class="text-left py-2">Kode</th>
                        <th class="text-left py-2">Akun</th>
                        <th class="text-left py-2">Type</th>
                        <th class="text-right py-2">Debit</th>
                        <th class="text-right py-2">Kredit</th>
                        <th class="text-right py-2">Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($accounts as $account)
                        <tr class="border-b border-[#d7e3ef]/50">
                            <td class="py-2 font-mono text-xs">{{ $account['code'] }}</td>
                            <td class="py-2">{{ $account['name'] }}</td>
                            <td class="py-2"><span class="badge badge-secondary">{{ $account['type'] }}</span></td>
                            <td class="py-2 text-right">{{ $rp($account['debit']) }}</td>
                            <td class="py-2 text-right">{{ $rp($account['credit']) }}</td>
                            <td class="py-2 text-right font-semibold {{ $account['balance'] < 0 ? 'text-red-600' : '' }}">{{ $rp(abs($account['balance'])) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="border-t-2 border-[#002875]">
                        <td colspan="3" class="py-3 font-bold">Total</td>
                        <td class="py-3 text-right font-bold">{{ $rp($accounts->sum('debit')) }}</td>
                        <td class="py-3 text-right font-bold">{{ $rp($accounts->sum('credit')) }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endif
</section>
@endsection
