@extends('layouts.erp', ['activePage' => 'budgets', 'pageTitle' => 'Budget & Forecast'])

@section('content')
<div class="space-y-4">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-gray-900 tracking-tight">Anggaran (Budget Planning)</h1>
            <p class="text-xs text-gray-500">Kelola master data dan transaksi Anggaran (Budget Planning)</p>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm">
        <form method="get" action="{{ route('budgets.index') }}" class="p-3 border-b border-gray-200 bg-gray-50/50 flex flex-wrap items-center gap-2.5">
            
        <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}" class="px-2.5 py-1.5 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white">
        @if(isset($projects))
        <select name="project_id" class="px-2.5 py-1.5 text-xs border border-gray-300 rounded-md focus:ring-1 focus:ring-[#002875] focus:border-[#002875] bg-white">
            <option value="">Semua Project</option>
            @foreach($projects as $project)
            <option value="{{ $project->id }}" @selected(request('project_id') == $project->id)>{{ $project->code }}</option>
            @endforeach
        </select>
        @endif
        <div class="flex gap-2 items-end">
            <button class="px-3 py-1.5 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition">Filter</button>
            <a href="{{ route('budgets.index') }}" class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">Reset</a>
        </div>
    
        </form>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Period</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Project</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Account</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Budget</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Forecast</th><th class="px-3 py-2.5 text-[11px] font-semibold text-gray-600 uppercase tracking-wider text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    
            @forelse($budgets as $budget)
                <tr>
                    <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $budget->period }}</td>
                    <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $budget->project?->code ?? 'Company' }}</td>
                    <td class="px-3 py-2 text-xs text-gray-700 text-left"><span class="muted">{{ $budget->account?->code }} {{ $budget->account?->name ?? '-' }}</span></td>
                    <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $rp($budget->budget_amount) }}</td>
                    <td class="px-3 py-2 text-xs text-gray-700 text-left">{{ $rp($budget->forecast_amount) }}</td>
                    <td class="px-3 py-2 text-xs text-right whitespace-nowrap">
                        @if($can('admin', 'finance'))<a class="button mini ghost" href="{{ route('budgets.edit-page', $budget) }}">Edit</a>@endif
                        @if($can('admin'))<form method="post" action="{{ route('budgets.destroy', $budget) }}">@csrf @method('delete')<button class="inline-flex items-center px-2 py-1 text-[11px] font-medium text-red-700 bg-red-50 border border-red-200 rounded hover:bg-red-100 transition">Hapus</button></form>@endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="py-8 text-center text-slate-500">Belum ada budget.</td></tr>
            @endforelse
        
                </tbody>
            </table>
        </div>
        @if($budgets->hasPages())
        <div class="p-3 border-t border-gray-200 bg-gray-50/50 flex items-center justify-between text-xs text-gray-500">
            <span>Menampilkan {{$budgets->firstItem()}}-{{$budgets->lastItem()}} dari {{$budgets->total()}}</span>
            {{$budgets->links()}}
        </div>
        @endif
    </div>
</div>
@endsection
