<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    use LoadsErpData;

    public function store(Request $request): RedirectResponse
    {
        $task = Task::create($request->validate([
            'title' => ['required', 'max:255'],
            'description' => ['nullable'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'status' => ['required', Rule::in(['todo', 'in_progress', 'review', 'done'])],
            'priority' => ['required', Rule::in(['low', 'medium', 'high', 'urgent'])],
            'due_date' => ['nullable', 'date'],
        ]) + ['created_by' => auth()->id()]);

        $this->audit('created', $task, 'Task dibuat');

        return back()->with('status', 'Task berhasil dibuat.');
    }

    public function updateStatus(Request $request, Task $task): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['todo', 'in_progress', 'review', 'done'])],
        ]);

        $old = $task->toArray();
        $task->update($data);
        $this->audit('status_updated', $task, 'Status task menjadi '.$task->status, $old, $task->fresh()->toArray());

        return back()->with('status', 'Status task berhasil diupdate.');
    }

    public function destroy(Task $task): RedirectResponse
    {
        $this->audit('deleted', $task, 'Task dihapus', $task->toArray());
        $task->delete();

        return back()->with('status', 'Task berhasil dihapus.');
    }
}
