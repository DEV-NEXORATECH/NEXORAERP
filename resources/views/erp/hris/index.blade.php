@extends('layouts.erp', ['activePage' => 'hris', 'pageTitle' => 'HRIS Suite'])

@section('content')
<section class="grid cards section">
    <div class="stat-card"><div class="stat-card-label">Skills</div><div class="stat-card-metric">{{ $skills->total() }}</div><div class="muted">Mapping kompetensi</div></div>
    <div class="stat-card"><div class="stat-card-label">Attendance</div><div class="stat-card-metric">{{ $attendances->total() }}</div><div class="muted">Absensi tercatat</div></div>
    <div class="stat-card"><div class="stat-card-label">Timesheet</div><div class="stat-card-metric">{{ $timesheets->total() }}</div><div class="muted">Jam kerja project</div></div>
    <div class="stat-card"><div class="stat-card-label">Leave Pending</div><div class="stat-card-metric bad">{{ $leaves->getCollection()->where('status', 'pending')->count() }}</div><div class="muted">Butuh approval</div></div>
</section>

<section class="grid two section">
    <div class="card">
        <h2>Skill Mapping</h2>
        <form method="post" action="{{ route('hris.skills.store') }}" class="form-grid">
            @csrf
            <div><label>Employee</label><select name="employee_id" required>@foreach($employees as $employee)<option value="{{ $employee->id }}">{{ $employee->name }}</option>@endforeach</select></div>
            <div><label>Skill</label><input name="skill" required></div>
            <div><label>Level</label><select name="level"><option>basic</option><option>intermediate</option><option>advanced</option><option>expert</option></select></div>
            <div class="full"><label>Notes</label><input name="notes"></div>
            <div class="full"><button>Simpan Skill</button></div>
        </form>
        <table class="section"><thead><tr><th>Skill</th><th>Level</th></tr></thead><tbody>@foreach($skills as $row)<tr><td class="font-bold">{{ $row->skill }}</td><td><span class="badge">{{ $row->level }}</span></td></tr>@endforeach</tbody></table>
        <div class="pager">{{ $skills->links() }}</div>
    </div>

    <div class="card">
        <h2>Attendance</h2>
        <form method="post" action="{{ route('hris.attendances.store') }}" class="form-grid">
            @csrf
            <div><label>Employee</label><select name="employee_id" required>@foreach($employees as $employee)<option value="{{ $employee->id }}">{{ $employee->name }}</option>@endforeach</select></div>
            <div><label>Date</label><input name="work_date" type="date" value="{{ now()->toDateString() }}" required></div>
            <div><label>Check In</label><input name="check_in" type="time"></div>
            <div><label>Check Out</label><input name="check_out" type="time"></div>
            <div><label>Mode</label><select name="work_mode"><option>office</option><option>remote</option><option>hybrid</option></select></div>
            <div><label>Status</label><select name="status"><option>present</option><option>late</option><option>absent</option><option>leave</option></select></div>
            <div class="full"><button>Catat Attendance</button></div>
        </form>
        <table class="section"><thead><tr><th>Date</th><th>Status</th></tr></thead><tbody>@foreach($attendances as $row)<tr><td>{{ $row->work_date }}</td><td><span class="badge badge-{{ $row->status === 'absent' ? 'void' : 'active' }}">{{ $row->status }}</span></td></tr>@endforeach</tbody></table>
        <div class="pager">{{ $attendances->links() }}</div>
    </div>
</section>

<section class="grid two section">
    <div class="card">
        <h2>Timesheet Project</h2>
        <form method="post" action="{{ route('hris.timesheets.store') }}" class="form-grid">
            @csrf
            <div><label>Employee</label><select name="employee_id" required>@foreach($employees as $employee)<option value="{{ $employee->id }}">{{ $employee->name }}</option>@endforeach</select></div>
            <div><label>Project</label><select name="project_id"><option value="">Internal</option>@foreach($projects as $project)<option value="{{ $project->id }}">{{ $project->code }} - {{ $project->name }}</option>@endforeach</select></div>
            <div><label>Date</label><input name="work_date" type="date" value="{{ now()->toDateString() }}" required></div>
            <div><label>Hours</label><input name="hours" type="number" step="0.25" min="0" max="24" required></div>
            <div><label>Status</label><select name="status"><option>submitted</option><option>approved</option><option>rejected</option></select></div>
            <div class="full"><label>Description</label><textarea name="description"></textarea></div>
            <div class="full"><button>Submit Timesheet</button></div>
        </form>
        <table class="section"><thead><tr><th>Date</th><th>Hours</th><th>Status</th></tr></thead><tbody>@foreach($timesheets as $row)<tr><td>{{ $row->work_date }}</td><td>{{ $row->hours }}</td><td><span class="badge badge-{{ $row->status === 'approved' ? 'active' : 'pending' }}">{{ $row->status }}</span></td></tr>@endforeach</tbody></table>
        <div class="pager">{{ $timesheets->links() }}</div>
    </div>

    <div class="card">
        <h2>Leave / Off Request</h2>
        <form method="post" action="{{ route('hris.leaves.store') }}" class="form-grid">
            @csrf
            <div><label>Employee</label><select name="employee_id" required>@foreach($employees as $employee)<option value="{{ $employee->id }}">{{ $employee->name }}</option>@endforeach</select></div>
            <div><label>Type</label><select name="type"><option>annual</option><option>sick</option><option>unpaid</option><option>special</option></select></div>
            <div><label>Start</label><input name="start_date" type="date" required></div>
            <div><label>End</label><input name="end_date" type="date" required></div>
            <div><label>Status</label><select name="status"><option>pending</option><option>approved</option><option>rejected</option></select></div>
            <div class="full"><label>Reason</label><input name="reason"></div>
            <div class="full"><button>Buat Leave Request</button></div>
        </form>
        <table class="section"><thead><tr><th>Period</th><th>Status</th></tr></thead><tbody>@foreach($leaves as $row)<tr><td>{{ $row->start_date }} - {{ $row->end_date }}</td><td><span class="badge badge-{{ $row->status === 'approved' ? 'active' : ($row->status === 'rejected' ? 'void' : 'pending') }}">{{ $row->status }}</span></td></tr>@endforeach</tbody></table>
        <div class="pager">{{ $leaves->links() }}</div>
    </div>
</section>

<section class="grid two section">
    <div class="card">
        <h2>KPI / OKR Review</h2>
        <form method="post" action="{{ route('hris.reviews.store') }}" class="form-grid">
            @csrf
            <div><label>Employee</label><select name="employee_id" required>@foreach($employees as $employee)<option value="{{ $employee->id }}">{{ $employee->name }}</option>@endforeach</select></div>
            <div><label>Period</label><input name="period" value="{{ now()->format('Y-m') }}" required></div>
            <div><label>KPI</label><input name="kpi_score" type="number" min="0" max="100" required></div>
            <div><label>OKR</label><input name="okr_score" type="number" min="0" max="100" required></div>
            <div><label>Rating</label><select name="rating"><option>meeting</option><option>needs_improvement</option><option>exceeding</option><option>outstanding</option></select></div>
            <div class="full"><button>Simpan Review</button></div>
        </form>
        <table class="section"><thead><tr><th>Period</th><th>Score</th><th>Rating</th></tr></thead><tbody>@foreach($reviews as $row)<tr><td>{{ $row->period }}</td><td>{{ $row->kpi_score }}/{{ $row->okr_score }}</td><td><span class="badge">{{ $row->rating }}</span></td></tr>@endforeach</tbody></table>
        <div class="pager">{{ $reviews->links() }}</div>
    </div>

    <div class="card">
        <h2>Payroll Benefit Detail</h2>
        <form method="post" action="{{ route('hris.benefits.store') }}" class="form-grid">
            @csrf
            <div><label>Employee</label><select name="employee_id" required>@foreach($employees as $employee)<option value="{{ $employee->id }}">{{ $employee->name }}</option>@endforeach</select></div>
            <div><label>Salary Slip</label><select name="salary_id"><option value="">No slip</option>@foreach($salaries as $salary)<option value="{{ $salary->id }}">{{ $salary->slip_number }} - {{ $salary->period }}</option>@endforeach</select></div>
            <div><label>Period</label><input name="period" value="{{ now()->format('Y-m') }}" required></div>
            <div><label>BPJS Health</label><input name="bpjs_health" type="number" min="0" value="0"></div>
            <div><label>BPJS TK</label><input name="bpjs_employment" type="number" min="0" value="0"></div>
            <div><label>PPh 21</label><input name="pph21" type="number" min="0" value="0"></div>
            <div><label>Incentive</label><input name="incentive" type="number" min="0" value="0"></div>
            <div><label>Status</label><select name="status"><option>draft</option><option>approved</option><option>paid</option></select></div>
            <div class="full"><button>Simpan Benefit</button></div>
        </form>
        <table class="section"><thead><tr><th>Period</th><th>Total Add/Deduct</th><th>Status</th></tr></thead><tbody>@foreach($benefits as $row)<tr><td>{{ $row->period }}</td><td>{{ $rp($row->incentive - $row->bpjs_health - $row->bpjs_employment - $row->pph21) }}</td><td><span class="badge badge-{{ $row->status === 'paid' ? 'active' : 'pending' }}">{{ $row->status }}</span></td></tr>@endforeach</tbody></table>
        <div class="pager">{{ $benefits->links() }}</div>
    </div>
</section>
@endsection
