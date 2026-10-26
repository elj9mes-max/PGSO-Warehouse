<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Official Requisition and Issue Slip (RIS) form template - Provincial Government of Aurora">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            object-fit: contain;
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

            .edit-modal-backdrop,
            .ris-toast {
                display: none !important;
            }

            [contenteditable="true"] {
                background: transparent !important;
                box-shadow: none !important;
            }
        }

        /* Edit Details Modal Styles */
        .edit-modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(5px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 999;
            padding: 20px;
        }

        .edit-modal-card {
            background: #ffffff;
            border-radius: 16px;
            width: 100%;
            max-width: 640px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.45);
            border: 1px solid #e2e8f0;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            animation: modalFadeIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes modalFadeIn {
            from { opacity: 0; transform: scale(0.96) translateY(8px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }

        .edit-modal-header {
            padding: 20px 24px 16px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            background: #f8fafc;
        }

        .edit-modal-header h3 {
            font-size: 1.125rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
        }

        .edit-modal-header p {
            font-size: 0.8125rem;
            color: #64748b;
            margin-top: 3px;
        }

        .btn-close-modal {
            background: none;
            border: none;
            font-size: 1.5rem;
            line-height: 1;
            color: #94a3b8;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 6px;
            transition: color 0.15s ease;
        }

        .btn-close-modal:hover {
            color: #0f172a;
        }

        .edit-modal-body {
            padding: 20px 24px;
            overflow-y: auto;
            max-height: 70vh;
        }

        .autofill-banner {
            background: linear-gradient(135deg, #eff6ff 0%, #f0fdf4 100%);
            border: 1px solid #bfdbfe;
            border-radius: 10px;
            padding: 12px 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 18px;
        }

        .autofill-info strong {
            display: block;
            font-size: 0.8125rem;
            color: #1e40af;
            font-weight: 700;
        }

        .autofill-info span {
            font-size: 0.75rem;
            color: #475569;
        }

        .btn-autofill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: 8px;
            background: #2563eb;
            color: #ffffff;
            font-size: 0.75rem;
            font-weight: 700;
            border: none;
            cursor: pointer;
            white-space: nowrap;
            transition: background 0.15s ease;
        }

        .btn-autofill:hover {
            background: #1d4ed8;
        }

        .modal-form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .form-group label {
            font-size: 0.75rem;
            font-weight: 700;
            color: #334155;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .form-group input, .form-group textarea {
            padding: 8px 12px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            font-size: 0.875rem;
            color: #0f172a;
            font-family: inherit;
            outline: none;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .form-group input:focus, .form-group textarea:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        .field-hint {
            font-size: 0.6875rem;
            color: #94a3b8;
        }

        .edit-modal-footer {
            padding: 16px 24px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
            background: #f8fafc;
        }

        .btn-cancel {
            padding: 8px 16px;
            border-radius: 8px;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            color: #475569;
            font-size: 0.8125rem;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-cancel:hover {
            background: #f1f5f9;
        }

        .btn-save-modal {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 18px;
            border-radius: 8px;
            background: #059669;
            border: none;
            color: #ffffff;
            font-size: 0.8125rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(5, 150, 105, 0.3);
            transition: background 0.15s ease;
        }

        .btn-save-modal:hover {
            background: #047857;
        }

        /* Toast Alert */
        .ris-toast {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: #0f172a;
            color: #ffffff;
            padding: 12px 20px;
            border-radius: 10px;
            font-size: 0.875rem;
            font-weight: 600;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.4);
            border: 1px solid #334155;
            z-index: 1000;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: toastSlideUp 0.25s ease-out;
        }

        @keyframes toastSlideUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .editable-meta {
            outline: none;
            border-radius: 3px;
            padding: 1px 4px;
            transition: background 0.15s ease;
        }

        .editable-meta:hover {
            background: rgba(37, 99, 235, 0.1);
        }

        .editable-meta:focus {
            background: rgba(37, 99, 235, 0.18);
            box-shadow: 0 0 0 1px #2563eb;
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
            @if($requisition)
                <span class="badge-req" style="background: rgba(59, 130, 246, 0.15); color: #93c5fd; border: 1px solid rgba(59, 130, 246, 0.3); padding: 4px 10px; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; font-family: var(--font-mono);">
                    REQ: {{ $requisition->ris_no ?? $requisition->request_number }}
                </span>
            @endif
        </div>

        <div class="toolbar-right">
            <button type="button" class="btn-tool btn-tool-secondary" id="openEditDetailsModalBtn" title="Fill or edit AIR/IAR, PO, Supplier and RIS numbers">
                <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                <span>Edit / Fill Details</span>
            </button>

            @if($requisition)
                <button type="button" class="btn-tool btn-tool-secondary" id="saveRisDetailsBtn" title="Save changes to database">
                    <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    <span id="saveBtnLabel">Save to DB</span>
                </button>
            @endif

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
                        <!-- Aurora Provincial Seal Logo -->
                        <td class="ris-logo-col">
                            <img src="{{ asset('aurora-logo.png') }}" alt="Seal of the Province of Aurora" class="ris-logo-img">
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

    <!-- Edit RIS Slip Details Modal -->
    <div class="edit-modal-backdrop" id="editModalBackdrop" style="display: none;">
        <div class="edit-modal-card">
            <div class="edit-modal-header">
                <div>
                    <h3>Edit RIS Slip Information</h3>
                    <p>Update AIR / IAR Number, PO Number, Supplier, and administrative metadata</p>
                </div>
                <button type="button" class="btn-close-modal" id="closeEditModalBtn" aria-label="Close modal">&times;</button>
            </div>

            <div class="edit-modal-body">
                <!-- Auto-fill Banner -->
                <div class="autofill-banner">
                    <div class="autofill-info">
                        <strong>Warehouse Delivery Batches</strong>
                        <span>Auto-detect AIR/IAR, PO, and Supplier from the items on this requisition.</span>
                    </div>
                    <button type="button" class="btn-autofill" id="autoFillFromBatchesBtn">
                        <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                        <span>Auto-fill from Item Batches</span>
                    </button>
                </div>

                <div class="modal-form-grid">
                    <div class="form-group">
                        <label for="inputAirNumber">AIR / IAR Number</label>
                        <input type="text" id="inputAirNumber" placeholder="e.g. AIR-2026-001 or IAR-2026-0045" value="{{ $requisition->air_number ?? '' }}">
                        <span class="field-hint">Acknowledgement of Receipt or Inspection & Acceptance reference</span>
                    </div>

                    <div class="form-group">
                        <label for="inputPoNumber">P.O. Number</label>
                        <input type="text" id="inputPoNumber" placeholder="e.g. PO-2026-088" value="{{ $requisition->po_number ?? '' }}">
                        <span class="field-hint">Purchase Order number for the delivered batch</span>
                    </div>

                    <div class="form-group" style="grid-column: span 2;">
                        <label for="inputSupplier">Supplier</label>
                        <input type="text" id="inputSupplier" placeholder="e.g. Goldmine Office Supplies Inc." value="{{ $requisition->supplier ?? '' }}">
                        <span class="field-hint">Registered vendor / supplier of goods</span>
                    </div>

                    <div class="form-group">
                        <label for="inputDivision">Division</label>
                        <input type="text" id="inputDivision" placeholder="e.g. Administrative Division" value="{{ $requisition->division ?? '' }}">
                    </div>

                    <div class="form-group">
                        <label for="inputRespCode">Responsibility Center Code</label>
                        <input type="text" id="inputRespCode" placeholder="1061" value="{{ $requisition->responsibility_center_code ?? '1061' }}">
                    </div>

                    <div class="form-group">
                        <label for="inputRisNo">RIS No.</label>
                        <input type="text" id="inputRisNo" placeholder="e.g. 2026-10-0012" value="{{ $requisition->ris_no ?? $requisition->request_number ?? '' }}">
                    </div>

                    <div class="form-group">
                        <label for="inputSaiNo">SAI No.</label>
                        <input type="text" id="inputSaiNo" placeholder="e.g. SAI-2026-004" value="{{ $requisition->sai_no ?? '' }}">
                    </div>

                    <div class="form-group" style="grid-column: span 2;">
                        <label for="inputPurpose">Purpose</label>
                        <textarea id="inputPurpose" rows="2" placeholder="Official Office Supply Consumption">{{ $requisition->purpose ?? '' }}</textarea>
                    </div>
                </div>
            </div>

            <div class="edit-modal-footer">
                <button type="button" class="btn-cancel" id="cancelEditModalBtn">Cancel</button>
                <button type="button" class="btn-save-modal" id="applyAndSaveModalBtn">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    <span>Apply & Save to Form</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Floating Feedback Toast -->
    <div class="ris-toast" id="risToast" style="display: none;"></div>

    <!-- JavaScript to Handle Automatic Table Grid, Cart Sync, Editing, and Printing -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const tableBody = document.getElementById('risTableBody');
            const TOTAL_ROWS = 27; // Exactly fills standard letter sheet height
            let currentItems = [];

            // Server-passed requisition data (loaded from database if requested)
            @php
                $serverRequisitionData = $requisition ? [
                    'id' => $requisition->id,
                    'risNo' => $requisition->ris_no ?? $requisition->request_number,
                    'purpose' => $requisition->purpose,
                    'division' => $requisition->division,
                    'respCode' => $requisition->responsibility_center_code ?? '1061',
                    'saiNo' => $requisition->sai_no,
                    'airNumber' => $requisition->air_number,
                    'poNumber' => $requisition->po_number,
                    'supplier' => $requisition->supplier,
                    'items' => $requisition->items->map(function ($it) {
                        $batches = $it->variant?->batches;
                        $firstBatch = $batches?->first(fn ($b) => ! empty($b->air_number)) ?? $batches?->first();
                        return [
                            'stockNo' => $it->stock_no ?: ($it->variant?->item_code ?: ($it->variant?->sku ?: '')),
                            'unit' => $it->unit ?? $it->variant?->effective_unit ?? 'pcs',
                            'description' => ($it->item_name ?? 'Item') . ($it->variant_name ? ' (' . $it->variant_name . ')' : ''),
                            'quantity' => $it->quantity_requested,
                            'issuedQuantity' => $it->quantity_issued > 0 ? $it->quantity_issued : '',
                            'remarks' => $it->remarks ?? '',
                            'airNumber' => $firstBatch?->air_number ?? '',
                            'batchNumber' => $firstBatch?->batch_number,
                            'poNumber' => $firstBatch?->po_number,
                            'supplier' => $firstBatch?->supplier,
                        ];
                    })->values()->all(),
                ] : null;
            @endphp
            const serverRequisition = {!! json_encode($serverRequisitionData) !!};

            // Active State
            let currentAirNumber = serverRequisition?.airNumber || sessionStorage.getItem('ris_air_number') || '';
            let currentPoNumber = serverRequisition?.poNumber || sessionStorage.getItem('ris_po_number') || '';
            let currentSupplier = serverRequisition?.supplier || sessionStorage.getItem('ris_supplier') || '';
            let currentDivision = serverRequisition?.division || sessionStorage.getItem('ris_division') || (document.getElementById('divisionField')?.textContent.trim() || '');
            let currentRespCode = serverRequisition?.respCode || sessionStorage.getItem('ris_resp_code') || (document.getElementById('respCodeField')?.textContent.trim() || '1061');
            let currentRisNo = serverRequisition?.risNo || sessionStorage.getItem('ris_no') || (document.getElementById('risNoField')?.textContent.trim() || '');
            let currentSaiNo = serverRequisition?.saiNo || sessionStorage.getItem('ris_sai_no') || (document.getElementById('saiNoField')?.textContent.trim() || '');
            let currentPurpose = serverRequisition?.purpose || sessionStorage.getItem('ris_purpose') || (document.getElementById('purposeContentField')?.textContent.trim() || '');

            // Toast helper
            function showToast(message, icon = '✓') {
                const toast = document.getElementById('risToast');
                if (!toast) return;
                toast.innerHTML = `<span style="font-size: 1.1rem; color: #34d399;">${icon}</span> <span>${message}</span>`;
                toast.style.display = 'flex';
                clearTimeout(toast._timeout);
                toast._timeout = setTimeout(() => {
                    toast.style.display = 'none';
                }, 3500);
            }

            // Detect and auto-resolve empty procurement values from items batches
            function autoResolveBatchesFromItems(items) {
                if (!items || items.length === 0) return;
                for (const it of items) {
                    if (!currentAirNumber && it.airNumber) {
                        currentAirNumber = it.airNumber;
                    }
                    if (!currentPoNumber && it.poNumber) {
                        currentPoNumber = it.poNumber;
                    }
                    if (!currentSupplier && it.supplier) {
                        currentSupplier = it.supplier;
                    }
                }
            }

            // Render table rows
            function renderTableRows(items = []) {
                currentItems = items;
                tableBody.innerHTML = '';

                // Auto-resolve batch info if fields are blank
                autoResolveBatchesFromItems(items);

                // Special meta rows indices towards the bottom (AIR NO, PO NO, SUPPLIER)
                const airRowIndex = TOTAL_ROWS - 6; // Row 21
                const poRowIndex = TOTAL_ROWS - 5;  // Row 22
                const supplierRowIndex = TOTAL_ROWS - 4; // Row 23
                const dotRowIndex = TOTAL_ROWS - 3; // Row 24

                const airVal = currentAirNumber ? ` ${currentAirNumber}` : '';
                const poVal = currentPoNumber ? ` ${currentPoNumber}` : '';
                const supplierVal = currentSupplier ? ` ${currentSupplier}` : '';

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
                            <td class="col-desc special-desc-cell">AIR / IAR NUMBER:<span id="valAir" class="editable-meta" contenteditable="true" title="Click to edit AIR / IAR Number">${airVal}</span></td>
                            <td class="col-req-qty" contenteditable="true"></td>
                            <td class="col-iss-qty" contenteditable="true"></td>
                            <td class="col-remarks" contenteditable="true"></td>
                        `;
                    } else if (i === poRowIndex) {
                        tr.innerHTML = `
                            <td class="col-stock-no" contenteditable="true"></td>
                            <td class="col-unit" contenteditable="true"></td>
                            <td class="col-desc special-desc-cell">P.O. NUMBER:<span id="valPo" class="editable-meta" contenteditable="true" title="Click to edit P.O. Number">${poVal}</span></td>
                            <td class="col-req-qty" contenteditable="true"></td>
                            <td class="col-iss-qty" contenteditable="true"></td>
                            <td class="col-remarks" contenteditable="true"></td>
                        `;
                    } else if (i === supplierRowIndex) {
                        tr.innerHTML = `
                            <td class="col-stock-no" contenteditable="true"></td>
                            <td class="col-unit" contenteditable="true"></td>
                            <td class="col-desc special-desc-cell">SUPPLIER:<span id="valSupplier" class="editable-meta" contenteditable="true" title="Click to edit Supplier">${supplierVal}</span></td>
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
                    const savedAir = sessionStorage.getItem('ris_air_number');
                    const savedPo = sessionStorage.getItem('ris_po_number');
                    const savedSupplier = sessionStorage.getItem('ris_supplier');
                    const savedDiv = sessionStorage.getItem('ris_division');
                    const savedResp = sessionStorage.getItem('ris_resp_code');
                    const savedSai = sessionStorage.getItem('ris_sai_no');

                    if (savedPurpose) {
                        currentPurpose = savedPurpose;
                        const purposeElem = document.getElementById('purposeContentField');
                        if (purposeElem) purposeElem.textContent = savedPurpose;
                    }

                    if (savedRisNo) {
                        currentRisNo = savedRisNo;
                        const risNoElem = document.getElementById('risNoField');
                        if (risNoElem) risNoElem.textContent = savedRisNo;
                    }

                    if (savedDiv) {
                        currentDivision = savedDiv;
                        const divElem = document.getElementById('divisionField');
                        if (divElem) divElem.textContent = savedDiv;
                    }

                    if (savedResp) {
                        currentRespCode = savedResp;
                        const respElem = document.getElementById('respCodeField');
                        if (respElem) respElem.textContent = savedResp;
                    }

                    if (savedSai) {
                        currentSaiNo = savedSai;
                        const saiElem = document.getElementById('saiNoField');
                        if (saiElem) saiElem.textContent = savedSai;
                    }

                    if (savedAir) currentAirNumber = savedAir;
                    if (savedPo) currentPoNumber = savedPo;
                    if (savedSupplier) currentSupplier = savedSupplier;

                    if (rawCart) {
                        const cartObj = JSON.parse(rawCart);
                        const items = Object.values(cartObj).map(item => ({
                            stockNo: item.sku || item.item_code || item.code || item.stockNo || '',
                            unit: item.unit || 'pcs',
                            description: item.name ? (item.variant && item.variant !== item.name ? `${item.name} (${item.variant})` : item.name) : (item.variant || item.description || ''),
                            quantity: item.quantity || 1,
                            airNumber: item.air_number || item.airNumber || '',
                            poNumber: item.po_number || item.poNumber || '',
                            supplier: item.supplier || '',
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

            // Inline edits listening on valAir, valPo, valSupplier
            document.addEventListener('input', (e) => {
                if (e.target && e.target.id === 'valAir') {
                    currentAirNumber = e.target.textContent.trim();
                    sessionStorage.setItem('ris_air_number', currentAirNumber);
                } else if (e.target && e.target.id === 'valPo') {
                    currentPoNumber = e.target.textContent.trim();
                    sessionStorage.setItem('ris_po_number', currentPoNumber);
                } else if (e.target && e.target.id === 'valSupplier') {
                    currentSupplier = e.target.textContent.trim();
                    sessionStorage.setItem('ris_supplier', currentSupplier);
                } else if (e.target && e.target.id === 'divisionField') {
                    currentDivision = e.target.textContent.trim();
                    sessionStorage.setItem('ris_division', currentDivision);
                } else if (e.target && e.target.id === 'respCodeField') {
                    currentRespCode = e.target.textContent.trim();
                    sessionStorage.setItem('ris_resp_code', currentRespCode);
                } else if (e.target && e.target.id === 'risNoField') {
                    currentRisNo = e.target.textContent.trim();
                    sessionStorage.setItem('ris_no', currentRisNo);
                } else if (e.target && e.target.id === 'saiNoField') {
                    currentSaiNo = e.target.textContent.trim();
                    sessionStorage.setItem('ris_sai_no', currentSaiNo);
                } else if (e.target && e.target.id === 'purposeContentField') {
                    currentPurpose = e.target.textContent.trim();
                    sessionStorage.setItem('ris_purpose', currentPurpose);
                }
            });

            // Modal Controls
            const editModalBackdrop = document.getElementById('editModalBackdrop');
            const openEditDetailsModalBtn = document.getElementById('openEditDetailsModalBtn');
            const closeEditModalBtn = document.getElementById('closeEditModalBtn');
            const cancelEditModalBtn = document.getElementById('cancelEditModalBtn');
            const applyAndSaveModalBtn = document.getElementById('applyAndSaveModalBtn');
            const autoFillFromBatchesBtn = document.getElementById('autoFillFromBatchesBtn');

            function openEditModal() {
                document.getElementById('inputAirNumber').value = currentAirNumber || '';
                document.getElementById('inputPoNumber').value = currentPoNumber || '';
                document.getElementById('inputSupplier').value = currentSupplier || '';
                document.getElementById('inputDivision').value = currentDivision || '';
                document.getElementById('inputRespCode').value = currentRespCode || '1061';
                document.getElementById('inputRisNo').value = currentRisNo || '';
                document.getElementById('inputSaiNo').value = currentSaiNo || '';
                document.getElementById('inputPurpose').value = currentPurpose || '';
                editModalBackdrop.style.display = 'flex';
            }

            function closeEditModal() {
                editModalBackdrop.style.display = 'none';
            }

            if (openEditDetailsModalBtn) openEditDetailsModalBtn.addEventListener('click', openEditModal);
            if (closeEditModalBtn) closeEditModalBtn.addEventListener('click', closeEditModal);
            if (cancelEditModalBtn) cancelEditModalBtn.addEventListener('click', closeEditModal);
            if (editModalBackdrop) {
                editModalBackdrop.addEventListener('click', (e) => {
                    if (e.target === editModalBackdrop) closeEditModal();
                });
            }

            // Auto-fill from batches in modal
            if (autoFillFromBatchesBtn) {
                autoFillFromBatchesBtn.addEventListener('click', () => {
                    let detectedAir = '';
                    let detectedPo = '';
                    let detectedSupplier = '';

                    const itemsToCheck = currentItems && currentItems.length > 0 
                        ? currentItems 
                        : (serverRequisition?.items || []);

                    for (const it of itemsToCheck) {
                        if (!detectedAir && it.airNumber) {
                            detectedAir = it.airNumber;
                        }
                        if (!detectedPo && it.poNumber) {
                            detectedPo = it.poNumber;
                        }
                        if (!detectedSupplier && it.supplier) {
                            detectedSupplier = it.supplier;
                        }
                    }

                    if (detectedAir) document.getElementById('inputAirNumber').value = detectedAir;
                    if (detectedPo) document.getElementById('inputPoNumber').value = detectedPo;
                    if (detectedSupplier) document.getElementById('inputSupplier').value = detectedSupplier;

                    if (detectedAir || detectedPo || detectedSupplier) {
                        showToast(`Auto-detected: ${[detectedAir, detectedPo, detectedSupplier].filter(Boolean).join(', ')}`, '⚡');
                    } else {
                        showToast('No delivery batch procurement records found on current items.', '⚠️');
                    }
                });
            }

            // Persist to Server via AJAX
            async function persistDetailsToServer(payload) {
                if (!serverRequisition || !serverRequisition.id) return;

                const saveBtnLabel = document.getElementById('saveBtnLabel');
                if (saveBtnLabel) saveBtnLabel.textContent = 'Saving...';

                try {
                    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    const url = `{{ url('/requisition/ris-details') }}/${serverRequisition.id}`;

                    const res = await fetch(url, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify(payload)
                    });

                    if (!res.ok) {
                        throw new Error(`HTTP error ${res.status}`);
                    }

                    const data = await res.json();
                    if (data.success) {
                        showToast('RIS details permanently saved to database!', '✓');
                        if (saveBtnLabel) saveBtnLabel.textContent = 'Saved ✓';
                        setTimeout(() => {
                            if (saveBtnLabel) saveBtnLabel.textContent = 'Save to DB';
                        }, 2500);
                    }
                } catch (err) {
                    console.error('Error saving RIS details:', err);
                    showToast('Failed to save to database. Changes remain in session.', '⚠️');
                    if (saveBtnLabel) saveBtnLabel.textContent = 'Save to DB';
                }
            }

            // Apply Modal Values
            if (applyAndSaveModalBtn) {
                applyAndSaveModalBtn.addEventListener('click', async () => {
                    currentAirNumber = document.getElementById('inputAirNumber').value.trim();
                    currentPoNumber = document.getElementById('inputPoNumber').value.trim();
                    currentSupplier = document.getElementById('inputSupplier').value.trim();
                    currentDivision = document.getElementById('inputDivision').value.trim();
                    currentRespCode = document.getElementById('inputRespCode').value.trim();
                    currentRisNo = document.getElementById('inputRisNo').value.trim();
                    currentSaiNo = document.getElementById('inputSaiNo').value.trim();
                    currentPurpose = document.getElementById('inputPurpose').value.trim();

                    // Update DOM elements on sheet
                    const divField = document.getElementById('divisionField');
                    if (divField) divField.textContent = currentDivision;
                    const respField = document.getElementById('respCodeField');
                    if (respField) respField.textContent = currentRespCode;
                    const risNoField = document.getElementById('risNoField');
                    if (risNoField) risNoField.textContent = currentRisNo;
                    const saiNoField = document.getElementById('saiNoField');
                    if (saiNoField) saiNoField.textContent = currentSaiNo;
                    const purpField = document.getElementById('purposeContentField');
                    if (purpField) purpField.textContent = currentPurpose;

                    // Update table rows
                    renderTableRows(currentItems);

                    // Save to sessionStorage
                    sessionStorage.setItem('ris_air_number', currentAirNumber);
                    sessionStorage.setItem('ris_po_number', currentPoNumber);
                    sessionStorage.setItem('ris_supplier', currentSupplier);
                    sessionStorage.setItem('ris_division', currentDivision);
                    sessionStorage.setItem('ris_resp_code', currentRespCode);
                    sessionStorage.setItem('ris_no', currentRisNo);
                    sessionStorage.setItem('ris_sai_no', currentSaiNo);
                    sessionStorage.setItem('ris_purpose', currentPurpose);

                    // Close modal
                    closeEditModal();

                    // Save to database if requisition exists
                    if (serverRequisition && serverRequisition.id) {
                        await persistDetailsToServer({
                            air_number: currentAirNumber,
                            po_number: currentPoNumber,
                            supplier: currentSupplier,
                            division: currentDivision,
                            responsibility_center_code: currentRespCode,
                            ris_no: currentRisNo,
                            sai_no: currentSaiNo,
                            purpose: currentPurpose,
                        });
                    } else {
                        showToast('Applied details to RIS Form!', '✓');
                    }
                });
            }

            // Toolbar Save to DB Button
            const saveRisDetailsBtn = document.getElementById('saveRisDetailsBtn');
            if (saveRisDetailsBtn) {
                saveRisDetailsBtn.addEventListener('click', () => {
                    const airElem = document.getElementById('valAir');
                    const poElem = document.getElementById('valPo');
                    const suppElem = document.getElementById('valSupplier');
                    const divElem = document.getElementById('divisionField');
                    const respElem = document.getElementById('respCodeField');
                    const risNoElem = document.getElementById('risNoField');
                    const saiNoElem = document.getElementById('saiNoField');
                    const purpElem = document.getElementById('purposeContentField');

                    persistDetailsToServer({
                        air_number: (airElem ? airElem.textContent.trim() : currentAirNumber) || null,
                        po_number: (poElem ? poElem.textContent.trim() : currentPoNumber) || null,
                        supplier: (suppElem ? suppElem.textContent.trim() : currentSupplier) || null,
                        division: (divElem ? divElem.textContent.trim() : currentDivision) || null,
                        responsibility_center_code: (respElem ? respElem.textContent.trim() : currentRespCode) || null,
                        ris_no: (risNoElem ? risNoElem.textContent.trim() : currentRisNo) || null,
                        sai_no: (saiNoElem ? saiNoElem.textContent.trim() : currentSaiNo) || null,
                        purpose: (purpElem ? purpElem.textContent.trim() : currentPurpose) || null,
                    });
                });
            }

            // Sync Button Click
            const syncCartBtn = document.getElementById('syncCartBtn');
            if (syncCartBtn) {
                syncCartBtn.addEventListener('click', () => {
                    const success = loadFromCart();
                    if (success) {
                        showToast('RIS Items synchronized from your active cart!', '✓');
                    } else {
                        showToast('No items currently found in your active RIS cart.', '⚠️');
                    }
                });
            }

            // Clear Button Click
            const clearRowsBtn = document.getElementById('clearRowsBtn');
            if (clearRowsBtn) {
                clearRowsBtn.addEventListener('click', () => {
                    if (confirm('Clear all items from this form?')) {
                        renderTableRows([]);
                        showToast('Cleared all items from form', 'ℹ️');
                    }
                });
            }
        });
    </script>
</body>
</html>
