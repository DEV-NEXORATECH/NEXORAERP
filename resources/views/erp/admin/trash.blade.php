@extends('layouts.erp', ['activePage' => 'trash', 'pageTitle' => 'Trash Restore'])

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col gap-4 rounded-[12px] border border-[#D9E2EC] bg-white p-5 shadow-xs md:flex-row md:items-center md:justify-between">
        <div class="flex items-center gap-3.5">
            <a href="{{ route('admin.index') }}" class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-[8px] border border-[#D9E2EC] bg-white text-[#475569] transition hover:border-[#0B2345] hover:text-[#0B2345]" title="Kembali ke Admin">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-[#0B2345]">Data Recovery</span>
                    <span class="text-[#475569]">·</span>
                    <span class="text-[11px] font-medium text-[#475569]">Recycle Bin</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-[#0F172A]">Trash & Restore</h1>
                <p class="mt-0.5 text-xs text-[#475569]">Pulihkan data penting transaksi atau master yang terhapus (soft-deleted).</p>
            </div>
        </div>
    </div>

    {{-- Trash Table Container --}}
    <div class="rounded-[12px] border border-[#D9E2EC] bg-white shadow-xs overflow-hidden">
        <div class="border-b border-[#D9E2EC] px-5 py-4">
            <h2 class="text-sm font-bold text-[#0F172A]">Daftar Record di Tempat Sampah</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#F8FAFC] border-b border-[#D9E2EC] text-[#475569] font-semibold">
                    <tr>
                        <th class="px-5 py-3.5">Tipe Modul</th>
                        <th class="px-5 py-3.5">Nama / Nomor Dokumen</th>
                        <th class="px-5 py-3.5 text-right">Aksi Pemulihan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#D9E2EC]">
                    @if($hasTrash)
                        @foreach($trash as $type => $items)
                            @foreach($items as $item)
                                <tr class="hover:bg-[#F8FAFC]/60 transition">
                                    <td class="px-5 py-3.5">
                                        <span class="inline-flex items-center rounded-full bg-[#E8EEF5] px-2.5 py-0.5 text-[11px] font-bold uppercase tracking-wider text-[#0B2345]">
                                            {{ $type }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5 font-bold text-[#0F172A]">
                                        {{ $item->name ?? $item->title ?? $item->number ?? $item->code }}
                                    </td>
                                    <td class="px-5 py-3.5 text-right">
                                        <form method="post" action="{{ route('trash.restore', [$type, $item->id]) }}" class="inline-block">
                                            @csrf @method('patch')
                                            <button type="submit" class="btn btn-outline h-7 px-3 text-xs font-semibold text-[#16A34A] border-[#86EFAC] hover:bg-[#F0FDF4]">
                                                Pulihkan Record
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3" class="px-5 py-8 text-center text-xs text-[#94A3B8]">
                                Tempat sampah kosong. Tidak ada data yang dihapus sementara.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        @foreach($trash as $type => $items)
            @if($items->total() > 0)
                <div class="flex items-center justify-between border-t border-[#D9E2EC] px-5 py-3 bg-[#F8FAFC]">
                    <span class="text-xs text-[#475569]">{{ ucfirst($type) }}: {{ $items->count() }} dari {{ $items->total() }}</span>
                    <div>{{ $items->links() }}</div>
                </div>
            @endif
        @endforeach
    </div>
</div>
@endsection
