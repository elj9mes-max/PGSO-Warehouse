<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Official Acceptance and Inspection Report (IAR / AIR) Form - Provincial Government of Aurora">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Acceptance and Inspection Report (IAR) - A4 Printable</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --font-ui: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
            --font-doc: Arial, 'Helvetica Neue', Helvetica, sans-serif;
            --ink: #000000;
            --line-width: 1.5px;
            --line-thin: 1px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-ui);
            background: #0b0f19;
            color: #e2e8f0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* ================= ON-SCREEN TOOLBAR ================= */
        .iar-toolbar {
            width: 100%;
            background: #111827;
            border-bottom: 1px solid #1f2937;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(8px);
        }

        .toolbar-left, .toolbar-center, .toolbar-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-toolbar {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 0.8125rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.15s ease;
            border: 1px solid transparent;
            font-family: var(--font-ui);
        }

        .btn-toolbar-ghost {
            background: #1f2937;
            color: #cbd5e1;
            border-color: #374151;
        }

        .btn-toolbar-ghost:hover {
            background: #374151;
            color: #ffffff;
            border-color: #4b5563;
        }

        .btn-toolbar-primary {
            background: #059669;
            color: #ffffff;
            border-color: #10b981;
            box-shadow: 0 2px 10px rgba(5, 150, 105, 0.35);
        }

        .btn-toolbar-primary:hover {
            background: #047857;
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(5, 150, 105, 0.45);
        }

        .badge-a4 {
            background: rgba(16, 185, 129, 0.12);
            color: #34d399;
            border: 1px solid rgba(16, 185, 129, 0.25);
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .badge-a4::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #10b981;
        }

        /* Status Alert Banner for newly confirmed receipts */
        .iar-status-banner {
            width: 100%;
            background: linear-gradient(135deg, #064e3b 0%, #065f46 100%);
            border-bottom: 1.5px solid #10b981;
            padding: 10px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: #ecfdf5;
            font-size: 0.8125rem;
            box-shadow: 0 4px 14px rgba(6, 78, 59, 0.45);
            position: sticky;
            top: 57px;
            z-index: 99;
            animation: iarBannerSlide 0.25s ease-out;
        }

        @keyframes iarBannerSlide {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .iar-status-content {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .iar-status-icon {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: #10b981;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 2px 6px rgba(16, 185, 129, 0.3);
        }

        .iar-status-po {
            background: rgba(255, 255, 255, 0.18);
            padding: 2px 8px;
            border-radius: 5px;
            font-family: var(--font-mono);
            font-weight: 700;
            letter-spacing: 0.02em;
            margin-left: 6px;
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.25);
        }

        .iar-status-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* ================= SHEET VIEWPORT ================= */
        .iar-viewport {
            padding: 30px 16px 60px;
            display: flex;
            justify-content: center;
            width: 100%;
        }

        /*
           Exact A4 Sheet in Screen Mode:
           210mm x 297mm.
           Screen margin: 8mm top/bottom, 10mm left/right.
           Printable area: 190mm wide x 281mm tall.
        */
        .iar-sheet {
            width: 210mm;
            height: 297mm;
            max-height: 297mm;
            background: #ffffff;
            padding: 8mm 10mm 8mm 10mm;
            box-shadow: 0 16px 45px rgba(0, 0, 0, 0.5), 0 2px 8px rgba(0, 0, 0, 0.2);
            font-family: var(--font-doc);
            color: var(--ink);
            display: flex;
            flex-direction: column;
            box-sizing: border-box;
            position: relative;
            overflow: hidden;
        }

        /* The Outer Form Enclosure Box */
        .iar-form-border {
            border: var(--line-width) solid var(--ink);
            width: 100%;
            height: 100%;
            max-height: 100%;
            display: flex;
            flex-direction: column;
            background: #ffffff;
            box-sizing: border-box;
            overflow: hidden;
        }

        /* ================= 1. HEADER ROW ================= */
        .iar-header {
            width: 100%;
            border-bottom: var(--line-width) solid var(--ink);
            display: flex;
            align-items: center;
            padding: 4px 12px;
            position: relative;
            height: 58px;
            flex-shrink: 0;
            box-sizing: border-box;
        }

        .iar-logo-wrapper {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .iar-logo-img {
            width: 46px;
            height: 46px;
            display: block;
            object-fit: contain;
        }

        .iar-title-wrapper {
            width: 100%;
            text-align: center;
            padding: 0 55px; /* Offset to keep title strictly centered relative to form */
        }

        .iar-main-title {
            font-size: 13pt;
            font-weight: 800;
            letter-spacing: 0.04em;
            color: #000000;
            text-transform: uppercase;
            line-height: 1.2;
        }

        /* ================= 2. METADATA SECTION ================= */
        .iar-meta-grid {
            width: 100%;
            border-bottom: var(--line-width) solid var(--ink);
            display: flex;
            height: 80px;
            flex-shrink: 0;
            box-sizing: border-box;
        }

        /* Left Side: Supplier, PO No, Date, Requisitioning Office */
        .iar-meta-left {
            width: 63%;
            border-right: var(--line-width) solid var(--ink);
            display: flex;
            flex-direction: column;
            justify-content: space-around;
            padding: 2px 8px;
            font-size: 8.5pt;
            height: 100%;
            box-sizing: border-box;
        }

        .meta-row {
            display: flex;
            align-items: center;
            height: 24px;
            padding: 0;
        }

        .meta-label {
            font-weight: 700;
            white-space: nowrap;
            margin-right: 5px;
            font-size: 8.5pt;
        }

        .meta-fill {
            flex: 1;
            font-weight: 500;
            color: #000000;
            outline: none;
            min-height: 18px;
            display: inline-block;
        }

        .meta-fill[contenteditable="true"]:hover,
        .meta-fill[contenteditable="true"]:focus {
            background: #f1f5f9;
        }

        .meta-po-date-split {
            display: flex;
            align-items: center;
            width: 100%;
        }

        .meta-po-col {
            display: flex;
            align-items: center;
            flex: 1.4;
        }

        .meta-date-col {
            display: flex;
            align-items: center;
            flex: 1;
            padding-left: 10px;
        }

        /* Right Side: AIR, Date, Invoice No., Date */
        .iar-meta-right {
            width: 37%;
            display: flex;
            flex-direction: column;
            font-size: 8.5pt;
            height: 100%;
            box-sizing: border-box;
        }

        .meta-cell-right {
            display: flex;
            align-items: center;
            padding: 0 8px;
            border-bottom: var(--line-thin) solid var(--ink);
            height: 25%;
            box-sizing: border-box;
        }

        .meta-cell-right:last-child {
            border-bottom: none;
        }

        /* ================= 3. ITEMS TABLE ================= */
        .iar-table-container {
            width: 100%;
            flex: 1;
            min-height: 0;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-sizing: border-box;
        }

        .iar-items-table {
            width: 100%;
            height: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .iar-items-table thead,
        .iar-items-table thead tr {
            height: 26px;
        }

        .iar-items-table th {
            height: 26px;
            border-bottom: var(--line-width) solid var(--ink);
            border-right: var(--line-thin) solid var(--ink);
            font-size: 8pt;
            font-weight: 800;
            text-transform: uppercase;
            text-align: center;
            padding: 2px 4px;
            background: #ffffff;
            line-height: 1.2;
            letter-spacing: 0.02em;
            vertical-align: middle;
            box-sizing: border-box;
        }

        .iar-items-table th:last-child {
            border-right: none;
        }

        /* Column Width Distribution */
        .col-stock-no {
            width: 17%;
            text-align: center;
        }

        .col-description {
            width: 55%;
            text-align: left;
        }

        .col-unit {
            width: 14%;
            text-align: center;
        }

        .col-qty {
            width: 14%;
            text-align: center;
        }

        /* Table Body & Rows: evenly distributes remaining container space */
        .iar-items-table tbody tr {
            height: 30px;
        }

        .iar-items-table td {
            border-right: var(--line-thin) solid var(--ink);
            border-bottom: var(--line-thin) solid #d1d5db;
            padding: 2px 6px;
            font-size: 8pt;
            line-height: 1.25;
            vertical-align: middle;
            color: #000000;
            outline: none;
            box-sizing: border-box;
        }

        .iar-items-table tbody tr:last-child td {
            border-bottom: none;
        }

        .iar-items-table td:last-child {
            border-right: none;
        }

        .iar-items-table td.col-stock-no,
        .iar-items-table td.col-unit,
        .iar-items-table td.col-qty {
            text-align: center;
        }

        .iar-items-table td.col-description {
            text-align: left;
            padding-left: 8px;
        }

        .iar-items-table td[contenteditable="true"]:hover,
        .iar-items-table td[contenteditable="true"]:focus {
            background: #f8fafc;
        }

        /* Clean last row border */
        .iar-items-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* ================= 4. BOTTOM ACCEPTANCE & INSPECTION ================= */
        .iar-bottom-section {
            width: 100%;
            border-top: var(--line-width) solid var(--ink);
            display: flex;
            height: 155px;
            flex-shrink: 0;
            box-sizing: border-box;
        }

        .iar-bottom-col {
            width: 50%;
            display: flex;
            flex-direction: column;
            height: 100%;
            box-sizing: border-box;
        }

        .iar-bottom-left {
            border-right: var(--line-width) solid var(--ink);
        }

        .section-header-bar {
            text-align: center;
            font-weight: 800;
            font-size: 9pt;
            letter-spacing: 0.04em;
            padding: 3px 6px;
            border-bottom: var(--line-thin) solid var(--ink);
            text-transform: uppercase;
            height: 22px;
            flex-shrink: 0;
            box-sizing: border-box;
            line-height: 1.2;
        }

        .section-body {
            padding: 6px 10px 8px 10px;
            flex: 1;
            min-height: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            font-size: 8pt;
            box-sizing: border-box;
        }

        .status-row {
            display: flex;
            align-items: center;
            margin-bottom: 4px;
            font-size: 8pt;
        }

        .date-line {
            display: flex;
            align-items: baseline;
            width: 100%;
        }

        .date-label {
            font-weight: 700;
            white-space: nowrap;
            margin-right: 4px;
        }

        .date-underline {
            flex: 1;
            border-bottom: 1px solid var(--ink);
            min-height: 16px;
            outline: none;
            padding-left: 4px;
            font-weight: 600;
        }

        /* Checkbox items */
        .checkbox-group {
            display: flex;
            flex-direction: column;
            gap: 4px;
            margin-top: 3px;
        }

        .checkbox-item {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            cursor: pointer;
            user-select: none;
        }

        .box-square {
            width: 13px;
            height: 13px;
            border: 1.2px solid var(--ink);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: 800;
            line-height: 1;
            flex-shrink: 0;
            margin-top: 1px;
            background: #ffffff;
            cursor: pointer;
        }

        .box-label {
            font-size: 7.75pt;
            line-height: 1.25;
            color: #000000;
        }

        .specify-underline {
            display: inline-block;
            border-bottom: 1px solid var(--ink);
            min-width: 90px;
            outline: none;
            padding: 0 4px;
        }

        /* Signatory Clearance & Block */
        .signature-block {
            text-align: center;
            margin-top: 6px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .signature-name {
            font-size: 9.5pt;
            font-weight: 800;
            letter-spacing: 0.02em;
            color: #000000;
            outline: none;
            line-height: 1.2;
        }

        .signature-title {
            font-size: 8pt;
            font-weight: 600;
            color: #000000;
            outline: none;
            line-height: 1.2;
            margin-top: 1px;
        }

        .signature-line-placeholder {
            width: 72%;
            border-bottom: 1px solid var(--ink);
            margin-bottom: 3px;
            height: 14px;
        }

        /* ================= PRINT MEDIA QUERIES (EXACT A4 1-PAGE FIT) ================= */
        @media print {
            @page {
                size: A4 portrait;
                margin: 6mm 8mm 6mm 8mm;
            }

            html, body {
                width: 100% !important;
                height: 100% !important;
                max-height: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                background: #ffffff !important;
                color: #000000 !important;
                overflow: hidden !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .iar-toolbar,
            .no-print {
                display: none !important;
            }

            .iar-viewport {
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
                height: 100% !important;
                overflow: hidden !important;
            }

            .iar-sheet {
                width: 100% !important;
                height: 100% !important;
                max-height: 100% !important;
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
                margin: 0 !important;
                box-sizing: border-box !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
                page-break-after: avoid !important;
                break-after: avoid !important;
                overflow: hidden !important;
            }

            .iar-form-border {
                border: var(--line-width) solid #000000 !important;
                width: 100% !important;
                height: 100% !important;
                max-height: 100% !important;
                box-sizing: border-box !important;
                display: flex !important;
                flex-direction: column !important;
                overflow: hidden !important;
            }

            .iar-table-container {
                width: 100% !important;
                flex: 1 !important;
                min-height: 0 !important;
                display: flex !important;
                flex-direction: column !important;
                overflow: hidden !important;
                box-sizing: border-box !important;
            }

            .iar-items-table {
                width: 100% !important;
                height: 100% !important;
                border-collapse: collapse !important;
                table-layout: fixed !important;
            }

            .iar-header,
            .iar-meta-grid,
            .iar-meta-left,
            .iar-bottom-section,
            .iar-bottom-left,
            .section-header-bar,
            .iar-items-table th,
            .iar-items-table td,
            .meta-cell-right {
                border-color: #000000 !important;
            }

            .iar-items-table thead,
            .iar-items-table thead tr,
            .iar-items-table th {
                height: 26px !important;
                max-height: 26px !important;
            }

            .iar-items-table td {
                border-bottom: 1px solid #d1d5db !important;
            }

            .iar-items-table tbody tr:last-child td {
                border-bottom: none !important;
            }

            [contenteditable="true"] {
                background: transparent !important;
            }

            .box-square {
                border-color: #000000 !important;
            }
        }
    </style>
</head>
<body>

    <!-- On-Screen Action Bar -->
    <header class="iar-toolbar" aria-label="IAR Controls Toolbar">
        <div class="toolbar-left">
            <a href="{{ route('admin.inventory.purchase-request') }}" class="btn-toolbar btn-toolbar-ghost" title="Back to Purchase Order & Inventory Management">
                <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                <span>Back to Deliveries</span>
            </a>
            <span class="badge-a4">A4 Printable Template &bull; COA Standard</span>
        </div>

        <div class="toolbar-center">
            <button type="button" class="btn-toolbar btn-toolbar-ghost" id="addRowBtn" onclick="addTableRow()" title="Add empty row">
                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                <span>Add Row</span>
            </button>
            <button type="button" class="btn-toolbar btn-toolbar-ghost" id="removeRowBtn" onclick="removeLastRow()" title="Remove last row">
                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                <span>Remove Row</span>
            </button>
            <button type="button" class="btn-toolbar btn-toolbar-ghost" onclick="fillSampleData()" title="Fill sample delivery data">
                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                <span>Sample Data</span>
            </button>
            <button type="button" class="btn-toolbar btn-toolbar-ghost" onclick="clearTableItems()" title="Clear item rows">
                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                <span>Clear</span>
            </button>
            <button type="button" class="btn-toolbar btn-toolbar-ghost" id="saveAirBtn" onclick="saveAirNumberToDatabase()" title="Persist AIR number into database" style="border-color: #059669; color: #34d399;">
                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                <span id="saveAirBtnText">Save AIR No.</span>
            </button>
        </div>

        <div class="toolbar-right">
            <button type="button" class="btn-toolbar btn-toolbar-primary" id="printFormBtn" onclick="window.print()">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                <span>Print IAR Form</span>
            </button>
        </div>
    </header>

    @if(session('status'))
        <div class="iar-status-banner no-print" id="iarStatusBanner">
            <div class="iar-status-content">
                <div class="iar-status-icon">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                </div>
                <div>
                    <strong style="color: #ecfdf5;">Delivery Confirmed & Items Received!</strong>
                    <span style="color: #a7f3d0; margin-left: 4px;">{{ session('status') }}</span>
                    @if(session('received_po'))
                        <span class="iar-status-po">PO: {{ session('received_po') }}</span>
                    @endif
                </div>
            </div>
            <div class="iar-status-actions">
                <button type="button" onclick="window.print()" class="btn-toolbar btn-toolbar-primary" style="padding: 6px 14px; font-size: 0.7813rem;">
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                    <span>Print Form (Ctrl+P)</span>
                </button>
                <a href="{{ route('admin.inventory.purchase-request') }}" class="btn-toolbar btn-toolbar-ghost" style="padding: 6px 12px; font-size: 0.7813rem;">
                    <span>+ Receive Another PO</span>
                </a>
            </div>
        </div>
    @endif

    <!-- Document Viewport -->
    <main class="iar-viewport">
        <div class="iar-sheet" id="printableIarDocument">
            <div class="iar-form-border">

                <!-- 1. HEADER (Official Seal + Title) -->
                <div class="iar-header">
                    <!-- Provincial Seal of Aurora -->
                    <div class="iar-logo-wrapper">
                        <img src="{{ asset('aurora-logo.png') }}" alt="Seal of the Province of Aurora (LALAWIGAN NG AURORA)" class="iar-logo-img">
                    </div>

                    <!-- Centered Title -->
                    <div class="iar-title-wrapper">
                        <h1 class="iar-main-title">ACCEPTANCE AND INSPECTION REPORT</h1>
                    </div>
                </div>

                <!-- 2. METADATA SECTION -->
                <div class="iar-meta-grid">
                    <!-- Left Column -->
                    <div class="iar-meta-left">
                        <div class="meta-row">
                            <span class="meta-label">Supplier :</span>
                            <span class="meta-fill" id="fieldSupplier" contenteditable="true" spellcheck="false">{{ $supplier ?? '' }}</span>
                        </div>
                        <div class="meta-row">
                            <div class="meta-po-date-split">
                                <div class="meta-po-col">
                                    <span class="meta-label">PO No. :</span>
                                    <span class="meta-fill" id="fieldPoNo" contenteditable="true" spellcheck="false">{{ $poNumber ?? '' }}</span>
                                </div>
                                <div class="meta-date-col">
                                    <span class="meta-label">Date:</span>
                                    <span class="meta-fill" id="fieldPoDate" contenteditable="true" spellcheck="false">{{ $poDate ?? '' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="meta-row">
                            <span class="meta-label">Requisitioning Office/Dept.:</span>
                            <span class="meta-fill" id="fieldOffice" contenteditable="true" spellcheck="false">{{ $office ?? '' }}</span>
                        </div>
                    </div>

                    <!-- Right Column (4 Stacked Cells) -->
                    <div class="iar-meta-right">
                        <div class="meta-cell-right">
                            <span class="meta-label">AIR:</span>
                            <span class="meta-fill" id="fieldAirNo" contenteditable="true" spellcheck="false">{{ $airNumber ?? '' }}</span>
                        </div>
                        <div class="meta-cell-right">
                            <span class="meta-label">Date :</span>
                            <span class="meta-fill" id="fieldAirDate" contenteditable="true" spellcheck="false">{{ $airDate ?? now()->format('m/d/Y') }}</span>
                        </div>
                        <div class="meta-cell-right">
                            <span class="meta-label">Invoice No. :</span>
                            <span class="meta-fill" id="fieldInvoiceNo" contenteditable="true" spellcheck="false">{{ $invoiceNumber ?? '' }}</span>
                        </div>
                        <div class="meta-cell-right">
                            <span class="meta-label">Date :</span>
                            <span class="meta-fill" id="fieldInvoiceDate" contenteditable="true" spellcheck="false">{{ $invoiceDate ?? '' }}</span>
                        </div>
                    </div>
                </div>

                <!-- 3. ITEMS TABLE -->
                <div class="iar-table-container">
                    <table class="iar-items-table" id="iarItemsTable">
                        <thead>
                            <tr>
                                <th class="col-stock-no">Stock/ Property No.</th>
                                <th class="col-description">DESCRIPTION</th>
                                <th class="col-unit">UNIT</th>
                                <th class="col-qty">QTY.</th>
                            </tr>
                        </thead>
                        <tbody id="iarTableBody">
                            @php
                                $passedItems = $items ?? [];
                                $itemCount = count($passedItems);
                                $totalRows = max(16, $itemCount);
                            @endphp

                            @for ($i = 0; $i < $totalRows; $i++)
                                @php
                                    $currItem = $passedItems[$i] ?? null;
                                @endphp
                                <tr>
                                    <td class="col-stock-no" contenteditable="true" spellcheck="false">{{ $currItem['stock_no'] ?? '' }}</td>
                                    <td class="col-description" contenteditable="true" spellcheck="false">{{ $currItem['description'] ?? '' }}</td>
                                    <td class="col-unit" contenteditable="true" spellcheck="false">{{ $currItem['unit'] ?? '' }}</td>
                                    <td class="col-qty" contenteditable="true" spellcheck="false">{{ isset($currItem['quantity']) ? $currItem['quantity'] : '' }}</td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>

                <!-- 4. BOTTOM ACCEPTANCE & INSPECTION SECTION -->
                <div class="iar-bottom-section">
                    <!-- Left: Acceptance -->
                    <div class="iar-bottom-col iar-bottom-left">
                        <div class="section-header-bar">ACCEPTANCE</div>
                        <div class="section-body">
                            <div>
                                <div class="date-line">
                                    <span class="date-label">Date Received :</span>
                                    <span class="date-underline" id="fieldDateReceived" contenteditable="true" spellcheck="false">{{ $dateReceived ?? now()->format('m/d/Y') }}</span>
                                </div>

                                <div class="checkbox-group" style="margin-top: 10px;">
                                    <div class="checkbox-item" onclick="toggleCheckbox('checkComplete', 'checkPartial')">
                                        <span class="box-square" id="checkComplete">&#10003;</span>
                                        <span class="box-label">Complete</span>
                                    </div>
                                    <div class="checkbox-item" onclick="toggleCheckbox('checkPartial', 'checkComplete')">
                                        <span class="box-square" id="checkPartial"></span>
                                        <span class="box-label">Partial (pls. specify) <span class="specify-underline" contenteditable="true" spellcheck="false" onclick="event.stopPropagation()"></span></span>
                                    </div>
                                </div>
                            </div>

                            <div class="signature-block">
                                <div class="signature-name" contenteditable="true" spellcheck="false">RICARDO Q. BAUTISTA</div>
                                <div class="signature-title" contenteditable="true" spellcheck="false">PGDH-PGSO</div>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Inspection -->
                    <div class="iar-bottom-col">
                        <div class="section-header-bar">INSPECTION</div>
                        <div class="section-body">
                            <div>
                                <div class="date-line">
                                    <span class="date-label">Date Inspected :</span>
                                    <span class="date-underline" id="fieldDateInspected" contenteditable="true" spellcheck="false">{{ $dateInspected ?? now()->format('m/d/Y') }}</span>
                                </div>

                                <div class="checkbox-group" style="margin-top: 10px;">
                                    <div class="checkbox-item" onclick="toggleSingleCheckbox('checkInspected')">
                                        <span class="box-square" id="checkInspected">&#10003;</span>
                                        <span class="box-label">Inspected, verified and found in order as to quantity and specifications</span>
                                    </div>
                                </div>
                            </div>

                            <div class="signature-block">
                                <div class="signature-line-placeholder"></div>
                                <div class="signature-title" contenteditable="true" spellcheck="false">Inspection Officer</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <!-- Script for Dynamic Row Management, Checkbox Toggles, and Sample Data -->
    <script>
        // Checkbox Toggle Logic
        function toggleCheckbox(selectedId, otherId) {
            const selected = document.getElementById(selectedId);
            const other = document.getElementById(otherId);
            
            if (selected.textContent.trim() === '✓' || selected.textContent.trim() === '✔') {
                selected.textContent = '';
            } else {
                selected.textContent = '✓';
                if (other) other.textContent = '';
            }
        }

        function toggleSingleCheckbox(id) {
            const el = document.getElementById(id);
            if (el.textContent.trim() === '✓' || el.textContent.trim() === '✔') {
                el.textContent = '';
            } else {
                el.textContent = '✓';
            }
        }

        // Table Row Management
        function addTableRow(stock = '', desc = '', unit = '', qty = '') {
            const tbody = document.getElementById('iarTableBody');
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="col-stock-no" contenteditable="true" spellcheck="false">${stock}</td>
                <td class="col-description" contenteditable="true" spellcheck="false">${desc}</td>
                <td class="col-unit" contenteditable="true" spellcheck="false">${unit}</td>
                <td class="col-qty" contenteditable="true" spellcheck="false">${qty}</td>
            `;
            tbody.appendChild(row);
        }

        function removeLastRow() {
            const tbody = document.getElementById('iarTableBody');
            if (tbody.children.length > 5) {
                tbody.removeChild(tbody.lastElementChild);
            }
        }

        function clearTableItems() {
            const tbody = document.getElementById('iarTableBody');
            tbody.innerHTML = '';
            for (let i = 0; i < 16; i++) {
                addTableRow();
            }
            document.getElementById('fieldSupplier').textContent = '';
            document.getElementById('fieldPoNo').textContent = '';
            document.getElementById('fieldPoDate').textContent = '';
            document.getElementById('fieldOffice').textContent = '';
            document.getElementById('fieldAirNo').textContent = '';
            document.getElementById('fieldInvoiceNo').textContent = '';
            document.getElementById('fieldInvoiceDate').textContent = '';
        }

        function fillSampleData() {
            const now = new Date();
            const dateStr = String(now.getMonth() + 1).padStart(2, '0') + '/' + String(now.getDate()).padStart(2, '0') + '/' + now.getFullYear();

            document.getElementById('fieldSupplier').textContent = 'Advance Paper Corporation';
            document.getElementById('fieldPoNo').textContent = 'PO-2026-0418';
            document.getElementById('fieldPoDate').textContent = dateStr;
            document.getElementById('fieldOffice').textContent = 'Provincial General Services Office (PGSO)';
            document.getElementById('fieldAirNo').textContent = 'AIR-2026-0089';
            document.getElementById('fieldAirDate').textContent = dateStr;
            document.getElementById('fieldInvoiceNo').textContent = 'INV-88392';
            document.getElementById('fieldInvoiceDate').textContent = dateStr;

            const tbody = document.getElementById('iarTableBody');
            tbody.innerHTML = '';

            const sampleItems = [
                { stock: 'STK-001', desc: 'Paper, Multi-Purpose, A4 size, 70gsm (Substance 20)', unit: 'ream', qty: '150' },
                { stock: 'STK-002', desc: 'Ballpen, Fine Point, 0.5mm, Black ink (12 pcs/box)', unit: 'box', qty: '35' },
                { stock: 'STK-003', desc: 'Ballpen, Fine Point, 0.5mm, Blue ink (12 pcs/box)', unit: 'box', qty: '30' },
                { stock: 'STK-004', desc: 'Folder, Pressboard, Legal size, Expandable, Blue', unit: 'piece', qty: '200' },
                { stock: 'STK-005', desc: 'Stapler #35 Heavy Duty with staple remover', unit: 'unit', qty: '15' },
                { stock: 'STK-006', desc: 'Staple Wire #35, Standard (5,000 staples/box)', unit: 'box', qty: '40' },
                { stock: 'STK-007', desc: 'Correction Tape, 5mm x 8m disposable', unit: 'piece', qty: '50' },
                { stock: 'STK-008', desc: 'Highlighter Pen, Assorted fluorescent colors (3 pcs/set)', unit: 'set', qty: '25' },
            ];

            sampleItems.forEach(item => {
                addTableRow(item.stock, item.desc, item.unit, item.qty);
            });

            // Fill remainder up to 16 rows to maintain exact A4 height layout
            for (let i = sampleItems.length; i < 16; i++) {
                addTableRow();
            }

            document.getElementById('checkComplete').textContent = '✓';
            document.getElementById('checkPartial').textContent = '';
            document.getElementById('checkInspected').textContent = '✓';
        }

        async function saveAirNumberToDatabase() {
            const airElem = document.getElementById('fieldAirNo');
            const airNumber = airElem ? airElem.textContent.trim() : '';
            if (!airNumber) return;

            const saveBtnText = document.getElementById('saveAirBtnText');
            if (saveBtnText) saveBtnText.textContent = 'Saving...';

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            try {
                const response = await fetch('{{ route('admin.inventory.iar.save-air') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        air_number: airNumber,
                        po_number: '{{ $poNumber }}' || null,
                        batch_id: '{{ $batchId ?? '' }}' || null,
                    })
                });

                if (response.ok) {
                    if (saveBtnText) saveBtnText.textContent = 'Saved ✓';
                    setTimeout(() => {
                        if (saveBtnText) saveBtnText.textContent = 'Save AIR No.';
                    }, 2000);
                } else {
                    if (saveBtnText) saveBtnText.textContent = 'Save AIR No.';
                }
            } catch (e) {
                console.error('Error saving AIR number:', e);
                if (saveBtnText) saveBtnText.textContent = 'Save AIR No.';
            }
        }

        // Auto-print if query parameter 'print' or server variable autoPrint is set
        window.addEventListener('DOMContentLoaded', () => {
            const airElem = document.getElementById('fieldAirNo');
            if (airElem) {
                airElem.addEventListener('blur', () => {
                    saveAirNumberToDatabase();
                });
            }

            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('print') === '1' || urlParams.get('autoprint') === '1' || {{ !empty($autoPrint) ? 'true' : 'false' }}) {
                setTimeout(() => {
                    window.print();
                }, 350);
            }
        });
    </script>
</body>
</html>
