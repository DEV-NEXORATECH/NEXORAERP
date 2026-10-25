<?php

namespace Database\Seeders;

use App\Models\BankAccount;
use App\Models\BankReconciliationItem;
use App\Models\Budget;
use App\Models\ChartAccount;
use App\Models\ClientContract;
use App\Models\CurrencyRate;
use App\Models\CurrencyVariance;
use App\Models\Employee;
use App\Models\EmployeeSkill;
use App\Models\FixedAsset;
use App\Models\GoodsReceipt;
use App\Models\Invoice;
use App\Models\JournalEntry;
use App\Models\JournalLine;
use App\Models\LeaveRequest;
use App\Models\Payment;
use App\Models\PaymentReminder;
use App\Models\PayrollBenefit;
use App\Models\PerformanceReview;
use App\Models\ProcurementContract;
use App\Models\Product;
use App\Models\Project;
use App\Models\PurchaseMatch;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\RecurringBilling;
use App\Models\RevenueSchedule;
use App\Models\SalesCommission;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\SalesTarget;
use App\Models\Salary;
use App\Models\StockAdjustment;
use App\Models\TaxRule;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorBill;
use App\Models\VendorPayment;
use Illuminate\Database\Seeder;

class CompleteFeatureSeeder extends Seeder
{
    public function run(): void
    {
        $companyId = (int) \App\Models\Company::where('code', 'NX-001')->value('id');
        $user = User::where('email', 'raul@nexora.test')->first() ?? User::first();
        $employee = Employee::where('name', $user?->name)->first() ?? Employee::first();
        $project = Project::first(); $client = \App\Models\Client::first(); $vendor = Vendor::first();
        $bank = BankAccount::first(); $invoice = Invoice::first(); $bill = VendorBill::first();
        $product = Product::first(); $order = SalesOrder::first(); $purchase = PurchaseOrder::first();
        $receipt = GoodsReceipt::first(); $salary = Salary::first();

        foreach ([
            ['1100','Cash','asset','current_asset'], ['1200','Accounts Receivable','asset','current_asset'],
            ['2100','Accounts Payable','liability','current_liability'], ['4000','Revenue','revenue','revenue'],
            ['5000','Operating Expense','expense','expense'],
        ] as [$code,$name,$type,$group]) ChartAccount::updateOrCreate(['code'=>$code], ['company_id'=>$companyId,'name'=>$name,'type'=>$type,'account_group'=>$group,'is_active'=>true]);
        foreach ([['PPN 11%','ppn',11,'sales'],['PPh 23%','pph23',2,'purchase']] as [$name,$type,$rate,$direction]) TaxRule::updateOrCreate(['name'=>$name], ['company_id'=>$companyId,'name'=>$name,'tax_type'=>$type,'rate'=>$rate,'direction'=>$direction,'is_active'=>true]);

        if ($employee) {
            foreach ([['Laravel','advanced'],['Financial Analysis','intermediate']] as [$skill,$level]) EmployeeSkill::updateOrCreate(['employee_id'=>$employee->id,'skill'=>$skill], ['company_id'=>$companyId, 'skill'=>$skill, 'level'=>$level, 'notes'=>'Demo skill']);
            PerformanceReview::updateOrCreate(['employee_id'=>$employee->id,'period'=>now()->format('Y-m')], ['company_id'=>$companyId,'kpi_score'=>88,'okr_score'=>84,'rating'=>'Very Good','notes'=>'Demo performance review.']);
        }
        if ($user) {
            SalesTarget::updateOrCreate(['user_id'=>$user->id,'period'=>now()->format('Y-m')], ['company_id'=>$companyId,'target_amount'=>500000000,'achieved_amount'=>325000000]);
            SalesCommission::updateOrCreate(['user_id'=>$user->id,'period'=>now()->format('Y-m')], ['company_id'=>$companyId,'base_amount'=>325000000,'rate'=>2.5,'commission_amount'=>8125000,'status'=>'approved']);
        }
        if ($client) ClientContract::updateOrCreate(['contract_number'=>'CTR-DEMO-001'], ['company_id'=>$companyId,'client_id'=>$client->id,'title'=>'Managed ERP Support','start_date'=>now()->subMonth()->toDateString(),'end_date'=>now()->addYear()->toDateString(),'reminder_date'=>now()->addMonths(11)->toDateString(),'amount'=>180000000,'status'=>'active','notes'=>'Demo contract.']);
        if ($vendor) ProcurementContract::updateOrCreate(['contract_number'=>'VCTR-DEMO-001'], ['company_id'=>$companyId,'vendor_id'=>$vendor->id,'title'=>'Cloud Infrastructure Agreement','start_date'=>now()->subMonth()->toDateString(),'end_date'=>now()->addYear()->toDateString(),'renewal_reminder_date'=>now()->addMonths(11)->toDateString(),'amount'=>240000000,'status'=>'active']);
        FixedAsset::updateOrCreate(['asset_code'=>'FA-DEMO-001'], ['company_id'=>$companyId,'name'=>'Demo Server','category'=>'IT Equipment','purchase_date'=>now()->subMonths(6)->toDateString(),'purchase_cost'=>120000000,'residual_value'=>10000000,'useful_life_years'=>4,'depreciation_method'=>'straight_line','monthly_depreciation'=>2291666.67,'accumulated_depreciation'=>13750000,'book_value'=>106250000,'status'=>'active','notes'=>'Demo fixed asset.']);
        $rate = CurrencyRate::updateOrCreate(['from_currency'=>'USD','to_currency'=>'IDR','rate_date'=>today()->toDateString()], ['company_id'=>$companyId,'rate'=>16500,'source'=>'Demo market','currency'=>'USD','rate_to_idr'=>16500]);
        CurrencyVariance::updateOrCreate(['rate_id'=>$rate->id,'period'=>now()->format('Y-m')], ['company_id'=>$companyId,'variance_percent'=>1.25,'variance_amount'=>1250000,'notes'=>'Demo FX variance.']);
        if ($project) RevenueSchedule::updateOrCreate(['project_id'=>$project->id,'schedule_date'=>now()->addDays(30)->toDateString()], ['company_id'=>$companyId,'amount'=>75000000,'status'=>'scheduled','notes'=>'Demo milestone billing.','description'=>'Demo milestone billing.','recognition_month'=>now()->format('Y-m')]);
        if ($bank) BankReconciliationItem::updateOrCreate(['bank_account_id'=>$bank->id,'reference'=>'BANK-DEMO-001'], ['company_id'=>$companyId,'statement_date'=>today()->toDateString(),'description'=>'Demo bank transaction','amount'=>25000000,'type'=>'credit','reconciled'=>false]);
        if ($invoice && $bank) { Payment::updateOrCreate(['reference'=>'PAY-DEMO-001'], ['company_id'=>$companyId,'invoice_id'=>$invoice->id,'bank_account_id'=>$bank->id,'amount'=>25000000,'payment_date'=>today()->toDateString(),'method'=>'bank_transfer','currency_id'=>null,'exchange_rate'=>1]); PaymentReminder::updateOrCreate(['invoice_id'=>$invoice->id,'reminder_date'=>today()->toDateString()], ['company_id'=>$companyId,'channel'=>'email','status'=>'pending','message'=>'Demo payment reminder.']); }
        if ($bill && $bank) VendorPayment::updateOrCreate(['reference'=>'VPAY-DEMO-001'], ['company_id'=>$companyId,'vendor_bill_id'=>$bill->id,'bank_account_id'=>$bank->id,'amount'=>10000000,'payment_date'=>today()->toDateString(),'exchange_rate'=>1]);
        if ($project) Budget::updateOrCreate(['project_id'=>$project->id,'period'=>now()->format('Y-m')], ['company_id'=>$companyId,'chart_account_id'=>ChartAccount::where('code','5000')->value('id'),'budget_amount'=>200000000,'forecast_amount'=>185000000,'notes'=>'Demo operating budget.']);
        if ($order && $product) SalesOrderItem::updateOrCreate(['sales_order_id'=>$order->id,'product_id'=>$product->id], ['company_id'=>$companyId,'quantity'=>5,'unit_price'=>$product->price,'amount'=>$product->price*5,'notes'=>'Demo order item.']);
        if ($purchase && $product) PurchaseOrderItem::updateOrCreate(['purchase_order_id'=>$purchase->id,'product_id'=>$product->id], ['company_id'=>$companyId,'quantity'=>10,'unit_price'=>$product->cost,'amount'=>$product->cost*10,'notes'=>'Demo purchase item.']);
        if ($purchase && $receipt) PurchaseMatch::updateOrCreate(['purchase_order_id'=>$purchase->id,'goods_receipt_id'=>$receipt->id], ['company_id'=>$companyId,'variance_amount'=>0,'match_status'=>'matched','matched_at'=>now(),'notes'=>'Demo 3-way match.']);
        if ($product) { $warehouse = \App\Models\Warehouse::first(); $location = \App\Models\StockLocation::where('warehouse_id',$warehouse?->id)->first(); StockAdjustment::updateOrCreate(['number'=>'ADJ-DEMO-001'], ['company_id'=>$companyId,'product_id'=>$product->id,'warehouse_id'=>$warehouse?->id,'location_id'=>$location?->id,'expected_quantity'=>10,'actual_quantity'=>9,'reason'=>'Demo stock count','adjustment_date'=>today()->toDateString(),'status'=>'approved','notes'=>'Demo adjustment.']); }
        if ($client) RecurringBilling::updateOrCreate(['name'=>'Monthly ERP Support'], ['company_id'=>$companyId,'client_id'=>$client->id,'frequency'=>'monthly','amount'=>15000000,'tax_rate'=>11,'next_invoice_date'=>now()->addMonth()->toDateString(),'end_date'=>now()->addYear()->toDateString(),'status'=>'active']);
    }
}
