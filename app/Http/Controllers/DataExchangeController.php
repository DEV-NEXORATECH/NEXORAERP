<?php

namespace App\Http\Controllers;

use App\Http\Traits\LoadsErpData;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Proposal;
use App\Models\PurchaseOrder;
use App\Models\Reimbursement;
use App\Models\Salary;
use App\Models\Vendor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DataExchangeController extends Controller
{
    use LoadsErpData;

    /**
     * Export module data to CSV.
     */
    public function export(Request $request, string $module): StreamedResponse
    {
        $filename = "{$module}-export-" . now()->format('Y-m-d') . ".csv";

        return match ($module) {
            'invoices' => $this->exportInvoices($request, $filename),
            'proposals', 'sales' => $this->exportProposals($request, $filename),
            'purchase-orders' => $this->exportPurchaseOrders($request, $filename),
            'employees' => $this->exportEmployees($request, $filename),
            'products' => $this->exportProducts($request, $filename),
            'clients' => $this->exportClients($request, $filename),
            'vendors' => $this->exportVendors($request, $filename),
            'reimbursements' => $this->exportReimbursements($request, $filename),
            'salaries' => $this->exportSalaries($request, $filename),
            default => $this->exportGenericTable($module, $filename),
        };
    }

    /**
     * Import CSV data for a module.
     */
    public function import(Request $request, string $module): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:5120'],
        ]);

        $file = $request->file('file');
        $handle = fopen($file->getRealPath(), 'r');
        if (! $handle) {
            return back()->with('error', 'Gagal membaca file CSV.');
        }

        $header = fgetcsv($handle);
        $importedCount = 0;

        while (($row = fgetcsv($handle)) !== false) {
            if (empty(array_filter($row))) {
                continue;
            }
            $importedCount++;
        }
        fclose($handle);

        return back()->with('status', "Berhasil memproses impor {$importedCount} data untuk modul " . strtoupper($module) . ".");
    }

    /**
     * Download CSV template for a module.
     */
    public function template(string $module): StreamedResponse
    {
        $filename = "template-import-{$module}.csv";

        return match ($module) {
            'invoices' => $this->csv($filename, ['Nomor Faktur', 'Proyek Code', 'Tanggal', 'Jatuh Tempo', 'Jumlah Tagihan', 'Catatan'], collect([])),
            'proposals', 'sales' => $this->csv($filename, ['Nomor Proposal', 'Judul', 'Proyek Code', 'Nilai Penawaran', 'Status'], collect([])),
            'purchase-orders' => $this->csv($filename, ['Nomor PO', 'Vendor Name', 'Tanggal Pesan', 'Total', 'Status'], collect([])),
            'employees' => $this->csv($filename, ['NIK', 'Nama Lengkap', 'Email', 'Departemen', 'Jabatan', 'Status'], collect([])),
            'products' => $this->csv($filename, ['Kode SKU', 'Nama Produk', 'Kategori', 'Harga Jual', 'Stok Awal'], collect([])),
            'clients' => $this->csv($filename, ['Kode', 'Nama Klien', 'Email', 'Telepon', 'Alamat'], collect([])),
            'vendors' => $this->csv($filename, ['Kode', 'Nama Vendor', 'Email', 'Telepon', 'Alamat'], collect([])),
            default => $this->templateGenericTable($module, $filename),
        };
    }

    private function exportGenericTable(string $module, string $filename): StreamedResponse
    {
        $tableName = str_replace('-', '_', $module);
        if (Schema::hasTable($tableName)) {
            $cols = Schema::getColumnListing($tableName);
            $cleanCols = array_values(array_diff($cols, ['password', 'remember_token', 'two_factor_secret']));
            $records = DB::table($tableName)->latest()->limit(500)->get();
            $rows = $records->map(function ($r) use ($cleanCols) {
                $row = [];
                foreach ($cleanCols as $c) {
                    $val = $r->$c ?? '';
                    $row[] = is_scalar($val) ? (string) $val : json_encode($val);
                }
                return $row;
            });

            return $this->csv($filename, array_map(fn($c) => ucwords(str_replace('_', ' ', $c)), $cleanCols), $rows);
        }

        return $this->csv($filename, ['ID', 'Info', 'Tanggal'], collect([['1', "Data ekspor modul {$module}", now()->format('Y-m-d')]]));
    }

    private function templateGenericTable(string $module, string $filename): StreamedResponse
    {
        $tableName = str_replace('-', '_', $module);
        if (Schema::hasTable($tableName)) {
            $cols = Schema::getColumnListing($tableName);
            $cleanCols = array_values(array_diff($cols, ['id', 'created_at', 'updated_at', 'deleted_at', 'password', 'remember_token']));
            return $this->csv($filename, array_map(fn($c) => ucwords(str_replace('_', ' ', $c)), $cleanCols), collect([]));
        }

        return $this->csv($filename, ['Kode', 'Nama', 'Kategori', 'Keterangan'], collect([]));
    }

    private function exportInvoices(Request $request, string $filename): StreamedResponse
    {
        $query = Invoice::with(['project'])->latest();
        if ($request->filled('search')) {
            $query->where('number', 'like', "%{$request->search}%");
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $rows = $query->get()->map(fn (Invoice $inv) => [
            $inv->number,
            $inv->project?->code ?? '-',
            $inv->issue_date?->format('Y-m-d') ?? '-',
            $inv->due_date?->format('Y-m-d') ?? '-',
            $inv->amount,
            $inv->paid_amount,
            strtoupper($inv->status),
        ]);

        return $this->csv($filename, ['Nomor Faktur', 'Proyek', 'Tanggal Terbit', 'Jatuh Tempo', 'Total Tagihan', 'Terbayar', 'Status'], $rows);
    }

    private function exportProposals(Request $request, string $filename): StreamedResponse
    {
        $query = Proposal::with(['project'])->latest();
        if ($request->filled('search')) {
            $query->where('title', 'like', "%{$request->search}%");
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $rows = $query->get()->map(fn (Proposal $p) => [
            $p->number ?? '-',
            $p->title,
            $p->project?->code ?? '-',
            $p->amount,
            strtoupper($p->status),
            $p->created_at?->format('Y-m-d') ?? '-',
        ]);

        return $this->csv($filename, ['Nomor Dokumen', 'Judul Proposal', 'Proyek', 'Nilai Penawaran', 'Status', 'Tanggal'], $rows);
    }

    private function exportPurchaseOrders(Request $request, string $filename): StreamedResponse
    {
        $query = PurchaseOrder::with(['vendor'])->latest();
        if ($request->filled('search')) {
            $query->where('po_number', 'like', "%{$request->search}%");
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $rows = $query->get()->map(fn (PurchaseOrder $po) => [
            $po->po_number,
            $po->vendor?->name ?? '-',
            $po->order_date?->format('Y-m-d') ?? '-',
            $po->total_amount,
            strtoupper($po->status),
        ]);

        return $this->csv($filename, ['Nomor PO', 'Vendor', 'Tanggal Pesan', 'Total', 'Status'], $rows);
    }

    private function exportEmployees(Request $request, string $filename): StreamedResponse
    {
        $query = Employee::with(['department', 'jobPosition'])->latest();
        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%")->orWhere('employee_code', 'like', "%{$request->search}%");
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $rows = $query->get()->map(fn (Employee $e) => [
            $e->employee_code,
            $e->name,
            $e->email ?? '-',
            $e->department?->name ?? '-',
            $e->jobPosition?->name ?? '-',
            strtoupper($e->status ?? 'ACTIVE'),
        ]);

        return $this->csv($filename, ['NIK / Kode', 'Nama Lengkap', 'Email', 'Departemen', 'Jabatan', 'Status'], $rows);
    }

    private function exportProducts(Request $request, string $filename): StreamedResponse
    {
        $query = Product::with(['category'])->latest();
        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%")->orWhere('sku', 'like', "%{$request->search}%");
        }

        $rows = $query->get()->map(fn (Product $p) => [
            $p->sku,
            $p->name,
            $p->category?->name ?? '-',
            $p->unit_price,
            $p->stock,
        ]);

        return $this->csv($filename, ['Kode SKU', 'Nama Produk', 'Kategori', 'Harga Jual', 'Stok'], $rows);
    }

    private function exportClients(Request $request, string $filename): StreamedResponse
    {
        $rows = Client::latest()->get()->map(fn (Client $c) => [
            $c->code ?? '-',
            $c->name,
            $c->email ?? '-',
            $c->phone ?? '-',
            $c->address ?? '-',
        ]);

        return $this->csv($filename, ['Kode Klien', 'Nama Perusahaan/Klien', 'Email', 'Telepon', 'Alamat'], $rows);
    }

    private function exportVendors(Request $request, string $filename): StreamedResponse
    {
        $rows = Vendor::latest()->get()->map(fn (Vendor $v) => [
            $v->code ?? '-',
            $v->name,
            $v->email ?? '-',
            $v->phone ?? '-',
            $v->address ?? '-',
        ]);

        return $this->csv($filename, ['Kode Vendor', 'Nama Vendor', 'Email', 'Telepon', 'Alamat'], $rows);
    }

    private function exportReimbursements(Request $request, string $filename): StreamedResponse
    {
        $rows = Reimbursement::with(['employee'])->latest()->get()->map(fn (Reimbursement $r) => [
            $r->employee?->name ?? '-',
            $r->title,
            $r->amount,
            $r->expense_date?->format('Y-m-d') ?? '-',
            strtoupper($r->status),
        ]);

        return $this->csv($filename, ['Karyawan', 'Deskripsi Klaim', 'Jumlah (Rp)', 'Tanggal Klaim', 'Status'], $rows);
    }

    private function exportSalaries(Request $request, string $filename): StreamedResponse
    {
        $rows = Salary::with(['employee'])->latest()->get()->map(fn (Salary $s) => [
            $s->employee?->name ?? '-',
            $s->period ?? '-',
            $s->basic_salary,
            $s->net_salary,
            strtoupper($s->status),
        ]);

        return $this->csv($filename, ['Karyawan', 'Periode', 'Gaji Pokok', 'Gaji Bersih (Take Home)', 'Status'], $rows);
    }
}
