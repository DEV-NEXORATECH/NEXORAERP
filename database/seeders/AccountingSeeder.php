<?php

namespace Database\Seeders;

use App\Models\AccountingJournal;
use App\Models\Currency;
use App\Models\FiscalPeriod;
use App\Models\PaymentTerm;
use Illuminate\Database\Seeder;

class AccountingSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['JSL', 'Sales Journal', 'sales'],
            ['JPO', 'Purchase Journal', 'purchase'],
            ['JBN', 'Bank Journal', 'bank'],
            ['JCS', 'Cash Journal', 'cash'],
            ['JGN', 'General Journal', 'general'],
            ['JOP', 'Opening Balance Journal', 'opening'],
            ['JAD', 'Adjustment Journal', 'adjustment'],
            ['JAS', 'Asset Journal', 'asset'],
        ] as [$code, $name, $type]) {
            AccountingJournal::updateOrCreate(['code' => $code], [
                'name' => $name,
                'type' => $type,
                'is_active' => true,
            ]);
        }

        foreach ([
            ['IMM', 'Immediate', 0],
            ['NET7', 'Net 7 Hari', 7],
            ['NET14', 'Net 14 Hari', 14],
            ['NET30', 'Net 30 Hari', 30],
            ['NET60', 'Net 60 Hari', 60],
        ] as [$code, $name, $days]) {
            PaymentTerm::updateOrCreate(['code' => $code], [
                'name' => $name,
                'due_days' => $days,
                'due_type' => 'day',
                'is_active' => true,
            ]);
        }

        foreach ([
            ['IDR', 'Indonesian Rupiah', 'Rp', 1],
            ['USD', 'US Dollar', '$', 16500],
            ['EUR', 'Euro', '€', 18200],
            ['SGD', 'Singapore Dollar', 'S$', 12200],
        ] as [$code, $name, $symbol, $rate]) {
            Currency::updateOrCreate(['code' => $code], [
                'name' => $name,
                'symbol' => $symbol,
                'rate' => $rate,
                'is_active' => true,
            ]);
        }

        $year = 2026;
        foreach (range(1, 12) as $month) {
            $label = sprintf('%s-%02d', $year, $month);
            FiscalPeriod::updateOrCreate(['code' => $label], [
                'name' => sprintf('%s - Bulan %s', $year, str_pad((string) $month, 2, '0', STR_PAD_LEFT)),
                'start_date' => sprintf('%s-%02d-01', $year, $month),
                'end_date' => date('Y-m-t', strtotime(sprintf('%s-%02d-01', $year, $month))),
                'status' => $month === 1 ? 'open' : 'open',
            ]);
        }
    }
}
