<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\BankAccount;
use App\Models\BlogPost;
use App\Models\Cashflow;
use App\Models\Client;
use App\Models\CmsSection;
use App\Models\Department;
use App\Models\Employee;
use App\Models\ExpenseCategory;
use App\Models\GoodsReceipt;
use App\Models\Invoice;
use App\Models\JobPosition;
use App\Models\LeaveRequest;
use App\Models\Project;
use App\Models\Proposal;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequisition;
use App\Models\Reimbursement;
use App\Models\Salary;
use App\Models\SalesInquiry;
use App\Models\SalesLead;
use App\Models\SalesOrder;
use App\Models\Task;
use App\Models\Timesheet;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorBill;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DemoCommandCenterSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $companyId = DB::table('companies')->where('code', 'NX-001')->value('id');
            $admin = User::where('email', 'raul@nexora.test')->first() ?? User::first();

            $bank = BankAccount::updateOrCreate(
                ['name' => 'Kas Utama'],
                ['company_id' => $companyId, 'bank_name' => 'BCA', 'account_number' => '0888123456', 'opening_balance' => 250000000]
            );

            $departments = collect(['IT', 'Finance', 'HR', 'Operations', 'Sales'])
                ->mapWithKeys(fn ($name) => [$name => Department::updateOrCreate(['name' => $name], ['company_id' => $companyId])]);

            $positions = collect(['Full-stack Developer', 'Project Manager', 'Finance Officer', 'HR Officer', 'Business Analyst', 'Sales Manager'])
                ->mapWithKeys(fn ($name) => [$name => JobPosition::updateOrCreate(['name' => $name], ['company_id' => $companyId])]);

            $categories = collect([
                ['Cloud Infrastructure', 'cloud'],
                ['Software Tools', 'tools'],
                ['Vendor/Subcontractor', 'vendor'],
                ['Transport', 'reimbursement'],
                ['Office Supplies', 'operational'],
                ['Marketing', 'marketing'],
            ])->mapWithKeys(fn ($row) => [$row[0] => ExpenseCategory::updateOrCreate(['name' => $row[0]], ['company_id' => $companyId, 'type' => $row[1]])]);

            $clients = collect([
                ['Astra Logistics', 'Maya Putri', 'maya@astra.test'],
                ['Savana Finance', 'Bima Hartono', 'bima@savana.test'],
                ['Kirana Group', 'Rani Prasetya', 'rani@kirana.test'],
                ['Metro Health', 'Dimas Anggara', 'dimas@metro.test'],
                ['Nusantara Retail', 'Intan Sari', 'intan@nusantara.test'],
            ])->mapWithKeys(fn ($row) => [$row[0] => Client::updateOrCreate(
                ['name' => $row[0]],
                ['company_id' => $companyId, 'contact_name' => $row[1], 'email' => $row[2], 'phone' => '081200000' . random_int(10, 99), 'address' => 'Jakarta, Indonesia']
            )]);

            $employees = collect([
                ['Raul', 'Project Manager', 'Operations', 20000000],
                ['Nadia Putri', 'Full-stack Developer', 'IT', 14500000],
                ['Arman Yusuf', 'Project Manager', 'Operations', 18500000],
                ['Salsa Rahma', 'Finance Officer', 'Finance', 12500000],
                ['Tio Wicaksono', 'Business Analyst', 'Operations', 13500000],
                ['Mira Oktaviani', 'HR Officer', 'HR', 11000000],
                ['Fajar Nugroho', 'Sales Manager', 'Sales', 15500000],
                ['Dewi Lestari', 'Full-stack Developer', 'IT', 14000000],
                ['Rizky Pratama', 'Business Analyst', 'Sales', 13000000],
            ])->map(function ($row) use ($companyId, $departments, $positions) {
                return Employee::updateOrCreate(
                    ['name' => $row[0]],
                    [
                        'company_id' => $companyId,
                        'position' => $row[1],
                        'job_position_id' => $positions[$row[1]]?->id,
                        'department' => $row[2],
                        'department_id' => $departments[$row[2]]?->id,
                        'base_salary' => $row[3],
                    ]
                );
            });

            $projects = collect([
                ['NX-ERP-ASTRA', 'ERP Rollout Astra Logistics', 'Astra Logistics', 'active', 780000000, 590000000],
                ['NX-FIN-SAVANA', 'Finance Automation Savana', 'Savana Finance', 'active', 520000000, 380000000],
                ['NX-PORT-KIRANA', 'Customer Portal Kirana', 'Kirana Group', 'planning', 340000000, 250000000],
                ['NX-HRIS-METRO', 'HRIS Metro Health', 'Metro Health', 'on_hold', 280000000, 220000000],
                ['NX-RETAIL-NUSA', 'Retail Analytics Nusantara', 'Nusantara Retail', 'completed', 450000000, 310000000],
            ])->mapWithKeys(function ($row) use ($companyId, $clients) {
                return [$row[0] => Project::updateOrCreate(
                    ['code' => $row[0]],
                    [
                        'company_id' => $companyId,
                        'name' => $row[1],
                        'client' => $row[2],
                        'client_id' => $clients[$row[2]]?->id,
                        'status' => $row[3],
                        'start_date' => now()->subMonths(random_int(2, 8))->toDateString(),
                        'end_date' => now()->addMonths(random_int(1, 6))->toDateString(),
                        'contract_value' => $row[4],
                        'budget' => $row[5],
                    ]
                )];
            });

            foreach ($projects as $project) {
                Proposal::updateOrCreate(
                    ['title' => 'Proposal ' . $project->name],
                    [
                        'company_id' => $companyId,
                        'project_id' => $project->id,
                        'number' => 'PROP-' . $project->code,
                        'status' => $project->status === 'planning' ? 'sent' : ($project->status === 'on_hold' ? 'draft' : 'approved'),
                        'amount' => $project->contract_value,
                        'scope' => 'Implementation, integration, user training, and post go-live support.',
                        'valid_until' => now()->addDays(30)->toDateString(),
                    ]
                );
            }

            foreach (range(5, 0) as $i) {
                $month = now()->subMonths($i);
                $factor = 6 - $i;
                foreach ($projects->values() as $index => $project) {
                    $income = 42000000 + ($factor * 8500000) + ($index * 5500000);
                    $expense = 21000000 + ($factor * 4200000) + ($index * 3000000);

                    Cashflow::updateOrCreate(
                        ['project_id' => $project->id, 'description' => 'Milestone billing ' . $month->format('M Y')],
                        ['company_id' => $companyId, 'type' => 'income', 'category' => 'Project Revenue', 'bank_account_id' => $bank->id, 'cost_type' => 'revenue', 'amount' => $income, 'transaction_date' => $month->copy()->day(10)->toDateString()]
                    );

                    Cashflow::updateOrCreate(
                        ['project_id' => $project->id, 'description' => 'Delivery cost ' . $month->format('M Y')],
                        ['company_id' => $companyId, 'type' => 'expense', 'category' => 'Project Delivery', 'expense_category_id' => $categories['Vendor/Subcontractor']?->id, 'bank_account_id' => $bank->id, 'cost_type' => $index % 2 === 0 ? 'vendor' : 'operational', 'vendor' => $index % 2 === 0 ? 'Partner Tech Studio' : null, 'amount' => $expense, 'transaction_date' => $month->copy()->day(20)->toDateString()]
                    );
                }
            }

            foreach ($projects->values() as $index => $project) {
                $amount = (float) $project->contract_value * 0.35;
                $paid = $index % 3 === 0 ? $amount : ($index % 3 === 1 ? $amount * 0.45 : 0);
                Invoice::updateOrCreate(
                    ['number' => 'INV-' . now()->format('Ym') . '-' . str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT)],
                    ['company_id' => $companyId, 'project_id' => $project->id, 'proposal_id' => $project->proposals()->first()?->id, 'status' => $paid >= $amount ? 'paid' : ($paid > 0 ? 'partial' : 'sent'), 'issue_date' => now()->subDays(25 + ($index * 12))->toDateString(), 'due_date' => now()->addDays(20 - ($index * 24))->toDateString(), 'amount' => $amount, 'paid_amount' => $paid, 'tax_rate' => 11, 'notes' => 'Demo invoice for dashboard aging.', 'payment_terms' => 'Net 30']
                );
            }

            $vendors = collect([
                ['Partner Tech Studio', 'Implementation'],
                ['Cloud Prima Indonesia', 'Cloud'],
                ['Sarana Office Supply', 'Office'],
            ])->mapWithKeys(fn ($row) => [$row[0] => Vendor::updateOrCreate(
                ['name' => $row[0]],
                ['company_id' => $companyId, 'category' => $row[1], 'contact_name' => 'PIC ' . $row[0], 'email' => Str::slug($row[0]) . '@vendor.test', 'phone' => '082100000' . random_int(10, 99), 'payment_terms' => 'Net 14', 'status' => 'active']
            )]);

            foreach ($vendors->values() as $index => $vendor) {
                $billAmount = 35000000 + ($index * 18000000);
                VendorBill::updateOrCreate(
                    ['bill_number' => 'VB-' . now()->format('Ym') . '-' . str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT)],
                    ['company_id' => $companyId, 'project_id' => $projects->values()[$index]?->id, 'bank_account_id' => $bank->id, 'vendor_name' => $vendor->name, 'bill_date' => now()->subDays(35 + ($index * 18))->toDateString(), 'due_date' => now()->addDays(8 - ($index * 28))->toDateString(), 'amount' => $billAmount, 'paid_amount' => $index === 0 ? 0 : $billAmount * 0.35, 'tax_rate' => 11, 'status' => $index === 2 ? 'partial' : 'unpaid', 'notes' => 'Demo vendor bill for AP aging.']
                );
            }

            foreach ($employees as $index => $employee) {
                Salary::updateOrCreate(
                    ['employee_id' => $employee->id, 'period' => now()->format('Y-m')],
                    ['company_id' => $companyId, 'project_id' => $projects->values()[$index % $projects->count()]->id, 'base_salary' => $employee->base_salary, 'allowance' => 1000000, 'deduction' => 250000, 'net_salary' => $employee->base_salary + 750000, 'status' => $index < 5 ? 'paid' : 'approved']
                );

                Attendance::updateOrCreate(
                    ['employee_id' => $employee->id, 'work_date' => today()->toDateString()],
                    ['company_id' => $companyId, 'check_in' => $index === 1 ? '09:21:00' : '08:45:00', 'check_out' => null, 'work_mode' => $index % 3 === 0 ? 'remote' : 'office', 'status' => $index === 1 ? 'late' : ($index === 4 ? 'leave' : 'present'), 'notes' => 'Demo attendance.']
                );

                Timesheet::updateOrCreate(
                    ['employee_id' => $employee->id, 'work_date' => today()->toDateString()],
                    ['company_id' => $companyId, 'project_id' => $projects->values()[$index % $projects->count()]->id, 'hours' => $index < 6 ? 7.5 : 0, 'status' => $index < 6 ? 'submitted' : 'draft', 'description' => 'Sprint delivery and client coordination.']
                );
            }

            foreach ($employees->take(4) as $index => $employee) {
                Reimbursement::updateOrCreate(
                    ['employee_id' => $employee->id, 'description' => 'Client visit transport ' . ($index + 1)],
                    ['company_id' => $companyId, 'project_id' => $projects->values()[$index]->id, 'category' => 'Transport', 'amount' => 450000 + ($index * 150000), 'status' => $index < 2 ? 'pending' : 'approved', 'expense_date' => now()->subDays(3 + $index)->toDateString()]
                );
            }

            LeaveRequest::updateOrCreate(
                ['employee_id' => $employees[4]->id, 'start_date' => now()->addDays(3)->toDateString()],
                ['company_id' => $companyId, 'type' => 'annual', 'end_date' => now()->addDays(4)->toDateString(), 'status' => 'pending', 'reason' => 'Family event.']
            );

            foreach (range(1, 12) as $i) {
                $inquiry = SalesInquiry::updateOrCreate(
                    ['company_name' => 'Prospect Company ' . $i],
                    ['company_id' => $companyId, 'contact_name' => 'Prospect PIC ' . $i, 'email' => 'prospect' . $i . '@example.test', 'phone' => '083100000' . random_int(10, 99), 'source' => $i % 2 === 0 ? 'Website' : 'Referral', 'need' => 'ERP implementation', 'status' => $i <= 8 ? 'qualified' : 'new', 'owner_id' => $admin?->id, 'notes' => 'Demo funnel inquiry.']
                );

                if ($i <= 8) {
                    SalesLead::updateOrCreate(
                        ['title' => 'ERP Opportunity ' . $i],
                        ['company_id' => $companyId, 'sales_inquiry_id' => $inquiry->id, 'client_id' => $clients->values()[($i - 1) % $clients->count()]->id, 'owner_id' => $admin?->id, 'stage' => $i <= 4 ? 'proposal' : 'qualified', 'value' => 180000000 + ($i * 25000000), 'probability' => $i <= 4 ? 65 : 35, 'expected_close_date' => now()->addDays(15 + $i)->toDateString(), 'notes' => 'Demo funnel lead.']
                    );
                }
            }

            foreach ($projects->take(3)->values() as $index => $project) {
                SalesOrder::updateOrCreate(
                    ['number' => 'SO-' . now()->format('Ym') . '-' . str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT)],
                    ['company_id' => $companyId, 'proposal_id' => $project->proposals()->first()?->id, 'client_id' => $project->client_id, 'title' => 'Sales Order ' . $project->name, 'amount' => $project->contract_value, 'order_date' => now()->subDays(14 - $index)->toDateString(), 'status' => $index === 0 ? 'approved' : 'draft', 'notes' => 'Demo sales order.']
                );
            }

            foreach (range(1, 5) as $i) {
                $requisition = PurchaseRequisition::updateOrCreate(
                    ['number' => 'PR-' . now()->format('Ym') . '-' . str_pad((string) $i, 3, '0', STR_PAD_LEFT)],
                    ['company_id' => $companyId, 'requester_id' => $admin?->id, 'department_id' => $departments[$i % 2 === 0 ? 'IT' : 'Operations']?->id, 'title' => 'Procurement request ' . $i, 'amount' => 18000000 + ($i * 7000000), 'required_date' => now()->addDays(7 + $i)->toDateString(), 'status' => $i <= 3 ? 'approved' : 'pending', 'reason' => 'Project delivery requirement.']
                );

                $order = PurchaseOrder::updateOrCreate(
                    ['number' => 'PO-' . now()->format('Ym') . '-' . str_pad((string) $i, 3, '0', STR_PAD_LEFT)],
                    ['company_id' => $companyId, 'purchase_requisition_id' => $requisition->id, 'vendor_id' => $vendors->values()[($i - 1) % $vendors->count()]->id, 'order_date' => now()->subDays(10 - $i)->toDateString(), 'amount' => $requisition->amount, 'status' => $i <= 3 ? 'ordered' : 'draft', 'notes' => 'Demo procurement pipeline.']
                );

                if ($i <= 3) {
                    GoodsReceipt::updateOrCreate(
                        ['purchase_order_id' => $order->id],
                        ['company_id' => $companyId, 'receipt_date' => now()->subDays(2 + $i)->toDateString(), 'status' => $i === 3 ? 'partial' : 'received', 'notes' => 'Demo goods receipt.']
                    );
                }
            }

            foreach ([
                ['Approve overdue vendor bill', 'urgent', 'todo', now()->subDay()->toDateString()],
                ['Review proposal Savana Finance', 'high', 'review', now()->addDay()->toDateString()],
                ['Submit missing timesheet', 'medium', 'todo', today()->toDateString()],
                ['Follow up invoice partial payment', 'high', 'in_progress', now()->addDays(2)->toDateString()],
                ['Prepare go-live checklist', 'medium', 'in_progress', now()->addDays(5)->toDateString()],
            ] as $row) {
                Task::updateOrCreate(
                    ['title' => $row[0]],
                    ['company_id' => $companyId, 'created_by' => $admin?->id, 'assigned_to' => $admin?->id, 'description' => 'Demo command center task.', 'priority' => $row[1], 'status' => $row[2], 'due_date' => $row[3]]
                );
            }

            foreach ([
                ['hero', 'Hero / Header', 'ERP Command Center for Growing Teams', 'Finance, project, HR, sales, procurement, and CMS in one operating system.', 'Turn fragmented spreadsheets into one reliable workspace.', ['Live finance visibility', 'Approval workflow', 'Public CMS API']],
                ['about', 'About / Deskripsi', 'Built for operators who need clarity fast', 'Nexora keeps every process measurable, auditable, and connected.', 'Every module feeds the command center so leaders can see what needs attention in seconds.', ['Centralized data', 'Role based access', 'Audit trail']],
                ['services', 'Services / Fitur', 'Connected modules', 'Everything your internal team needs to run the business.', 'Finance, CRM, procurement, HRIS, project tracking, reports, and CMS are managed from one ERP.', ['Finance Suite', 'Sales CRM', 'Project Management', 'Procurement', 'HRIS', 'CMS Blog']],
                ['why', 'Keunggulan', 'Why teams choose Nexora', 'Less manual follow-up, more control.', 'The dashboard highlights overdue invoices, blocked procurement, task backlog, and fresh CMS content.', ['Aging analysis', 'Sales funnel', 'Action table', 'CMS freshness']],
                ['contact', 'Contact / CTA', 'Ready to run cleaner operations?', 'Book a Nexora demo and see the flow end to end.', 'Email hello@nexora.test or open the ERP login to explore demo data.', ['Demo ready', 'API ready']],
            ] as $index => $row) {
                CmsSection::updateOrCreate(
                    ['key' => $row[0]],
                    ['label' => $row[1], 'title' => $row[2], 'subtitle' => $row[3], 'content' => $row[4], 'items' => $row[5], 'button_label' => $row[0] === 'hero' ? 'Open ERP' : null, 'button_url' => $row[0] === 'hero' ? '/login' : null, 'sort_order' => ($index + 1) * 10, 'is_visible' => true]
                );
            }

            foreach ([
                ['How ERP dashboards reduce operational blind spots', 'A practical look at finance aging, task backlog, and sales funnel visibility.'],
                ['Why company profile content should be API driven', 'CMS sections and blog content should be updated once and consumed everywhere.'],
                ['Reading cashflow health in under five seconds', 'A simple dashboard pattern for income, expense, net trend, and aging pressure.'],
            ] as $index => $row) {
                BlogPost::updateOrCreate(
                    ['slug' => Str::slug($row[0])],
                    ['user_id' => $admin?->id, 'title' => $row[0], 'excerpt' => $row[1], 'body' => $row[1] . "\n\nThis demo article is seeded for the Nexora command center and public CMS preview.", 'status' => $index === 2 ? 'draft' : 'published', 'published_at' => $index === 2 ? null : now()->subDays(12 - ($index * 5))]
                );
            }
        });
    }
}
