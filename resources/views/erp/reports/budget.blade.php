@extends('layouts.erp', ['activePage' => 'reports-budget', 'pageTitle' => 'Budget vs Actual Report'])

@section('content')
<section class="report-hero">
    <div>
        <span>Financial Intelligence</span>
        <h1>Budget vs Actual</h1>
        <p>Perbandingan anggaran dengan realisasi biaya per periode.</p>
    </div>
</section>

<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5 section">
    <h2 class="font-bold text-lg mb-4">Anggaran vs Realisasi</h2>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-[#d7e3ef]">
                    <th class="text-left py-2">Period</th>
                    <th class="text-left py-2">Project / Account</th>
                    <th class="text-right py-2">Budget</th>
                    <th class="text-right py-2">Actual</th>
                    <th class="text-right py-2">Variance</th>
                </tr>
            </thead>
            <tbody>
                @foreach($budgetVsActual as $row)
                    <tr class="border-b border-[#d7e3ef]/50">
                        <td class="py-2">{{ $row['budget']->period }}</td>
                        <td class="py-2">{{ $row['budget']->project?->code ?? 'Company' }}<br><span class="muted text-xs">{{ $row['budget']->account?->code }} {{ $row['budget']->account?->name }}</span></td>
                        <td class="text-right py-2">{{ $rp($row['budget']->budget_amount) }}</td>
                        <td class="text-right py-2">{{ $rp($row['actual']) }}</td>
                        <td class="text-right py-2 font-bold {{ $row['variance'] >= 0 ? 'good' : 'bad' }}">{{ $rp($row['variance']) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
@endsection
