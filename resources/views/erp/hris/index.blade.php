@extends('layouts.erp', ['activePage' => 'hris', 'pageTitle' => 'HRIS Suite'])

@section('content')
<div class="space-y-5">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-5 rounded-lg border border-gray-200 shadow-sm">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('modules.index') }}" class="text-xs font-medium text-gray-500 hover:text-gray-700">Hub Modul</a>
                <span class="text-gray-300">/</span>
                <span class="text-xs font-semibold text-[#002875]">HR & Kepegawaian</span>
            </div>
            <h1 class="text-xl font-bold text-gray-900 tracking-tight">HRIS Suite & Manajemen Karyawan</h1>
            <p class="text-xs text-gray-500 mt-0.5">Pusat kelola sumber daya manusia: absensi harian, timesheet project, pengajuan cuti, keahlian, dan penggajian terintegrasi.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('dashboard.hr') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-white bg-[#002875] hover:bg-[#001B50] rounded-md transition shadow-sm">
                Lihat HR Dashboard
            </a>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
            <div class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Pemetaan Skill</div>
            <div class="text-2xl font-bold text-gray-900 mt-1">{{ $skills->total() }}</div>
            <div class="text-xs text-gray-500 mt-0.5">Kompetensi terdaftar</div>
        </div>
        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
            <div class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Total Presensi</div>
            <div class="text-2xl font-bold text-gray-900 mt-1">{{ $attendances->total() }}</div>
            <div class="text-xs text-gray-500 mt-0.5">Log kehadiran karyawan</div>
        </div>
        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
            <div class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Jam Timesheet</div>
            <div class="text-2xl font-bold text-gray-900 mt-1">{{ $timesheets->total() }}</div>
            <div class="text-xs text-gray-500 mt-0.5">Log pengerjaan project</div>
        </div>
        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
            <div class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Permohonan Cuti Pending</div>
            <div class="text-2xl font-bold text-amber-600 mt-1">{{ $leaves->getCollection()->where('status', 'pending')->count() }}</div>
            <div class="text-xs text-gray-500 mt-0.5">Membutuhkan persetujuan</div>
        </div>
    </div>

    <!-- Sub Modules Navigation -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <a href="{{ route('hr.index') }}" class="group bg-white p-4 rounded-lg border border-gray-200 hover:border-[#002875] hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-gray-900 group-hover:text-[#002875] transition">Database Karyawan</h3>
                <svg class="w-4 h-4 text-gray-400 group-hover:text-[#002875] group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
            <p class="text-xs text-gray-500 mt-1">Data induk pegawai, jabatan, departemen, dan gaji pokok.</p>
        </a>

        <a href="{{ route('attendances.index') }}" class="group bg-white p-4 rounded-lg border border-gray-200 hover:border-[#002875] hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-gray-900 group-hover:text-[#002875] transition">Presensi Kehadiran</h3>
                <svg class="w-4 h-4 text-gray-400 group-hover:text-[#002875] group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
            <p class="text-xs text-gray-500 mt-1">Pencatatan check-in/out, keterlambatan, dan mode kerja (WFO/WFH).</p>
        </a>

        <a href="{{ route('timesheets.index') }}" class="group bg-white p-4 rounded-lg border border-gray-200 hover:border-[#002875] hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-gray-900 group-hover:text-[#002875] transition">Timesheet Project</h3>
                <svg class="w-4 h-4 text-gray-400 group-hover:text-[#002875] group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
            <p class="text-xs text-gray-500 mt-1">Alokasi jam kerja staf terhadap project untuk kalkulasi biaya dan billing.</p>
        </a>

        <a href="{{ route('leave-requests.index') }}" class="group bg-white p-4 rounded-lg border border-gray-200 hover:border-[#002875] hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-gray-900 group-hover:text-[#002875] transition">Permohonan Cuti</h3>
                <svg class="w-4 h-4 text-gray-400 group-hover:text-[#002875] group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
            <p class="text-xs text-gray-500 mt-1">Pengajuan izin/cuti tahunan, sakit, dan workflow approval atasan.</p>
        </a>

        <a href="{{ route('salaries.index-page') }}" class="group bg-white p-4 rounded-lg border border-gray-200 hover:border-[#002875] hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-gray-900 group-hover:text-[#002875] transition">Penggajian (Payroll)</h3>
                <svg class="w-4 h-4 text-gray-400 group-hover:text-[#002875] group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
            <p class="text-xs text-gray-500 mt-1">Kalkulasi gaji bulanan, potongan, tunjangan, dan cetak slip gaji.</p>
        </a>

        <a href="{{ route('performance-reviews.index') }}" class="group bg-white p-4 rounded-lg border border-gray-200 hover:border-[#002875] hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-gray-900 group-hover:text-[#002875] transition">Penilaian Kinerja</h3>
                <svg class="w-4 h-4 text-gray-400 group-hover:text-[#002875] group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
            <p class="text-xs text-gray-500 mt-1">Evaluasi KPI/OKR periodik dan evaluasi kompetensi tahunan.</p>
        </a>
    </div>
</div>
@endsection