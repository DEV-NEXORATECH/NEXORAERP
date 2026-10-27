<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $docTitle ?? 'Dokumen' }} — {{ $companySetting?->company_name ?? 'NEXORA360 ERP' }}</title>
    <style>
        @page {
            size: {{ $paperSize ?? 'A4 portrait' }};
            margin: 15mm 15mm 15mm 15mm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #000000;
            background: #FFFFFF;
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .no-print {
            background: #002875;
            color: #FFFFFF;
            padding: 10px 18px;
            font-size: 10pt;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-bottom: 20px;
            font-weight: bold;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }

        .doc-header {
            width: 100%;
            border-bottom: 2px solid #000000;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: top;
            border: none;
            padding: 0;
        }

        .company-logo {
            max-height: 55px;
            max-width: 160px;
            object-fit: contain;
            margin-bottom: 6px;
        }

        .company-name {
            font-size: 14pt;
            font-weight: bold;
            color: #000000;
            margin: 0 0 2px 0;
            text-transform: uppercase;
        }

        .company-meta {
            font-size: 8.5pt;
            color: #333333;
            line-height: 1.35;
        }

        .doc-title-block {
            text-align: right;
        }

        .doc-type-title {
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0 0 4px 0;
        }

        .doc-meta-table {
            margin-left: auto;
            border-collapse: collapse;
            font-size: 8.5pt;
        }

        .doc-meta-table td {
            padding: 2px 4px;
            border: none;
        }

        .doc-meta-table td.meta-label {
            color: #555555;
            text-align: right;
        }

        .doc-meta-table td.meta-value {
            font-weight: bold;
            text-align: left;
        }

        /* ── Standard Tables ─────────────────────────────────────── */
        table.doc-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 14px;
            margin-bottom: 16px;
            font-size: 9pt;
        }

        table.doc-table th {
            background-color: #F0F0F0;
            color: #000000;
            border: 1px solid #777777;
            padding: 6px 8px;
            font-weight: bold;
            font-size: 8.5pt;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        table.doc-table td {
            border: 1px solid #AAAAAA;
            padding: 6px 8px;
            vertical-align: top;
        }

        table.doc-table tr:nth-child(even) td {
            background-color: #FAFAFA;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-bold {
            font-weight: bold;
        }

        /* ── Summary / Totals block ──────────────────────────────── */
        .summary-block {
            width: 100%;
            margin-top: 10px;
        }

        .summary-table {
            width: 45%;
            margin-left: auto;
            border-collapse: collapse;
            font-size: 9pt;
        }

        .summary-table td {
            padding: 4px 8px;
            border: 1px solid #AAAAAA;
        }

        .summary-table tr.grand-total td {
            background-color: #EAEAEA;
            font-size: 10.5pt;
            font-weight: bold;
            border-top: 2px solid #000000;
            border-bottom: 2px solid #000000;
        }

        /* ── Signature Section ───────────────────────────────────── */
        .signature-block {
            width: 100%;
            margin-top: 35px;
            page-break-inside: avoid;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }

        .signature-table td {
            border: none;
            width: 33.3%;
            text-align: center;
            vertical-align: top;
            font-size: 8.5pt;
        }

        .sig-space {
            height: 65px;
        }

        .sig-name {
            font-weight: bold;
            text-decoration: underline;
        }

        .sig-role {
            color: #555555;
            font-size: 8pt;
        }

        .footer-note {
            margin-top: 25px;
            border-top: 1px solid #CCCCCC;
            padding-top: 8px;
            font-size: 7.5pt;
            color: #666666;
            text-align: center;
        }
    </style>
</head>
<body>
    <button class="no-print" onclick="window.print()">Print / Simpan PDF</button>

    {{-- Document Header --}}
    <header class="doc-header">
        <table class="header-table">
            <tr>
                <td style="width: 55%;">
                    @php
                        $companySetting = $companySetting ?? \App\Models\CompanySetting::first();
                    @endphp
                    @if($companySetting && $companySetting->logo_path)
                        <img class="company-logo" src="{{ asset('storage/'.$companySetting->logo_path) }}" alt="Logo">
                    @else
                        <div style="font-size: 13pt; font-weight: 800; color: #002875; margin-bottom: 4px;">NEXORA360 ERP</div>
                    @endif
                    <div class="company-name">{{ $companySetting?->company_name ?? 'PT. NEXORA ENTERPRISE INDONESIA' }}</div>
                    <div class="company-meta">
                        @if($companySetting && $companySetting->address)
                            {{ $companySetting->address }}<br>
                        @endif
                        @if($companySetting && ($companySetting->email || $companySetting->phone))
                            Telp: {{ $companySetting->phone ?? '-' }} | Email: {{ $companySetting->email ?? '-' }}<br>
                        @endif
                        @if($companySetting && $companySetting->npwp)
                            NPWP: {{ $companySetting->npwp }}
                        @endif
                    </div>
                </td>
                <td class="doc-title-block" style="width: 45%;">
                    <div class="doc-type-title">{{ $docTitle ?? 'DOKUMEN' }}</div>
                    <table class="doc-meta-table">
                        @if(isset($docNumber))
                            <tr>
                                <td class="meta-label">Nomor:</td>
                                <td class="meta-value">{{ $docNumber }}</td>
                            </tr>
                        @endif
                        @if(isset($docDate))
                            <tr>
                                <td class="meta-label">Tanggal:</td>
                                <td class="meta-value">{{ $docDate }}</td>
                            </tr>
                        @endif
                        @if(isset($docDueDate))
                            <tr>
                                <td class="meta-label">Jatuh Tempo:</td>
                                <td class="meta-value">{{ $docDueDate }}</td>
                            </tr>
                        @endif
                        @if(isset($docStatus))
                            <tr>
                                <td class="meta-label">Status:</td>
                                <td class="meta-value" style="text-transform: uppercase;">{{ $docStatus }}</td>
                            </tr>
                        @endif
                    </table>
                </td>
            </tr>
        </table>
    </header>

    {{-- Main Document Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Signature Block --}}
    @if(!isset($hideSignature) || !$hideSignature)
        <div class="signature-block">
            <table class="signature-table">
                <tr>
                    <td>
                        <div>Dibuat Oleh,</div>
                        <div class="sig-space"></div>
                        <div class="sig-name">{{ $preparedBy ?? 'Staff Administrasi' }}</div>
                        <div class="sig-role">Prepared by</div>
                    </td>
                    <td>
                        <div>Diperiksa Oleh,</div>
                        <div class="sig-space"></div>
                        <div class="sig-name">{{ $reviewedBy ?? 'Manager Operasional' }}</div>
                        <div class="sig-role">Reviewed by</div>
                    </td>
                    <td>
                        <div>Disetujui Oleh,</div>
                        <div class="sig-space"></div>
                        <div class="sig-name">{{ $approvedBy ?? 'Direktur / Finance Head' }}</div>
                        <div class="sig-role">Authorized Signature</div>
                    </td>
                </tr>
            </table>
        </div>
    @endif

    {{-- Footer --}}
    <footer class="footer-note">
        Dokumen ini diterbitkan secara sah melalui Sistem Terintegrasi NEXORA360 ERP pada {{ now()->translatedFormat('d F Y, H:i:s') }} WIB.
    </footer>
</body>
</html>
