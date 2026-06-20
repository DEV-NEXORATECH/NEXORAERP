@extends('layouts.erp', ['activePage' => 'masters', 'pageTitle' => 'Master Data'])

@section('content')
<section class="grid three section" id="master-data">
    <div class="card">
        <h2>Client</h2>
        <form method="post" action="{{ route('masters.store', 'clients') }}" class="grid">
            @csrf
            <input name="name" placeholder="Nama client" required>
            <input name="contact_name" placeholder="PIC">
            <input name="email" type="email" placeholder="Email">
            <button>Tambah Client</button>
        </form>
        <details><summary>Client List ({{ $clients->total() }})</summary>
            <table>
                @foreach($clients as $client)
                <tr>
                    <td>{{ $client->name }}<br><span class="muted">{{ $client->contact_name }}</span></td>
                    <td><form class="inline" method="post" action="{{ route('masters.destroy', ['clients', $client->id]) }}">@csrf @method('delete')<button class="mini danger">Delete</button></form></td>
                </tr>
                @endforeach
            </table>
            <div class="pager">{{ $clients->links() }}</div>
        </details>
    </div>

    <div class="card">
        <h2>HR Master</h2>
        <form method="post" action="{{ route('masters.store', 'departments') }}" class="toolbar">@csrf<input name="name" placeholder="Department" required><button class="mini">Add</button></form>
        <form method="post" action="{{ route('masters.store', 'job_positions') }}" class="toolbar" style="margin-top:8px">@csrf<input name="name" placeholder="Job position" required><button class="mini">Add</button></form>
        <details><summary>Department & Position</summary>
            <table>
                @foreach($departments as $department)
                <tr><td>Dept: {{ $department->name }}</td><td><form class="inline" method="post" action="{{ route('masters.destroy', ['departments', $department->id]) }}">@csrf @method('delete')<button class="mini danger">Delete</button></form></td></tr>
                @endforeach
                @foreach($jobPositions as $position)
                <tr><td>Pos: {{ $position->name }}</td><td><form class="inline" method="post" action="{{ route('masters.destroy', ['job_positions', $position->id]) }}">@csrf @method('delete')<button class="mini danger">Delete</button></form></td></tr>
                @endforeach
            </table>
            <div class="pager">
                <span>Departments</span>{{ $departments->links() }}
            </div>
            <div class="pager">
                <span>Positions</span>{{ $jobPositions->links() }}
            </div>
        </details>
    </div>

    <div class="card">
        <h2>Finance Master</h2>
        <form method="post" action="{{ route('masters.store', 'expense_categories') }}" class="toolbar">
            @csrf
            <input name="name" placeholder="Expense category" required>
            <input name="type" placeholder="cloud/tools/vendor" required>
            <button class="mini">Add</button>
        </form>
        <form method="post" action="{{ route('masters.store', 'bank_accounts') }}" class="grid" style="margin-top:8px">
            @csrf
            <input name="name" placeholder="Nama kas/bank" required>
            <input name="bank_name" placeholder="Bank">
            <input name="account_number" placeholder="No rekening">
            <input name="opening_balance" type="number" value="0">
            <button class="mini">Add Bank</button>
        </form>
        <details><summary>Bank & Expense Category</summary>
            <table>
                @foreach($bankAccounts as $bank)
                <tr><td>{{ $bank->name }}<br><span class="muted">{{ $bank->bank_name }} {{ $bank->account_number }}</span></td><td><form class="inline" method="post" action="{{ route('masters.destroy', ['bank_accounts', $bank->id]) }}">@csrf @method('delete')<button class="mini danger">Delete</button></form></td></tr>
                @endforeach
                @foreach($expenseCategories as $category)
                <tr><td>{{ $category->name }}<br><span class="muted">{{ $category->type }}</span></td><td><form class="inline" method="post" action="{{ route('masters.destroy', ['expense_categories', $category->id]) }}">@csrf @method('delete')<button class="mini danger">Delete</button></form></td></tr>
                @endforeach
            </table>
            <div class="pager">
                <span>Bank Accounts</span>{{ $bankAccounts->links() }}
            </div>
            <div class="pager">
                <span>Expense Categories</span>{{ $expenseCategories->links() }}
            </div>
        </details>
    </div>
</section>
@endsection
