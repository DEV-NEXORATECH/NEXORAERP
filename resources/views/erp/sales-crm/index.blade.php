@extends('layouts.erp', ['activePage' => 'sales-crm', 'pageTitle' => 'Sales CRM'])

@section('content')
@php
    $pipelineValue = $leads->getCollection()->sum('value');
    $orderValue = $orders->getCollection()->sum('amount');
@endphp

<section class="grid cards section">
    <div class="stat-card"><div class="stat-card-label">Inquiry</div><div class="stat-card-metric">{{ $inquiries->total() }}</div><div class="muted">Masuk dari channel sales</div></div>
    <div class="stat-card"><div class="stat-card-label">Pipeline Value</div><div class="stat-card-metric good">{{ $rp($pipelineValue) }}</div><div class="muted">Lead aktif di halaman ini</div></div>
    <div class="stat-card"><div class="stat-card-label">Sales Order</div><div class="stat-card-metric">{{ $orders->total() }}</div><div class="muted">{{ $rp($orderValue) }}</div></div>
    <div class="stat-card"><div class="stat-card-label">Contracts</div><div class="stat-card-metric">{{ $contracts->total() }}</div><div class="muted">Lifecycle client</div></div>
</section>

<section class="grid two section">
    <div class="card">
        <h2>Inquiry Management</h2>
        <form method="post" action="{{ route('sales-crm.inquiries.store') }}" class="form-grid">
            @csrf
            <div><label>Company</label><input name="company_name" required></div>
            <div><label>PIC</label><input name="contact_name"></div>
            <div><label>Email</label><input name="email" type="email"></div>
            <div><label>Phone</label><input name="phone"></div>
            <div><label>Source</label><input name="source" placeholder="Website / referral"></div>
            <div><label>Owner</label><select name="owner_id"><option value="">Unassigned</option>@foreach($users as $user)<option value="{{ $user->id }}">{{ $user->name }}</option>@endforeach</select></div>
            <div><label>Status</label><select name="status"><option>new</option><option>contacted</option><option>qualified</option><option>lost</option></select></div>
            <div><label>Need</label><input name="need" placeholder="ERP / website / support"></div>
            <div class="full"><label>Notes</label><textarea name="notes"></textarea></div>
            <div class="full"><button>Tambah Inquiry</button></div>
        </form>
        <div class="wide section">
            <table><thead><tr><th>Company</th><th>Need</th><th>Status</th></tr></thead><tbody>
                @forelse($inquiries as $row)
                    <tr><td class="font-bold">{{ $row->company_name }}<br><span class="muted">{{ $row->contact_name }}</span></td><td>{{ $row->need ?? '-' }}</td><td><span class="badge badge-{{ $row->status === 'lost' ? 'void' : ($row->status === 'new' ? 'pending' : 'active') }}">{{ $row->status }}</span></td></tr>
                @empty
                    <tr><td colspan="3" class="text-center muted">Belum ada inquiry.</td></tr>
                @endforelse
            </tbody></table>
            <div class="pager">{{ $inquiries->links() }}</div>
        </div>
    </div>

    <div class="card">
        <h2>Lead & Pipeline</h2>
        <form method="post" action="{{ route('sales-crm.leads.store') }}" class="form-grid">
            @csrf
            <div><label>Title</label><input name="title" required></div>
            <div><label>Client</label><select name="client_id"><option value="">Prospect</option>@foreach($clients as $client)<option value="{{ $client->id }}">{{ $client->name }}</option>@endforeach</select></div>
            <div><label>Owner</label><select name="owner_id"><option value="">Unassigned</option>@foreach($users as $user)<option value="{{ $user->id }}">{{ $user->name }}</option>@endforeach</select></div>
            <div><label>Stage</label><select name="stage"><option>qualified</option><option>proposal</option><option>negotiation</option><option>won</option><option>lost</option></select></div>
            <div><label>Value</label><input name="value" type="number" min="0" required></div>
            <div><label>Probability %</label><input name="probability" type="number" min="0" max="100" value="30" required></div>
            <div><label>Expected Close</label><input name="expected_close_date" type="date"></div>
            <div class="full"><label>Notes</label><input name="notes"></div>
            <div class="full"><button>Simpan Lead</button></div>
        </form>
        <div class="wide section">
            <table><thead><tr><th>Lead</th><th>Stage</th><th class="text-right">Value</th></tr></thead><tbody>
                @forelse($leads as $row)
                    <tr><td class="font-bold">{{ $row->title }}<br><span class="muted">{{ $row->probability }}% probability</span></td><td><span class="badge badge-{{ $row->stage === 'lost' ? 'void' : ($row->stage === 'won' ? 'active' : 'pending') }}">{{ $row->stage }}</span></td><td class="text-right">{{ $rp($row->value) }}</td></tr>
                @empty
                    <tr><td colspan="3" class="text-center muted">Belum ada lead.</td></tr>
                @endforelse
            </tbody></table>
            <div class="pager">{{ $leads->links() }}</div>
        </div>
    </div>
</section>

<section class="grid three section">
    <div class="card">
        <h2>Sales Order</h2>
        <form method="post" action="{{ route('sales-crm.orders.store') }}" class="form-grid">
            @csrf
            <div class="full"><label>Proposal</label><select name="proposal_id"><option value="">No proposal</option>@foreach($proposals as $proposal)<option value="{{ $proposal->id }}">{{ $proposal->number }} - {{ $proposal->title }}</option>@endforeach</select></div>
            <div><label>Title</label><input name="title" required></div>
            <div><label>Amount</label><input name="amount" type="number" min="0" required></div>
            <div><label>Order Date</label><input name="order_date" type="date" value="{{ now()->toDateString() }}" required></div>
            <div><label>Status</label><select name="status"><option>draft</option><option>confirmed</option><option>delivered</option><option>cancelled</option></select></div>
            <div class="full"><button>Buat SO</button></div>
        </form>
        <table class="section"><thead><tr><th>SO</th><th>Status</th></tr></thead><tbody>@foreach($orders as $row)<tr><td>{{ $row->number }}<br><span class="muted">{{ $rp($row->amount) }}</span></td><td><span class="badge badge-{{ $row->status === 'cancelled' ? 'void' : 'active' }}">{{ $row->status }}</span></td></tr>@endforeach</tbody></table>
        <div class="pager">{{ $orders->links() }}</div>
    </div>

    <div class="card">
        <h2>Target & Commission</h2>
        <form method="post" action="{{ route('sales-crm.targets.store') }}" class="form-grid">
            @csrf
            <div><label>Sales</label><select name="user_id"><option value="">Team</option>@foreach($users as $user)<option value="{{ $user->id }}">{{ $user->name }}</option>@endforeach</select></div>
            <div><label>Period</label><input name="period" value="{{ now()->format('Y-m') }}" required></div>
            <div><label>Target</label><input name="target_amount" type="number" min="0" required></div>
            <div><label>Achieved</label><input name="achieved_amount" type="number" min="0" value="0"></div>
            <div class="full"><button>Simpan Target</button></div>
        </form>
        <form method="post" action="{{ route('sales-crm.commissions.store') }}" class="form-grid section">
            @csrf
            <div><label>Sales</label><select name="user_id"><option value="">Team</option>@foreach($users as $user)<option value="{{ $user->id }}">{{ $user->name }}</option>@endforeach</select></div>
            <div><label>Period</label><input name="period" value="{{ now()->format('Y-m') }}" required></div>
            <div><label>Base Amount</label><input name="base_amount" type="number" min="0" required></div>
            <div><label>Rate %</label><input name="rate" type="number" min="0" value="2" required></div>
            <div><label>Status</label><select name="status"><option>draft</option><option>approved</option><option>paid</option></select></div>
            <div class="full"><button>Hitung Komisi</button></div>
        </form>
    </div>

    <div class="card">
        <h2>Contract Lifecycle</h2>
        <form method="post" action="{{ route('sales-crm.contracts.store') }}" class="form-grid">
            @csrf
            <div class="full"><label>Client</label><select name="client_id"><option value="">Manual</option>@foreach($clients as $client)<option value="{{ $client->id }}">{{ $client->name }}</option>@endforeach</select></div>
            <div><label>Title</label><input name="title" required></div>
            <div><label>Amount</label><input name="amount" type="number" min="0" required></div>
            <div><label>Start</label><input name="start_date" type="date"></div>
            <div><label>End</label><input name="end_date" type="date"></div>
            <div><label>Reminder</label><input name="reminder_date" type="date"></div>
            <div><label>Status</label><select name="status"><option>active</option><option>draft</option><option>expired</option><option>terminated</option></select></div>
            <div class="full"><button>Simpan Contract</button></div>
        </form>
        <table class="section"><thead><tr><th>Contract</th><th>Status</th></tr></thead><tbody>@foreach($contracts as $row)<tr><td>{{ $row->contract_number }}<br><span class="muted">{{ $row->title }}</span></td><td><span class="badge badge-{{ $row->status === 'active' ? 'active' : 'pending' }}">{{ $row->status }}</span></td></tr>@endforeach</tbody></table>
        <div class="pager">{{ $contracts->links() }}</div>
    </div>
</section>
@endsection
