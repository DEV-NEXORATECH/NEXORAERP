<?php

namespace App\Http\Controllers;

use App\Http\Traits\AppliesListFilters;
use App\Http\Traits\LoadsErpData;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\UnitOfMeasure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductController extends Controller
{
    use LoadsErpData, AppliesListFilters;

    public function index(Request $request): View
    {
        $products = $this->applyListFilters(
            Product::with(['category:id,code,name', 'unit:id,code,name', 'movements' => fn ($q) => $q->where('is_posted', true)->select('product_id', 'warehouse_id', 'type', 'quantity', 'is_posted')]),
            $request,
            ['name', 'code', 'sku', 'barcode']
        )->paginate(15)->withQueryString();

        return view('erp.products.index', compact('products'));
    }

    public function create(): View
    {
        $categories = ProductCategory::orderBy('name')->get(['id', 'code', 'name']);
        $units = UnitOfMeasure::orderBy('name')->get(['id', 'code', 'name']);

        return view('erp.products.create', compact('categories', 'units'));
    }

        public function show(Product $product): View
    {
        $product->load(['category', 'unit', 'movements.warehouse']);
        return view('erp.products.show', compact('product'));
    }
public function edit(Product $product): View
    {
        $categories = ProductCategory::orderBy('name')->get(['id', 'code', 'name']);
        $units = UnitOfMeasure::orderBy('name')->get(['id', 'code', 'name']);

        return view('erp.products.edit', compact('product', 'categories', 'units'));
    }

    public function store(Request $request): RedirectResponse
    {
        $product = Product::create($this->validated($request));
        $this->audit('created', $product, 'Produk dibuat');

        return redirect()->route('products.index')->with('status', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $old = $product->toArray();
        $product->update($this->validated($request, $product));
        $this->audit('updated', $product, 'Produk diupdate', $old, $product->fresh()->toArray());

        return redirect()->route('products.index')->with('status', 'Produk berhasil diupdate.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->audit('deleted', $product, 'Produk dihapus', $product->toArray());
        $product->delete();

        return redirect()->route('products.index')->with('status', 'Produk berhasil dihapus.');
    }

    private function validated(Request $request, ?Product $product = null): array
    {
        $data = $request->validate([
            'code' => ['required', 'max:50', 'regex:/^[a-zA-Z0-9_.-]+$/', Rule::unique('products', 'code')->ignore($product?->id)],
            'name' => ['required', 'max:255'],
            'type' => ['required', Rule::in(['product', 'service'])],
            'category_id' => ['nullable', 'exists:product_categories,id'],
            'unit_id' => ['nullable', 'exists:units_of_measure,id'],
            'sku' => ['nullable', 'max:100'],
            'barcode' => ['nullable', 'max:100'],
            'cost' => ['required', 'numeric', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
            'min_stock' => ['nullable', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['min_stock'] = $data['min_stock'] ?? 0;

        return $data;
    }
}
