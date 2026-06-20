@extends('layouts.erp', ['activePage' => 'performance-review-edit', 'pageTitle' => 'Edit Review'])

@section('content')
<section class="section">
    <div class="rounded-3xl border border-[#d7e3ef] bg-white p-6 sm:p-8 shadow-lg shadow-[#002F59]/5">
        <div class="mb-8 border-b border-[#d7e3ef] pb-5">
            <h2 class="mb-1">Edit Performance Review</h2>
            <p class="muted">Perbarui penilaian KPI / OKR.</p>
        </div>
        <form method="post" action="{{ route('performance-reviews.update', $review) }}" class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2">
            @csrf @method('put')
            <div class="grid gap-1.5"><label>Karyawan</label>
                <select name="employee_id" required>
                    @foreach($employees as $e)<option value="{{ $e->id }}" @selected($review->employee_id === $e->id)>{{ $e->name }}</option>@endforeach
                </select>
            </div>
            <div class="grid gap-1.5"><label>Period</label><input name="period" value="{{ $review->period }}" required></div>
            <div class="grid gap-1.5"><label>KPI Score (0-100)</label><input name="kpi_score" type="number" min="0" max="100" value="{{ $review->kpi_score }}" required></div>
            <div class="grid gap-1.5"><label>OKR Score (0-100)</label><input name="okr_score" type="number" min="0" max="100" value="{{ $review->okr_score }}" required></div>
            <div class="grid gap-1.5"><label>Rating</label>
                <select name="rating" required>
                    <option value="needs_improvement" @selected($review->rating === 'needs_improvement')>Needs Improvement</option>
                    <option value="meeting" @selected($review->rating === 'meeting')>Meeting</option>
                    <option value="exceeding" @selected($review->rating === 'exceeding')>Exceeding</option>
                    <option value="outstanding" @selected($review->rating === 'outstanding')>Outstanding</option>
                </select>
            </div>
            <div class="grid gap-1.5 md:col-span-2"><label>Notes</label><textarea name="notes" rows="3">{{ $review->notes }}</textarea></div>
            <div class="mt-4 flex items-center gap-3 md:col-span-2">
                <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#0059A7] px-5 py-2.5 text-sm font-black text-white shadow-sm transition hover:bg-[#002F59]">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg> Update Review
                </button>
                <a class="button ghost" href="{{ route('performance-reviews.index') }}">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Kembali
                </a>
            </div>
        </form>
    </div>
</section>
@endsection
