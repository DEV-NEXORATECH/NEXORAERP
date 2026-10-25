@extends('layouts.erp', ['activePage' => 'masters', 'pageTitle' => 'Master Data'])

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col gap-4 rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs md:flex-row md:items-center md:justify-between">
        <div class="flex items-center gap-3.5">
            <a href="{{ route('admin.index') }}" class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-md border border-[#E5E7EB] bg-white text-[#475569] transition hover:border-[#002875] hover:text-[#002875]" title="Kembali ke Admin">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-[#002875]">System Administration</span>
                    <span class="text-[#475569]">·</span>
                    <span class="text-[11px] font-medium text-[#475569]">Master Data</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-[#0F172A]">Master Data Central</h1>
                <p class="mt-0.5 text-xs text-[#475569]">Kelola data referensi master klien, struktur organisasi departemen & jabatan, serta kategori kas/bank.</p>
            </div>
        </div>
    </div>

    {{-- 3 Columns Grid --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Card 1: Client Master --}}
        <div class="flex flex-col rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs">
            <div class="mb-4 border-b border-[#E5E7EB] pb-3">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-bold text-[#0F172A]">Master Klien</h2>
                    <span class="inline-flex items-center rounded-full bg-[#E8EEF5] px-2 py-0.5 text-[10px] font-bold text-[#002875]">
                        {{ $clients->total() }} Klien
                    </span>
                </div>
                <p class="text-xs text-[#475569]">Registrasi data klien untuk project dan invoicing.</p>
            </div>

            <form method="post" action="{{ route('masters.store', 'clients') }}" class="space-y-3">
                @csrf
                <div>
                    <input name="name" placeholder="Nama Perusahaan Klien *" class="field-input w-full text-xs" required>
                </div>
                <div>
                    <input name="contact_name" placeholder="Nama Kontak PIC" class="field-input w-full text-xs">
                </div>
                <div>
                    <input name="email" type="email" placeholder="Email Klien" class="field-input w-full text-xs">
                </div>
                <button type="submit" class="btn btn-primary w-full h-8 text-xs font-semibold">
                    Tambah Klien
                </button>
            </form>

            <div class="mt-4 border-t border-[#E5E7EB] pt-3 flex-1 flex flex-col justify-between">
                <h3 class="text-xs font-semibold uppercase tracking-wider text-[#475569] mb-2">Daftar Klien</h3>
                <div class="divide-y divide-[#E2E8F0] overflow-y-auto max-h-64">
                    @forelse($clients as $client)
                    <div class="py-2 flex items-center justify-between gap-2">
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-[#0F172A] truncate">{{ $client->name }}</p>
                            <p class="text-[11px] text-[#475569] truncate">{{ $client->contact_name ?: 'No PIC' }}</p>
                        </div>
                        <form method="post" action="{{ route('masters.destroy', ['clients', $client->id]) }}">
                            @csrf @method('delete')
                            <button type="submit" class="inline-flex h-6 items-center rounded-[4px] border border-[#FECACA] bg-[#FEF2F2] px-2 text-[10px] font-bold text-[#DC2626] hover:bg-[#FEE2E2]">
                                Hapus
                            </button>
                        </form>
                    </div>
                    @empty
                    <p class="text-xs text-[#94A3B8] py-3 text-center">Belum ada data klien.</p>
                    @endforelse
                </div>
                @if($clients->hasPages())
                <div class="pt-2 border-t border-[#E2E8F0] mt-2">
                    {{ $clients->links() }}
                </div>
                @endif
            </div>
        </div>

        {{-- Card 2: HR Master --}}
        <div class="flex flex-col rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs">
            <div class="mb-4 border-b border-[#E5E7EB] pb-3">
                <h2 class="text-sm font-bold text-[#0F172A]">Master HR & Organisasi</h2>
                <p class="text-xs text-[#475569]">Departemen dan jabatan struktur organisasi.</p>
            </div>

            <div class="space-y-4">
                <form method="post" action="{{ route('masters.store', 'departments') }}" class="flex gap-2">
                    @csrf
                    <input name="name" placeholder="Tambah Departemen..." class="field-input flex-1 text-xs" required>
                    <button type="submit" class="btn btn-primary h-8 px-3 text-xs font-semibold shrink-0">Add</button>
                </form>

                <form method="post" action="{{ route('masters.store', 'job_positions') }}" class="flex gap-2">
                    @csrf
                    <input name="name" placeholder="Tambah Jabatan..." class="field-input flex-1 text-xs" required>
                    <button type="submit" class="btn btn-primary h-8 px-3 text-xs font-semibold shrink-0">Add</button>
                </form>
            </div>

            <div class="mt-4 border-t border-[#E5E7EB] pt-3 flex-1 flex flex-col justify-between">
                <h3 class="text-xs font-semibold uppercase tracking-wider text-[#475569] mb-2">Departemen & Jabatan</h3>
                <div class="divide-y divide-[#E2E8F0] overflow-y-auto max-h-64">
                    @foreach($departments as $department)
                    <div class="py-2 flex items-center justify-between gap-2">
                        <span class="text-xs font-medium text-[#0F172A] truncate">
                            <span class="font-bold text-[#002875]">Dept:</span> {{ $department->name }}
                        </span>
                        <form method="post" action="{{ route('masters.destroy', ['departments', $department->id]) }}">
                            @csrf @method('delete')
                            <button type="submit" class="inline-flex h-6 items-center rounded-[4px] border border-[#FECACA] bg-[#FEF2F2] px-2 text-[10px] font-bold text-[#DC2626] hover:bg-[#FEE2E2]">
                                Hapus
                            </button>
                        </form>
                    </div>
                    @endforeach
                    @foreach($jobPositions as $position)
                    <div class="py-2 flex items-center justify-between gap-2">
                        <span class="text-xs font-medium text-[#0F172A] truncate">
                            <span class="font-bold text-[#475569]">Pos:</span> {{ $position->name }}
                        </span>
                        <form method="post" action="{{ route('masters.destroy', ['job_positions', $position->id]) }}">
                            @csrf @method('delete')
                            <button type="submit" class="inline-flex h-6 items-center rounded-[4px] border border-[#FECACA] bg-[#FEF2F2] px-2 text-[10px] font-bold text-[#DC2626] hover:bg-[#FEE2E2]">
                                Hapus
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Card 3: Finance Master --}}
        <div class="flex flex-col rounded-lg border border-[#E5E7EB] bg-white p-5 shadow-xs">
            <div class="mb-4 border-b border-[#E5E7EB] pb-3">
                <h2 class="text-sm font-bold text-[#0F172A]">Master Kas & Bank</h2>
                <p class="text-xs text-[#475569]">Rekening bank dan klasifikasi biaya pengeluaran.</p>
            </div>

            <form method="post" action="{{ route('masters.store', 'expense_categories') }}" class="flex gap-2 mb-3">
                @csrf
                <input name="name" placeholder="Kategori Beban" class="field-input flex-1 text-xs" required>
                <input name="type" placeholder="Tipe" class="field-input w-20 text-xs" required>
                <button type="submit" class="btn btn-primary h-8 px-3 text-xs font-semibold shrink-0">Add</button>
            </form>

            <form method="post" action="{{ route('masters.store', 'bank_accounts') }}" class="space-y-2 border-t border-[#E5E7EB] pt-3">
                @csrf
                <input name="name" placeholder="Nama Kas / Akun *" class="field-input w-full text-xs" required>
                <div class="grid grid-cols-2 gap-2">
                    <input name="bank_name" placeholder="Nama Bank" class="field-input w-full text-xs">
                    <input name="account_number" placeholder="No Rekening" class="field-input w-full text-xs">
                </div>
                <button type="submit" class="btn btn-outline w-full h-8 text-xs font-semibold">
                    Tambah Akun Bank
                </button>
            </form>

            <div class="mt-4 border-t border-[#E5E7EB] pt-3 flex-1 flex flex-col justify-between">
                <h3 class="text-xs font-semibold uppercase tracking-wider text-[#475569] mb-2">Akun Bank & Kategori</h3>
                <div class="divide-y divide-[#E2E8F0] overflow-y-auto max-h-56">
                    @foreach($bankAccounts as $bank)
                    <div class="py-2 flex items-center justify-between gap-2">
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-[#0F172A] truncate">{{ $bank->name }}</p>
                            <p class="text-[11px] text-[#475569] truncate">{{ $bank->bank_name }} - {{ $bank->account_number }}</p>
                        </div>
                        <form method="post" action="{{ route('masters.destroy', ['bank_accounts', $bank->id]) }}">
                            @csrf @method('delete')
                            <button type="submit" class="inline-flex h-6 items-center rounded-[4px] border border-[#FECACA] bg-[#FEF2F2] px-2 text-[10px] font-bold text-[#DC2626] hover:bg-[#FEE2E2]">
                                Hapus
                            </button>
                        </form>
                    </div>
                    @endforeach
                    @foreach($expenseCategories as $category)
                    <div class="py-2 flex items-center justify-between gap-2">
                        <div class="min-w-0">
                            <p class="text-xs font-medium text-[#0F172A] truncate">{{ $category->name }}</p>
                            <span class="text-[10px] font-bold text-[#002875] uppercase">{{ $category->type }}</span>
                        </div>
                        <form method="post" action="{{ route('masters.destroy', ['expense_categories', $category->id]) }}">
                            @csrf @method('delete')
                            <button type="submit" class="inline-flex h-6 items-center rounded-[4px] border border-[#FECACA] bg-[#FEF2F2] px-2 text-[10px] font-bold text-[#DC2626] hover:bg-[#FEE2E2]">
                                Hapus
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
