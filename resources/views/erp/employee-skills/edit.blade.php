@extends('layouts.erp', ['activePage' => 'employee-skill-edit', 'pageTitle' => 'Edit Skill'])

@section('content')
<section class="section">
    <div class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5">
        <div class="mb-8 border-b border-[#d7e3ef] pb-5">
            <h2 class="mb-1">Edit Skill Karyawan</h2>
            <p class="muted">Perbarui data kompetensi.</p>
        </div>
        <form method="post" action="{{ route('employee-skills.update', $skill) }}" class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2">
            @csrf @method('put')
            <div class="grid gap-1.5"><label>Karyawan</label>
                <select name="employee_id" required>
                    @foreach($employees as $e)<option value="{{ $e->id }}" @selected($skill->employee_id === $e->id)>{{ $e->name }}</option>@endforeach
                </select>
            </div>
            <div class="grid gap-1.5"><label>Skill</label><input name="skill" value="{{ $skill->skill }}" required></div>
            <div class="grid gap-1.5"><label>Level</label>
                <select name="level" required>
                    <option value="basic" @selected($skill->level === 'basic')>Basic</option>
                    <option value="intermediate" @selected($skill->level === 'intermediate')>Intermediate</option>
                    <option value="advanced" @selected($skill->level === 'advanced')>Advanced</option>
                    <option value="expert" @selected($skill->level === 'expert')>Expert</option>
                </select>
            </div>
            <div class="grid gap-1.5 md:col-span-2"><label>Notes</label><textarea name="notes" rows="3">{{ $skill->notes }}</textarea></div>
            <div class="mt-4 flex items-center gap-3 md:col-span-2">
                <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-[#002F59]">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Update Skill
                </button>
                <a class="button ghost" href="{{ route('employee-skills.index') }}">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Kembali
                </a>
            </div>
        </form>
    </div>
</section>
@endsection
