<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Official Requisition and Issue Slip (RIS) form template - Provincial Government of Aurora">
    <title>Requisition and Issue Slip (RIS) - Provincial Government of Aurora</title>
    
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
            gap: 12px;
        }

        .btn-tool {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 16px;
            border-radius: 9px;
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
            background: rgba(16, 185, 129, 0.15);
            color: #34d399;
            border: 1px solid rgba(16, 185, 129, 0.3);
            padding: 4px 10px;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.02em;
        }

        /* Paper Container */
        .paper-viewport {
            padding: 30px 16px 50px;
            display: flex;
            justify-content: center;
            width: 100%;
        }

        /* Exact Letter Sheet Dimensions (8.5in x 11in) */
        .ris-sheet {
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
        .ris-form-border {
            border: 1.5px solid var(--border-ink);
            width: 100%;
            display: flex;
            flex-direction: column;
        }

        /* Form Header */
        .ris-header-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 1.5px solid var(--border-ink);
        }

        .ris-header-table td {
            padding: 6px 10px;
            vertical-align: middle;
        }

        .ris-logo-col {
            width: 84px;
            text-align: center;
            padding-left: 12px !important;
        }

        .ris-logo-img {
            width: 70px;
            height: 70px;
            display: block;
            margin: 0 auto;
        }

        .ris-title-col {
            text-align: center;
            padding-right: 90px !important; /* Offset for centered alignment relative to logo */
        }

        .ris-title-col .line-1 {
            font-size: 10.5pt;
            letter-spacing: 0.01em;
            line-height: 1.25;
        }

        .ris-title-col .line-2 {
            font-size: 11pt;
            font-weight: 700;
            letter-spacing: 0.02em;
            line-height: 1.25;
        }

        .ris-title-col .line-3 {
            font-size: 10pt;
            letter-spacing: 0.02em;
            line-height: 1.25;
        }

        .ris-title-col .line-title {
            font-size: 11pt;
            font-weight: 800;
            letter-spacing: 0.02em;
            margin-top: 2px;
            line-height: 1.3;
        }

        /* Meta Information Grid (Division, Office, RIS No, etc) */
        .ris-meta-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 1.5px solid var(--border-ink);
        }

        .ris-meta-table td {
            border-right: 1px solid var(--border-ink);
            border-bottom: 1px solid var(--border-ink);
            padding: 3px 6px;
            font-size: 8.5pt;
            vertical-align: middle;
            height: 24px;
        }

        .ris-meta-table tr:last-child td {
            border-bottom: none;
        }

        .ris-meta-table td:last-child {
            border-right: none;
        }

        .meta-field-label {
            font-weight: 700;
            color: #000000;
        }

        .meta-field-value {
            font-weight: 400;
            outline: none;
        }

        .meta-field-value[contenteditable="true"]:hover {
            background: #f8fafc;
        }

        /* Main Items Table */
        .ris-main-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 1.5px solid var(--border-ink);
        }

        .ris-main-table th,
        .ris-main-table td {
            border-right: 1px solid var(--border-ink);
            border-bottom: 1px solid var(--border-ink);
            padding: 2px 4px;
            font-size: 8pt;
            line-height: 1.2;
        }

        .ris-main-table th:last-child,
        .ris-main-table td:last-child {
            border-right: none;
        }

        .ris-main-table th {
            text-align: center;
            font-weight: 700;
            background: #ffffff;
            font-size: 8.5pt;
            padding: 3px 4px;
        }

        .col-stock-no {
            width: 11%;
            text-align: center;
        }

        .col-unit {
            width: 9%;
            text-align: center;
        }

        .col-desc {
            width: 44%;
            text-align: left;
            padding-left: 6px !important;
        }

        .col-req-qty {
            width: 12%;
            text-align: center;
        }

        .col-iss-qty {
            width: 11%;
            text-align: center;
        }

        .col-remarks {
            width: 13%;
            text-align: left;
        }

        .item-row {
            height: 19px;
        }

        .item-row td {
            vertical-align: middle;
            outline: none;
        }

        .item-row td[contenteditable="true"]:hover {
            background: #f1f5f9;
        }

        /* Special Description Rows (AIR NO, PO NO, SUPPLIER) */
        .special-desc-cell {
            font-weight: 700;
            font-size: 8pt;
            padding-left: 6px !important;
        }

        /* Purpose Section */
        .ris-purpose-box {
            width: 100%;
            border-bottom: 1.5px solid var(--border-ink);
            padding: 4px 6px;
            min-height: 38px;
            font-size: 8.5pt;
            display: flex;
            align-items: flex-start;
            gap: 6px;
        }

        .purpose-label {
            font-weight: 800;
            font-size: 8.5pt;
            flex-shrink: 0;
        }

        .purpose-content {
            flex: 1;
            font-size: 8.5pt;
            outline: none;
            min-height: 28px;
        }

        .purpose-content[contenteditable="true"]:hover {
            background: #f8fafc;
        }

        /* Signatures Table */
        .ris-signatures-table {
            width: 100%;
            border-collapse: collapse;
        }

        .ris-signatures-table th,
        .ris-signatures-table td {
            border-right: 1px solid var(--border-ink);
            border-bottom: 1px solid var(--border-ink);
            padding: 3px 6px;
            font-size: 8pt;
        }

        .ris-signatures-table tr:last-child td {
            border-bottom: none;
        }

        .ris-signatures-table th:last-child,
        .ris-signatures-table td:last-child {
            border-right: none;
        }

        .ris-signatures-table th {
            font-weight: 700;
            text-align: center;
            font-size: 8.5pt;
            height: 22px;
            background: #ffffff;
        }

        .col-sig-label {
            width: 16%;
            font-weight: 700;
            font-size: 8pt;
            text-align: left;
            padding-left: 6px !important;
        }

        .col-sig-1, .col-sig-2, .col-sig-3, .col-sig-4 {
            width: 21%;
            text-align: center;
        }

        .sig-space-row td {
            height: 28px;
            vertical-align: middle;
        }

        .sig-name-row td {
            font-weight: 800;
            font-size: 8.5pt;
            height: 22px;
            vertical-align: middle;
        }

        .sig-desig-row td {
            font-size: 8pt;
            font-weight: 600;
            height: 20px;
            vertical-align: middle;
        }

        .sig-date-row td {
            height: 20px;
            vertical-align: middle;
            font-size: 8pt;
        }

        /* ================= PRINT MEDIA STYLES ================= */
        @media print {
            @page {
                size: letter portrait;
                margin: 8mm 10mm 8mm 10mm;
            }

            html, body {
                background: #ffffff !important;
                color: #000000 !important;
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
            }

            .top-toolbar {
                display: none !important;
            }

            .paper-viewport {
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
            }

            .ris-sheet {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
                width: 100% !important;
                min-height: auto !important;
            }

            .ris-form-border {
                border: 1.5px solid #000000 !important;
            }

            .ris-header-table,
            .ris-meta-table,
            .ris-main-table,
            .ris-purpose-box,
            .ris-signatures-table {
                border-color: #000000 !important;
            }

            th, td {
                border-color: #000000 !important;
            }

            [contenteditable="true"] {
                background: transparent !important;
            }
        }
    </style>
</head>
<body>

    <!-- Top Action Bar (On-screen only) -->
    <header class="top-toolbar" aria-label="Form Controls Toolbar">
        <div class="toolbar-left">
            <a href="{{ route('staff.requisition.ris') }}" class="btn-tool btn-tool-secondary">
                <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                <span>Back to RIS Inventory</span>
            </a>
            <span class="badge-template">Official LGU Template &bull; Appendix 63</span>
        </div>

        <div class="toolbar-right">
            <button type="button" class="btn-tool btn-tool-secondary" id="syncCartBtn" title="Sync items selected from the RIS Cart">
                <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                <span>Sync from Cart</span>
            </button>

            <button type="button" class="btn-tool btn-tool-secondary" id="clearRowsBtn" title="Clear all table rows">
                <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                <span>Clear Items</span>
            </button>

            <button type="button" class="btn-tool btn-tool-primary" id="printFormBtn" onclick="window.print()">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                <span>Print RIS Slip</span>
            </button>
        </div>
    </header>

    <!-- Paper Sheet Container -->
    <main class="paper-viewport">
        <div class="ris-sheet" id="printableRisDocument">
            <div class="ris-form-border">
                
                <!-- HEADER TABLE (Logo + Province Title) -->
                <table class="ris-header-table">
                    <tr>
                        <!-- Aurora Provincial Seal Logo (Vector SVG) -->
                        <td class="ris-logo-col">
                            <svg class="ris-logo-img" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Seal of the Province of Aurora">
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
                        <td class="ris-title-col">
                            <div class="line-1">Republic of the Philippines</div>
                            <div class="line-2">PROVINCIAL GOVERNMENT OF AURORA</div>
                            <div class="line-3">BALER, AURORA</div>
                            <div class="line-title">REQUISITION AND ISSUE SLIP (RIS)</div>
                        </td>
                    </tr>
                </table>

                <!-- METADATA INFORMATION TABLE -->
                <table class="ris-meta-table">
                    <tr>
                        <td style="width: 32%;">
                            <span class="meta-field-label">DIVISION:</span>
                            <span class="meta-field-value" id="divisionField" contenteditable="true">{{ $requisition->division ?? '' }}</span>
                        </td>
                        <td style="width: 25%;">
                            <span class="meta-field-label">RESPONSIBILITY CENTER CODE:</span>
                            <span class="meta-field-value" id="respCodeField" contenteditable="true">{{ $requisition->responsibility_center_code ?? '1061' }}</span>
                        </td>
                        <td style="width: 21%;">
                            <span class="meta-field-label">RIS NO.</span>
                            <span class="meta-field-value" id="risNoField" contenteditable="true">{{ $requisition->ris_no ?? $requisition->request_number ?? '' }}</span>
                        </td>
                        <td style="width: 22%;">
                            <span class="meta-field-label">DATE:(mm/dd/yyyy)</span>
                            <span class="meta-field-value" id="risDateField" contenteditable="true">{{ $requisition && $requisition->created_at ? $requisition->created_at->format('m/d/Y') : now()->format('m/d/Y') }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <span class="meta-field-label">OFFICE:</span>
                            <span class="meta-field-value" id="officeField" contenteditable="true">{{ $requisition->office->office_name ?? $activeOffice->office_name ?? 'PGSO' }}</span>
                        </td>
                        <td colspan="2">
                            <span class="meta-field-label">SAI NO.</span>
                            <span class="meta-field-value" id="saiNoField" contenteditable="true">{{ $requisition->sai_no ?? '' }}</span>
                        </td>
                    </tr>
                </table>

                <!-- MAIN TABLE OF ITEMS (REQUISITION & ISSUANCE) -->
                <table class="ris-main-table" id="risItemsTable">
                    <thead>
                        <!-- Category Spanning Headers -->
                        <tr>
                            <th colspan="4">REQUISITION</th>
                            <th colspan="2">ISSUANCE</th>
                        </tr>
                        <!-- Subheader Columns -->
                        <tr>
                            <th class="col-stock-no">STOCK NO.</th>
                            <th class="col-unit">UNIT</th>
                            <th class="col-desc">DESCRIPTION</th>
                            <th class="col-req-qty">QUANTITY</th>
                            <th class="col-iss-qty">QUANTITY</th>
                            <th class="col-remarks">REMARKS</th>
                        </tr>
                    </thead>
                    <tbody id="risTableBody">
                        <!-- Standard Ruled Rows (Populated by JavaScript or Blank) -->
                    </tbody>
                </table>

                <!-- PURPOSE SECTION -->
                <div class="ris-purpose-box">
                    <span class="purpose-label">PURPOSE:</span>
                    <span class="purpose-content" id="purposeContentField" contenteditable="true">{{ $requisition->purpose ?? 'Official Office Supply & Operations Consumption' }}</span>
                </div>

                <!-- SIGNATURES BLOCK -->
                <table class="ris-signatures-table">
                    <thead>
                        <tr>
                            <th class="col-sig-label"></th>
                            <th class="col-sig-1">REQUESTED BY:</th>
                            <th class="col-sig-2">APPROVED BY:</th>
                            <th class="col-sig-3">ISSUED BY:</th>
                            <th class="col-sig-4">RECEIVED BY:</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Signature Space Row -->
                        <tr class="sig-space-row">
                            <td class="col-sig-label">SIGNATURE</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>

                        <!-- Printed Name Row -->
                        <tr class="sig-name-row">
                            <td class="col-sig-label">PRINTED NAME</td>
                            <td contenteditable="true" id="requestedByName">{{ $requisition->requested_by_name ?? ($requisition->user?->full_name ?? 'RICARDO Q. BAUTISTA') }}</td>
                            <td contenteditable="true" id="approvedByName">{{ $requisition->approved_by_name ?? 'RICARDO Q. BAUTISTA' }}</td>
                            <td contenteditable="true" id="issuedByName">{{ $requisition->issued_by_name ?? 'RAYMOND G. AMAT' }}</td>
                            <td contenteditable="true" id="receivedByName">{{ $requisition->received_by_name ?? 'IMEE SALAMANCA' }}</td>
                        </tr>

                        <!-- Designation Row -->
                        <tr class="sig-desig-row">
                            <td class="col-sig-label">DESIGNATION</td>
                            <td contenteditable="true" id="requestedByDesig">{{ $requisition->requested_by_designation ?? 'PGDH-PGSO' }}</td>
                            <td contenteditable="true" id="approvedByDesig">{{ $requisition->approved_by_designation ?? 'PGDH-PGSO' }}</td>
                            <td contenteditable="true" id="issuedByDesig">{{ $requisition->issued_by_designation ?? 'AA-III' }}</td>
                            <td contenteditable="true" id="receivedByDesig">{{ $requisition->received_by_designation ?? 'AA-I' }}</td>
                        </tr>

                        <!-- Date Row -->
                        <tr class="sig-date-row">
                            <td class="col-sig-label">DATE</td>
                            <td contenteditable="true"></td>
                            <td contenteditable="true"></td>
                            <td contenteditable="true"></td>
                            <td contenteditable="true"></td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </main>

    <!-- JavaScript to Handle Automatic Table Grid, Cart Sync, and Printing -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const tableBody = document.getElementById('risTableBody');
            const TOTAL_ROWS = 27; // Exactly fills standard letter sheet height

            // Server-passed requisition data (loaded from database if requested)
            @php
                $serverRequisitionData = $requisition ? [
                    'risNo' => $requisition->ris_no ?? $requisition->request_number,
                    'purpose' => $requisition->purpose,
                    'airNumber' => $requisition->air_number,
                    'poNumber' => $requisition->po_number,
                    'supplier' => $requisition->supplier,
                    'items' => $requisition->items->map(function ($it) {
                        return [
                            'stockNo' => $it->stock_no ?? $it->variant?->sku ?? '',
                            'unit' => $it->unit ?? $it->variant?->effective_unit ?? 'pcs',
                            'description' => ($it->item_name ?? 'Item') . ($it->variant_name ? ' (' . $it->variant_name . ')' : ''),
                            'quantity' => $it->quantity_requested,
                            'issuedQuantity' => $it->quantity_issued > 0 ? $it->quantity_issued : '',
                            'remarks' => $it->remarks ?? '',
                        ];
                    })->values()->all(),
                ] : null;
            @endphp
            const serverRequisition = {!! json_encode($serverRequisitionData) !!};

            // Sample default template data or load from cart
            function renderTableRows(items = []) {
                tableBody.innerHTML = '';

                // Special meta rows indices towards the bottom (AIR NO, PO NO, SUPPLIER)
                const airRowIndex = TOTAL_ROWS - 6; // Row 21
                const poRowIndex = TOTAL_ROWS - 5;  // Row 22
                const supplierRowIndex = TOTAL_ROWS - 4; // Row 23
                const dotRowIndex = TOTAL_ROWS - 3; // Row 24

                const airVal = serverRequisition?.airNumber ? ` ${serverRequisition.airNumber}` : '';
                const poVal = serverRequisition?.poNumber ? ` ${serverRequisition.poNumber}` : '';
                const supplierVal = serverRequisition?.supplier ? ` ${serverRequisition.supplier}` : '';

                for (let i = 0; i < TOTAL_ROWS; i++) {
                    const tr = document.createElement('tr');
                    tr.className = 'item-row';

                    const item = items[i] || null;

                    if (item) {
                        tr.innerHTML = `
                            <td class="col-stock-no" contenteditable="true">${item.stockNo || item.sku || ''}</td>
                            <td class="col-unit" contenteditable="true">${item.unit || ''}</td>
                            <td class="col-desc" contenteditable="true">${item.description || item.name || ''}</td>
                            <td class="col-req-qty" contenteditable="true">${item.quantity ?? ''}</td>
                            <td class="col-iss-qty" contenteditable="true">${item.issuedQuantity ?? ''}</td>
                            <td class="col-remarks" contenteditable="true">${item.remarks || ''}</td>
                        `;
                    } else if (i === airRowIndex) {
                        tr.innerHTML = `
                            <td class="col-stock-no" contenteditable="true"></td>
                            <td class="col-unit" contenteditable="true"></td>
                            <td class="col-desc special-desc-cell" contenteditable="true">AIR NUMBER:${airVal}</td>
                            <td class="col-req-qty" contenteditable="true"></td>
                            <td class="col-iss-qty" contenteditable="true"></td>
                            <td class="col-remarks" contenteditable="true"></td>
                        `;
                    } else if (i === poRowIndex) {
                        tr.innerHTML = `
                            <td class="col-stock-no" contenteditable="true"></td>
                            <td class="col-unit" contenteditable="true"></td>
                            <td class="col-desc special-desc-cell" contenteditable="true">P.O NUMBER:${poVal}</td>
                            <td class="col-req-qty" contenteditable="true"></td>
                            <td class="col-iss-qty" contenteditable="true"></td>
                            <td class="col-remarks" contenteditable="true"></td>
                        `;
                    } else if (i === supplierRowIndex) {
                        tr.innerHTML = `
                            <td class="col-stock-no" contenteditable="true"></td>
                            <td class="col-unit" contenteditable="true"></td>
                            <td class="col-desc special-desc-cell" contenteditable="true">SUPPLIER:${supplierVal}</td>
                            <td class="col-req-qty" contenteditable="true"></td>
                            <td class="col-iss-qty" contenteditable="true"></td>
                            <td class="col-remarks" contenteditable="true"></td>
                        `;
                    } else if (i === dotRowIndex) {
                        tr.innerHTML = `
                            <td class="col-stock-no" contenteditable="true"></td>
                            <td class="col-unit" contenteditable="true"></td>
                            <td class="col-desc" contenteditable="true">.</td>
                            <td class="col-req-qty" contenteditable="true"></td>
                            <td class="col-iss-qty" contenteditable="true"></td>
                            <td class="col-remarks" contenteditable="true"></td>
                        `;
                    } else {
                        tr.innerHTML = `
                            <td class="col-stock-no" contenteditable="true"></td>
                            <td class="col-unit" contenteditable="true"></td>
                            <td class="col-desc" contenteditable="true"></td>
                            <td class="col-req-qty" contenteditable="true"></td>
                            <td class="col-iss-qty" contenteditable="true"></td>
                            <td class="col-remarks" contenteditable="true"></td>
                        `;
                    }

                    tableBody.appendChild(tr);
                }
            }

            // Sync from sessionStorage (loaded from staff RIS catalog cart)
            function loadFromCart() {
                try {
                    const rawCart = sessionStorage.getItem('ris_cart_items');
                    const savedPurpose = sessionStorage.getItem('ris_purpose');
                    const savedRisNo = sessionStorage.getItem('ris_no');

                    if (savedPurpose) {
                        const purposeElem = document.getElementById('purposeContentField');
                        if (purposeElem) purposeElem.textContent = savedPurpose;
                    }

                    if (savedRisNo) {
                        const risNoElem = document.getElementById('risNoField');
                        if (risNoElem) risNoElem.textContent = savedRisNo;
                    }

                    if (rawCart) {
                        const cartObj = JSON.parse(rawCart);
                        const items = Object.values(cartObj).map(item => ({
                            stockNo: item.sku || '',
                            unit: item.unit || '',
                            description: item.name + (item.variant ? ` (${item.variant})` : ''),
                            quantity: item.quantity || 1
                        }));

                        renderTableRows(items);
                        return true;
                    }
                } catch (e) {
                    console.error('Error loading cart data from sessionStorage:', e);
                }
                return false;
            }

            // Initial render: Prioritize server-loaded requisition, then cart, then blank template
            if (serverRequisition && serverRequisition.items && serverRequisition.items.length > 0) {
                renderTableRows(serverRequisition.items);
            } else {
                const loaded = loadFromCart();
                if (!loaded) {
                    renderTableRows([]);
                }
            }

            // Sync Button Click
            const syncCartBtn = document.getElementById('syncCartBtn');
            if (syncCartBtn) {
                syncCartBtn.addEventListener('click', () => {
                    const success = loadFromCart();
                    if (success) {
                        alert('RIS Items successfully synchronized from your active catalog cart!');
                    } else {
                        alert('No items currently found in your active RIS cart. Select items from the RIS Inventory page and click "Sync from Cart".');
                    }
                });
            }

            // Clear Button Click
            const clearRowsBtn = document.getElementById('clearRowsBtn');
            if (clearRowsBtn) {
                clearRowsBtn.addEventListener('click', () => {
                    if (confirm('Clear all items from this form?')) {
                        renderTableRows([]);
                    }
                });
            }
        });
    </script>

</body>
</html>
