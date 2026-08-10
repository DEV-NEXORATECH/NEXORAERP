<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\StockLocation;
use App\Models\StockMovement;
use App\Models\UnitOfMeasure;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::firstOrCreate(
            ['code' => 'NX-001'],
            ['name' => 'NEXORA', 'access_type' => 'internal', 'is_active' => true]
        );

        // ── Categories ────────────────────────────────────────────────────
        $categories = [
            ['code' => 'CAT-ELC', 'name' => 'Elektronik'],
            ['code' => 'CAT-FUR', 'name' => 'Furniture'],
            ['code' => 'CAT-ATK', 'name' => 'Alat Tulis Kantor'],
            ['code' => 'CAT-SVC', 'name' => 'Jasa & Layanan'],
        ];
        $categoryMap = [];
        foreach ($categories as ['code' => $code, 'name' => $name]) {
            $categoryMap[$code] = ProductCategory::updateOrCreate(
                ['code' => $code],
                ['company_id' => $company->id, 'name' => $name, 'is_active' => true]
            );
        }

        // ── Units of Measure ──────────────────────────────────────────────
        $units = [
            ['code' => 'PCS', 'name' => 'Pieces', 'category' => 'unit'],
            ['code' => 'KG',  'name' => 'Kilogram', 'category' => 'weight'],
            ['code' => 'BOX', 'name' => 'Box',     'category' => 'unit'],
            ['code' => 'LTR', 'name' => 'Liter',   'category' => 'volume'],
            ['code' => 'JAM', 'name' => 'Jam',     'category' => 'unit'],
        ];
        $unitMap = [];
        foreach ($units as ['code' => $code, 'name' => $name, 'category' => $category]) {
            $unitMap[$code] = UnitOfMeasure::updateOrCreate(
                ['code' => $code],
                ['company_id' => $company->id, 'name' => $name, 'category' => $category, 'is_active' => true]
            );
        }

        // ── Warehouses & Locations ────────────────────────────────────────
        $warehouse = Warehouse::updateOrCreate(
            ['code' => 'WH-MAIN'],
            ['company_id' => $company->id, 'name' => 'Main Warehouse', 'address' => 'Jakarta, Indonesia', 'is_active' => true]
        );

        foreach ([
            ['WH-MAIN', 'RECV', 'Receiving Area', 'input'],
            ['WH-MAIN', 'STK',  'Stock Room',     'internal'],
            ['WH-MAIN', 'SHIP', 'Shipping Area',  'output'],
        ] as [$whCode, $locCode, $locName, $locType]) {
            StockLocation::updateOrCreate(
                ['warehouse_id' => $warehouse->id, 'code' => $locCode],
                ['company_id' => $company->id, 'name' => $locName, 'type' => $locType, 'is_active' => true]
            );
        }

        // ── Products ──────────────────────────────────────────────────────
        $products = [
            ['code' => 'PRD-LAP-001', 'name' => 'Laptop ThinkPad X1', 'category' => 'CAT-ELC', 'unit' => 'PCS', 'cost' => 15000000, 'price' => 17500000, 'min_stock' => 2, 'stock' => 5],
            ['code' => 'PRD-MON-001', 'name' => 'Monitor 24 inch',    'category' => 'CAT-ELC', 'unit' => 'PCS', 'cost' => 2500000,  'price' => 3200000,  'min_stock' => 3, 'stock' => 8],
            ['code' => 'PRD-MEJ-001', 'name' => 'Meja Kerja',         'category' => 'CAT-FUR', 'unit' => 'PCS', 'cost' => 1800000,  'price' => 2500000,  'min_stock' => 2, 'stock' => 4],
            ['code' => 'PRD-KUR-001', 'name' => 'Kursi Ergonomis',    'category' => 'CAT-FUR', 'unit' => 'PCS', 'cost' => 1200000,  'price' => 1750000,  'min_stock' => 4, 'stock' => 6],
            ['code' => 'PRD-ATK-001', 'name' => 'Kertas A4 80gsm',    'category' => 'CAT-ATK', 'unit' => 'BOX', 'cost' => 45000,    'price' => 65000,    'min_stock' => 10, 'stock' => 20],
            ['code' => 'SVC-IT-001',  'name' => 'Jasa Maintenance IT', 'category' => 'CAT-SVC', 'unit' => 'JAM', 'cost' => 150000,   'price' => 250000,   'min_stock' => 0, 'stock' => 0],
        ];

        foreach ($products as $data) {
            $type = str_starts_with($data['code'], 'SVC') ? 'service' : 'product';

            $product = Product::updateOrCreate(
                ['code' => $data['code']],
                [
                    'company_id' => $company->id,
                    'category_id' => $categoryMap[$data['category']]?->id,
                    'unit_id' => $unitMap[$data['unit']]?->id,
                    'name' => $data['name'],
                    'type' => $type,
                    'cost' => $data['cost'],
                    'price' => $data['price'],
                    'min_stock' => $data['min_stock'],
                    'is_active' => true,
                ]
            );

            if ($type === 'product' && $data['stock'] > 0 && StockMovement::where('product_id', $product->id)->doesntExist()) {
                StockMovement::create([
                    'company_id' => $company->id,
                    'product_id' => $product->id,
                    'warehouse_id' => $warehouse->id,
                    'location_id' => StockLocation::where('warehouse_id', $warehouse->id)->where('code', 'STK')->value('id'),
                    'type' => 'in',
                    'quantity' => $data['stock'],
                    'unit_price' => $data['cost'],
                    'reference' => 'INV-SEED',
                    'source_type' => 'manual',
                    'movement_date' => now()->toDateString(),
                    'is_posted' => true,
                    'notes' => 'Stok awal seeding',
                ]);
            }
        }
    }
}
