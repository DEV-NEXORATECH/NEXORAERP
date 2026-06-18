<?php

namespace Database\Seeders;

use App\Models\BankAccount;
use App\Models\Cashflow;
use App\Models\Client;
use App\Models\CompanySetting;
use App\Models\Department;
use App\Models\Employee;
use App\Models\ExpenseCategory;
use App\Models\Invoice;
use App\Models\JobPosition;
use App\Models\Payment;
use App\Models\Project;
use App\Models\Proposal;
use App\Models\Reimbursement;
use App\Models\Salary;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        foreach ([
            ['Admin NEXORA', 'admin@nexora.test', 'admin'],
            ['HR NEXORA', 'hr@nexora.test', 'hr'],
            ['Finance NEXORA', 'finance@nexora.test', 'finance'],
            ['Sales NEXORA', 'sales@nexora.test', 'sales'],
        ] as [$name, $email, $role]) {
            User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'role' => $role,
                    'password' => Hash::make('password'),
                    'is_active' => true,
                    'must_change_password' => false,
                    'password_changed_at' => now(),
                ]
            );
        }

        $internal = Client::updateOrCreate(['name' => 'NEXORA'], ['contact_name' => 'Management', 'email' => 'hello@nexora.test']);
        $clientA = Client::updateOrCreate(['name' => 'Client A'], ['contact_name' => 'Budi Santoso', 'email' => 'budi@client-a.test']);

        $it = Department::updateOrCreate(['name' => 'IT']);
        $finance = Department::updateOrCreate(['name' => 'Finance']);
        $developer = JobPosition::updateOrCreate(['name' => 'Full-stack Developer']);
        JobPosition::updateOrCreate(['name' => 'Project Manager']);
        ExpenseCategory::updateOrCreate(['name' => 'Cloud Infrastructure'], ['type' => 'cloud']);
        ExpenseCategory::updateOrCreate(['name' => 'Software Tools'], ['type' => 'tools']);
        ExpenseCategory::updateOrCreate(['name' => 'Vendor/Subcontractor'], ['type' => 'vendor']);
        ExpenseCategory::updateOrCreate(['name' => 'Transport'], ['type' => 'reimbursement']);
        $bca = BankAccount::updateOrCreate(['name' => 'Kas Utama NEXORA'], ['bank_name' => 'BCA', 'account_number' => '1234567890', 'opening_balance' => 10000000]);
        BankAccount::updateOrCreate(['name' => 'Kas Operasional'], ['bank_name' => 'Mandiri', 'account_number' => '99887766', 'opening_balance' => 5000000]);
        CompanySetting::updateOrCreate(['id' => 1], [
            'company_name' => 'NEXORA',
            'address' => 'Jakarta, Indonesia',
            'email' => 'hello@nexora.test',
            'phone' => '+62 812 0000 0000',
            'npwp' => '00.000.000.0-000.000',
            'signature_name' => 'NEXORA Finance',
            'default_bank_account_id' => $bca->id,
        ]);

        $erp = Project::updateOrCreate(['code' => 'NX-ERP-001'], [
            'name' => 'ERP Internal NEXORA',
            'client' => $internal->name,
            'client_id' => $internal->id,
            'status' => 'active',
            'start_date' => now()->startOfMonth()->toDateString(),
            'end_date' => now()->addMonths(3)->toDateString(),
            'budget' => 45000000,
            'contract_value' => 75000000,
        ]);

        $mobile = Project::updateOrCreate(['code' => 'NX-MOB-002'], [
            'name' => 'Mobile App Client A',
            'client' => $clientA->name,
            'client_id' => $clientA->id,
            'status' => 'planning',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addMonths(2)->toDateString(),
            'budget' => 30000000,
            'contract_value' => 55000000,
        ]);

        $proposal = Proposal::updateOrCreate(['project_id' => $erp->id, 'title' => 'Proposal ERP NEXORA'], [
            'number' => 'PRP-NX-202606-0001',
            'status' => 'approved',
            'amount' => 75000000,
            'scope' => 'Dashboard, project finance, proposal, payroll, reimbursement, cashflow, invoice, payment.',
            'valid_until' => now()->addMonth()->toDateString(),
        ]);

        $employee = Employee::updateOrCreate(['name' => 'Raka Developer'], [
            'position' => $developer->name,
            'job_position_id' => $developer->id,
            'department' => $it->name,
            'department_id' => $it->id,
            'base_salary' => 8500000,
        ]);

        Employee::updateOrCreate(['name' => 'Maya Finance'], [
            'position' => 'Finance Officer',
            'department' => $finance->name,
            'department_id' => $finance->id,
            'base_salary' => 7000000,
        ]);

        $invoice = Invoice::updateOrCreate(['number' => 'INV/NX/2026/001'], [
            'project_id' => $erp->id,
            'proposal_id' => $proposal->id,
            'status' => 'partial',
            'issue_date' => now()->subDays(7)->toDateString(),
            'due_date' => now()->addDays(14)->toDateString(),
            'amount' => 75000000,
            'paid_amount' => 25000000,
            'tax_rate' => 11,
            'notes' => 'DP project ERP internal NEXORA.',
            'payment_terms' => 'Pembayaran maksimal 14 hari setelah invoice diterbitkan.',
        ]);

        $income = Cashflow::updateOrCreate(['project_id' => $erp->id, 'description' => 'Payment invoice INV/NX/2026/001'], [
            'type' => 'income',
            'category' => 'Invoice Payment',
            'bank_account_id' => $bca->id,
            'cost_type' => 'client_payment',
            'amount' => 25000000,
            'transaction_date' => now()->subDays(5)->toDateString(),
        ]);

        Payment::updateOrCreate(['invoice_id' => $invoice->id, 'reference' => 'TRX-DP-001'], [
            'bank_account_id' => $bca->id,
            'cashflow_id' => $income->id,
            'amount' => 25000000,
            'payment_date' => now()->subDays(5)->toDateString(),
            'method' => 'transfer',
        ]);

        Cashflow::updateOrCreate(['project_id' => $mobile->id, 'description' => 'Cloud setup'], [
            'type' => 'expense',
            'category' => 'Cloud Infrastructure',
            'expense_category_id' => ExpenseCategory::where('name', 'Cloud Infrastructure')->value('id'),
            'bank_account_id' => $bca->id,
            'cost_type' => 'cloud',
            'vendor' => 'AWS',
            'amount' => 3500000,
            'transaction_date' => now()->subDays(2)->toDateString(),
        ]);

        Salary::updateOrCreate(['employee_id' => $employee->id, 'period' => now()->format('Y-m')], [
            'slip_number' => 'SLP-NX-202606-0001',
            'project_id' => $erp->id,
            'base_salary' => 8500000,
            'allowance' => 500000,
            'deduction' => 0,
            'net_salary' => 9000000,
            'status' => 'approved',
        ]);

        Reimbursement::updateOrCreate(['employee_id' => $employee->id, 'category' => 'Transport', 'expense_date' => now()->subDay()->toDateString()], [
            'project_id' => $erp->id,
            'description' => 'Meeting client dan koordinasi deployment.',
            'amount' => 250000,
            'status' => 'pending',
        ]);
    }
}
