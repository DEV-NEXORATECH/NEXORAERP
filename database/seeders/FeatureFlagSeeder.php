<?php

namespace Database\Seeders;

use App\Models\FeatureFlag;
use Illuminate\Database\Seeder;

class FeatureFlagSeeder extends Seeder
{
    public function run(): void
    {
        $flags = [
            // ── Platform ────────────────────────────────────────────────────
            ['platform.dashboard',        'Dashboard',            'platform',   'Halaman ringkasan eksekutif.'],
            ['platform.approvals',        'Approval & Task',      'platform',   'Pusat approval, task dan item pending.'],
            ['platform.settings',         'Settings Admin',       'platform',   'Pengaturan admin, master data dan user.'],
            ['platform.audit-log',        'Audit Log',            'platform',   'Log otomatis seluruh aktivitas CRUD.'],
            ['platform.trash',            'Trash / Sampah',       'platform',   'Restore data yang dihapus (soft delete).'],
            ['platform.backup',           'Backup Database',      'platform',   'Download backup database.'],

            // ── Accounting ──────────────────────────────────────────────────
            ['accounting.reimbursements',      'Reimbursements',      'accounting', 'Klaim pengeluaran karyawan.'],
            ['accounting.cashflows',           'Cashflows',           'accounting', 'Pencatatan arus kas (income/expense).'],
            ['accounting.invoices',            'Invoices (AR)',       'accounting', 'Tagihan piutang pelanggan.'],
            ['accounting.payments',            'Payments',            'accounting', 'Pembayaran invoice pelanggan.'],
            ['accounting.chart-accounts',      'Chart of Accounts',   'accounting', 'Struktur akun pembukuan.'],
            ['accounting.journal-entries',     'Journal Entries',     'accounting', 'Jurnal double-entry.'],
            ['accounting.recurring-billings',  'Recurring Billings',  'accounting', 'Tagihan berulang.'],
            ['accounting.payment-reminders',   'Payment Reminders',   'accounting', 'Pengingat pembayaran.'],
            ['accounting.vendor-bills',        'Vendor Bills (AP)',   'accounting', 'Tagihan hutang ke vendor.'],
            ['accounting.vendor-payments',     'Vendor Payments',     'accounting', 'Pembayaran ke vendor.'],
            ['accounting.budgets',             'Budgets & Forecasts', 'accounting', 'Anggaran per proyek/periode.'],
            ['accounting.tax-rules',           'Tax Rules',           'accounting', 'Aturan pajak PPN/PPh.'],
            ['accounting.fixed-assets',        'Fixed Assets',        'accounting', 'Aset tetap dan depresiasi.'],
            ['accounting.currency-rates',      'Currency Rates',      'accounting', 'Nilai tukar mata uang.'],
            ['accounting.currency-variances',  'Currency Variances',  'accounting', 'Selisih kurs.'],
            ['accounting.revenue-schedules',   'Revenue Schedules',   'accounting', 'Pengakuan pendapatan.'],
            ['accounting.bank-reconciliation', 'Bank Reconciliation', 'accounting', 'Rekonsiliasi bank.'],
            ['accounting.purchase-matches',    'Purchase Matches',    'accounting', 'Three-way matching PO-GR-Bill.'],
            ['accounting.journals',            'Accounting Journals', 'accounting', 'Jurnal akuntansi (sales, purchase, bank, dll).'],
            ['accounting.payment-terms',       'Payment Terms',       'accounting', 'Syarat pembayaran (immediate, net-30, dll).'],
            ['accounting.currencies',          'Currencies',          'accounting', 'Master mata uang dan nilai tukar.'],
            ['accounting.fiscal-periods',      'Fiscal Periods',      'accounting', 'Periode fiskal akuntansi (open/close).'],

            // ── Sales ───────────────────────────────────────────────────────
            ['sales.projects',   'Projects',        'sales', 'Proyek dan kontrak.'],
            ['sales.proposals',  'Proposals',       'sales', 'Penawaran ke pelanggan.'],
            ['sales.orders',     'Sales Orders',    'sales', 'Order penjualan.'],
            ['sales.targets',    'Sales Targets',   'sales', 'Target penjualan.'],
            ['sales.commissions','Sales Commissions','sales', 'Komisi penjualan.'],
            ['sales.contracts',  'Client Contracts','sales', 'Kontrak klien.'],

            // ── CRM ─────────────────────────────────────────────────────────
            ['crm.inquiries',    'Sales Inquiries', 'crm', 'Inquiry masuk.'],
            ['crm.leads',        'Sales Leads',     'crm', 'Pipeline prospek.'],
            ['crm.funnel',       'Sales Funnel',    'crm', 'Visualisasi funnel penjualan.'],

            // ── Purchase ────────────────────────────────────────────────────
            ['purchase.vendors',        'Vendor Management',   'purchase', 'Data vendor.'],
            ['purchase.requisitions',   'Purchase Requisition','purchase', 'Permintaan pembelian.'],
            ['purchase.orders',         'Purchase Orders',     'purchase', 'Order pembelian.'],
            ['purchase.goods-receipts', 'Goods Receipts',      'purchase', 'Penerimaan barang.'],
            ['purchase.contracts',      'Procurement Contracts','purchase','Kontrak pengadaan.'],

            // ── Inventory ───────────────────────────────────────────────────
            ['inventory.products',    'Products',       'inventory', 'Master produk.'],
            ['inventory.categories',  'Product Categories','inventory','Kategori produk.'],
            ['inventory.units',       'Units of Measure','inventory', 'Satuan ukuran produk.'],
            ['inventory.warehouses',  'Warehouses',     'inventory', 'Gudang penyimpanan.'],
            ['inventory.locations',   'Stock Locations', 'inventory','Lokasi stok dalam gudang.'],
            ['inventory.stock',       'Stock Movement',  'inventory','Perpindahan stok masuk/keluar.'],
            ['inventory.adjustments', 'Stock Adjustments','inventory','Opname dan penyesuaian stok.'],

            // ── Contact ─────────────────────────────────────────────────────
            ['contact.clients',  'Clients', 'contact', 'Data pelanggan.'],

            // ── HR ──────────────────────────────────────────────────────────
            ['hr.employees',   'Employees',          'hr', 'Data karyawan.'],
            ['hr.skills',      'Employee Skills',    'hr', 'Keahlian karyawan.'],
            ['hr.attendances', 'Attendances',        'hr', 'Absensi karyawan.'],
            ['hr.timesheets',  'Timesheets',         'hr', 'Jam kerja per proyek.'],
            ['hr.leaves',      'Leave Requests',     'hr', 'Cuti karyawan.'],
            ['hr.reviews',     'Performance Reviews', 'hr', 'Penilaian kinerja.'],
            ['hr.salaries',    'Salaries',           'hr', 'Penggajian.'],
            ['hr.benefits',    'Payroll Benefits',   'hr', 'Benefit BPJS/PPh21.'],

            // ── Reports ─────────────────────────────────────────────────────
            ['reports.profit-loss',         'Profit & Loss',        'reports', 'Laporan laba rugi.'],
            ['reports.balance-sheet',       'Balance Sheet',        'reports', 'Laporan neraca.'],
            ['reports.cash-flow',           'Cash Flow',            'reports', 'Laporan arus kas.'],
            ['reports.project-profitability','Project Profitability','reports','Profitabilitas proyek.'],
            ['reports.aging',               'Aging Report (AR/AP)','reports', 'Umur piutang/hutang.'],
            ['reports.tax',                 'Tax Summary',          'reports', 'Ringkasan pajak.'],
            ['reports.budget-vs-actual',    'Budget vs Actual',     'reports', 'Perbandingan anggaran.'],
            ['reports.transactions',        'Transactions',         'reports', 'Daftar transaksi.'],
            ['reports.reconciliation',      'Bank Reconciliation',  'reports', 'Rekonsiliasi bank report.'],
            ['reports.trial-balance',       'Trial Balance',        'reports', 'Neraca saldo per akun.'],
            ['reports.general-ledger',      'General Ledger',       'reports', 'Buku besar per akun.'],

            // ── CMS ─────────────────────────────────────────────────────────
            ['cms.sections', 'CMS Sections', 'cms', 'Section landing page.'],
            ['cms.blog',     'Blog Posts',   'cms', 'Artikel blog.'],
            ['cms.api',      'CMS API',      'cms', 'Endpoint API publik CMS.'],
        ];

        foreach ($flags as $i => [$key, $label, $module, $description]) {
            FeatureFlag::updateOrCreate(
                ['key' => $key],
                [
                    'label' => $label,
                    'module' => $module,
                    'description' => $description,
                    'is_enabled' => true,
                    'sort_order' => $i,
                ]
            );
        }

        FeatureFlag::flushCache();
    }
}
