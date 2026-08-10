<?php

namespace App\Http\Controllers;

use App\Http\Traits\AppliesListFilters;
use App\Http\Traits\LoadsErpData;
use App\Models\ProductCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductCategoryController extends Controller
{
    use LoadsErpData, AppliesListFilters;

    public function index(Request $request): View
    {
        $categories = $this->applyListFilters(ProductCategory::withCount('products')->orderBy('code'), $request, ['name', 'code'])->paginate(15)->withQueryString();
        return view('erp.product-categories.index', compact('categories'));
    }

    public function create(): View
    {
        $parents = ProductCategory::orderBy('name')->get(['id', 'code', 'name']);
        return view('erp.product-categories.create', compact('parents'));
    }

    public function edit(ProductCategory $category): View
    {
        $parents = ProductCategory::whereKeyNot($category->id)->orderBy('name')->get(['id', 'code', 'name']);
        return view('erp.product-categories.edit', compact('category', 'parents'));
    }

    public function store(Request $request): RedirectResponse
    {
        $category = ProductCategory::create($this->validated($request));
        $this->audit('created', $category, 'Kategori produk dibuat');

        return redirect()->route('product-categories.index')->with('status', 'Kategori produk berhasil ditambahkan.');
    }

    public function update(Request $request, ProductCategory $category): RedirectResponse
    {
        $old = $category->toArray();
        $category->update($this->validated($request, $category));
        $this->audit('updated', $category, 'Kategori produk diupdate', $old, $category->fresh()->toArray());

        return redirect()->route('product-categories.index')->with('status', 'Kategori produk berhasil diupdate.');
    }

    public function destroy(ProductCategory $category): RedirectResponse
    {
        $this->audit('deleted', $category, 'Kategori produk dihapus', $category->toArray());
        $category->delete();

        return redirect()->route('product-categories.index')->with('status', 'Kategori produk berhasil dihapus.');
    }

    private function validated(Request $request, ?ProductCategory $category = null): array
    {
        $data = $request->validate([
            'code' => ['required', 'max:50', 'regex:/^[a-zA-Z0-9_.-]+$/', Rule::unique('product_categories', 'code')->ignore($category?->id)],
            'name' => ['required', 'max:255'],
            'parent_id' => ['nullable', 'exists:product_categories,id'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
