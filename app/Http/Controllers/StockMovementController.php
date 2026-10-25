<?php

namespace App\Http\Controllers;

use App\Http\Traits\AppliesListFilters;
use App\Http\Traits\LoadsErpData;
use App\Models\Product;
use App\Models\StockLocation;
use App\Models\StockMovement;
use App\Models\Warehouse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StockMovementController extends Controller
{
    use LoadsErpData, AppliesListFilters;

    public function index(Request $request): View
    {
        $movements = $this->applyListFilters(
            StockMovement::with(['product:id,code,name', 'warehouse:id,code,name', 'location:id,code,name'])->latest('movement_date'),
            $request,
            ['reference', 'source_type', 'notes']
        )->paginate(8)->withQueryString();

        return view('erp.stock-movements.index', compact('movements'));
    }

    public function create(): View
    {
        $products = Product::where('type', 'product')->orderBy('name')->get(['id', 'code', 'name']);
        $warehouses = Warehouse::orderBy('name')->get(['id', 'code', 'name']);
        $locations = StockLocation::orderBy('name')->get(['id', 'code', 'name']);

        return view('erp.stock-movements.create', compact('products', 'warehouses', 'locations'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'warehouse_id' => ['nullable', 'exists:warehouses,id'],
            'location_id' => ['nullable', 'exists:stock_locations,id'],
            'type' => ['required', Rule::in(['in', 'out', 'adjustment'])],
            'quantity' => ['required', 'numeric', 'gt:0'],
            'unit_price' => ['nullable', 'numeric', 'min:0'],
            'movement_date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $data['reference'] = $this->nextMovementNumber();
        $data['movement_date'] = \Carbon\Carbon::parse($data['movement_date'])->toDateString();
        $data['unit_price'] = $data['unit_price'] ?? 0;
        $data['is_posted'] = true;

        $movement = StockMovement::create($data);
        $this->audit('created', $movement, 'Stok movement ' . $data['type']);

        return redirect()->route('stock-movements.index')->with('status', 'Movement stok berhasil dicatat.');
    }

    public function destroy(StockMovement $movement): RedirectResponse
    {
        $this->audit('deleted', $movement, 'Movement stok dihapus', $movement->toArray());
        $movement->delete();

        return redirect()->route('stock-movements.index')->with('status', 'Movement stok berhasil dihapus.');
    }

    private function nextMovementNumber(): string
    {
        $prefix = 'MV';
        $sequence = StockMovement::whereYear('created_at', now()->year)->count() + 1;

        return $this->nextNumber($prefix, $sequence);
    }
}

