<?php
namespace Tests\Feature;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class PdfSmokeTest extends TestCase {
    use RefreshDatabase;
    public function test_pdf_routes_render(): void {
        $this->seed();
        $admin = User::where('role','admin')->firstOrFail();
        $models = [
            'sales-orders.pdf' => \App\Models\SalesOrder::first(),
            'client-contracts.pdf' => \App\Models\ClientContract::first(),
            'proposals.pdf' => \App\Models\Proposal::first(),
            'leave-requests.pdf' => \App\Models\LeaveRequest::first(),
            'salaries.pdf' => \App\Models\Salary::first(),
            'reimbursements.pdf' => \App\Models\Reimbursement::first(),
            'cashflows.pdf' => \App\Models\Cashflow::first(),
            'invoices.pdf' => \App\Models\Invoice::first(),
            'vendor-bills.pdf' => \App\Models\VendorBill::first(),
            'vendor-payments.pdf' => \App\Models\VendorPayment::first(),
            'purchase-requisitions.pdf' => \App\Models\PurchaseRequisition::first(),
            'purchase-orders.pdf' => \App\Models\PurchaseOrder::first(),
            'goods-receipts.pdf' => \App\Models\GoodsReceipt::first(),
            'procurement-contracts.pdf' => \App\Models\ProcurementContract::first(),
        ];
        $failures = [];
        foreach ($models as $route => $model) {
            if (!$model) continue;
            $resp = $this->actingAs($admin)->get(route($route, $model));
            echo $route . ' => ' . $resp->getStatusCode() . "\n";
            if ($resp->getStatusCode() >= 500) {
                $failures[] = $route . ' => ' . $resp->getStatusCode();
            }
        }
        $this->assertSame([], $failures);
    }
}