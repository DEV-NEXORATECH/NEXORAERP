<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Employee;
use App\Models\GoodsReceipt;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Project;
use App\Models\Proposal;
use App\Models\PurchaseOrder;
use App\Models\Reimbursement;
use App\Models\Salary;
use App\Models\SalesOrder;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowDetailPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_crud_show_detail_pages_render_successfully(): void
    {
        $this->seed();

        $admin = User::where('role', 'admin')->firstOrFail();

        // 1. Invoices
        $invoice = Invoice::first();
        if ($invoice) {
            $resp = $this->actingAs($admin)->get(route('invoices.show', $invoice));
            $resp->assertOk();
            $resp->assertSee($invoice->number);
        }

        // 2. Proposals / Sales
        $proposal = Proposal::first();
        if ($proposal) {
            $resp = $this->actingAs($admin)->get(route('proposals.show', $proposal));
            $resp->assertOk();
            $resp->assertSee($proposal->title);
        }

        // 3. Sales Orders
        $salesOrder = SalesOrder::first();
        if ($salesOrder) {
            $resp = $this->actingAs($admin)->get(route('sales-orders.show', $salesOrder));
            $resp->assertOk();
            $resp->assertSee($salesOrder->number);
        }

        // 4. Purchase Orders
        $purchaseOrder = PurchaseOrder::first();
        if ($purchaseOrder) {
            $resp = $this->actingAs($admin)->get(route('purchase-orders.show', $purchaseOrder));
            $resp->assertOk();
            $resp->assertSee($purchaseOrder->number);
        }

        // 5. Goods Receipts
        $goodsReceipt = GoodsReceipt::first();
        if ($goodsReceipt) {
            $resp = $this->actingAs($admin)->get(route('goods-receipts.show', $goodsReceipt));
            $resp->assertOk();
        }

        // 6. Employees
        $employee = Employee::first();
        if ($employee) {
            $resp = $this->actingAs($admin)->get(route('employees.show', $employee));
            $resp->assertOk();
            $resp->assertSee($employee->name);
        }

        // 7. Clients
        $client = Client::first();
        if ($client) {
            $resp = $this->actingAs($admin)->get(route('clients.show', $client));
            $resp->assertOk();
            $resp->assertSee($client->name);
        }

        // 8. Vendors
        $vendor = Vendor::first();
        if ($vendor) {
            $resp = $this->actingAs($admin)->get(route('vendors.show', $vendor));
            $resp->assertOk();
            $resp->assertSee($vendor->name);
        }

        // 9. Salaries
        $salary = Salary::first();
        if ($salary) {
            $resp = $this->actingAs($admin)->get(route('salaries.show', $salary));
            $resp->assertOk();
        }

        // 10. Products
        $product = Product::first();
        if ($product) {
            $resp = $this->actingAs($admin)->get(route('products.show', $product));
            $resp->assertOk();
            $resp->assertSee($product->code);
        }

        // 11. Reimbursements
        $reimbursement = Reimbursement::first();
        if ($reimbursement) {
            $resp = $this->actingAs($admin)->get(route('reimbursements.show', $reimbursement));
            $resp->assertOk();
        }
    }
}
