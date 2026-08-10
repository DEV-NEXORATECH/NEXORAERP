<?php

namespace App\Http\Controllers;

use App\Http\Traits\AppliesListFilters;
use App\Http\Traits\LoadsErpData;
use App\Models\Product;
use App\Models\StockAdjustment;
use App\Models\StockLocation;
use App\Models\StockMovement;
use App\Models\Warehouse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StockAdjustmentController extends Controller
{
    use LoadsErpData, AppliesListFilters;

    public function index(Request $request): View
    {
        $adjustments = $this->applyListFilters(
            StockAdjustment::with(['product:id,code,name', 'warehouse:id,code,name'])->latest('adjustment_date'),
            $request,
            ['number', 'reason', 'notes']
        )->paginate(15)->withQueryString();

        return view('erp.stock-adjustments.index', compact('adjustments'));
    }

    public function create(): View
    {
        $products = Product::where('type', 'product')->orderBy('name')->get(['id', 'code', 'name']);
        $warehouses = Warehouse::orderBy('name')->get(['id', 'code', 'name']);
        $locations = StockLocation::orderBy('name')->get(['id', 'code', 'name']);

        return view('erp.stock-adjustments.create', compact('products', 'warehouses', 'locations'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'warehouse_id' => ['nullable', 'exists:warehouses,id'],
            'location_id' => ['nullable', 'exists:stock_locations,id'],
            'actual_quantity' => ['required', 'numeric', 'min:0'],
            'reason' => ['required', 'max:100'],
            'adjustment_date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $product = Product::findOrFail($data['product_id']);
        $data['expected_quantity'] = $product->stockOnHand($data['warehouse_id'] ?? null);
        $data['adjustment_date'] = \Carbon\Carbon::parse($data['adjustment_date'])->toDateString();
        $data['number'] = $this->nextAdjustmentNumber();
        $data['status'] = 'draft';

        $adjustment = StockAdjustment::create($data);
        $this->audit('created', $adjustment, 'Adjustment stok dibuat');

        return redirect()->route('stock-adjustments.index')->with('status', 'Adjustment stok berhasil dibuat.');
    }

    public function post(Request $request, StockAdjustment $adjustment): RedirectResponse
    {
        abort_if($adjustment->status === 'posted', 409, 'Adjustment sudah diposting.');

        $delta = $adjustment->deltaQuantity();

        if ($delta != 0) {
            StockMovement::create([
                'company_id' => $adjustment->company_id,
                'product_id' => $adjustment->product_id,
                'warehouse_id' => $adjustment->warehouse_id,
                'location_id' => $adjustment->location_id,
                'type' => $delta > 0 ? 'in' : 'out',
                'quantity' => abs($delta),
                'unit_price' => $adjustment->product?->cost ?? 0,
                'reference' => $adjustment->number,
                'source_type' => 'adjustment',
                'source_id' => $adjustment->id,
                'movement_date' => $adjustment->adjustment_date,
                'is_posted' => true,
                'notes' => $adjustment->reason,
            ]);
        }

        $old = $adjustment->toArray();
        $adjustment->update(['status' => 'posted']);
        $this->audit('updated', $adjustment, 'Adjustment stok diposting', $old, $adjustment->fresh()->toArray());

        return redirect()->route('stock-adjustments.index')->with('status', 'Adjustment stok berhasil diposting.');
    }

    public function destroy(StockAdjustment $adjustment): RedirectResponse
    {
        abort_if($adjustment->status === 'posted', 409, 'Adjustment yang sudah diposting tidak dapat dihapus.');

        $this->audit('deleted', $adjustment, 'Adjustment stok dihapus', $adjustment->toArray());
        $adjustment->delete();

        return redirect()->route('stock-adjustments.index')->with('status', 'Adjustment stok berhasil dihapus.');
    }

    private function nextAdjustmentNumber(): string
    {
        $prefix = 'ADJ';
        $sequence = StockAdjustment::whereYear('created_at', now()->year)->count() + 1;

        return $this->nextNumber($prefix, $sequence);
    }
}
