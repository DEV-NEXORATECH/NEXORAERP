@php
    $financeHeroMap = [
        'reimbursements.index-page' => [
            'title' => 'Reimbursements',
            'desc' => 'Kelola klaim reimbursement, approval, payment, dan bukti transaksi finance.',
            'icon' => 'reimbursement',
        ],
        'cashflows.index-page' => [
            'title' => 'Cashflows',
            'desc' => 'Pantau arus kas income dan expense per project maupun keseluruhan perusahaan.',
            'icon' => 'cashflow',
        ],
        'finance.index' => [
            'title' => 'Invoices',
            'desc' => 'Kelola invoice client, status pembayaran, dan dokumen tagihan.',
            'icon' => 'invoice',
        ],
        'finance.invoices.index' => [
            'title' => 'Invoices',
            'desc' => 'Kelola invoice client, status pembayaran, dan dokumen tagihan.',
            'icon' => 'invoice',
        ],
        'chart-accounts.index' => [
            'title' => 'Chart of Accounts',
            'desc' => 'Kelola struktur akun dinamis untuk general ledger dan laporan finance.',
            'icon' => 'master',
        ],
        'finance.chart-accounts.index' => [
            'title' => 'Chart of Accounts',
            'desc' => 'Kelola struktur akun dinamis untuk general ledger dan laporan finance.',
            'icon' => 'master',
        ],
        'journal-entries.index' => [
            'title' => 'Journal Entries',
            'desc' => 'Catat jurnal umum, debit-kredit, dan transaksi akuntansi perusahaan.',
            'icon' => 'audit',
        ],
        'finance.journal-entries.index' => [
            'title' => 'Journal Entries',
            'desc' => 'Catat jurnal umum, debit-kredit, dan transaksi akuntansi perusahaan.',
            'icon' => 'audit',
        ],
        'recurring-billings.index' => [
            'title' => 'Recurring Billings',
            'desc' => 'Kelola billing berulang, subscription, cycle, dan jadwal penagihan otomatis.',
            'icon' => 'cashflow',
        ],
        'finance.recurring-billings.index' => [
            'title' => 'Recurring Billings',
            'desc' => 'Kelola billing berulang, subscription, cycle, dan jadwal penagihan otomatis.',
            'icon' => 'cashflow',
        ],
        'payment-reminders.index' => [
            'title' => 'Payment Reminders',
            'desc' => 'Pantau reminder pembayaran client dan follow-up invoice jatuh tempo.',
            'icon' => 'reimbursement',
        ],
        'finance.payment-reminders.index' => [
            'title' => 'Payment Reminders',
            'desc' => 'Pantau reminder pembayaran client dan follow-up invoice jatuh tempo.',
            'icon' => 'reimbursement',
        ],
        'vendor-bills.index' => [
            'title' => 'Vendor Bills',
            'desc' => 'Kelola tagihan vendor, due date, project cost, dan status pembayaran.',
            'icon' => 'invoice',
        ],
        'finance.vendor-bills.index' => [
            'title' => 'Vendor Bills',
            'desc' => 'Kelola tagihan vendor, due date, project cost, dan status pembayaran.',
            'icon' => 'invoice',
        ],
        'vendor-payments.index' => [
            'title' => 'Vendor Payments',
            'desc' => 'Catat pembayaran vendor dan hubungkan ke accounts payable.',
            'icon' => 'salary',
        ],
        'finance.vendor-payments.index' => [
            'title' => 'Vendor Payments',
            'desc' => 'Catat pembayaran vendor dan hubungkan ke accounts payable.',
            'icon' => 'salary',
        ],
        'budgets.index' => [
            'title' => 'Budgets & Forecasts',
            'desc' => 'Kelola budget, forecast, dan perbandingan realisasi biaya.',
            'icon' => 'reports',
        ],
        'finance.budgets.index' => [
            'title' => 'Budgets & Forecasts',
            'desc' => 'Kelola budget, forecast, dan perbandingan realisasi biaya.',
            'icon' => 'reports',
        ],
        'tax-rules.index' => [
            'title' => 'Tax Rules',
            'desc' => 'Kelola aturan PPN, PPh, dan konfigurasi pajak transaksi.',
            'icon' => 'settings',
        ],
        'finance.tax-rules.index' => [
            'title' => 'Tax Rules',
            'desc' => 'Kelola aturan PPN, PPh, dan konfigurasi pajak transaksi.',
            'icon' => 'settings',
        ],
        'fixed-assets.index' => [
            'title' => 'Fixed Assets',
            'desc' => 'Kelola aset tetap, nilai perolehan, dan informasi asset register.',
            'icon' => 'cashflow',
        ],
        'finance.fixed-assets.index' => [
            'title' => 'Fixed Assets',
            'desc' => 'Kelola aset tetap, nilai perolehan, dan informasi asset register.',
            'icon' => 'cashflow',
        ],
        'currency-rates.index' => [
            'title' => 'Currency Rates',
            'desc' => 'Kelola kurs mata uang untuk transaksi dan laporan multi-currency.',
            'icon' => 'cashflow',
        ],
        'finance.currency-rates.index' => [
            'title' => 'Currency Rates',
            'desc' => 'Kelola kurs mata uang untuk transaksi dan laporan multi-currency.',
            'icon' => 'cashflow',
        ],
        'revenue-schedules.index' => [
            'title' => 'Revenue Schedules',
            'desc' => 'Kelola jadwal pengakuan revenue dan proyeksi pendapatan.',
            'icon' => 'reports',
        ],
        'finance.revenue-schedules.index' => [
            'title' => 'Revenue Schedules',
            'desc' => 'Kelola jadwal pengakuan revenue dan proyeksi pendapatan.',
            'icon' => 'reports',
        ],
        'bank-reconciliation-items.index' => [
            'title' => 'Bank Reconciliations',
            'desc' => 'Kelola item rekonsiliasi bank dan book balance per akun.',
            'icon' => 'audit',
        ],
        'finance.bank-reconciliations.index' => [
            'title' => 'Bank Reconciliations',
            'desc' => 'Kelola item rekonsiliasi bank dan book balance per akun.',
            'icon' => 'audit',
        ],
        'purchase-matches.index' => [
            'title' => 'Purchase Matches',
            'desc' => 'Cocokkan purchase, receipt, bill, dan payment untuk kontrol AP.',
            'icon' => 'master',
        ],
        'finance.purchase-matches.index' => [
            'title' => 'Purchase Matches',
            'desc' => 'Cocokkan purchase, receipt, bill, dan payment untuk kontrol AP.',
            'icon' => 'master',
        ],
    ];

    $financeHero = $financeHeroMap[request()->route()?->getName()] ?? null;
@endphp

@if($financeHero)
    <section class="module-detail-page">
        <div class="module-detail-hero">
            <div class="module-detail-copy">
                <a class="module-back-link" href="{{ route('modules.show', 'finance') }}">Back to Hub</a>
                <div class="module-title-row">
                    <span class="module-title-icon">{!! $icon($financeHero['icon']) !!}</span>
                    <div>
                        <span class="module-eyebrow">NEXORA ERP Finance</span>
                        <h1>{{ $financeHero['title'] }}</h1>
                    </div>
                </div>
                <p>{{ $financeHero['desc'] }}</p>
            </div>
            <div class="module-count">
                <strong>FIN</strong>
                <span>Module</span>
            </div>
        </div>
    </section>
@endif
