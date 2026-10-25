@extends('layouts.erp', ['activePage' => 'approvals', 'pageTitle' => 'Approval & Task'])

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex flex-col gap-4 rounded-[12px] border border-[#D9E2EC] bg-white p-5 shadow-xs md:flex-row md:items-center md:justify-between">
        <div class="flex items-center gap-3.5">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-[10px] bg-[#0B2345] text-white shadow-xs">
                {!! $icon('audit') !!}
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-[#0B2345]">Workflow Control</span>
                    <span class="text-[#475569]">·</span>
                    <span class="text-[11px] font-medium text-[#475569]">Central Hub</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-[#0F172A]">Approval & Task Center</h1>
                <p class="mt-0.5 text-xs text-[#475569]">Kelola antrean persetujuan dokumen penting dan task harian tim dalam satu workflow terpadu.</p>
            </div>
        </div>

        <div class="flex items-center gap-3 self-start md:self-auto">
            <div class="inline-flex h-9 items-center gap-2 rounded-[8px] border border-[#D9E2EC] bg-[#F8FAFC] px-3.5 text-xs font-semibold text-[#0F172A]">
                <span class="h-2 w-2 rounded-full {{ $items->sum('count') > 0 ? 'bg-[#DC2626]' : 'bg-[#16A34A]' }}"></span>
                <span class="text-[#475569]">Pending Approval:</span>
                <span class="font-bold {{ $items->sum('count') > 0 ? 'text-[#DC2626]' : 'text-[#0B2345]' }}">{{ $items->sum('count') }}</span>
            </div>
            <button type="button" class="btn btn-primary inline-flex h-9 items-center gap-2 px-3.5 text-xs font-semibold" data-modal-open="task-create-modal">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="h-4 w-4"><path d="M12 5v14M5 12h14"/></svg>
                <span>New Task</span>
            </button>
        </div>
    </div>

    {{-- Approval Cards Grid --}}
    <div>
        <div class="mb-3 flex items-center justify-between">
            <h2 class="text-sm font-bold uppercase tracking-wider text-[#0B2345]">Antrean Persetujuan Dokumen</h2>
            <span class="text-xs text-[#475569]">{{ count($items) }} Kategori Verifikasi</span>
        </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($items as $item)
                <a href="{{ route($item['route']) }}" class="group flex min-h-[160px] flex-col justify-between rounded-[12px] border border-[#D9E2EC] bg-white p-5 no-underline shadow-xs transition-all duration-200 hover:border-[#0B2345] hover:shadow-sm focus:outline-none focus:ring-2 focus:ring-[#0B2345]/20">
                    <div>
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-[10px] bg-[#0B2345] text-white shadow-xs">
                                {!! $icon($item['icon']) !!}
                            </div>
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[11px] font-bold {{ $item['count'] > 0 ? 'bg-[#FEF2F2] text-[#DC2626] border border-[#FCA5A5]' : 'bg-[#F0FDF4] text-[#16A34A] border border-[#86EFAC]' }}">
                                {{ $item['count'] > 0 ? $item['count'] . ' Pending' : 'Clear' }}
                            </span>
                        </div>
                        <div class="mt-3.5">
                            <h3 class="text-sm font-bold text-[#0F172A] group-hover:text-[#0B2345]">{{ $item['label'] }}</h3>
                            <p class="mt-1 text-xs text-[#475569] line-clamp-2">{{ $item['desc'] }}</p>
                        </div>
                    </div>

                    <div class="mt-4 flex items-center justify-between border-t border-[#D9E2EC] pt-3">
                        <span class="text-xs font-semibold text-[#0B2345]">Buka Antrean</span>
                        <span class="text-[#0B2345] transition-transform duration-200 group-hover:translate-x-1">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="h-4 w-4"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    {{-- Task Kanban Board --}}
    <div class="rounded-[12px] border border-[#D9E2EC] bg-white p-5 shadow-xs">
        <div class="mb-5 flex flex-col gap-2 border-b border-[#D9E2EC] pb-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-[#0B2345]">Team Workflow</span>
                    <span class="text-[#475569]">·</span>
                    <span class="text-[11px] font-medium text-[#475569]">Kanban Board</span>
                </div>
                <h2 class="text-lg font-bold text-[#0F172A]">Task Board</h2>
                <p class="text-xs text-[#475569]">Tracking eksekusi tugas operasional harian secara realtime antardepartemen.</p>
            </div>
            <button type="button" class="btn btn-outline inline-flex h-9 items-center gap-2 self-start px-3.5 text-xs font-semibold sm:self-auto" data-modal-open="task-create-modal">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="h-4 w-4"><path d="M12 5v14M5 12h14"/></svg>
                <span>Tambah Task</span>
            </button>
        </div>

        <div class="grid grid-cols-1 gap-4 overflow-x-auto pb-2 md:grid-cols-2 lg:grid-cols-4">
            @foreach($taskStatuses as $status => $label)
                <div class="flex min-w-[260px] flex-col rounded-[10px] border border-[#D9E2EC] bg-[#F8FAFC] p-3.5">
                    <div class="mb-3 flex items-center justify-between border-b border-[#D9E2EC] pb-2.5">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-[#0F172A]">{{ $label }}</h3>
                        <span class="inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-[#0B2345] px-1.5 text-[10px] font-bold text-white">
                            {{ ($tasks[$status] ?? collect())->count() }}
                        </span>
                    </div>

                    <div class="flex flex-col gap-3">
                        @forelse($tasks[$status] ?? collect() as $task)
                            <article class="flex flex-col justify-between rounded-[8px] border border-[#D9E2EC] bg-white p-3.5 shadow-xs transition hover:border-[#0B2345] hover:shadow-sm">
                                <div>
                                    <div class="flex items-start justify-between gap-2">
                                        <h4 class="text-xs font-bold text-[#0F172A] line-clamp-2">{{ $task->title }}</h4>
                                        @php
                                            $priorityClass = match(strtolower($task->priority)) {
                                                'urgent', 'high' => 'bg-[#FEF2F2] text-[#DC2626] border border-[#FCA5A5]',
                                                'medium' => 'bg-[#FFFBEB] text-[#D97706] border border-[#FDE68A]',
                                                default => 'bg-[#F0FDF4] text-[#16A34A] border border-[#86EFAC]'
                                            };
                                        @endphp
                                        <span class="inline-flex shrink-0 items-center rounded-full px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider {{ $priorityClass }}">
                                            {{ $task->priority }}
                                        </span>
                                    </div>
                                    <p class="mt-1.5 text-[11px] leading-relaxed text-[#475569] line-clamp-2">{{ $task->description ?: 'Tidak ada deskripsi tambahan.' }}</p>
                                </div>

                                <div class="mt-3 border-t border-[#F1F5F9] pt-2.5">
                                    <div class="flex items-center justify-between text-[10px] font-medium text-[#475569]">
                                        <span class="inline-flex items-center gap-1 font-semibold text-[#0F172A]">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-3 w-3 text-[#0B2345]"><circle cx="12" cy="7" r="4"/><path d="M6 21v-2a6 6 0 0 1 12 0v2"/></svg>
                                            {{ $task->assignee?->name ?? 'Unassigned' }}
                                        </span>
                                        <span>{{ $task->due_date ? date('d M Y', strtotime($task->due_date)) : 'No deadline' }}</span>
                                    </div>

                                    <div class="mt-2.5 flex items-center justify-between gap-2 pt-2 border-t border-dashed border-[#E2E8F0]">
                                        <form method="post" action="{{ route('tasks.status', $task) }}" class="flex items-center gap-1.5" data-confirmed="1">
                                            @csrf @method('patch')
                                            <select name="status" class="h-7 rounded-[6px] border border-[#D9E2EC] bg-white px-2 text-[10px] font-medium text-[#0F172A] focus:border-[#0B2345] focus:outline-none">
                                                @foreach($taskStatuses as $nextStatus => $nextLabel)
                                                    <option value="{{ $nextStatus }}" @selected($task->status === $nextStatus)>{{ $nextLabel }}</option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="inline-flex h-7 items-center rounded-[6px] border border-[#D9E2EC] bg-white px-2 text-[10px] font-bold text-[#0B2345] hover:bg-[#F8FAFC]">Pindah</button>
                                        </form>
                                        <form method="post" action="{{ route('tasks.destroy', $task) }}">
                                            @csrf @method('delete')
                                            <button type="submit" class="inline-flex h-7 items-center rounded-[6px] border border-[#FECACA] bg-[#FEF2F2] px-2 text-[10px] font-bold text-[#DC2626] hover:bg-[#FEE2E2]" title="Hapus Task">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </article>
                        @empty
                            <div class="rounded-[8px] border border-dashed border-[#D9E2EC] bg-white p-5 text-center text-xs text-[#94A3B8]">
                                Tidak ada task di kolom ini
                            </div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Create Task Modal --}}
<div class="ui-modal" id="task-create-modal" aria-hidden="true">
    <div class="ui-modal-backdrop" data-modal-close></div>
    <div class="ui-modal-card max-w-lg rounded-[12px] border border-[#D9E2EC] bg-white p-6 shadow-xl" role="dialog" aria-modal="true" aria-labelledby="task-create-title">
        <div class="flex items-center justify-between border-b border-[#D9E2EC] pb-4">
            <div>
                <span class="text-[11px] font-semibold uppercase tracking-wider text-[#0B2345]">Workflow Management</span>
                <h2 id="task-create-title" class="text-lg font-bold text-[#0F172A]">Buat Task Baru</h2>
                <p class="text-xs text-[#475569]">Tetapkan delegasi tugas, prioritas, dan tenggat waktu.</p>
            </div>
            <button type="button" class="inline-flex h-8 w-8 items-center justify-center rounded-[8px] border border-[#D9E2EC] text-[#475569] hover:bg-[#F8FAFC] hover:text-[#0F172A]" data-modal-close aria-label="Close">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="h-4 w-4"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
        </div>

        <form method="post" action="{{ route('tasks.store') }}" class="mt-5 space-y-4">
            @csrf
            <div>
                <label class="mb-1 block text-xs font-semibold text-[#0F172A]">Judul Task <span class="text-[#DC2626]">*</span></label>
                <input name="title" class="field-input w-full" placeholder="Contoh: Review invoice vendor PT Bahari" required>
            </div>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1 block text-xs font-semibold text-[#0F172A]">Assign To</label>
                    <select name="assigned_to" class="field-input w-full">
                        <option value="">Unassigned</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ strtoupper($user->role) }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold text-[#0F172A]">Status Kolom</label>
                    <select name="status" class="field-input w-full">
                        @foreach($taskStatuses as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1 block text-xs font-semibold text-[#0F172A]">Prioritas</label>
                    <select name="priority" class="field-input w-full">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold text-[#0F172A]">Tenggat Waktu (Due Date)</label>
                    <input name="due_date" type="date" class="field-input w-full">
                </div>
            </div>
            <div>
                <label class="mb-1 block text-xs font-semibold text-[#0F172A]">Deskripsi & Catatan Task</label>
                <textarea name="description" rows="3" class="field-input w-full" placeholder="Detail deskripsi, instruksi atau checklist pekerjaan..."></textarea>
            </div>
            <div class="flex items-center justify-end gap-3 border-t border-[#D9E2EC] pt-4">
                <button type="button" class="btn btn-outline h-9 px-4 text-xs font-semibold" data-modal-close>Batal</button>
                <button type="submit" class="btn btn-primary h-9 px-4 text-xs font-semibold">Simpan Task</button>
            </div>
        </form>
    </div>
</div>
@endsection
