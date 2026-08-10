@extends('layouts.erp', ['activePage' => 'feature-flags', 'pageTitle' => 'Pengaturan Fitur'])

@section('content')
<section class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5" id="feature-flags">
    <div class="section-head">
        <h2>Pengaturan Fitur</h2>
        <div class="flex items-center gap-3">
            <span class="rounded-full bg-[#e8f4fd] px-3 py-1 text-xs font-bold text-[#0059A7]">
                {{ $enabledCount }}/{{ $totalCount }} aktif
            </span>
            <a href="{{ route('feature-flags.create-page') }}" class="button ghost">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M12 5v14M5 12h14"/></svg>
                Tambah Fitur
            </a>
        </div>
    </div>

    <p class="mb-6 text-sm text-slate-500">
        Aktifkan atau nonaktifkan fitur di seluruh sistem. Fitur yang dimatikan otomatis disembunyikan dari menu dan diblokir dari akses.
    </p>

    <div class="space-y-6">
        @foreach($modules as $moduleKey => $moduleLabel)
            @php $moduleFlags = $flags->get($moduleKey, collect()); @endphp
            <div class="rounded-2xl border border-[#d7e3ef] bg-[#f8fbff] p-4 sm:p-5">
                <div class="mb-3 flex items-center justify-between">
                    <h3 class="text-sm font-bold uppercase tracking-wide text-[#0059A7]">{{ $moduleLabel }}</h3>
                    <span class="text-xs font-semibold text-slate-400">
                        {{ $moduleFlags->where('is_enabled', true)->count() }}/{{ $moduleFlags->count() }} aktif
                    </span>
                </div>

                <div class="grid grid-cols-1 gap-2">
                    @foreach($moduleFlags as $flag)
                    <div class="flex items-center justify-between gap-3 rounded-xl border border-[#d7e3ef] bg-white px-4 py-3">
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
                            <a href="{{ route('feature-flags.edit-page', $flag) }}" class="mini ghost">Edit</a>
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
