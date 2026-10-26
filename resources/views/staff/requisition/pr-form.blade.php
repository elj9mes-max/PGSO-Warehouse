<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Official Purchase Request (PR) form template - Provincial Government of Aurora">
    <title>Purchase Request (PR) - Provincial Government of Aurora</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800|jetbrains-mono:400,500,600" rel="stylesheet" />

    <style>
        :root {
            --font-main: 'Instrument Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
            --font-print: Arial, 'Helvetica Neue', Helvetica, sans-serif;
            --border-ink: #000000;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-main);
            background: #0f172a;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0 0 40px;
        }

        /* Top Action Bar (Hidden on Print) */
        .top-toolbar {
            width: 100%;
            background: #1e293b;
            border-bottom: 1px solid #334155;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
        }

        .toolbar-left, .toolbar-right {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-tool {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 15px;
            border-radius: 8px;
            font-size: 0.8125rem;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.18s ease;
            border: 1px solid transparent;
        }

        .btn-tool-secondary {
            background: #334155;
            color: #f1f5f9;
            border-color: #475569;
        }

        .btn-tool-secondary:hover {
            background: #475569;
            color: #ffffff;
        }

        .btn-tool-accent {
            background: #1e3a8a;
            color: #bfdbfe;
            border-color: #3b82f6;
        }

        .btn-tool-accent:hover {
            background: #2563eb;
            color: #ffffff;
        }

        .btn-tool-primary {
            background: #059669;
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(5, 150, 105, 0.3);
        }

        .btn-tool-primary:hover {
            background: #047857;
            transform: translateY(-1px);
        }

        .badge-template {
            background: rgba(59, 130, 246, 0.15);
            color: #60a5fa;
            border: 1px solid rgba(59, 130, 246, 0.3);
            padding: 4px 10px;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.02em;
        }

        /* Paper Viewport Container */
        .paper-viewport {
            padding: 24px 16px 50px;
            display: flex;
            justify-content: center;
            width: 100%;
        }

        /* Exact Letter Sheet Dimensions (8.5in x 11in) */
        .pr-sheet {
            width: 8.5in;
            min-height: 11in;
            background: #ffffff;
            padding: 0.4in 0.45in 0.4in;
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.35);
            font-family: var(--font-print);
            color: #000000;
            border: 1px solid #cbd5e1;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        /* The Outer Form Box Border */
        .pr-form-border {
            border: 2px solid var(--border-ink);
            width: 100%;
            display: flex;
            flex-direction: column;
        }

        /* Form Header */
        .pr-header-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2px solid var(--border-ink);
        }

        .pr-header-table td {
            padding: 8px 10px;
            vertical-align: middle;
        }

        .pr-logo-col {
            width: 90px;
            text-align: center;
            padding-left: 14px !important;
        }

        .pr-logo-img {
            width: 72px;
            height: 72px;
            display: block;
            margin: 0 auto;
        }

        .pr-title-col {
            text-align: center;
            padding-right: 90px !important; /* Visual offset to center text relative to page */
        }

        .pr-title-col .line-1 {
            font-size: 11pt;
            letter-spacing: 0.01em;
            line-height: 1.25;
            font-weight: 500;
        }

        .pr-title-col .line-2 {
            font-size: 12pt;
            font-weight: 700;
            letter-spacing: 0.02em;
            line-height: 1.25;
        }

        .pr-title-col .line-title {
            font-size: 15pt;
            font-weight: 800;
            letter-spacing: 0.04em;
            margin-top: 4px;
            line-height: 1.2;
        }

        /* Metadata Information Table (Office, Division, PR No., Date) */
        .pr-meta-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2px solid var(--border-ink);
            table-layout: fixed;
        }

        .pr-meta-table td {
            border-right: 1px solid var(--border-ink);
            padding: 4px 8px;
            font-size: 9.5pt;
            vertical-align: middle;
        }

        .pr-meta-table td:last-child {
            border-right: none;
        }

        /* Column widths match the item table below:
           Col 1 (Stock + Unit) = 18% (10% + 8%)
           Col 2 (Item Description) = 48%
           Col 3 (Qty + Unit Cost + Total Cost) = 34% (8% + 13% + 13%)
        */
        .meta-col-office {
            width: 18%;
            padding: 0 !important;
        }

        .meta-col-prno {
            width: 48%;
            height: 48px;
            vertical-align: middle;
        }

        .meta-col-date {
            width: 34%;
            height: 48px;
            vertical-align: middle;
        }

        .office-division-subtable {
            width: 100%;
            border-collapse: collapse;
            height: 100%;
        }

        .office-division-subtable td {
            border: none;
            padding: 3px 6px;
            font-size: 9pt;
            height: 24px;
        }

        .office-division-subtable tr:first-child td {
            border-bottom: 1px solid var(--border-ink);
        }

        .meta-field-label {
            font-weight: 700;
            color: #000000;
        }

        .meta-field-value {
            font-weight: 400;
            outline: none;
            display: inline-block;
            min-width: 40px;
        }

        .meta-field-value[contenteditable="true"]:hover {
            background: #f8fafc;
        }

        /* Main Purchase Request Items Table */
        .pr-main-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .pr-main-table th {
            border-right: 1px solid var(--border-ink);
            border-bottom: 2px solid var(--border-ink);
            padding: 6px 4px;
            font-size: 9pt;
            font-weight: 700;
            text-align: center;
            background: #ffffff;
            line-height: 1.25;
            vertical-align: middle;
        }

        .pr-main-table td {
            border-right: 1px solid var(--border-ink);
            padding: 4px 6px;
            font-size: 8.5pt;
            line-height: 1.3;
            vertical-align: top;
        }

        .pr-main-table th:last-child,
        .pr-main-table td:last-child {
            border-right: none;
        }

        /* Exact column proportions */
        .col-stock-no {
            width: 10%;
            text-align: center;
        }

        .col-unit {
            width: 8%;
            text-align: center;
        }

        .col-desc {
            width: 48%;
            text-align: left;
        }

        .col-qty {
            width: 8%;
            text-align: center;
        }

        .col-unit-cost {
            width: 13%;
            text-align: right;
            padding-right: 10px !important;
            font-variant-numeric: tabular-nums;
        }

        .col-total-cost {
            width: 13%;
            text-align: right;
            padding-right: 10px !important;
            font-variant-numeric: tabular-nums;
        }

        /* Items Container (Tbody min-height emulation) */
        .item-row {
            height: 24px;
        }

        .item-row td {
            border-bottom: none; /* In official PR forms, vertical borders run down continuously without horizontal row lines, except item breaks */
        }

        .item-title {
            font-weight: 700;
            text-transform: uppercase;
            font-size: 8.5pt;
            display: block;
            margin-bottom: 4px;
        }

        .item-spec-heading {
            font-weight: 700;
            text-transform: uppercase;
            font-size: 8.5pt;
            display: block;
            margin-top: 10px;
            margin-bottom: 3px;
        }

        .item-spec-body {
            font-size: 8.5pt;
            color: #000000;
            line-height: 1.35;
            display: block;
        }

        /* Total Row */
        .pr-total-table {
            width: 100%;
            border-collapse: collapse;
            border-top: 2px solid var(--border-ink);
            border-bottom: 1.5px solid var(--border-ink);
            table-layout: fixed;
        }

        .pr-total-table td {
            border-right: 1px solid var(--border-ink);
            padding: 4px 6px;
            font-size: 9pt;
            font-weight: 700;
            vertical-align: middle;
            height: 26px;
        }

        .pr-total-table td:last-child {
            border-right: none;
        }

        .pr-total-label {
            font-weight: 700;
            letter-spacing: 0.05em;
        }

        .pr-total-amount {
            text-align: right;
            padding-right: 10px !important;
            font-variant-numeric: tabular-nums;
        }

        /* Purpose & Charging Section */
        .pr-meta-bottom-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2px solid var(--border-ink);
            table-layout: fixed;
        }

        .pr-meta-bottom-table td {
            border-right: 1px solid var(--border-ink);
            border-bottom: 1px solid var(--border-ink);
            padding: 5px 8px;
            font-size: 9pt;
            vertical-align: middle;
            height: 26px;
        }

        .pr-meta-bottom-table tr:last-child td {
            border-bottom: none;
        }

        .pr-meta-bottom-table td:last-child {
            border-right: none;
        }

        .col-purpose-charging-left {
            width: 66%; /* 10% + 8% + 48% (aligns with Item Description right border) */
        }

        .col-purpose-charging-right {
            width: 34%; /* 8% + 13% + 13% */
        }

        /* Signatures Section */
        .pr-signatures-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .pr-signatures-table td {
            border-right: 1.5px solid var(--border-ink);
            border-bottom: 1px solid var(--border-ink);
            padding: 4px 8px;
            font-size: 9pt;
            vertical-align: middle;
        }

        .pr-signatures-table tr:last-child td {
            border-bottom: none;
        }

        .pr-signatures-table td:last-child {
            border-right: none;
        }

        .sig-col-half {
            width: 50%;
        }

        .sig-header-cell {
            font-weight: 700;
            height: 24px;
        }

        .sig-space-cell {
            height: 38px;
            vertical-align: top !important;
        }

        .sig-name-cell {
            height: 24px;
        }

        .sig-name-requested {
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        .sig-name-approved {
            font-weight: 700;
            text-transform: uppercase;
            text-align: center;
            letter-spacing: 0.02em;
        }

        .sig-title-cell {
            height: 24px;
        }

        .sig-title-requested {
            font-style: italic;
            font-size: 8.5pt;
        }

        .sig-title-approved {
            font-style: italic;
            font-size: 8.5pt;
            text-align: center;
        }

        .sig-date-cell {
            height: 24px;
        }

        /* Contenteditable subtle cues */
        [contenteditable="true"] {
            outline: none;
            transition: background-color 0.15s ease;
        }

        [contenteditable="true"]:hover {
            background-color: rgba(59, 130, 246, 0.08);
            border-radius: 2px;
        }

        [contenteditable="true"]:focus {
            background-color: rgba(59, 130, 246, 0.15);
            box-shadow: 0 0 0 1px #3b82f6;
            border-radius: 2px;
        }

        /* Print Media Styles */
        @media print {
            @page {
                size: letter portrait;
                margin: 0.35in 0.4in;
            }

            body {
                background: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
                color: #000000 !important;
            }

            .top-toolbar {
                display: none !important;
            }

            .paper-viewport {
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
            }

            .pr-sheet {
                width: 100% !important;
                min-height: 0 !important;
                padding: 0 !important;
                margin: 0 !important;
                box-shadow: none !important;
                border: none !important;
            }

            .pr-form-border {
                border: 2px solid #000000 !important;
            }

            .pr-header-table,
            .pr-meta-table,
            .pr-main-table th,
            .pr-main-table td,
            .pr-total-table,
            .pr-total-table td,
            .pr-meta-bottom-table,
            .pr-meta-bottom-table td,
            .pr-signatures-table,
            .pr-signatures-table td,
            .office-division-subtable td {
                border-color: #000000 !important;
            }

            [contenteditable="true"] {
                background: transparent !important;
                box-shadow: none !important;
            }
        }
    </style>
</head>
<body>

    <!-- Top Action Bar (On-screen only) -->
    <header class="top-toolbar" aria-label="Form Controls Toolbar">
        <div class="toolbar-left">
            <a href="{{ route('staff.requisition.pr') }}" class="btn-tool btn-tool-secondary" title="Back to PR Inventory Catalog">
                <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                <span>Back to PR Catalog</span>
            </a>
            <a href="{{ route('staff.request-stat') }}" class="btn-tool btn-tool-secondary" title="View All Requisition & PR Requests">
                <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                <span>Request Logs</span>
            </a>
            <span class="badge-template">Official LGU Template &bull; Purchase Request (PR)</span>
        </div>

        <div class="toolbar-right">
            <button type="button" class="btn-tool btn-tool-accent" id="loadPdfSampleBtn" title="Load exact sample data shown in the reference PDF">
                <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                <span>Load PDF Sample</span>
            </button>

            <button type="button" class="btn-tool btn-tool-secondary" id="syncCartBtn" title="Sync items drafted in the PR Catalog Tray">
                <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                <span>Sync PR Draft</span>
            </button>

            <button type="button" class="btn-tool btn-tool-secondary" id="addRowBtn" title="Add a new custom line item">
                <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                <span>Add Item</span>
            </button>

            <button type="button" class="btn-tool btn-tool-secondary" id="clearRowsBtn" title="Clear all table rows">
                <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                <span>Clear</span>
            </button>

            <button type="button" class="btn-tool btn-tool-primary" id="printFormBtn" onclick="window.print()" title="Print Form or Save as PDF">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                <span>Print PR Form</span>
            </button>
        </div>
    </header>

    <!-- Paper Sheet Container -->
    <main class="paper-viewport">
        <div class="pr-sheet" id="printablePrDocument">
            <div class="pr-form-border">
                
                <!-- HEADER (Provincial Seal + Government Heading) -->
                <table class="pr-header-table">
                    <tr>
                        <!-- Aurora Provincial Seal Logo (Vector SVG) -->
                        <td class="pr-logo-col">
                            <svg class="pr-logo-img" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Seal of the Province of Aurora">
                                <!-- Outer Ring -->
                                <circle cx="50" cy="50" r="48" fill="#ffffff" stroke="#000000" stroke-width="1.8" />
                                <circle cx="50" cy="50" r="44" fill="#0f5132" stroke="#eab308" stroke-width="2" />
                                
                                <!-- Curved Header Text Simulation -->
                                <path id="textPathTop" d="M 16 50 A 34 34 0 0 1 84 50" fill="none" stroke="none" />
                                <text fill="#ffffff" font-size="7.5" font-weight="bold" font-family="Arial, sans-serif" letter-spacing="1.2">
                                    <textPath href="#textPathTop" startOffset="50%" text-anchor="middle">LALAWIGAN NG AURORA</textPath>
                                </text>

                                <path id="textPathBottom" d="M 84 50 A 34 34 0 0 1 16 50" fill="none" stroke="none" />
                                <text fill="#ffffff" font-size="8.5" font-weight="bold" font-family="Arial, sans-serif" letter-spacing="2">
                                    <textPath href="#textPathBottom" startOffset="50%" text-anchor="middle">★ 1979 ★</textPath>
                                </text>

                                <!-- Inner Circle with Shield -->
                                <circle cx="50" cy="50" r="28" fill="#ffffff" stroke="#eab308" stroke-width="1.5" />
                                
                                <!-- Shield Colors -->
                                <clipPath id="shieldClip">
                                    <path d="M 32 34 L 68 34 L 68 54 C 68 66 50 72 50 72 C 50 72 32 66 32 54 Z" />
                                </clipPath>

                                <g clip-path="url(#shieldClip)">
                                    <!-- Blue Top Half -->
                                    <rect x="32" y="34" width="36" height="20" fill="#1d4ed8" />
                                    <!-- Red Bottom Half -->
                                    <rect x="32" y="54" width="36" height="20" fill="#dc2626" />
                                    <!-- Sun Rays in Center -->
                                    <circle cx="50" cy="54" r="8" fill="#facc15" />
                                    <!-- Mountain Silhouettes -->
                                    <polygon points="32,54 44,45 53,54" fill="#15803d" />
                                    <polygon points="46,54 58,43 68,54" fill="#166534" />
                                    <!-- Sea Waves -->
                                    <path d="M 32 62 Q 41 58 50 62 T 68 62 L 68 72 L 32 72 Z" fill="#0284c7" />
                                </g>

                                <!-- Shield Outline -->
                                <path d="M 32 34 L 68 34 L 68 54 C 68 66 50 72 50 72 C 50 72 32 66 32 54 Z" fill="none" stroke="#eab308" stroke-width="1.8" />
                            </svg>
                        </td>

                        <!-- Heading Titles -->
                        <td class="pr-title-col">
                            <div class="line-1">Republic of the Philippines</div>
                            <div class="line-2">PROVINCIAL GOVERNMENT OF AURORA</div>
                            <div class="line-title">PURCHASE REQUEST</div>
                        </td>
                    </tr>
                </table>

                <!-- METADATA INFORMATION TABLE (Office, Division, PR No., Date) -->
                <table class="pr-meta-table">
                    <tr>
                        <!-- Col 1: Office & Division (stacked with divider line) -->
                        <td class="meta-col-office">
                            <table class="office-division-subtable">
                                <tr>
                                    <td>
                                        <span class="meta-field-label">Office:</span>&nbsp;
                                        <span class="meta-field-value" id="officeField" contenteditable="true">{{ $purchaseRequest->office->office_name ?? $requisition->office->office_name ?? $activeOffice->office_name ?? 'PGSO' }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="meta-field-label">Division:</span>&nbsp;
                                        <span class="meta-field-value" id="divisionField" contenteditable="true">{{ $purchaseRequest->division ?? $requisition->division ?? '' }}</span>
                                    </td>
                                </tr>
                            </table>
                        </td>

                        <!-- Col 2: PR No. -->
                        <td class="meta-col-prno">
                            <span class="meta-field-label">PR No.</span>&nbsp;
                            <span class="meta-field-value" id="prNoField" contenteditable="true">{{ $purchaseRequest->pr_number ?? ($requisition && $requisition->request_type === 'PR' ? $requisition->request_number : '') }}</span>
                        </td>

                        <!-- Col 3: Date -->
                        <td class="meta-col-date">
                            <span class="meta-field-label">Date:</span>&nbsp;
                            <span class="meta-field-value" id="prDateField" contenteditable="true">{{ $purchaseRequest && $purchaseRequest->created_at ? $purchaseRequest->created_at->format('m/d/Y') : ($requisition && $requisition->created_at ? $requisition->created_at->format('m/d/Y') : now()->format('m/d/Y')) }}</span>
                        </td>
                    </tr>
                </table>

                <!-- MAIN TABLE OF ITEMS -->
                <table class="pr-main-table">
                    <thead>
                        <tr>
                            <th class="col-stock-no">Stock<br>Number</th>
                            <th class="col-unit">Unit</th>
                            <th class="col-desc">Item Description</th>
                            <th class="col-qty">Qty.</th>
                            <th class="col-unit-cost">Unit<br>Cost</th>
                            <th class="col-total-cost">Total<br>Cost</th>
                        </tr>
                    </thead>
                    <tbody id="prItemsTableBody">
                        <!-- Dynamic Item & Spacer Rows rendered by JS -->
                    </tbody>
                </table>

                <!-- TOTAL ROW -->
                <table class="pr-total-table">
                    <tr>
                        <td class="col-stock-no pr-total-label">TOTAL</td>
                        <td class="col-unit"></td>
                        <td class="col-desc"></td>
                        <td class="col-qty"></td>
                        <td class="col-unit-cost"></td>
                        <td class="col-total-cost pr-total-amount" id="grandTotalField" contenteditable="true">
                            {{ $purchaseRequest && $purchaseRequest->total_estimated_cost ? number_format($purchaseRequest->total_estimated_cost, 2) : '1,000,000.00' }}
                        </td>
                    </tr>
                </table>

                <!-- PURPOSE & CHARGING SECTION -->
                <table class="pr-meta-bottom-table">
                    <tr>
                        <td class="col-purpose-charging-left">
                            <span class="meta-field-label">Purpose:</span>&nbsp;
                            <span class="meta-field-value" id="purposeField" contenteditable="true">{{ $purchaseRequest->purpose ?? $requisition->purpose ?? 'FOR USE OF PGSO' }}</span>
                        </td>
                        <td class="col-purpose-charging-right"></td>
                    </tr>
                    <tr>
                        <td class="col-purpose-charging-left">
                            <span class="meta-field-label">Charging:</span>&nbsp;
                            <span class="meta-field-value" id="chargingField" contenteditable="true">{{ $purchaseRequest->responsibility_center_code ?? $requisition->responsibility_center_code ?? '1061 1-09-01-020' }}</span>
                        </td>
                        <td class="col-purpose-charging-right"></td>
                    </tr>
                </table>

                <!-- SIGNATURES SECTION (Requested by & Approved by) -->
                <table class="pr-signatures-table">
                    <!-- Heading Row -->
                    <tr>
                        <td class="sig-col-half sig-header-cell">
                            <span>Requested by:</span>
                        </td>
                        <td class="sig-col-half sig-header-cell">
                            <span>Approved by:</span>
                        </td>
                    </tr>

                    <!-- Signature Area Row -->
                    <tr>
                        <td class="sig-col-half sig-space-cell">
                            <span>Signature:</span>
                        </td>
                        <td class="sig-col-half sig-space-cell">
                            <!-- Blank signature area -->
                        </td>
                    </tr>

                    <!-- Name Row -->
                    <tr>
                        <td class="sig-col-half sig-name-cell">
                            <span>Name:</span>&nbsp;&nbsp;&nbsp;
                            <span class="sig-name-requested" id="requestedByNameField" contenteditable="true">{{ $purchaseRequest->requested_by_name ?? ($requisition->requested_by_name ?? 'RICARDO Q. BAUTISTA') }}</span>
                        </td>
                        <td class="sig-col-half sig-name-cell" style="text-align: center;">
                            <span class="sig-name-approved" id="approvedByNameField" contenteditable="true">{{ $purchaseRequest->approved_by_name ?? 'HON. ISIDRO P. GALBAN' }}</span>
                        </td>
                    </tr>

                    <!-- Designation Row -->
                    <tr>
                        <td class="sig-col-half sig-title-cell">
                            <span>Designation:</span>&nbsp;
                            <span class="sig-title-requested" id="requestedByDesignationField" contenteditable="true">{{ $purchaseRequest->requested_by_designation ?? ($requisition->requested_by_designation ?? 'PGDH-PGSO') }}</span>
                        </td>
                        <td class="sig-col-half sig-title-cell" style="text-align: center;">
                            <span class="sig-title-approved" id="approvedByDesignationField" contenteditable="true">{{ $purchaseRequest->approved_by_designation ?? 'GOVERNOR' }}</span>
                        </td>
                    </tr>

                    <!-- Date Row -->
                    <tr>
                        <td class="sig-col-half sig-date-cell">
                            <span>Date:</span>&nbsp;
                            <span class="meta-field-value" id="requestedDateField" contenteditable="true"></span>
                        </td>
                        <td class="sig-col-half sig-date-cell">
                            <!-- Blank date field -->
                        </td>
                    </tr>
                </table>

            </div>
        </div>
    </main>

    <!-- Embedded Server Data (if loaded via controller/route) -->
    <script>
        const serverPurchaseRequest = @json($purchaseRequest ?? null);
        const serverRequisition = @json($requisition ?? null);

        document.addEventListener('DOMContentLoaded', () => {
            const tableBody = document.getElementById('prItemsTableBody');
            const grandTotalField = document.getElementById('grandTotalField');
            const TOTAL_MIN_ROWS = 14; // Gives realistic document height filling the page perfectly

            // Number formatting helper
            function formatCurrency(num) {
                const val = parseFloat(num);
                if (isNaN(val)) return '';
                return val.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }

            function parseCurrency(str) {
                if (!str) return 0;
                const cleaned = String(str).replace(/[^0-9.-]+/g, '');
                const val = parseFloat(cleaned);
                return isNaN(val) ? 0 : val;
            }

            // Recalculate Grand Total from rows
            function updateTotals() {
                let sum = 0;
                const rows = tableBody.querySelectorAll('tr.item-data-row');
                rows.forEach(row => {
                    const totalCell = row.querySelector('.col-total-cost');
                    if (totalCell) {
                        const val = parseCurrency(totalCell.textContent);
                        sum += val;
                    }
                });

                if (sum > 0 || rows.length > 0) {
                    grandTotalField.textContent = formatCurrency(sum);
                }
            }

            // Render table rows
            function renderRows(items = []) {
                tableBody.innerHTML = '';

                // Render actual items
                items.forEach((item, index) => {
                    const tr = document.createElement('tr');
                    tr.className = 'item-row item-data-row';

                    const qty = item.quantity || item.quantity_requested || 1;
                    const unitCost = item.unit_cost || item.estimated_unit_cost || 0;
                    const totalCost = item.total_cost || item.estimated_total_cost || (qty * unitCost);

                    // Formatted description with title + specs
                    let descHtml = '';
                    if (item.title) {
                        descHtml += `<span class="item-title">${item.title}</span>`;
                    }
                    if (item.specification_heading) {
                        descHtml += `<span class="item-spec-heading">${item.specification_heading}</span>`;
                    }
                    if (item.specification) {
                        descHtml += `<span class="item-spec-body">${item.specification}</span>`;
                    }
                    if (!item.title && !item.specification) {
                
                        if (item.variant_name) {
                            descHtml += `<span class="item-spec-body">${item.variant_name}</span>`;
                        }
                    }

                    tr.innerHTML = `
                        <td class="col-stock-no" contenteditable="true">${item.stock_no || item.stock_number || item.item_code || ''}</td>
                        <td class="col-unit" contenteditable="true">${item.unit || 'lot'}</td>
                        <td class="col-desc" contenteditable="true">${descHtml}</td>
                        <td class="col-qty" contenteditable="true">${qty}</td>
                        <td class="col-unit-cost" contenteditable="true">${unitCost ? formatCurrency(unitCost) : ''}</td>
                        <td class="col-total-cost" contenteditable="true">${totalCost ? formatCurrency(totalCost) : ''}</td>
                    `;

                    // Add live calculation events
                    const qtyCell = tr.querySelector('.col-qty');
                    const costCell = tr.querySelector('.col-unit-cost');
                    const totalCell = tr.querySelector('.col-total-cost');

                    function recalcLine() {
                        const q = parseCurrency(qtyCell.textContent);
                        const c = parseCurrency(costCell.textContent);
                        if (q && c) {
                            totalCell.textContent = formatCurrency(q * c);
                        }
                        updateTotals();
                    }

                    qtyCell.addEventListener('input', recalcLine);
                    costCell.addEventListener('input', recalcLine);
                    totalCell.addEventListener('input', updateTotals);

                    tableBody.appendChild(tr);
                });

                // Fill remaining rows to maintain government form layout height
                const emptyRowCount = Math.max(0, TOTAL_MIN_ROWS - items.length);
                for (let i = 0; i < emptyRowCount; i++) {
                    const tr = document.createElement('tr');
                    tr.className = 'item-row item-spacer-row';
                    tr.innerHTML = `
                        <td class="col-stock-no" contenteditable="true"></td>
                        <td class="col-unit" contenteditable="true"></td>
                        <td class="col-desc" contenteditable="true"></td>
                        <td class="col-qty" contenteditable="true"></td>
                        <td class="col-unit-cost" contenteditable="true"></td>
                        <td class="col-total-cost" contenteditable="true"></td>
                    `;
                    tableBody.appendChild(tr);
                }

                updateTotals();
            }

            // PDF Demo Sample Data (Exact replica of user's PDF)
            function getPdfSampleData() {
                return [
                    {
                        stock_no: '',
                        unit: 'lot',
                        title: 'SUPPLY & DELIVERY OF COMPUTER SOFTWARE',
                        specification_heading: 'SPECIFICATION:',
                        specification: 'Upgrading of Property & Supply Management Information Software',
                        quantity: 1,
                        unit_cost: 1000000.00,
                        total_cost: 1000000.00
                    }
                ];
            }

            function loadPdfSample() {
                document.getElementById('officeField').textContent = 'PGSO';
                document.getElementById('divisionField').textContent = '';
                document.getElementById('prNoField').textContent = '';
                document.getElementById('prDateField').textContent = '';
                document.getElementById('purposeField').textContent = 'FOR USE OF PGSO';
                document.getElementById('chargingField').textContent = '1061 1-09-01-020';
                document.getElementById('requestedByNameField').textContent = 'RICARDO Q. BAUTISTA';
                document.getElementById('requestedByDesignationField').textContent = 'PGDH-PGSO';
                document.getElementById('approvedByNameField').textContent = 'HON. ISIDRO P. GALBAN';
                document.getElementById('approvedByDesignationField').textContent = 'GOVERNOR';
                document.getElementById('requestedDateField').textContent = '';

                renderRows(getPdfSampleData());
                grandTotalField.textContent = '1,000,000.00';
            }

            // Try loading from sessionStorage (if drafted via PR creation view)
            function loadFromPrStorage() {
                try {
                    const rawItems = sessionStorage.getItem('pr_draft_items');
                    const savedPurpose = sessionStorage.getItem('pr_purpose');
                    const savedCharging = sessionStorage.getItem('pr_charging');

                    if (savedPurpose) {
                        document.getElementById('purposeField').textContent = savedPurpose;
                    }
                    if (savedCharging) {
                        document.getElementById('chargingField').textContent = savedCharging;
                    }

                    if (rawItems) {
                        const parsed = JSON.parse(rawItems);
                        const items = Object.values(parsed).map(it => ({
                            stock_no: it.code || it.sku || '',
                            unit: it.unit || 'pcs',
                            // title: it.name || it.itemName || 'Warehouse Item',
                            specification: it.variant ? `Variant: ${it.variant}` : '',
                            quantity: it.quantity || 1,
                            unit_cost: it.cost || 0,
                            total_cost: (it.quantity || 1) * (it.cost || 0)
                        }));

                        if (items.length > 0) {
                            renderRows(items);
                            return true;
                        }
                    }
                } catch (e) {
                    console.error('Error loading PR data from sessionStorage:', e);
                }
                return false;
            }

            // Initial load strategy:
            // 1. Server PurchaseRequest or RequisitionRequest with items
            // 2. Storage items
            // 3. Fallback to PDF Sample Data so user immediately sees their PDF replica!
            if (serverPurchaseRequest && serverPurchaseRequest.items && serverPurchaseRequest.items.length > 0) {
                renderRows(serverPurchaseRequest.items);
            } else if (serverRequisition && serverRequisition.items && serverRequisition.items.length > 0) {
                renderRows(serverRequisition.items.map(it => ({
                    stock_no: it.stock_no,
                    unit: it.unit,
                    // title: it.item_name,
                    specification: it.variant_name,
                    quantity: it.quantity_requested,
                    unit_cost: it.unit_cost,
                    total_cost: it.total_cost
                })));
            } else {
                const loadedFromDraft = loadFromPrStorage();
                if (!loadedFromDraft) {
                    loadPdfSample();
                }
            }

            // Button Click Handlers
            const loadPdfSampleBtn = document.getElementById('loadPdfSampleBtn');
            if (loadPdfSampleBtn) {
                loadPdfSampleBtn.addEventListener('click', () => {
                    loadPdfSample();
                });
            }

            const syncCartBtn = document.getElementById('syncCartBtn');
            if (syncCartBtn) {
                syncCartBtn.addEventListener('click', () => {
                    const loaded = loadFromPrStorage();
                    if (loaded) {
                        alert('PR items successfully synchronized from your active PR catalog draft!');
                    } else {
                        alert('No items currently found in your active PR draft tray. Select items in the PR Catalog page first.');
                    }
                });
            }

            const addRowBtn = document.getElementById('addRowBtn');
            if (addRowBtn) {
                addRowBtn.addEventListener('click', () => {
                    const tr = document.createElement('tr');
                    tr.className = 'item-row item-data-row';
                    tr.innerHTML = `
                        <td class="col-stock-no" contenteditable="true"></td>
                        <td class="col-unit" contenteditable="true">pcs</td>
                        <td class="col-desc" contenteditable="true">New Item Description</td>
                        <td class="col-qty" contenteditable="true">1</td>
                        <td class="col-unit-cost" contenteditable="true">0.00</td>
                        <td class="col-total-cost" contenteditable="true">0.00</td>
                    `;

                    const qtyCell = tr.querySelector('.col-qty');
                    const costCell = tr.querySelector('.col-unit-cost');
                    const totalCell = tr.querySelector('.col-total-cost');

                    function recalcLine() {
                        const q = parseCurrency(qtyCell.textContent);
                        const c = parseCurrency(costCell.textContent);
                        if (q && c) {
                            totalCell.textContent = formatCurrency(q * c);
                        }
                        updateTotals();
                    }

                    qtyCell.addEventListener('input', recalcLine);
                    costCell.addEventListener('input', recalcLine);
                    totalCell.addEventListener('input', updateTotals);

                    // Insert before first spacer row or at end
                    const firstSpacer = tableBody.querySelector('.item-spacer-row');
                    if (firstSpacer) {
                        tableBody.insertBefore(tr, firstSpacer);
                        firstSpacer.remove();
                    } else {
                        tableBody.appendChild(tr);
                    }
                    updateTotals();
                    tr.querySelector('.col-desc').focus();
                });
            }

            const clearRowsBtn = document.getElementById('clearRowsBtn');
            if (clearRowsBtn) {
                clearRowsBtn.addEventListener('click', () => {
                    if (confirm('Clear all items from this PR form?')) {
                        renderRows([]);
                        grandTotalField.textContent = '0.00';
                    }
                });
            }
        });
    </script>

</body>
</html>
