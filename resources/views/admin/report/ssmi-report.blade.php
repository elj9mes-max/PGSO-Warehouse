<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Summary of Supplies and Materials Issued (SSMI) - Provincial Government of Aurora</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --font-sans: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
            --font-form: Arial, 'Helvetica Neue', Helvetica, sans-serif;
            --border-ink: #000000;
            --bg-canvas: #0f172a;
            /* Long Paper / Folio (8.5 × 13 in / 215.9 × 330.2 mm) Landscape */
            --paper-width: 330.2mm;
            --paper-height: 215.9mm;
        }

        body {
            font-family: var(--font-sans);
            background-color: var(--bg-canvas);
            color: #0f172a;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-bottom: 60px;
            -webkit-font-smoothing: antialiased;
        }

        /* Top Action Bar (Sticky, hidden on print) */
        .top-toolbar {
            width: 100%;
            background: #1e293b;
            border-bottom: 1px solid #334155;
            padding: 10px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.35);
            flex-wrap: wrap;
            gap: 12px;
        }

        .toolbar-group {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .toolbar-title-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: #334155;
            color: #f8fafc;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-back:hover {
            background: #475569;
            transform: translateX(-2px);
        }

        .toolbar-title {
            color: #ffffff;
            font-size: 1.05rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .badge-standard {
            background: rgba(245, 158, 11, 0.2);
            color: #fbbf24;
            border: 1px solid rgba(245, 158, 11, 0.4);
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }

        .badge-paper {
            background: rgba(56, 189, 248, 0.15);
            color: #38bdf8;
            border: 1px solid rgba(56, 189, 248, 0.35);
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.02em;
        }

        .filter-input-group {
            display: flex;
            align-items: center;
            background: #0f172a;
            border: 1px solid #334155;
            border-radius: 8px;
            padding: 4px 10px;
            gap: 8px;
        }

        .filter-label {
            color: #94a3b8;
            font-size: 0.78rem;
            font-weight: 600;
            white-space: nowrap;
        }

        .filter-input {
            background: transparent;
            border: none;
            color: #f8fafc;
            font-family: var(--font-sans);
            font-size: 0.82rem;
            font-weight: 600;
            outline: none;
            cursor: pointer;
        }

        .filter-input::-webkit-calendar-picker-indicator {
            filter: invert(1);
            cursor: pointer;
        }

        .filter-select {
            background: transparent;
            border: none;
            color: #f8fafc;
            font-family: var(--font-sans);
            font-size: 0.82rem;
            font-weight: 600;
            outline: none;
            cursor: pointer;
            max-width: 160px;
        }

        .filter-select option {
            background: #1e293b;
            color: #f8fafc;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            height: 36px;
            padding: 0 14px;
            border-radius: 8px;
            font-family: var(--font-sans);
            font-size: 0.8125rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            border: 1px solid transparent;
            white-space: nowrap;
        }

        .btn-action-secondary {
            background: #334155;
            color: #e2e8f0;
            border-color: #475569;
        }

        .btn-action-secondary:hover {
            background: #475569;
            color: #ffffff;
        }

        .btn-action-sample {
            background: #1e293b;
            color: #38bdf8;
            border-color: #0284c7;
        }

        .btn-action-sample:hover {
            background: #0284c7;
            color: #ffffff;
        }

        .btn-action-primary {
            background: #2563eb;
            color: #ffffff;
            box-shadow: 0 2px 10px rgba(37, 99, 235, 0.35);
        }

        .btn-action-primary:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
        }

        .btn-action-print {
            background: #059669;
            color: #ffffff;
            box-shadow: 0 2px 10px rgba(5, 150, 105, 0.35);
        }

        .btn-action-print:hover {
            background: #047857;
            transform: translateY(-1px);
        }

        /* Paper Viewport & Container */
        .paper-viewport {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 24px 16px;
            gap: 32px;
            overflow-x: auto;
        }

        .badge-page-indicator {
            display: inline-block;
            margin-left: 10px;
            background: #0f172a;
            color: #38bdf8;
            border: 1px solid #0284c7;
            font-size: 7.2pt;
            font-weight: 700;
            padding: 1px 7px;
            border-radius: 4px;
            letter-spacing: 0.02em;
            vertical-align: middle;
        }

        /* Long Paper Landscape Sheet (Exact 215.9 x 330.2 mm / 8.5 x 13 in) */
        .ssmi-sheet {
            width: var(--paper-width);
            max-width: var(--paper-width);
            background: #ffffff;
            padding: 5mm 6mm 5mm;
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.45);
            font-family: var(--font-form);
            color: #000000;
            display: block;
            position: relative;
            box-sizing: border-box;
        }

        /* Outer Box Border */
        .ssmi-outer-frame {
            border: 1.5px solid var(--border-ink);
            width: 100%;
            display: block;
            box-sizing: border-box;
            background: #ffffff;
        }

        /* Header Area */
        .ssmi-header {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 6px 10px 5px;
            border-bottom: 1.5px solid var(--border-ink);
            position: relative;
        }

        .ssmi-logo-wrap {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2px;
        }

        .ssmi-logo-img {
            width: 54px;
            height: 54px;
            display: block;
            object-fit: contain;
        }

        .ssmi-title-main {
            font-family: var(--font-form);
            font-size: 10.5pt;
            font-weight: 800;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            text-align: center;
            margin-top: 1px;
            line-height: 1.2;
            outline: none;
        }

        .ssmi-title-province {
            font-family: var(--font-form);
            font-size: 10.5pt;
            font-weight: 800;
            text-decoration: underline;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            text-align: center;
            margin-top: 3px;
            line-height: 1.2;
            outline: none;
        }

        .ssmi-title-period {
            font-family: var(--font-form);
            font-size: 9pt;
            font-weight: 600;
            text-align: center;
            margin-top: 4px;
            line-height: 1.2;
            outline: none;
        }

        .ssmi-title-period span {
            font-weight: 700;
        }

        /* Main Table */
        .ssmi-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7.5pt;
            line-height: 1.15;
            table-layout: fixed;
        }

        .ssmi-table th,
        .ssmi-table td {
            border-right: 1px solid var(--border-ink);
            border-bottom: 1px solid var(--border-ink);
            padding: 1.5px 3px;
            vertical-align: middle;
            color: #000000;
        }

        .ssmi-table th:last-child,
        .ssmi-table td:last-child {
            border-right: none;
        }

        /* Header Rows */
        .ssmi-table thead tr th {
            font-weight: 700;
            text-align: center;
            background: #ffffff;
            color: #000000;
            padding: 2px 2px;
            font-size: 7.5pt;
            letter-spacing: 0.01em;
        }

        .th-no {
            width: 9mm;
        }

        .th-stock {
            width: 22mm;
        }

        .th-desc {
            width: 82mm;
            text-align: center;
        }

        .th-unit {
            width: 14mm;
        }

        .th-ris-header {
            letter-spacing: 0.01em;
        }

        .th-ris-col {
            width: 14.5mm;
            max-width: 15.5mm;
            word-break: break-all;
            font-size: 7pt;
            padding: 1px 1px !important;
            font-weight: 700;
            text-align: center;
            vertical-align: middle;
            line-height: 1.1;
        }

        .th-total-qty {
            width: 18mm;
            font-size: 7pt;
            line-height: 1.1;
        }

        .th-cost {
            width: 24mm;
        }

        .th-total-cost {
            width: 26mm;
        }

        .peso-cell {
            font-weight: 800;
            font-size: 8pt;
            text-align: center;
        }

        /* Body Rows */
        .ssmi-table tbody tr {
            height: 16.5px;
        }

        .col-center {
            text-align: center;
        }

        .col-left {
            text-align: left;
            padding-left: 4px !important;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .col-right {
            text-align: right;
            padding-right: 4px !important;
            font-variant-numeric: tabular-nums;
        }

        .cell-mono {
            font-family: var(--font-form);
            font-variant-numeric: tabular-nums;
        }

        /* Editable cell interaction styling */
        [contenteditable="true"] {
            outline: none;
            transition: background 0.15s ease;
        }

        [contenteditable="true"]:hover {
            background: #f1f5f9;
        }

        [contenteditable="true"]:focus {
            background: #eff6ff;
            box-shadow: inset 0 0 0 1px #3b82f6;
        }

        /* Summary Total Row */
        .tr-totals td {
            font-weight: 800;
            background: #ffffff;
            border-top: 1.5px solid var(--border-ink);
            border-bottom: 1.5px solid var(--border-ink);
            font-size: 7.5pt;
            height: 19px;
            padding: 1px 3px;
        }

        /* Signatories Box */
        .ssmi-signatories {
            width: 100%;
            border-collapse: collapse;
            font-size: 8pt;
            margin-top: 2px;
        }

        .ssmi-signatories td {
            border-right: 1.5px solid var(--border-ink);
            padding: 3px 8px 6px;
            vertical-align: top;
        }

        .ssmi-signatories td:last-child {
            border-right: none;
        }

        .sig-col-prepared {
            width: 40%;
        }

        .sig-col-approved {
            width: 40%;
        }

        .sig-col-accounting {
            width: 20%;
        }

        .sig-label {
            font-size: 7.5pt;
            font-weight: 700;
            color: #000000;
            margin-bottom: 14px;
        }

        .sig-name-box {
            text-align: center;
            margin-top: 10px;
        }

        .sig-name {
            font-size: 8.5pt;
            font-weight: 800;
            text-transform: capitalize;
            color: #000000;
            line-height: 1.15;
            outline: none;
        }

        .sig-designation {
            font-size: 7.5pt;
            font-weight: 600;
            color: #000000;
            line-height: 1.15;
            margin-top: 1px;
            outline: none;
        }

        .sig-accounting-box {
            text-align: center;
            margin-top: 28px;
        }

        .sig-accounting-title {
            font-size: 8pt;
            font-weight: 700;
            outline: none;
        }

        /* Table helper status bar */
        .table-status-bar {
            width: var(--paper-width);
            max-width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 12px;
            color: #94a3b8;
            font-size: 0.78rem;
            padding: 0 4px;
        }

        .hint-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #1e293b;
            padding: 4px 10px;
            border-radius: 6px;
            border: 1px solid #334155;
            color: #cbd5e1;
        }

        /* Toast notifications */
        .toast {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: #1e293b;
            color: #ffffff;
            border: 1px solid #334155;
            padding: 10px 18px;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
            transform: translateY(100px);
            opacity: 0;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 2000;
        }

        .toast.show {
            transform: translateY(0);
            opacity: 1;
        }

        /* PRINT STYLES - Exact Long Paper / Folio (215.9 x 330.2 mm) Landscape */
        @media print {
            @page {
                size: 330.2mm 215.9mm;
                /* Exact Long Paper Landscape: Width Height */
                margin: 4.5mm 5mm 4.5mm;
            }

            html,
            body {
                background: #ffffff !important;
                color: #000000 !important;
                padding: 0 !important;
                margin: 0 !important;
                width: 330.2mm !important;
                height: auto !important;
                min-height: auto !important;
                max-height: none !important;
                overflow: visible !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .top-toolbar,
            .table-status-bar,
            .toast {
                display: none !important;
            }

            .paper-viewport {
                padding: 0 !important;
                margin: 0 !important;
                overflow: visible !important;
                width: 100% !important;
                display: block !important;
                gap: 0 !important;
            }

            .ssmi-sheet {
                box-shadow: none !important;
                width: 100% !important;
                max-width: 100% !important;
                height: auto !important;
                min-height: auto !important;
                padding: 0 !important;
                margin: 0 !important;
                display: block !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
                page-break-after: always !important;
                break-after: page !important;
            }

            .ssmi-sheet:last-child {
                page-break-after: auto !important;
                break-after: auto !important;
            }

            .badge-page-indicator {
                background: transparent !important;
                color: #000000 !important;
                border: 1px solid #000000 !important;
                font-size: 7.2pt !important;
                display: none;
            }

            .ssmi-outer-frame {
                border: 1.5px solid #000000 !important;
                width: 100% !important;
                height: auto !important;
                max-height: none !important;
                display: block !important;
                box-sizing: border-box !important;
            }

            .ssmi-header {
                border-bottom: 1.5px solid #000000 !important;
                padding: 4px 6px 3px !important;
                margin: 0 !important;
                display: block !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }

            .ssmi-table {
                border-collapse: collapse !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
                width: 100% !important;
                margin: 0 !important;
                border: none !important;
                table-layout: fixed !important;
            }

            .ssmi-table tr {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
                height: 17.5px !important;
            }

            .ssmi-table th,
            .ssmi-table td {
                border-color: #000000 !important;
                color: #000000 !important;
                padding: 1px 2px !important;
                font-size: 7.2pt !important;
            }

            .ssmi-signatories {
                border-top: 1.5px solid #000000 !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
                width: 100% !important;
                margin: 0 !important;
                display: table !important;
            }

            .ssmi-signatories td {
                border-right: 1.5px solid #000000 !important;
                padding: 3px 6px 4px !important;
            }

            [contenteditable="true"]:hover,
            [contenteditable="true"]:focus {
                background: transparent !important;
                box-shadow: none !important;
            }
        }
    </style>
</head>

<body>

    <!-- TOP ACTION & TOOLBAR -->
    <header class="top-toolbar">
        <div class="toolbar-group">
            <a href="{{ Route::has('admin.reports.index') ? route('admin.reports.index') : (Route::has('reports.index') ? route('reports.index') : url('/admin/reports')) }}"
                class="btn-back" title="Back to Reports Center">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </a>
            <div class="toolbar-title-wrap">
                <span class="toolbar-title">SSMI Report</span>
                <span class="badge-standard">Appendix 64 • GAM</span>
                <span class="badge-paper">215.9 × 330.2 mm (Long Landscape)</span>
                @if($totalPages > 1)
                    <span
                        style="background: rgba(56, 189, 248, 0.2); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.4); padding: 3px 8px; border-radius: 6px; font-size: 0.72rem; font-weight: 700;">
                        {{ $totalPages }} Pages
                    </span>
                @elseif(!empty($ssmiReport))
                    <span
                        style="background: rgba(16, 185, 129, 0.2); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.4); padding: 3px 8px; border-radius: 6px; font-size: 0.72rem; font-weight: 700;">
                        {{ $ssmiReport->report_no }}
                    </span>
                @endif
            </div>
        </div>

        <div class="toolbar-group">
            <!-- Calendar Date Range Filter (From / To) -->
            <form id="filterForm" method="GET" action="{{ route('admin.reports.ssmi') }}" class="toolbar-group">
                <div class="filter-input-group">
                    <label for="from_date" class="filter-label">From:</label>
                    <input type="date" id="from_date" name="from_date"
                        value="{{ $fromDate ?? request('from_date', '') }}"
                        class="filter-input"
                        title="Start Date (Calendar)">
                </div>

                <div class="filter-input-group">
                    <label for="to_date" class="filter-label">To:</label>
                    <input type="date" id="to_date" name="to_date"
                        value="{{ $toDate ?? request('to_date', '') }}"
                        class="filter-input"
                        title="End Date (Calendar)">
                </div>

                <button type="submit" class="btn-action btn-action-primary" style="height: 32px; padding: 0 12px; font-size: 0.8rem;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <span>Filter</span>
                </button>

                @if(!empty($fromDate) || !empty($toDate) || request()->has('from_date') || request()->has('to_date'))
                    <a href="{{ route('admin.reports.ssmi') }}" class="btn-action btn-action-secondary" style="height: 32px; padding: 0 10px; font-size: 0.8rem; text-decoration: none;" title="Clear date filter">
                        Clear
                    </a>
                @endif
            </form>

            <button type="button" class="btn-action btn-action-secondary" id="btnClearForm"
                title="Clear all fields and reset form to blank">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 6h18" />
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" />
                    <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                </svg>
                <span>Blank Form</span>
            </button>

            <!-- Export to CSV -->
            <button type="button" class="btn-action btn-action-secondary" id="btnExportCsv"
                title="Export this table to CSV format">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                    <polyline points="7 10 12 15 17 10" />
                    <line x1="12" y1="15" x2="12" y2="3" />
                </svg>
                <span>CSV</span>
            </button>

            <!-- Print Button -->
            <button type="button" class="btn-action btn-action-print" onclick="window.print()"
                title="Print SSMI (Landscape)">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 6 2 18 2 18 9" />
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                    <rect x="6" y="14" width="12" height="8" />
                </svg>
                <span>Print SSMI</span>
            </button>
        </div>
    </header>

    <!-- DOCUMENT VIEWPORT -->
    <main class="paper-viewport" id="paperViewport">
        @foreach($pages as $pageIndex => $page)
            <div class="ssmi-sheet" data-page-index="{{ $pageIndex }}">
                <div class="ssmi-outer-frame">

                    <!-- HEADER SECTION: Seal + Title + Period -->
                    <div class="ssmi-header">
                        <!-- Provincial Seal of Aurora -->
                        <div class="ssmi-logo-wrap">
                            <img src="{{ asset('aurora-logo.png') }}" alt="Seal of the Province of Aurora (LALAWIGAN NG AURORA)" class="ssmi-logo-img">
                        </div>

                        <!-- Titles -->
                        <div class="ssmi-title-main" contenteditable="true">SUMARY OF SUPPLIES AND MATERIALS ISSUED</div>
                        <div class="ssmi-title-province" contenteditable="true">PROVINCIAL GOVERNMENT OF AURORA</div>
                        <div class="ssmi-title-period">
                            For the period: <span contenteditable="true"
                                class="doc-period-text">{{ $page['period_text'] ?: ($periodRangeText ?: '') }}</span>
                            @if($totalPages > 1)
                                <span class="badge-page-indicator">Page {{ $pageIndex + 1 }} of {{ $totalPages }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- MAIN TABLE GRID -->
                    <table class="ssmi-table ssmi-page-table" data-page-index="{{ $pageIndex }}">
                        <thead>
                            <!-- Header Row 1 -->
                            <tr>
                                <th rowspan="2" class="th-no">No.</th>
                                <th rowspan="2" class="th-stock">STOCK<br>NO.</th>
                                <th class="th-desc">ITEM</th>
                                <th rowspan="2" class="th-unit">UNIT</th>
                                <th colspan="8" class="th-ris-header">Requisition and Issue Slip Numbers</th>
                                <th rowspan="2" class="th-total-qty">Total<br>Quantity<br>Issued</th>
                                <th class="th-cost">UNIT COST</th>
                                <th class="th-total-cost">TOTAL COST</th>
                            </tr>
                            <!-- Header Row 2 -->
                            <tr class="tr-header-sub">
                                <th class="th-desc" style="font-size: 7.5pt; font-weight: 700;">DESCRIPTION</th>
                                @for($c = 0; $c < 8; $c++)
                                    <th class="th-ris-col" contenteditable="true" data-ris-idx="{{ $c }}">{{ $page['ris_headers'][$c] }}</th>
                                @endfor
                                <!-- Currency symbols -->
                                <th class="peso-cell">₱</th>
                                <th class="peso-cell">₱</th>
                            </tr>
                        </thead>
                        <tbody class="ssmi-table-body">
                            <!-- Rows populated dynamically by JS matching 27 rows per sheet -->
                        </tbody>
                        <tfoot>
                            <tr class="tr-totals">
                                <td colspan="4" class="col-right" style="font-weight: 800; padding-right: 12px;">Total:</td>
                                @for($c = 0; $c < 8; $c++)
                                    <td class="col-center cell-mono sum-ris-col" data-ris-sum="{{ $c }}"></td>
                                @endfor
                                <td class="col-center cell-mono sum-total-qty" style="font-weight: 800;"></td>
                                <td></td>
                                <td class="col-right cell-mono sum-grand-total" style="font-weight: 800;"></td>
                            </tr>
                        </tfoot>
                    </table>

                    <!-- FOOTER / SIGNATORIES SECTION -->
                    <table class="ssmi-signatories">
                        <tr>
                            <!-- Prepared By -->
                            <td class="sig-col-prepared">
                                <div class="sig-label">Prepared by:</div>
                                <div class="sig-name-box">
                                    <div class="sig-name" contenteditable="true">{{ $page['prepared_by_name'] }}</div>
                                    <div class="sig-designation" contenteditable="true">{{ $page['prepared_by_designation'] }}</div>
                                </div>
                            </td>

                            <!-- Approved By -->
                            <td class="sig-col-approved">
                                <div class="sig-label">&nbsp;</div>
                                <div class="sig-name-box">
                                    <div class="sig-name" contenteditable="true">{{ $page['approved_by_name'] }}</div>
                                    <div class="sig-designation" contenteditable="true">{{ $page['approved_by_designation'] }}</div>
                                </div>
                            </td>

                            <!-- Accounting -->
                            <td class="sig-col-accounting">
                                <div class="sig-label">&nbsp;</div>
                                <div class="sig-accounting-box">
                                    <div class="sig-accounting-title" contenteditable="true">Accounting</div>
                                </div>
                            </td>
                        </tr>
                    </table>

                </div>
            </div>
        @endforeach
    </main>

    <!-- STATUS BAR / HINTS -->
    <div class="table-status-bar">
        <div class="hint-pill">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <circle cx="12" cy="12" r="10" />
                <line x1="12" y1="16" x2="12" y2="12" />
                <line x1="12" y1="8" x2="12.01" y2="8" />
            </svg>
            <span>Tip: Click directly on any cell to edit descriptions, RIS numbers, quantities, or signatories.</span>
        </div>
        <div class="hint-pill">
            <span>Paper: Long / Folio Landscape (215.9 × 330.2 mm / 8.5 × 13 in) • Exact Fit • {{ $totalPages }} {{ \Illuminate\Support\Str::plural('Page', $totalPages) }}</span>
        </div>
    </div>

    <!-- Notification Toast -->
    <div class="toast" id="toastMessage">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#34d399" stroke-width="2.5">
            <polyline points="20 6 9 17 4 12" />
        </svg>
        <span id="toastText">Data updated successfully</span>
    </div>

    <!-- DATA INITIALIZATION SCRIPT -->
    <script>
        const pagesData = @json($pages);
        const TOTAL_DISPLAY_ROWS = 27;

        // Format currency helper
        function formatNumber(num) {
            if (num === null || num === undefined || isNaN(num) || num === '' || num === 0 || num === '0' || num === 'null') return '';
            return Number(num).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        function parseNumber(val) {
            if (!val) return 0;
            const clean = String(val).replace(/,/g, '').trim();
            const num = parseFloat(clean);
            return isNaN(num) ? 0 : num;
        }

        // Render all sheets
        function renderAllSheets() {
            const sheetElements = document.querySelectorAll('.ssmi-sheet');

            sheetElements.forEach((sheet, pageIdx) => {
                const pageData = pagesData[pageIdx] || { ris_headers: ['', '', '', '', '', '', '', ''], items: [] };
                const tbody = sheet.querySelector('.ssmi-table-body');
                if (!tbody) return;

                tbody.innerHTML = '';
                const items = pageData.items || [];
                const rowCount = Math.max(items.length, TOTAL_DISPLAY_ROWS);

                for (let i = 0; i < rowCount; i++) {
                    const item = items[i] || null;
                    const tr = document.createElement('tr');
                    tr.setAttribute('data-row-idx', i);

                    const rowNo = i + 1;
                    const stockNo = (item && item.stock_no && item.stock_no !== 'null') ? item.stock_no : '';
                    const desc = (item && item.description && item.description !== 'null') ? item.description : '';
                    const unit = (item && item.unit && item.unit !== 'null') ? item.unit : '';
                    const totalQty = (item && item.total_qty && item.total_qty != 0 && item.total_qty !== 'null') ? item.total_qty : '';
                    const unitCost = (item && item.unit_cost && item.unit_cost != 0) ? formatNumber(item.unit_cost) : '';
                    const totalCost = (item && item.total_cost && item.total_cost != 0) ? formatNumber(item.total_cost) : '';

                    let risCellsHtml = '';
                    for (let r = 0; r < 8; r++) {
                        let qVal = '';
                        if (item && item.ris_qtys && item.ris_qtys[r] !== undefined && item.ris_qtys[r] !== null) {
                            const raw = item.ris_qtys[r];
                            if (raw !== '' && raw !== 'null' && raw !== null && raw != 0) {
                                qVal = raw;
                            }
                        }
                        risCellsHtml += `<td class="col-center cell-mono cell-ris-qty" contenteditable="true" data-ris="${r}">${qVal}</td>`;
                    }

                    tr.innerHTML = `
                        <td class="col-center cell-no" style="font-weight: 600;">${rowNo}</td>
                        <td class="col-center cell-stock" contenteditable="true">${stockNo}</td>
                        <td class="col-left cell-desc" contenteditable="true">${desc}</td>
                        <td class="col-center cell-unit" contenteditable="true">${unit}</td>
                        ${risCellsHtml}
                        <td class="col-center cell-mono cell-total-qty" contenteditable="true">${totalQty}</td>
                        <td class="col-right cell-mono cell-unit-cost" contenteditable="true">${unitCost}</td>
                        <td class="col-right cell-mono cell-total-cost" contenteditable="true">${totalCost}</td>
                    `;

                    tbody.appendChild(tr);
                }

                recalculateSheet(sheet);
            });

            attachCellListeners();
        }

        // Attach listeners for live edits
        function attachCellListeners() {
            document.querySelectorAll('.ssmi-sheet').forEach(sheet => {
                const table = sheet.querySelector('.ssmi-table');
                if (!table || table.dataset.listenersAttached) return;
                table.dataset.listenersAttached = 'true';

                table.addEventListener('input', function(e) {
                    const target = e.target;
                    if (!target.isContentEditable) return;

                    const tr = target.closest('tr');
                    if (tr && tr.hasAttribute('data-row-idx')) {
                        if (target.classList.contains('cell-ris-qty')) {
                            recalculateRowFromRis(tr);
                        } else if (target.classList.contains('cell-unit-cost') || target.classList.contains('cell-total-qty')) {
                            recalculateRowFromCost(tr);
                        }
                    }

                    recalculateSheet(sheet);
                });
            });
        }

        // Recalculate row when individual RIS quantities change
        function recalculateRowFromRis(tr) {
            const risCells = tr.querySelectorAll('.cell-ris-qty');
            let sumQty = 0;
            let hasAny = false;

            risCells.forEach((cell) => {
                const val = parseNumber(cell.innerText);
                if (val > 0) {
                    sumQty += val;
                    hasAny = true;
                }
            });

            const totalQtyCell = tr.querySelector('.cell-total-qty');
            if (hasAny && totalQtyCell) {
                totalQtyCell.innerText = sumQty > 0 ? sumQty : '';
            }

            recalculateRowFromCost(tr);
        }

        // Recalculate row total cost
        function recalculateRowFromCost(tr) {
            const totalQtyCell = tr.querySelector('.cell-total-qty');
            const unitCostCell = tr.querySelector('.cell-unit-cost');
            const totalCostCell = tr.querySelector('.cell-total-cost');

            const qtyVal = parseNumber(totalQtyCell?.innerText);
            const costVal = parseNumber(unitCostCell?.innerText);

            if (qtyVal > 0 && costVal > 0) {
                const totalCost = qtyVal * costVal;
                if (totalCostCell) {
                    totalCostCell.innerText = formatNumber(totalCost);
                }
            }
        }

        // Recalculate summary totals at table footer for a specific sheet
        function recalculateSheet(sheet) {
            const table = sheet.querySelector('.ssmi-table');
            if (!table) return;

            const trRows = table.querySelectorAll('.ssmi-table-body tr');
            const risSums = Array(8).fill(0);
            let grandTotalQty = 0;
            let grandTotalCost = 0;

            trRows.forEach(tr => {
                const risCells = tr.querySelectorAll('.cell-ris-qty');
                risCells.forEach((cell, idx) => {
                    const val = parseNumber(cell.innerText);
                    if (val > 0) {
                        risSums[idx] += val;
                    }
                });

                const qtyCell = tr.querySelector('.cell-total-qty');
                if (qtyCell) {
                    const qVal = parseNumber(qtyCell.innerText);
                    if (qVal > 0) grandTotalQty += qVal;
                }

                const costCell = tr.querySelector('.cell-total-cost');
                if (costCell) {
                    const cVal = parseNumber(costCell.innerText);
                    if (cVal > 0) grandTotalCost += cVal;
                }
            });

            // Set footer sums
            for (let r = 0; r < 8; r++) {
                const sumCell = table.querySelector(`.sum-ris-col[data-ris-sum="${r}"]`);
                if (sumCell) {
                    sumCell.innerText = risSums[r] > 0 ? risSums[r] : '';
                }
            }

            const totalQtyEl = table.querySelector('.sum-total-qty');
            if (totalQtyEl) {
                totalQtyEl.innerText = grandTotalQty > 0 ? grandTotalQty : '';
            }

            const grandCostEl = table.querySelector('.sum-grand-total');
            if (grandCostEl) {
                grandCostEl.innerText = grandTotalCost > 0 ? formatNumber(grandTotalCost) : '';
            }
        }

        // Toast display helper
        function showToast(message) {
            const toast = document.getElementById('toastMessage');
            const toastText = document.getElementById('toastText');
            toastText.innerText = message;
            toast.classList.add('show');
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }

        // Setup Buttons and Actions
        document.addEventListener('DOMContentLoaded', function() {
            renderAllSheets();

            // Clear / Reset to completely Blank Form
            const btnClear = document.getElementById('btnClearForm');
            if (btnClear) {
                btnClear.addEventListener('click', function() {
                    document.querySelectorAll('.ssmi-sheet').forEach(sheet => {
                        sheet.querySelectorAll('.ssmi-table-body td[contenteditable="true"]').forEach(td => td.textContent = '');
                        sheet.querySelectorAll('.th-ris-col').forEach(th => th.textContent = '');
                        const periodSpan = sheet.querySelector('.doc-period-text');
                        if (periodSpan) periodSpan.textContent = '';
                        recalculateSheet(sheet);
                    });
                    const fromInput = document.getElementById('from_date');
                    if (fromInput) fromInput.value = '';
                    const toInput = document.getElementById('to_date');
                    if (toInput) toInput.value = '';
                    showToast('Form reset to blank.');
                });
            }

            // Export CSV
            document.getElementById('btnExportCsv').addEventListener('click', function() {
                let csvContent = "data:text/csv;charset=utf-8,";
                csvContent += "SUMMARY OF SUPPLIES AND MATERIALS ISSUED\n";
                csvContent += "PROVINCIAL GOVERNMENT OF AURORA\n";

                const sheets = document.querySelectorAll('.ssmi-sheet');
                sheets.forEach((sheet, sheetIdx) => {
                    const periodText = sheet.querySelector('.doc-period-text')?.innerText || '';
                    csvContent += `\n--- PAGE ${sheetIdx + 1} OF ${sheets.length} (Period: ${periodText}) ---\n`;

                    // Headers
                    const headers = ["No.", "STOCK NO.", "ITEM DESCRIPTION", "UNIT"];
                    const risCols = sheet.querySelectorAll('.th-ris-col');
                    risCols.forEach((th, i) => {
                        const cleanH = th.innerText.trim() || `RIS ${i+1}`;
                        headers.push(`"${cleanH}"`);
                    });
                    headers.push("Total Quantity Issued", "UNIT COST", "TOTAL COST");
                    csvContent += headers.join(",") + "\n";

                    // Rows
                    const rows = sheet.querySelectorAll('.ssmi-table-body tr');
                    rows.forEach(tr => {
                        const desc = tr.querySelector('.cell-desc')?.innerText.trim() || '';
                        if (!desc) return; // skip completely empty rows

                        const no = tr.querySelector('.cell-no')?.innerText.trim() || '';
                        const stock = tr.querySelector('.cell-stock')?.innerText.trim() || '';
                        const unit = tr.querySelector('.cell-unit')?.innerText.trim() || '';

                        const rowData = [no, `"${stock}"`, `"${desc}"`, `"${unit}"`];

                        const risCells = tr.querySelectorAll('.cell-ris-qty');
                        risCells.forEach(c => rowData.push(c.innerText.trim() || ''));

                        const totalQty = tr.querySelector('.cell-total-qty')?.innerText.trim() || '';
                        const unitCost = tr.querySelector('.cell-unit-cost')?.innerText.replace(/,/g, '').trim() || '';
                        const totalCost = tr.querySelector('.cell-total-cost')?.innerText.replace(/,/g, '').trim() || '';

                        rowData.push(totalQty, unitCost, totalCost);
                        csvContent += rowData.join(",") + "\n";
                    });
                });

                const encodedUri = encodeURI(csvContent);
                const link = document.createElement("a");
                link.setAttribute("href", encodedUri);
                link.setAttribute("download", `SSMI_Report_${new Date().toISOString().slice(0, 10)}.csv`);
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                showToast("CSV file exported successfully!");
            });
        });
    </script>
</body>
</html>
