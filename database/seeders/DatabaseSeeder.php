<?php

namespace Database\Seeders;

use App\Models\BankAccount;
use App\Models\Company;
use App\Models\CompanySetting;
use App\Models\Department;
use App\Models\ExpenseCategory;
use App\Models\JobPosition;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $company = Company::firstOrCreate(
            ['code' => 'NX-001'],
            ['name' => 'NEXORA', 'access_type' => 'internal', 'email' => 'hello@nexora.test', 'is_active' => true]
        );
        $company->update(['access_type' => 'internal']);

        foreach ([
            ['CLIENT-001', 'Demo Client Company', 'client@nexora.test'],
            ['ASTRA-001', 'Astra Logistics', 'pic@astra.test'],
            ['SAVANA-001', 'Savana Finance', 'pic@savana.test'],
            ['KIRANA-001', 'Kirana Group', 'pic@kirana.test'],
        ] as [$code, $name, $email]) {
            Company::firstOrCreate(
                ['code' => $code],
                ['name' => $name, 'access_type' => 'external', 'email' => $email, 'is_active' => true]
            );
        }

        // ── Users ────────────────────────────────────────────────────────
        foreach ([
            ['Raul',  'raul@nexora.test',  'admin'],
            ['Fanni', 'fanni@nexora.test', 'admin'],
            ['Rei',   'rei@nexora.test',   'admin'],
        ] as [$name, $email, $role]) {
            User::updateOrCreate(
                ['email' => $email],
                [
                    'company_id'            => $company->id,
                    'name'                 => $name,
                    'role'                 => $role,
                    'password'             => Hash::make('Nexora2026!'),
                    'is_active'            => true,
                    'must_change_password' => false,
                    'password_changed_at'  => now(),
                ]
            );
        }

        // ── Company setting ──────────────────────────────────────────────
        $bca = BankAccount::updateOrCreate(
            ['name' => 'Kas Utama'],
            [
                'bank_name'       => 'BCA',
                'account_number'  => '',
                'opening_balance' => 0,
            ]
        );

        CompanySetting::updateOrCreate(
            ['id' => 1],
            [
                'company_name'           => 'NEXORA',
                'address'                => 'Jakarta, Indonesia',
                'email'                  => 'hello@nexora.test',
                'phone'                  => '',
                'npwp'                   => '',
                'signature_name'         => 'NEXORA Finance',
                'default_bank_account_id' => $bca->id,
            ]
        );

        // ── Master data ──────────────────────────────────────────────────
        foreach (['IT', 'Finance', 'HR', 'Operations', 'Sales'] as $dept) {
            Department::updateOrCreate(['name' => $dept]);
        }

        foreach (['Full-stack Developer', 'Project Manager', 'Finance Officer', 'HR Officer', 'Business Analyst'] as $pos) {
            JobPosition::updateOrCreate(['name' => $pos]);
        }

        foreach ([
            ['Cloud Infrastructure', 'cloud'],
            ['Software Tools',       'tools'],
            ['Vendor/Subcontractor', 'vendor'],
            ['Transport',            'reimbursement'],
            ['Office Supplies',      'reimbursement'],
            ['Marketing',            'marketing'],
        ] as [$name, $type]) {
            ExpenseCategory::updateOrCreate(['name' => $name], ['type' => $type]);
        }
    }
}
