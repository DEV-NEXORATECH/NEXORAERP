<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminController extends Controller
{
    use LoadsErpData;

    public function companyPage(): View
    {
        $companySetting = \App\Models\CompanySetting::firstOrCreate(['id' => 1], ['company_name' => 'NEXORA']);
        $bankAccounts   = \App\Models\BankAccount::orderBy('name')->get();
        return view('erp.admin.company', compact('companySetting', 'bankAccounts'));
    }

    public function usersPage(): View
    {
        $users = User::orderBy('name')->paginate(15)->withQueryString();
        return view('erp.admin.users', compact('users'));
    }

    public function mastersPage(): View
    {
        $clients           = \App\Models\Client::orderBy('name')->paginate(8, ['*'], 'clients_page')->withQueryString();
        $departments       = \App\Models\Department::orderBy('name')->paginate(8, ['*'], 'departments_page')->withQueryString();
        $jobPositions      = \App\Models\JobPosition::orderBy('name')->paginate(8, ['*'], 'positions_page')->withQueryString();
        $expenseCategories = \App\Models\ExpenseCategory::orderBy('name')->paginate(8, ['*'], 'categories_page')->withQueryString();
        $bankAccounts      = \App\Models\BankAccount::orderBy('name')->paginate(8, ['*'], 'banks_page')->withQueryString();
        return view('erp.admin.masters', compact('clients', 'departments', 'jobPositions', 'expenseCategories', 'bankAccounts'));
    }

    public function trashPage(): View
    {
        $trash = [
            'projects'  => \App\Models\Project::onlyTrashed()->latest()->paginate(8, ['*'], 'projects_page')->withQueryString(),
            'proposals' => \App\Models\Proposal::onlyTrashed()->latest()->paginate(8, ['*'], 'proposals_page')->withQueryString(),
            'invoices'  => \App\Models\Invoice::onlyTrashed()->latest()->paginate(8, ['*'], 'invoices_page')->withQueryString(),
            'users'     => User::onlyTrashed()->latest()->paginate(8, ['*'], 'users_page')->withQueryString(),
        ];
        $hasTrash = collect($trash)->sum(fn ($items) => $items->total()) > 0;
        return view('erp.admin.trash', compact('trash', 'hasTrash'));
    }

    public function auditPage(): View
    {
        $auditLogs = \App\Models\AuditLog::with('user:id,name')->latest()->paginate(50);
        return view('erp.admin.audit', compact('auditLogs'));
    }

    public function updateCompanySetting(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'company_name'           => ['required', 'max:255'],
            'address'                => ['nullable'],
            'email'                  => ['nullable', 'email'],
            'phone'                  => ['nullable', 'max:50'],
            'npwp'                   => ['nullable', 'max:100'],
            'signature_name'         => ['nullable', 'max:255'],
            'default_bank_account_id' => ['nullable', 'exists:bank_accounts,id'],
            'logo'                   => ['nullable', 'file', 'max:2048'],
        ]);

        $setting = \App\Models\CompanySetting::firstOrCreate(['id' => 1], ['company_name' => 'NEXORA']);
        $data['logo_path'] = $this->storeUpload($request, 'logo', 'company') ?? $setting->logo_path;
        unset($data['logo']);

        $setting->update($data);
        $this->audit('updated', $setting, 'Company setting diupdate');

        return back()->with('status', 'Setting perusahaan berhasil disimpan.');
    }

    public function storeUser(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'     => ['required', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'role'     => ['required', Rule::in(['admin', 'hr', 'finance', 'sales'])],
            'password' => ['nullable', 'min:8'],
        ]);

        $plain = $data['password'] ?: 'Nexora-' . random_int(100000, 999999);
        $user  = User::create([
            'name'                => $data['name'],
            'email'               => $data['email'],
            'role'                => $data['role'],
            'password'            => Hash::make($plain),
            'must_change_password' => true,
            'is_active'           => true,
        ]);
        $this->audit('created', $user, 'User dibuat');

        return back()->with('temporary_password', 'Password user baru: ' . $plain);
    }

    public function updateUser(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name'                => ['required', 'max:255'],
            'email'               => ['required', 'email', Rule::unique('users', 'email')->ignore($user)],
            'role'                => ['required', Rule::in(['admin', 'hr', 'finance', 'sales'])],
            'is_active'           => ['nullable', 'boolean'],
            'must_change_password' => ['nullable', 'boolean'],
        ]);

        $old = $user->toArray();
        $user->update($data + ['is_active' => false, 'must_change_password' => false]);
        $this->audit('updated', $user, 'User diupdate', $old, $user->fresh()->toArray());

        return back()->with('status', 'User berhasil diupdate.');
    }

    public function resetUserPassword(User $user): RedirectResponse
    {
        $plain = 'Nexora-' . random_int(100000, 999999);
        $user->forceFill([
            'password'             => Hash::make($plain),
            'must_change_password' => true,
            'failed_login_attempts' => 0,
            'locked_until'         => null,
        ])->save();
        $this->audit('password_reset', $user, 'Password user direset');

        return back()->with('temporary_password', 'Password sementara: ' . $plain);
    }

    public function destroyUser(User $user): RedirectResponse
    {
        abort_if($user->id === auth()->id(), 422, 'Tidak bisa hapus user yang sedang login.');
        $this->audit('deleted', $user, 'User dihapus', $user->toArray());
        $user->delete();

        return back()->with('status', 'User berhasil dinonaktifkan/dihapus.');
    }

    public function storeMaster(Request $request, string $type): RedirectResponse
    {
        $models = $this->masterModels();
        abort_unless(isset($models[$type]), 404);

        $rules = ['name' => ['required', 'max:255', Rule::unique($type, 'name')]];
        $extra = [];

        if ($type === 'clients') {
            $extra = $request->validate($rules + [
                'contact_name' => ['nullable', 'max:255'],
                'email'        => ['nullable', 'email'],
                'phone'        => ['nullable', 'max:50'],
                'address'      => ['nullable'],
            ]);
        } elseif ($type === 'bank_accounts') {
            $extra = $request->validate($rules + [
                'bank_name'       => ['nullable', 'max:100'],
                'account_number'  => ['nullable', 'max:100'],
                'opening_balance' => ['nullable', 'numeric', 'min:0'],
            ]);
        } elseif ($type === 'expense_categories') {
            $extra = $request->validate($rules + ['type' => ['required', 'max:50']]);
        } else {
            $extra = $request->validate($rules);
        }

        $model = $models[$type]::create($extra);
        $this->audit('created', $model, 'Master data ' . $type . ' dibuat');

        return back()->with('status', 'Master data berhasil ditambahkan.');
    }

    public function destroyMaster(string $type, int $id): RedirectResponse
    {
        $models = $this->masterModels();
        abort_unless(isset($models[$type]), 404);

        $model = $models[$type]::findOrFail($id);
        $this->audit('deleted', $model, 'Master data ' . $type . ' dihapus', $model->toArray());
        $model->delete();

        return back()->with('status', 'Master data berhasil dihapus.');
    }

    public function restoreTrash(string $type, int $id): RedirectResponse
    {
        $models = [
            'projects'  => \App\Models\Project::class,
            'proposals' => \App\Models\Proposal::class,
            'invoices'  => \App\Models\Invoice::class,
            'users'     => User::class,
        ];

        abort_unless(isset($models[$type]), 404);
        $model = $models[$type]::onlyTrashed()->findOrFail($id);
        $model->restore();
        $this->audit('restored', $model, 'Data ' . $type . ' direstore');

        return back()->with('status', 'Data berhasil direstore.');
    }

    public function backupDatabase()
    {
        $path = database_path('database.sqlite');
        abort_unless(file_exists($path), 404);

        return response()->download($path, 'nexora-backup-' . now()->format('Ymd-His') . '.sqlite');
    }
}
