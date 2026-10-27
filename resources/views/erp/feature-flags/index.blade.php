@extends('layouts.erp', ['activePage' => 'feature-flags', 'pageTitle' => 'Pengaturan Fitur'])

@section('content')
<section class="space-y-5" id="feature-flags">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 rounded-lg border border-[#D1D5DB] shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-[#0F172A] tracking-tight">Pengaturan Fitur</h1>
            <p class="text-xs text-[#475569]">Kelola status aktif/nonaktif fitur ERP untuk kontrol modul sistem.</p>
        </div>
        <x-erp.toolbar-actions module="feature_flags" :create-route="route('feature-flags.create-page')" create-label="+ Tambah Fitur" />
    </div>

    <p class="mb-6 text-sm text-slate-500">
        Aktifkan atau nonaktifkan fitur di seluruh sistem. Fitur yang dimatikan otomatis disembunyikan dari menu dan diblokir dari akses.
    </p>

    <div class="space-y-6">
        @foreach($modules as $moduleKey => $moduleLabel)
            @php $moduleFlags = $flags->get($moduleKey, collect()); @endphp
            <div class="rounded-lg border border-[#D1D5DB] bg-white shadow-sm p-4 sm:p-5">
                <div class="mb-3 flex items-center justify-between">
                    <h3 class="text-sm font-bold uppercase tracking-wide text-[#002875]">{{ $moduleLabel }}</h3>
                    <span class="text-xs font-semibold text-slate-400">
                        {{ $moduleFlags->where('is_enabled', true)->count() }}/{{ $moduleFlags->count() }} aktif
                    </span>
                </div>

                <div class="grid grid-cols-1 gap-2">
                    @foreach($moduleFlags as $flag)
                    <div class="flex items-center justify-between gap-3 rounded-xl border border-[#D1D5DB] bg-[#F8FAFC]/50 px-4 py-3">
                        <div class="min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="truncate text-sm font-bold text-slate-800">{{ $flag->label }}</span>
                                <span class="hidden rounded bg-slate-100 px-1.5 py-0.5 font-mono text-[10px] text-slate-500 sm:inline">{{ $flag->key }}</span>
                            </div>
                            @if($flag->description)
                                <p class="mt-0.5 truncate text-xs text-slate-400">{{ $flag->description }}</p>
                            @endif
                        </div>
                        <div class="flex shrink-0 items-center gap-2">
                            <a href="{{ route('feature-flags.edit-page', $flag) }}" class="erp-action-button inline-flex h-7 w-7 items-center justify-center rounded border border-[#BAE6FD] bg-[#F0F9FF] text-[#0369A1] hover:bg-[#E0F2FE]" title="Edit">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                        </a>
                            <form method="post" action="{{ route('feature-flags.toggle') }}">
                                @csrf
                                <input type="hidden" name="key" value="{{ $flag->key }}">
                                <input type="hidden" name="is_enabled" value="{{ $flag->is_enabled ? 0 : 1 }}">
                                <button type="submit"
                                    class="relative h-6 w-11 rounded-full transition-colors {{ $flag->is_enabled ? 'bg-emerald-500' : 'bg-slate-300' }}"
                                    title="{{ $flag->is_enabled ? 'Klik untuk nonaktifkan' : 'Klik untuk aktifkan' }}">
                                    <span class="absolute top-0.5 h-5 w-5 rounded-full bg-white shadow transition-all {{ $flag->is_enabled ? 'left-[22px]' : 'left-0.5' }}"></span>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</section>
@endsection
