<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\BankAccount;
use App\Models\CompanySetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompanySettingController extends Controller
{
    use LoadsErpData;

    public function hub(): View
    {
        $items = [
            [
                'label' => 'CMS Konten & Blog',
                'desc' => 'Kelola section konten, CTA, dan artikel blog untuk dipakai lewat API.',
                'icon' => 'list',
                'route' => 'cms.index',
            ],
            [
                'label' => 'Company Setting',
                'desc' => 'Identitas perusahaan, logo, NPWP, rekening default, dan tanda tangan.',
                'icon' => 'settings',
                'route' => 'company-settings.index',
            ],
            [
                'label' => 'User Management',
                'desc' => 'Tambah, edit, nonaktifkan user, role, dan reset password.',
                'icon' => 'users',
                'route' => 'user-management.index',
            ],
            [
                'label' => 'Clients',
                'desc' => 'Master client, PIC, email, dan data customer.',
                'icon' => 'master',
                'route' => 'clients.index',
            ],
            [
                'label' => 'Departments',
                'desc' => 'Master departemen untuk HR dan struktur organisasi.',
                'icon' => 'employees',
                'route' => 'departments.index',
            ],
            [
                'label' => 'Job Positions',
                'desc' => 'Master jabatan, posisi kerja, dan struktur role karyawan.',
                'icon' => 'salary',
                'route' => 'job-positions.index',
            ],
            [
                'label' => 'Expense Categories',
                'desc' => 'Kategori biaya untuk reimbursement, cashflow, dan reporting.',
                'icon' => 'cashflow',
                'route' => 'expense-categories.index',
            ],
            [
                'label' => 'Bank Accounts',
                'desc' => 'Kas, bank, rekening perusahaan, dan default payment account.',
                'icon' => 'invoice',
                'route' => 'bank-accounts.index',
            ],
            [
                'label' => 'Audit Log',
                'desc' => 'Riwayat aktivitas input, edit, approve, delete, dan restore data.',
                'icon' => 'audit',
                'route' => 'audit-logs.index',
            ],
            [
                'label' => 'Trash',
                'desc' => 'Data soft delete yang bisa direstore bila diperlukan.',
                'icon' => 'trash',
                'route' => 'trash.index',
            ],
            [
                'label' => 'Backup Database',
                'desc' => 'Download backup database untuk keamanan data demo maupun produksi.',
                'icon' => 'backup',
                'route' => 'backup.download',
            ],
        ];

        return view('erp.admin.index', compact('items'));
    }

    public function index(): View|JsonResponse
    {
        $companySetting = CompanySetting::firstOrCreate(['id' => 1], ['company_name' => 'NEXORA']);
        $bankAccounts   = BankAccount::orderBy('name')->get();
        if ($this->isApi()) {
            return $this->respond(['setting' => $companySetting, 'bank_accounts' => $bankAccounts]);
        }
        return view('erp.company-settings.index', compact('companySetting', 'bankAccounts'));
    }

    public function update(Request $request): RedirectResponse|JsonResponse
    {
        $data = $request->validate([
            'company_name'            => ['required', 'max:255'],
            'address'                 => ['nullable'],
            'email'                   => ['nullable', 'email'],
            'phone'                   => ['nullable', 'max:50'],
            'npwp'                    => ['nullable', 'max:100'],
            'signature_name'          => ['nullable', 'max:255'],
            'default_bank_account_id' => ['nullable', 'exists:bank_accounts,id'],
            'logo'                    => ['nullable', 'file', 'max:2048'],
        ]);

        $setting = CompanySetting::firstOrCreate(['id' => 1], ['company_name' => 'NEXORA']);
        $data['logo_path'] = $this->storeUpload($request, 'logo', 'company') ?? $setting->logo_path;
        unset($data['logo']);

        $setting->update($data);
        $this->audit('updated', $setting, 'Company setting diupdate');

        if ($this->isApi()) {
            return $this->respond($setting, 'Setting perusahaan berhasil disimpan.');
        }
        return back()->with('status', 'Setting perusahaan berhasil disimpan.');
    }
}
