@extends('layouts.erp', ['activePage' => 'projects', 'pageTitle' => $project->code.' Detail'])

@section('content')
<div class="space-y-5">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-5 rounded-lg border border-gray-200 shadow-sm">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('projects.index') }}" class="text-xs font-medium text-gray-500 hover:text-gray-700">Projects</a>
                <span class="text-gray-300">/</span>
                <span class="text-xs font-semibold text-[#002875]">{{ $project->code }}</span>
            </div>
            <h1 class="text-xl font-bold text-gray-900 tracking-tight">{{ $project->code }} — {{ $project->name }}</h1>
            <div class="flex items-center gap-2 mt-1 text-xs text-gray-500">
                <span class="font-medium text-gray-700">Klien: {{ $project->client }}</span>
                <span>&bull;</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-blue-50 text-[#002875] border border-blue-200 uppercase">
                    {{ $project->status }}
                </span>
                @if($project->contract_value)
                    <span>&bull;</span>
                    <span>Nilai Kontrak: <strong class="text-gray-900">{{ $rp($project->contract_value) }}</strong></span>
                @endif
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('projects.edit-page', $project) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit Proyek
            </a>
            <a href="{{ route('projects.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition shadow-sm">
                Kembali
            </a>
        </div>
    </div>

    <!-- KPI Summary Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
            <div class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Total Income</div>
            <div class="text-xl font-bold text-emerald-600 mt-1">{{ $rp($summary['income']) }}</div>
            <div class="text-xs text-gray-500 mt-0.5">Penerimaan termin invoice</div>
        </div>
        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
            <div class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Total Expense</div>
            <div class="text-xl font-bold text-red-600 mt-1">{{ $rp($summary['expense']) }}</div>
            <div class="text-xs text-gray-500 mt-0.5">Biaya operasional & vendor</div>
        </div>
        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
            <div class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Net Profit / Loss</div>
            <div class="text-xl font-bold {{ $summary['balance'] >= 0 ? 'text-emerald-700' : 'text-red-700' }} mt-1">
                {{ $rp($summary['balance']) }}
            </div>
            <div class="text-xs text-gray-500 mt-0.5">{{ $summary['balance'] >= 0 ? 'Surplus berjalan' : 'Defisit' }}</div>
        </div>
        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
            <div class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Profit Margin</div>
            <div class="text-xl font-bold text-gray-900 mt-1">{{ number_format($profitMargin, 2) }}%</div>
            <div class="text-xs text-gray-500 mt-0.5">Margin laba proyek</div>
        </div>
    </div>

    <!-- Costing Detail Table Card -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm">
        <div class="p-4 border-b border-gray-200 bg-gray-50/50 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-gray-900">Arus Kas & Biaya Terkait (Costing Detail)</h3>
                <p class="text-xs text-gray-500">Mutasi kas masuk dan pengeluaran beban langsung proyek</p>
            </div>
            <span class="text-xs font-semibold px-2 py-0.5 bg-gray-100 text-gray-700 rounded">{{ $project->cashflows->count() }} Transaksi</span>
        </div>
        <div class="overflow-x-auto">
            <table class="table-folk w-full text-left border-collapse">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                        <th class="px-4 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider">Tipe</th>
                        <th class="px-4 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider">Kategori Biaya</th>
                        <th class="px-4 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider">Vendor / Pihak Ketiga</th>
                        <th class="px-4 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-right">Jumlah (IDR)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-xs">
                    @forelse($project->cashflows as $flow)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-4 py-2.5 font-mono text-gray-500">{{ $flow->transaction_date }}</td>
                            <td class="px-4 py-2.5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold uppercase {{ $flow->type === 'income' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200' }}">
                                    {{ $flow->type }}
                                </span>
                            </td>
                            <td class="px-4 py-2.5 text-gray-700 font-medium">{{ $flow->cost_type ?: ($flow->category ?: '-') }}</td>
                            <td class="px-4 py-2.5 text-gray-600">{{ $flow->vendor ?: '-' }}</td>
                            <td class="px-4 py-2.5 text-right font-bold text-gray-900">{{ $rp($flow->amount) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Belum ada transaksi cashflow langsung untuk proyek ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bottom Grids: Invoices & Proposals -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        <!-- Invoices Card -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm">
            <div class="p-4 border-b border-gray-200 bg-gray-50/50 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Faktur Tagihan (Invoices)</h3>
                    <p class="text-xs text-gray-500">Daftar penagihan termin ke klien</p>
                </div>
                <span class="text-xs font-semibold px-2 py-0.5 bg-gray-100 text-gray-700 rounded">{{ $project->invoices->count() }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="table-folk w-full text-left border-collapse">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-2 text-[11px] font-semibold text-gray-600 uppercase">Nomor</th>
                            <th class="px-4 py-2 text-[11px] font-semibold text-gray-600 uppercase">Status</th>
                            <th class="px-4 py-2 text-[11px] font-semibold text-gray-600 uppercase text-right">Terbayar / Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-xs">
                        @forelse($project->invoices as $invoice)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-4 py-2.5 font-bold text-[#002875]">{{ $invoice->number }}</td>
                                <td class="px-4 py-2.5">
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold uppercase bg-gray-100 text-gray-700">
                                        {{ $invoice->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-2.5 text-right font-medium text-gray-900">
                                    {{ $rp($invoice->paid_amount) }} / {{ $rp($invoice->amount) }}
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-4 py-6 text-center text-gray-400">Belum ada invoice diterbitkan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Proposals Card -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm">
            <div class="p-4 border-b border-gray-200 bg-gray-50/50 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Proposal & Penawaran</h3>
                    <p class="text-xs text-gray-500">Dokumen penawaran harga dan ruang lingkup</p>
                </div>
                <span class="text-xs font-semibold px-2 py-0.5 bg-gray-100 text-gray-700 rounded">{{ $project->proposals->count() }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="table-folk w-full text-left border-collapse">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-2 text-[11px] font-semibold text-gray-600 uppercase">Judul Proposal</th>
                            <th class="px-4 py-2 text-[11px] font-semibold text-gray-600 uppercase">Status</th>
                            <th class="px-4 py-2 text-[11px] font-semibold text-gray-600 uppercase text-right">Nilai Penawaran</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-xs">
                        @forelse($project->proposals as $proposal)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-4 py-2.5 font-bold text-gray-900">{{ $proposal->title }}</td>
                                <td class="px-4 py-2.5">
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold uppercase bg-gray-100 text-gray-700">
                                        {{ $proposal->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-2.5 text-right font-medium text-gray-900">{{ $rp($proposal->amount) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-4 py-6 text-center text-gray-400">Belum ada proposal terkait.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
