@extends('layouts.erp', ['activePage' => 'approvals', 'pageTitle' => 'Approval & Task'])

@section('content')
<section class="approval-page">
    <div class="approval-hero">
        <div>
            <span>Workflow Control</span>
            <h1>Approval & Task Center</h1>
            <p>Kelola approval penting dan task harian team dari satu control center yang rapi.</p>
        </div>
        <div class="approval-hero-stat">
            <strong>{{ $items->sum('count') }}</strong>
            <small>Pending Approval</small>
        </div>
    </div>

    <div class="approval-grid">
        @foreach($items as $item)
            <a href="{{ route($item['route']) }}" class="approval-card">
                <div class="approval-card-top">
                    <span class="approval-icon">{!! $icon($item['icon']) !!}</span>
                    <span class="approval-chip">{{ $item['count'] > 0 ? 'Needs Action' : 'Clear' }}</span>
                </div>
                <div class="approval-card-body">
                    <div>
                        <h3>{{ $item['label'] }}</h3>
                        <p>{{ $item['desc'] }}</p>
                    </div>
                    <strong>{{ $item['count'] }}</strong>
                </div>
                <div class="approval-card-foot">
                    <span>Buka approval</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M5 12h14"/><path d="m13 6 6 6-6 6"/></svg>
                </div>
            </a>
        @endforeach
    </div>
</section>

<section class="task-panel section">
    <div class="task-panel-head">
        <div>
            <span>Team Board</span>
            <h2>Task Board</h2>
            <p>Tracking pekerjaan seperti Trello, tapi tetap simple untuk workflow ERP.</p>
        </div>
        <button type="button" class="task-create-btn" data-modal-open="task-create-modal">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            New Task
        </button>
    </div>

    <div class="task-board">
        @foreach($taskStatuses as $status => $label)
            <div class="task-column">
                <div class="task-column-head">
                    <h3>{{ $label }}</h3>
                    <span>{{ ($tasks[$status] ?? collect())->count() }}</span>
                </div>

                <div class="task-list">
                    @forelse($tasks[$status] ?? collect() as $task)
                        <article class="task-card priority-{{ $task->priority }}">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <h4>{{ $task->title }}</h4>
                                    <p>{{ $task->description ?: 'Tidak ada deskripsi.' }}</p>
                                </div>
                                <span class="task-priority">{{ $task->priority }}</span>
                            </div>
                            <div class="task-meta">
                                <span>{{ $task->assignee?->name ?? 'Unassigned' }}</span>
                                <span>{{ $task->due_date ? 'Due '.$task->due_date : 'No due date' }}</span>
                            </div>
                            <div class="task-actions">
                                <form method="post" action="{{ route('tasks.status', $task) }}">
                                    @csrf @method('patch')
                                    <select name="status" class="min-h-9 py-1.5 text-xs">
                                        @foreach($taskStatuses as $nextStatus => $nextLabel)
                                            <option value="{{ $nextStatus }}" @selected($task->status === $nextStatus)>{{ $nextLabel }}</option>
                                        @endforeach
                                    </select>
                                    <button class="mini ghost">Move</button>
                                </form>
                                <form method="post" action="{{ route('tasks.destroy', $task) }}">
                                    @csrf @method('delete')
                                    <button class="mini danger">Delete</button>
                                </form>
                            </div>
                        </article>
                    @empty
                        <div class="task-empty">Belum ada task.</div>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
</section>

<div class="ui-modal" id="task-create-modal" aria-hidden="true">
    <div class="ui-modal-backdrop" data-modal-close></div>
    <div class="ui-modal-card" role="dialog" aria-modal="true" aria-labelledby="task-create-title">
        <div class="ui-modal-head">
            <div>
                <span>Create Workflow Task</span>
                <h2 id="task-create-title">New Task</h2>
                <p>Buat task baru, assign PIC, lalu task langsung masuk board.</p>
            </div>
            <button type="button" class="ui-modal-close" data-modal-close aria-label="Close">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
        </div>

        <form method="post" action="{{ route('tasks.store') }}" class="form-grid">
            @csrf
            <div class="full"><label>Judul Task</label><input name="title" placeholder="Contoh: Follow up invoice client A" required></div>
            <div><label>Assign To</label><select name="assigned_to"><option value="">Unassigned</option>@foreach($users as $user)<option value="{{ $user->id }}">{{ $user->name }} - {{ strtoupper($user->role) }}</option>@endforeach</select></div>
            <div><label>Status</label><select name="status">@foreach($taskStatuses as $key => $label)<option value="{{ $key }}">{{ $label }}</option>@endforeach</select></div>
            <div><label>Priority</label><select name="priority"><option>low</option><option selected>medium</option><option>high</option><option>urgent</option></select></div>
            <div><label>Due Date</label><input name="due_date" type="date"></div>
            <div class="full"><label>Description</label><textarea name="description" placeholder="Detail task, checklist singkat, atau catatan untuk PIC."></textarea></div>
            <div class="ui-modal-actions full">
                <button type="button" class="ghost" data-modal-close>Batal</button>
                <button>Create Task</button>
            </div>
        </form>
    </div>
</div>
@endsection
