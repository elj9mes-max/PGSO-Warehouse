@php
    $variantOptions = collect([
        [
            'id' => 'all',
            'name' => 'All Items (Display Each Stock Card)',
            'subtext' => 'Show multiple stock cards for each item',
            'unit' => '',
            'stock_no' => 'ALL',
        ],
    ])->concat($variants->map(function ($variant) {
        $vItem = $variant->inventoryItem;
        $label = $variant->variant_name ?: ($vItem?->name ?? 'Standard Item');
        $code = $variant->item_code ?: ($variant->sku ?: ($variant->barcode ?: ''));
        $unit = $variant->effective_unit ?? ($variant->unit ?? 'unit');

        $subtext = $code;
        if ($vItem && $vItem->name !== $label) {
            $subtext = ($subtext ? $subtext . ' • ' : '') . $vItem->name;
        }

        return [
            'id' => (string) $variant->id,
            'name' => $label,
            'subtext' => $subtext,
            'unit' => $unit,
            'stock_no' => $code,
        ];
    }))->values()->all();

    $officeOptions = collect([
        [
            'id' => '',
            'name' => 'All Offices',
            'subtext' => 'Consolidated stock card for all offices',
            'office_name' => '',
        ],
    ])->concat($offices->map(function ($office) {
        return [
            'id' => (string) $office->id,
            'name' => $office->office_name,
            'subtext' => $office->office_code ?: '',
            'office_name' => $office->office_name,
        ];
    }))->values()->all();

    $isAllItems = ! empty($isAllItemsMode);
    $pageTitle = $isAllItems
        ? 'All Items Stock Cards'
        : ($selectedVariant?->variant_name ?: ($selectedVariant?->inventoryItem?->name ?: 'Stock Card Ledger'));
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Stock Card - {{ $pageTitle }}</title>

    <!-- Modern Sans & JetBrains Mono Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        /* Base Reset & Variables */
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
            --font-ledger: Arial, 'Helvetica Neue', Helvetica, sans-serif;
            /* --bg-canvas: #0f172a; */
                 --bg-canvas: #ffffffff;
            --paper-width: 216mm;
            --paper-min-height: 279mm;
            --border-ink: #000000;
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
            padding: 12px 24px;
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

        .badge-status {
            background: rgba(16, 185, 129, 0.15);
            color: #34d399;
            border: 1px solid rgba(16, 185, 129, 0.3);
            padding: 4px 10px;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
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
            text-decoration: none;
            transition: all 0.15s ease;
            border: 1px solid transparent;
            user-select: none;
        }

        .btn-action:disabled {
            opacity: 0.4;
            cursor: not-allowed !important;
            filter: grayscale(0.5);
        }

        .btn-ghost {
            background: transparent;
            color: #94a3b8;
            border-color: #334155;
        }

        .btn-ghost:hover {
            background: #334155;
            color: #ffffff;
        }

        .btn-secondary {
            background: #334155;
            color: #f1f5f9;
            border-color: #475569;
        }

        .btn-secondary:hover {
            background: #475569;
            color: #ffffff;
        }

        .btn-save {
            background: #059669;
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(5, 150, 105, 0.4);
            font-weight: 600;
        }

        .btn-save:hover:not(:disabled) {
            background: #047857;
            transform: translateY(-1px);
        }

        .btn-save:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .btn-primary {
            background: #2563eb;
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.35);
        }

        .btn-primary:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
        }

        .btn-toggle-active {
            background: #0284c7;
            color: #ffffff;
            border-color: #0369a1;
        }

        .btn-toggle-inactive {
            background: #334155;
            color: #94a3b8;
            border-color: #475569;
        }

        /* Scoped DropdownSearch styles inside Top Toolbar */
        .toolbar-dropdown-search-box {
            width: 430px;
            /* min-width: 240px;
            max-width: 380px; */
        }

        .toolbar-dropdown-office {
            width: 330px;
            min-width: 190px;
            max-width: 400px;
        }

        .toolbar-dropdown-search-box .pgso-ds-trigger {
            min-height: 36px;
            height: 36px;
            padding: 0 12px;
            border-radius: 8px;
            background: #0f172a;
            border: 1px solid #334155;
            color: #f1f5f9;
        }

        .toolbar-dropdown-search-box .pgso-ds-trigger:hover:not(.disabled) {
            border-color: #475569;
            background: #1e293b;
        }

        .toolbar-dropdown-search-box .pgso-ds-trigger.active,
        .toolbar-dropdown-search-box .pgso-ds-trigger:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15);
            background: #0f172a;
        }

        .toolbar-dropdown-search-box .pgso-ds-placeholder {
            color: #94a3b8;
            font-size: 0.8125rem;
        }

        .toolbar-dropdown-search-box .pgso-ds-selected-label {
            color: #f1f5f9;
            font-size: 0.8125rem;
            font-weight: 600;
        }

        .toolbar-dropdown-search-box .pgso-ds-selected-badge {
            background: #1e293b;
            color: #38bdf8;
            border: 1px solid #334155;
        }

        .toolbar-dropdown-search-box .pgso-ds-arrow svg {
            color: #94a3b8;
        }

        .toolbar-dropdown-search-box .pgso-ds-clear-btn {
            color: #94a3b8;
        }

        .toolbar-dropdown-search-box .pgso-ds-clear-btn:hover {
            color: #f1f5f9;
            background: #334155;
        }

        /* Toolbar Calendar / Activity Date Filter */
        .toolbar-date-filter {
            display: inline-flex;
            align-items: center;
        }

        .date-filter-box {
            background: #0f172a;
            border: 1px solid #334155;
            border-radius: 8px;
            height: 36px;
            padding: 0 10px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.8125rem;
            color: #94a3b8;
            box-sizing: border-box;
            transition: all 0.15s ease;
        }

        .date-filter-box:hover {
            border-color: #475569;
            background: #1e293b;
        }

        .date-filter-box.has-active-filter,
        .date-filter-box:focus-within {
            border-color: #0284c7;
            background: #0f172a;
            box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.2);
        }

        .date-filter-icon {
            color: #38bdf8;
            flex-shrink: 0;
        }

        .date-filter-label {
            font-size: 0.7188rem;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            white-space: nowrap;
        }

        .date-filter-input {
            background: transparent;
            border: none;
            outline: none;
            color: #f1f5f9;
            font-family: var(--font-sans);
            font-size: 0.75rem;
            padding: 2px 0;
            width: 110px;
            cursor: pointer;
            color-scheme: dark;
        }

        .date-filter-input:focus {
            color: #38bdf8;
        }

        .date-filter-sep {
            color: #64748b;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .date-filter-clear-btn {
            background: #334155;
            color: #cbd5e1;
            border: none;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 11px;
            line-height: 18px;
            text-align: center;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            padding: 0;
            margin-left: 2px;
            transition: all 0.15s ease;
        }

        .date-filter-clear-btn:hover {
            background: #ef4444;
            color: #ffffff;
            transform: scale(1.1);
        }

        /* Empty State (when filtered activity produces no cards) */
        .stock-cards-empty-state {
            width: 100%;
            max-width: 540px;
            margin: 60px auto 40px;
            text-align: center;
        }

        .empty-state-card {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 14px;
            padding: 36px 28px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
            color: #f8fafc;
        }

        .empty-state-icon {
            margin-bottom: 14px;
            display: inline-flex;
            padding: 12px;
            background: #0f172a;
            border-radius: 50%;
            border: 1px solid #334155;
        }

        .empty-state-title {
            font-size: 1.1875rem;
            font-weight: 700;
            margin-bottom: 8px;
            color: #f1f5f9;
        }

        .empty-state-desc {
            font-size: 0.8438rem;
            color: #94a3b8;
            line-height: 1.5;
            margin-bottom: 18px;
        }

        /* Printable Paper Canvas */
        .paper-canvas-wrap {
            margin-top: 28px;
            display: flex;
            justify-content: center;
            width: 100%;
        }

        .stock-card-sheet {
            width: var(--paper-width);
            min-height: var(--paper-min-height);
            background: #ffffff;
            color: #000000;
            padding: 16mm 14mm 16mm 14mm;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.45);
            box-sizing: border-box;
            position: relative;
            font-family: var(--font-ledger);
        }

        /* Card Header Layout matching User Photo */
        .sc-header-center {
            text-align: center;
            margin-bottom: 6px;
        }

        .sc-main-title {
            font-size: 1.45rem;
            font-weight: 800;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            font-family: var(--font-ledger);
            color: #000000;
            line-height: 1.2;
            margin-bottom: 14px;
        }

        .sc-office-wrapper {
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            width: 65%;
            margin: 0 auto;
        }

        .sc-office-line {
            width: 100%;
            border-bottom: 1.5px solid var(--border-ink);
            min-height: 22px;
            text-align: center;
            font-size: 0.9375rem;
            font-weight: 700;
            text-transform: uppercase;
            outline: none;
            padding-bottom: 2px;
            color: #000000;
        }

        .sc-office-line:empty::before {
            content: attr(data-placeholder);
            color: #94a3b8;
            font-weight: normal;
            font-size: 0.8125rem;
        }

        .sc-office-caption {
            font-size: 0.75rem;
            font-style: italic;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-top: 3px;
            color: #000000;
            font-weight: 600;
        }

        /* Metadata Details (ITEM, Unit, STOCK NO) */
        .sc-meta-grid {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 14px;
            margin-bottom: 8px;
            gap: 16px;
        }

        .sc-meta-left {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .sc-meta-row-item {
            display: flex;
            align-items: baseline;
            gap: 6px;
            width: 100%;
        }

        .sc-label-bold {
            font-size: 0.875rem;
            font-weight: 800;
            color: #000000;
            white-space: nowrap;
        }

        .sc-underline-field {
            border-bottom: 1px solid var(--border-ink);
            flex: 1;
            min-height: 19px;
            padding: 0 4px 1px;
            font-size: 0.875rem;
            font-weight: 700;
            outline: none;
            color: #000000;
            line-height: 1.2;
        }

        .sc-underline-field:empty::before {
            content: attr(data-placeholder);
            color: #94a3b8;
            font-weight: normal;
        }

        .sc-meta-row-unit {
            display: flex;
            align-items: baseline;
            gap: 4px;
            margin-left: 20px;
        }

        .sc-label-unit {
            font-size: 0.8125rem;
            color: #000000;
            font-weight: 600;
        }

        .sc-underline-unit {
            border-bottom: 1px solid var(--border-ink);
            min-width: 90px;
            min-height: 17px;
            padding: 0 4px;
            font-size: 0.8125rem;
            outline: none;
            color: #000000;
        }

        .sc-meta-right {
            display: flex;
            align-items: baseline;
            gap: 6px;
            min-width: 250px;
        }

        .sc-underline-stockno {
            border-bottom: 1px solid var(--border-ink);
            flex: 1;
            min-height: 19px;
            padding: 0 4px 1px;
            font-size: 0.875rem;
            font-weight: 700;
            font-family: var(--font-mono);
            outline: none;
            color: #000000;
        }

        /* Stock Card Ledger Table */
        .sc-table-container {
            width: 100%;
            margin-top: 6px;
            position: relative;
        }

        .sc-table {
            width: 100%;
            border-collapse: collapse;
            font-family: var(--font-ledger);
            border: 1.5px solid var(--border-ink);
        }

        .sc-table th,
        .sc-table td {
            border: 1px solid var(--border-ink);
            font-size: 0.7813rem;
            color: #000000;
            line-height: 1.2;
            padding: 3px 4px;
            vertical-align: middle;
        }

        .sc-table th {
            font-weight: 800;
            text-align: center;
            background: #ffffff;
            letter-spacing: 0.02em;
        }

        /* Header & Column Specific Sizing - Shrunk QTY/BALANCE and expanded RIS # / OFFICE */
        col.col-date,
        .th-date,
        .td-date,
        .cell-date {
            width: 8%;
        }

        col.col-po,
        .th-ref,
        .th-po,
        .td-po,
        .cell-po {
            width: 13%;
        }

        col.col-receipt-qty,
        .th-receipt,
        .th-receipt-qty,
        .td-receipt-qty,
        .cell-receipt-qty {
            width: 5.5%;
        }

        .th-issuance {
            width: 46.5%;
        }

        col.col-ris,
        .th-ris,
        .td-ris,
        .cell-ris {
            width: 41%;
        }

        col.col-iss-qty,
        .th-iss-qty,
        .td-iss-qty,
        .cell-iss-qty {
            width: 5.5%;
        }

        col.col-bal-qty,
        .th-balance,
        .th-bal-qty,
        .td-bal-qty,
        .cell-bal-qty {
            width: 6%;
        }

        col.col-remarks,
        .th-remarks,
        .td-remarks,
        .cell-remarks {
            width: 21%;
        }

        col.col-action {
            width: 24px;
        }

        /* Row heights and cell styling */
        .sc-table tbody tr {
            height: 22px;
            transition: background 0.1s ease;
        }

        .sc-table tbody tr:hover {
            background-color: #f8fafc;
        }

        /* Cell content & alignment */
        .cell-content {
            outline: none;
            width: 100%;
            min-height: 16px;
            display: inline-block;
            word-break: break-word;
        }

        .cell-content:focus {
            background-color: #fef08a;
            border-radius: 2px;
            box-shadow: 0 0 0 1px #ca8a04;
        }

        .cell-date {
            text-align: center;
            font-family: var(--font-mono);
            font-size: 0.75rem;
        }

        .cell-po {
            text-align: center;
            font-family: var(--font-mono);
            font-size: 0.75rem;
        }

        .cell-receipt-qty {
            text-align: center;
            font-weight: 700;
            font-family: var(--font-mono);
        }

        .cell-ris {
            text-align: center;
            padding-left: 6px;
            font-size: 0.75rem;
        }

        .cell-ris .cell-content.is-office-code {
            font-weight: 700 !important;
        }

        .cell-ris .cell-content.is-ris-no {
            font-weight: 400 !important;
        }

        .cell-iss-qty {
            text-align: center;
            font-weight: 700;
            font-family: var(--font-mono);
        }

        .cell-bal-qty {
            text-align: center;
            font-weight: 800;
            font-family: var(--font-mono);
        }

        .cell-remarks {
            text-align: left;
            padding-left: 6px;
            font-size: 0.75rem;
        }

        /* Row Controls Hover Action (Hidden in Print) */
        .row-action-cell {
            width: 24px;
            border: none !important;
            padding: 0 !important;
            text-align: center;
            background: transparent !important;
        }

        .row-del-btn {
            background: #ef4444;
            color: #ffffff;
            border: none;
            width: 18px;
            height: 18px;
            border-radius: 4px;
            font-size: 11px;
            line-height: 18px;
            text-align: center;
            cursor: pointer;
            opacity: 0;
            transition: opacity 0.15s ease;
        }

        .sc-table tbody tr:hover .row-del-btn {
            opacity: 0.75;
        }

        .row-del-btn:hover {
            opacity: 1 !important;
        }

        /* Floating Helper Ribbon (Instructions & Shortcuts) */
        .helper-ribbon {
            margin-top: 18px;
            background: #1e293b;
            border: 1px solid #334155;
            padding: 10px 18px;
            border-radius: 10px;
            color: #94a3b8;
            font-size: 0.7813rem;
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
            max-width: var(--paper-width);
            width: 100%;
        }

        .helper-ribbon kbd {
            background: #0f172a;
            color: #f1f5f9;
            padding: 2px 6px;
            border-radius: 4px;
            border: 1px solid #475569;
            font-family: var(--font-mono);
            font-size: 0.7188rem;
        }

        /* Undo Floating Toast Notification */
        .undo-toast {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: #1e293b;
            color: #ffffff;
            border: 1px solid #475569;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            border-radius: 10px;
            padding: 10px 14px;
            display: flex;
            align-items: center;
            gap: 12px;
            z-index: 2000;
            animation: slideUpToast 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            font-family: var(--font-sans);
            font-size: 0.8438rem;
        }

        @keyframes slideUpToast {
            from {
                opacity: 0;
                transform: translateY(12px) scale(0.97);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .undo-toast-content {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
        }

        .toast-undo-btn {
            background: #0284c7;
            color: #ffffff;
            border: none;
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 0.8125rem;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.15s ease;
        }

        .toast-undo-btn:hover {
            background: #0369a1;
            transform: translateY(-1px);
        }

        .toast-dismiss-btn {
            background: transparent;
            border: none;
            color: #94a3b8;
            font-size: 14px;
            cursor: pointer;
            padding: 2px 4px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .toast-dismiss-btn:hover {
            color: #ffffff;
            background: #334155;
        }

        .pgso-ds-options-list {
            max-height: 650px !important;
        }

        .sc-table th,
        .sc-table td {
            padding: 0;
        }

        /* Multi-Card Grid for All Items Mode (matching PDF layout) */
        .stock-cards-multi-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            width: 100%;
            max-width: 1840px;
            margin: 0 auto;
            padding: 0 10px 40px;
            box-sizing: border-box;
        }

        @media (max-width: 1600px) {
            .stock-cards-multi-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media (max-width: 1280px) {
            .stock-cards-multi-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 900px) {
            .stock-cards-multi-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 550px) {
            .stock-cards-multi-grid {
                grid-template-columns: 1fr;
            }
        }

        .stock-card-grid-card {
            background: #ffffff;
            color: #000000;
            padding: 10px 8px 12px 8px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.45);
            border-radius: 2px;
            box-sizing: border-box;
            font-family: var(--font-ledger);
            display: flex;
            flex-direction: column;
            min-height: 480px;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .stock-card-grid-card:hover {
            box-shadow: 0 8px 26px rgba(0, 0, 0, 0.6);
            transform: translateY(-2px);
        }

        .stock-card-grid-card .sc-main-title {
            font-size: 0.8125rem;
            font-weight: 800;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            text-align: center;
            margin-bottom: 2px;
            color: #000000;
        }

        .stock-card-grid-card .sc-office-wrapper {
            margin-bottom: 5px;
            text-align: center;
        }

        .stock-card-grid-card .sc-office-line {
            border-bottom: 1px solid #000000;
            font-size: 0.5938rem;
            font-weight: 700;
            min-height: 13px;
            line-height: 13px;
            padding-bottom: 1px;
            text-transform: uppercase;
            color: #000000;
        }

        .stock-card-grid-card .sc-office-caption {
            font-size: 0.4688rem;
            font-weight: 800;
            letter-spacing: 0.06em;
            color: #000000;
        }

        .stock-card-grid-card .sc-meta-grid {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 6px;
            margin-bottom: 5px;
            font-size: 0.5938rem;
            color: #000000;
        }

        .stock-card-grid-card .sc-meta-left {
            display: flex;
            flex-direction: column;
            gap: 2px;
            flex: 1;
            min-width: 0;
        }

        .stock-card-grid-card .sc-meta-row-item {
            display: flex;
            align-items: baseline;
            gap: 3px;
            min-width: 0;
        }

        .stock-card-grid-card .sc-meta-row-unit {
            display: flex;
            align-items: baseline;
            gap: 3px;
        }

        .stock-card-grid-card .sc-meta-right {
            display: flex;
            align-items: baseline;
            gap: 3px;
            white-space: nowrap;
        }

        .stock-card-grid-card .sc-label-bold {
            font-weight: 800;
            font-size: 0.5938rem;
            white-space: nowrap;
        }

        .stock-card-grid-card .sc-label-unit {
            font-size: 0.5625rem;
            font-style: italic;
            white-space: nowrap;
        }

        .stock-card-grid-card .sc-underline-field {
            border-bottom: 1px solid #000000;
            font-weight: 700;
            font-size: 0.5938rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            flex: 1;
        }

        .stock-card-grid-card .sc-underline-unit {
            border-bottom: 1px solid #000000;
            font-size: 0.5625rem;
            min-width: 28px;
        }

        .stock-card-grid-card .sc-underline-stockno {
            border-bottom: 1px solid #000000;
            font-weight: 700;
            font-size: 0.5938rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 80px;
        }

        .stock-card-grid-card .sc-table-container {
            border: 1px solid #000000;
            box-sizing: border-box;
            flex: 1;
        }

        .stock-card-grid-card .sc-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .stock-card-grid-card .sc-table th,
        .stock-card-grid-card .sc-table td {
            border: 1px solid #000000;
            padding: 0 1px;
            text-align: center;
            box-sizing: border-box;
        }

        .stock-card-grid-card .sc-table th {
            font-size: 0.4688rem;
            font-weight: 800;
            background: #ffffff;
            line-height: 1.1;
            padding: 1px;
            color: #000000;
        }

        .stock-card-grid-card .sc-table td {
            font-size: 0.5313rem;
            height: 15px;
            line-height: 15px;
            font-family: var(--font-mono);
            color: #000000;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .stock-card-grid-card .sc-table .td-po,
        .stock-card-grid-card .sc-table .td-ris,
        .stock-card-grid-card .sc-table .td-remarks {
            font-family: var(--font-sans);
            font-size: 0.4688rem;
            text-align: left;
            padding-left: 2px;
        }

        .stock-card-grid-card .sc-table .td-bal-qty {
            font-weight: 800;
        }

        /* Print Media Styles */
        @media print {
            body {
                background: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .no-print,
            .top-toolbar,
            .helper-ribbon,
            .row-action-cell,
            .undo-toast {
                display: none !important;
            }

            .paper-canvas-wrap {
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
                display: block !important;
            }

            .stock-cards-multi-grid {
                display: block !important;
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                gap: 0 !important;
            }

            .stock-card-sheet,
            .stock-card-grid-card {
                box-shadow: none !important;
                border-radius: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
                min-height: auto !important;
                padding: 8mm 6mm !important;
                margin: 0 !important;
                display: block !important;
                page-break-after: always !important;
                break-after: page !important;
            }

            .stock-card-sheet:last-child,
            .stock-card-grid-card:last-child {
                page-break-after: auto !important;
                break-after: auto !important;
            }

            .stock-card-grid-card .sc-main-title {
                font-size: 1.45rem !important;
                margin-bottom: 6px !important;
            }

            .stock-card-grid-card .sc-office-line {
                font-size: 0.875rem !important;
                min-height: 20px !important;
                line-height: 20px !important;
            }

            .stock-card-grid-card .sc-office-caption {
                font-size: 0.6875rem !important;
            }

            .stock-card-grid-card .sc-meta-grid {
                font-size: 0.8125rem !important;
                margin-bottom: 8px !important;
            }

            .stock-card-grid-card .sc-label-bold,
            .stock-card-grid-card .sc-underline-field,
            .stock-card-grid-card .sc-underline-unit,
            .stock-card-grid-card .sc-underline-stockno {
                font-size: 0.8125rem !important;
                max-width: none !important;
            }

            col.col-date, .th-date, .td-date, .cell-date { width: 8% !important; }
            col.col-po, .th-ref, .th-po, .td-po, .cell-po { width: 13% !important; }
            col.col-receipt-qty, .th-receipt, .th-receipt-qty, .td-receipt-qty, .cell-receipt-qty { width: 7.5% !important; }
            .th-issuance { width: 46.5% !important; }
            col.col-ris, .th-ris, .td-ris, .cell-ris { width: 41% !important; }
            col.col-iss-qty, .th-iss-qty, .td-iss-qty, .cell-iss-qty { width: 5.5% !important; }
            col.col-bal-qty, .th-balance, .th-bal-qty, .td-bal-qty, .cell-bal-qty { width: 8% !important; }
            col.col-remarks, .th-remarks, .td-remarks, .cell-remarks { width: 21% !important; }
            col.col-action { display: none !important; width: 0 !important; }

            .stock-card-grid-card .sc-table th,
            .sc-table th {
                font-size: 0.6563rem !important;
                padding: 1px 2px !important;
            }

            .stock-card-grid-card .sc-table td,
            .sc-table td {
                font-size: 0.6875rem !important;
                height: 5.3mm !important;
                line-height: 5.3mm !important;
                padding: 0 2px !important;
                overflow: visible !important;
                text-overflow: clip !important;
            }

            .stock-card-grid-card .sc-table .td-ris,
            .sc-table .td-ris,
            .sc-table .cell-ris {
                font-size: 0.6563rem !important;
                white-space: normal !important;
                word-break: break-word !important;
                line-height: 1.15 !important;
                padding: 0 4px !important;
                text-align: left !important;
            }

            .stock-card-grid-card .sc-table .td-po,
            .sc-table .td-po,
            .sc-table .cell-po {
                font-size: 0.6563rem !important;
                text-align: center !important;
                white-space: nowrap !important;
            }

            .stock-card-grid-card .sc-table .td-remarks,
            .sc-table .td-remarks,
            .sc-table .cell-remarks {
                font-size: 0.6563rem !important;
                text-align: left !important;
                padding: 0 3px !important;
            }

            .stock-card-grid-card .sc-table .td-receipt-qty,
            .stock-card-grid-card .sc-table .td-iss-qty,
            .stock-card-grid-card .sc-table .td-bal-qty,
            .sc-table .td-receipt-qty,
            .sc-table .td-iss-qty,
            .sc-table .td-bal-qty,
            .sc-table .cell-receipt-qty,
            .sc-table .cell-iss-qty,
            .sc-table .cell-bal-qty {
                font-size: 0.6875rem !important;
                padding: 0 1px !important;
                text-align: center !important;
                white-space: nowrap !important;
            }

            .sc-table tbody tr {
                height: 5.3mm !important;
            }

            .sc-table th,
            .sc-table td {
                border-color: #000000 !important;
            }

            .sc-table {
                border-color: #000000 !important;
            }

            @page {
                size: portrait;
                margin: 8mm 8mm 8mm 8mm;
            }
        }
    </style>
</head>

<body>

    <!-- Top Sticky Action Bar (Hidden in Print) -->
    <header class="top-toolbar no-print">
        <div class="toolbar-group">
            <a href="{{ route('admin.reports.index') }}" class="btn-action btn-ghost" title="Back to Reports Center">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="15 18 9 12 15 6" />
                </svg>
                <span>Reports Center</span>
            </a>

            {{-- <div class="toolbar-title-wrap">
                <span class="badge-status">Official Stock Card</span>
            </div> --}}

            <!-- Searchable Dropdown for Office Scope (Default: All Offices) -->
            <div class="toolbar-dropdown-search-box toolbar-dropdown-office">
                <x-dropdown-search name="selected_office_id" id="stockCardOfficeSelect" :options="$officeOptions"
                    valueKey="id" labelKey="name" subtextKey="subtext" :selected="$selectedOfficeId ?? ''"
                    placeholder="All Offices" searchPlaceholder="Search office..." :allowClear="true" />
            </div>

            <!-- Searchable Dropdown Component for Selecting Inventory Item -->
            <div class="toolbar-dropdown-search-box">
                <x-dropdown-search name="selected_variant_id" id="stockCardVariantSelect" :options="$variantOptions"
                    valueKey="id" labelKey="name" subtextKey="subtext" :selected="$selectedVariantId ?? ($selectedVariant?->id ?? '')"
                    placeholder="-- Select Item to Populate --" searchPlaceholder="Search item name, code, SKU..."
                    :allowClear="true" />
            </div>

            <!-- Calendar Activity Date Range Filter -->
            <div class="toolbar-date-filter" id="toolbarDateFilter" title="Filter stock cards by date of activity">
                <div class="date-filter-box {{ !empty($fromDate) || !empty($toDate) ? 'has-active-filter' : '' }}">
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" class="date-filter-icon">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                        <line x1="16" y1="2" x2="16" y2="6" />
                        <line x1="8" y1="2" x2="8" y2="6" />
                        <line x1="3" y1="10" x2="21" y2="10" />
                    </svg>
                    {{-- <span class="date-filter-label">Activity:</span> --}}
                    <input type="date" id="filterFromDate" class="date-filter-input" value="{{ $fromDate ?? '' }}" title="From Date" aria-label="From Date">
                    <span class="date-filter-sep">to</span>
                    <input type="date" id="filterToDate" class="date-filter-input" value="{{ $toDate ?? '' }}" title="To Date" aria-label="To Date">
                    @if(!empty($fromDate) || !empty($toDate))
                        <button type="button" id="clearDateFilterBtn" class="date-filter-clear-btn" title="Clear Date Filter (Show All)">✕</button>
                    @endif
                </div>
            </div>
        </div>

        <div class="toolbar-group">

            <!-- Auto-Compute Balance Toggle -->
            <button type="button" class="btn-action btn-toggle-active" id="autoComputeBtn"
                title="Automatically calculate running balance when receipt/issuance is entered">
                <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="20" x2="18" y2="10" />
                    <line x1="12" y1="20" x2="12" y2="4" />
                    <line x1="6" y1="20" x2="6" y2="14" />
                </svg>
                <span id="autoComputeLabel">Auto Balance: ON</span>
            </button>

            <!-- Add Rows Controls -->
            {{-- <button type="button" class="btn-action btn-secondary" id="addRowBtn" title="Append 1 blank row">
                <span>+ 1 Row</span>
            </button>
            <button type="button" class="btn-action btn-secondary" id="add5RowsBtn" title="Append 5 blank rows">
                <span>+ 5 Rows</span>
            </button> --}}

            <!-- Undo Delete Button -->
            <button type="button" class="btn-action btn-secondary" id="undoBtn" disabled
                title="Undo last deleted row (Ctrl+Z)">
                <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 7v6h6" />
                    <path d="M21 17a9 9 0 0 0-9-9 9 9 0 0 0-6 2.3L3 13" />
                </svg>
                <span>Undo</span>
                <span id="undoCountBadge"
                    style="display: none; background: #0284c7; color: #fff; font-size: 10px; padding: 1px 5px; border-radius: 10px; font-weight: 700; margin-left: 2px;">0</span>
            </button>

            <!-- Clear Table -->
            {{-- <button type="button" class="btn-action btn-ghost" id="clearTableBtn"
                title="Reset all rows to blank ledger lines">
                <span>Clear Table</span>
            </button> --}}

            <!-- Save Stock Card to Database -->
            <button type="button" class="btn-action btn-save" id="saveCardBtn"
                title="Save Stock Card and ledger rows to database">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                    <polyline points="17 21 17 13 7 13 7 21" />
                    <polyline points="7 3 7 8 15 8" />
                </svg>
                <span id="saveCardBtnText">Save Card</span>
            </button>

            <!-- Print Stock Card -->
            <button type="button" class="btn-action btn-primary" onclick="window.print()"
                title="Print Stock Card or Save as PDF">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="6 9 6 2 18 2 18 9" />
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                    <rect x="6" y="14" width="12" height="8" />
                </svg>
                <span>Print Stock Card</span>
            </button>
        </div>
    </header>

    <!-- Main Printable Canvas Area -->
    <main class="paper-canvas-wrap">
        @if(!empty($isAllItemsMode))
            @if(count($bulkCards) > 0)
                <div class="stock-cards-multi-grid" id="stockCardsMultiGrid">
                    @foreach($bulkCards as $index => $bCard)
                    <article class="stock-card-sheet stock-card-grid-card" id="stockCardSheet-{{ $index }}">
                        <!-- Document Title & Office Header -->
                        <div class="sc-header-center">
                            <h1 class="sc-main-title">STOCK CARD</h1>

                            <div class="sc-office-wrapper">
                                <div class="sc-office-line"></div>
                                <div class="sc-office-caption">OFFICE</div>
                            </div>
                        </div>

                        <!-- Metadata Row (ITEM, Unit, STOCK NO) -->
                        <div class="sc-meta-grid">
                            <div class="sc-meta-left">
                                <div class="sc-meta-row-item">
                                    <span class="sc-label-bold">ITEM:</span>
                                    <div class="sc-underline-field" title="{{ $bCard['item_name'] }}">{{ $bCard['item_name'] }}</div>
                                </div>
                                <div class="sc-meta-row-unit">
                                    <span class="sc-label-unit">Unit:</span>
                                    <div class="sc-underline-unit">{{ $bCard['unit'] }}</div>
                                </div>
                            </div>

                            <div class="sc-meta-right">
                                <span class="sc-label-bold">STOCK NO:</span>
                                <div class="sc-underline-stockno" title="{{ $bCard['stock_no'] }}">{{ $bCard['stock_no'] }}</div>
                            </div>
                        </div>

                        <!-- Ledger Table -->
                        <div class="sc-table-container">
                            <table class="sc-table">
                                <colgroup>
                                    <col class="col-date">
                                    <col class="col-po">
                                    <col class="col-receipt-qty">
                                    <col class="col-ris">
                                    <col class="col-iss-qty">
                                    <col class="col-bal-qty">
                                    <col class="col-remarks">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th rowspan="2" class="th-date">DATE</th>
                                        <th class="th-ref">Reference</th>
                                        <th class="th-receipt">RECIEPT</th>
                                        <th colspan="2" class="th-issuance">ISSUANCE</th>
                                        <th class="th-balance">BALANCE</th>
                                        <th rowspan="2" class="th-remarks">REMARKS</th>
                                    </tr>
                                    <tr>
                                        <th class="th-po">PO #</th>
                                        <th class="th-receipt-qty">QTY</th>
                                        <th class="th-ris">RIS # / OFFICE</th>
                                        <th class="th-iss-qty">QTY</th>
                                        <th class="th-bal-qty">QTY</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $bRows = $bCard['rows'];
                                        $bFilled = count($bRows);
                                        $bBlanks = max(0, 38 - $bFilled);
                                    @endphp

                                    @foreach($bRows as $r)
                                        <tr>
                                            <td class="td-date">{{ $r['date'] ?? '' }}</td>
                                            <td class="td-po">{{ $r['po'] ?? '' }}</td>
                                            <td class="td-receipt-qty">{{ $r['receiptQty'] !== '' && $r['receiptQty'] !== null ? number_format((float)$r['receiptQty'], 0) : '' }}</td>
                                            <td class="td-ris" style="{{ !empty($r['po']) && empty($r['issuanceQty']) ? 'font-weight: 700;' : '' }}">{{ $r['risOffice'] ?? '' }}</td>
                                            <td class="td-iss-qty">{{ $r['issuanceQty'] !== '' && $r['issuanceQty'] !== null ? number_format((float)$r['issuanceQty'], 0) : '' }}</td>
                                            <td class="td-bal-qty">{{ $r['balanceQty'] !== '' && $r['balanceQty'] !== null ? number_format((float)$r['balanceQty'], 0) : '' }}</td>
                                            <td class="td-remarks">{{ $r['remarks'] ?? '' }}</td>
                                        </tr>
                                    @endforeach

                                    @for($i = 0; $i < $bBlanks; $i++)
                                        <tr class="blank-row">
                                            <td class="td-date">&nbsp;</td>
                                            <td class="td-po"></td>
                                            <td class="td-receipt-qty"></td>
                                            <td class="td-ris"></td>
                                            <td class="td-iss-qty"></td>
                                            <td class="td-bal-qty"></td>
                                            <td class="td-remarks"></td>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    </article>
                @endforeach
            </div>
            @else
                <div class="stock-cards-empty-state no-print">
                    <div class="empty-state-card">
                        <div class="empty-state-icon">
                            <svg viewBox="0 0 24 24" width="36" height="36" fill="none" stroke="#38bdf8" stroke-width="1.8">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8" y1="2" x2="8" y2="6"/>
                                <line x1="3" y1="10" x2="21" y2="10"/>
                                <line x1="9" y1="14" x2="15" y2="14"/>
                            </svg>
                        </div>
                        <h3 class="empty-state-title">No Stock Card Activity</h3>
                        <p class="empty-state-desc">
                            No stock card transactions or activities were recorded
                            @if(!empty($fromDate) && !empty($toDate))
                                between <strong>{{ \Carbon\Carbon::parse($fromDate)->format('M d, Y') }}</strong> and <strong>{{ \Carbon\Carbon::parse($toDate)->format('M d, Y') }}</strong>.
                            @elseif(!empty($fromDate))
                                since <strong>{{ \Carbon\Carbon::parse($fromDate)->format('M d, Y') }}</strong>.
                            @elseif(!empty($toDate))
                                up to <strong>{{ \Carbon\Carbon::parse($toDate)->format('M d, Y') }}</strong>.
                            @else
                                for the selected filter.
                            @endif
                        </p>
                        <a href="{{ route('admin.reports.stock-card', array_filter(['variant_id' => $selectedVariantId, 'office_id' => $selectedOfficeId])) }}" class="btn-action btn-secondary" style="display: inline-flex;">
                            Clear Date Filter
                        </a>
                    </div>
                </div>
            @endif
        @else
            <article class="stock-card-sheet" id="stockCardSheet">
                <input type="hidden" id="stockCardId" value="{{ $stockCard?->id ?? '' }}">
                <input type="hidden" id="currentVariantId" value="{{ $selectedVariant?->id ?? '' }}">
                <input type="hidden" id="currentOfficeId" value="{{ $selectedOfficeId ?? ($stockCard?->office_id ?? '') }}">

                <!-- Document Title & Office Header -->
                <div class="sc-header-center">
                    <h1 class="sc-main-title">STOCK CARD</h1>

                    <div class="sc-office-wrapper">
                        <div class="sc-office-line" id="officeField" contenteditable="true" spellcheck="false" {{--
                            data-placeholder="Click to specify Office / Department" --}}>
                            {{ $stockCard?->office ?? ($selectedOffice?->office_name ?? '') }}</div>
                        <div class="sc-office-caption">OFFICE</div>
                    </div>
                </div>

                <!-- Metadata Row (ITEM, Unit, STOCK NO) -->
                <div class="sc-meta-grid">
                    <div class="sc-meta-left">
                        <div class="sc-meta-row-item">
                            <span class="sc-label-bold">ITEM:</span>
                            <div class="sc-underline-field" id="itemNameField" contenteditable="true" spellcheck="false"
                                data-placeholder="Enter Item Description">
                                {{ $stockCard?->item_name ?? ($selectedVariant?->variant_name ?? ($selectedVariant?->inventoryItem?->name ?? '')) }}
                            </div>
                        </div>
                        <div class="sc-meta-row-unit">
                            <span class="sc-label-unit">Unit:</span>
                            <div class="sc-underline-unit" id="unitField" contenteditable="true" spellcheck="false"
                                data-placeholder="unit">
                                {{ $stockCard?->unit ?? ($selectedVariant?->effective_unit ?? ($selectedVariant?->unit ?? '')) }}
                            </div>
                        </div>
                    </div>

                    <div class="sc-meta-right">
                        <span class="sc-label-bold">STOCK NO:</span>
                        <div class="sc-underline-stockno" id="stockNoField" contenteditable="true" spellcheck="false"
                            data-placeholder="Enter Stock No.">
                            {{ $stockCard?->stock_no ?? ($selectedVariant?->item_code ?? ($selectedVariant?->sku ?? '')) }}
                        </div>
                    </div>
                </div>

                <!-- Ledger Table -->
                <div class="sc-table-container">
                    <table class="sc-table" id="stockCardTable">
                        <colgroup>
                            <col class="col-date">
                            <col class="col-po">
                            <col class="col-receipt-qty">
                            <col class="col-ris">
                            <col class="col-iss-qty">
                            <col class="col-bal-qty">
                            <col class="col-remarks">
                            <col class="col-action no-print">
                        </colgroup>
                        <thead>
                            <tr>
                                <th rowspan="2" class="th-date">DATE</th>
                                <th class="th-ref">Reference</th>
                                <th class="th-receipt">RECIEPT</th>
                                <th colspan="2" class="th-issuance">ISSUANCE</th>
                                <th class="th-balance">BALANCE</th>
                                <th rowspan="2" class="th-remarks">REMARKS</th>
                                <th class="row-action-cell no-print"></th>
                            </tr>
                            <tr>
                                <th class="th-po">PO #</th>
                                <th class="th-receipt-qty">QTY</th>
                                <th class="th-ris">RIS # / OFFICE</th>
                                <th class="th-iss-qty">QTY</th>
                                <th class="th-bal-qty">QTY</th>
                            </tr>
                        </thead>
                        <tbody id="stockCardTableBody">
                            <!-- Populated dynamically with JavaScript -->
                        </tbody>
                    </table>
                </div>

            </article>
        @endif
    </main>

    <!-- Helpful Keyboard Shortcuts & Instructions (Hidden in Print) -->
    <div class="helper-ribbon no-print">
        @if(!empty($isAllItemsMode))
            @if(!empty($fromDate) || !empty($toDate))
                <span style="font-weight: 700; color: #38bdf8;">Filtered by Activity:</span>
                <span>
                    Showing <strong>{{ count($bulkCards) }}</strong> stock card{{ count($bulkCards) === 1 ? '' : 's' }} with transactions 
                    @if(!empty($fromDate) && !empty($toDate))
                        between <strong>{{ \Carbon\Carbon::parse($fromDate)->format('M d, Y') }}</strong> and <strong>{{ \Carbon\Carbon::parse($toDate)->format('M d, Y') }}</strong>.
                    @elseif(!empty($fromDate))
                        from <strong>{{ \Carbon\Carbon::parse($fromDate)->format('M d, Y') }}</strong> onwards.
                    @elseif(!empty($toDate))
                        up to <strong>{{ \Carbon\Carbon::parse($toDate)->format('M d, Y') }}</strong>.
                    @endif
                </span>
                @if(count($bulkCards) > 0)
                    <span style="margin-left: auto; color: #38bdf8; font-weight: 600;">{{ count($bulkCards) }} Ready to Print</span>
                @endif
            @else
                <span style="font-weight: 700; color: #38bdf8;">All Items Stock Cards:</span>
                <span>Displaying <strong>{{ count($bulkCards) }}</strong> stock card pages (one for each warehouse item). Click <strong>Print Stock Card</strong> to print all pages consecutively.</span>
                <span style="margin-left: auto; color: #38bdf8; font-weight: 600;">{{ count($bulkCards) }} Cards Ready to Print</span>
            @endif
        @else
            <span style="font-weight: 700; color: #f8fafc;">Editing Tips:</span>
            <span>Click any cell or underline to edit text directly.</span>
            <span><kbd>Enter</kbd> Moves down a cell</span>
            <span><kbd>Tab</kbd> Moves across columns</span>
            <span><kbd>Ctrl + Z</kbd> Undo deleted row</span>
            <span id="saveStatusIndicator" style="margin-left: auto; color: #10b981; font-weight: 600;">✓ Synced with
                Database</span>
        @endif
    </div>

    <!-- Undo Floating Toast Notification (Hidden in Print) -->
    <div id="undoToast" class="undo-toast no-print" style="display: none;">
        <div class="undo-toast-content">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="#f59e0b" stroke-width="2">
                <circle cx="12" cy="12" r="10" />
                <line x1="12" y1="8" x2="12" y2="12" />
                <line x1="12" y1="16" x2="12.01" y2="16" />
            </svg>
            <span id="undoToastText">Row deleted.</span>
        </div>
        <button type="button" id="toastUndoBtn" class="toast-undo-btn">
            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M3 7v6h6" />
                <path d="M21 17a9 9 0 0 0-9-9 9 9 0 0 0-6 2.3L3 13" />
            </svg>
            <span>Undo</span>
        </button>
        <button type="button" id="toastDismissBtn" class="toast-dismiss-btn" title="Dismiss">✕</button>
    </div>

    <!-- Interactive Client Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const DEFAULT_ROW_COUNT = 30;
            let autoComputeBalance = true;

            const tableBody = document.getElementById('stockCardTableBody');
            const officeField = document.getElementById('officeField');
            const itemNameField = document.getElementById('itemNameField');
            const unitField = document.getElementById('unitField');
            const stockNoField = document.getElementById('stockNoField');
            const stockCardIdInput = document.getElementById('stockCardId');
            const currentVariantIdInput = document.getElementById('currentVariantId');
            const currentOfficeIdInput = document.getElementById('currentOfficeId');

            const autoComputeBtn = document.getElementById('autoComputeBtn');
            const autoComputeLabel = document.getElementById('autoComputeLabel');
            const addRowBtn = document.getElementById('addRowBtn');
            const add5RowsBtn = document.getElementById('add5RowsBtn');
            const clearTableBtn = document.getElementById('clearTableBtn');
            const undoBtn = document.getElementById('undoBtn');
            const undoCountBadge = document.getElementById('undoCountBadge');
            const undoToast = document.getElementById('undoToast');
            const undoToastText = document.getElementById('undoToastText');
            const toastUndoBtn = document.getElementById('toastUndoBtn');
            const toastDismissBtn = document.getElementById('toastDismissBtn');
            const saveCardBtn = document.getElementById('saveCardBtn');
            const saveCardBtnText = document.getElementById('saveCardBtnText');
            const saveStatusIndicator = document.getElementById('saveStatusIndicator');

            // Inventory Item Variant & Office Lookups from DropdownSearch Components
            const variantHiddenInput = document.getElementById('stockCardVariantSelect');
            const variantLookup = {!! json_encode(collect($variantOptions)->keyBy('id')) !!};
            const officeHiddenInput = document.getElementById('stockCardOfficeSelect');
            const officeLookup = {!! json_encode(collect($officeOptions)->keyBy('id')) !!};
            const isAllItemsModeActive = {{ !empty($isAllItemsMode) ? 'true' : 'false' }};

            // Database preloaded data
            const preloadedStockCard = {!! json_encode($stockCard) !!};
            const preloadedRows = {!! json_encode($initialRows ?? []) !!};
            let currentStockCardId = preloadedStockCard ? preloadedStockCard.id : (stockCardIdInput?.value ? parseInt(stockCardIdInput.value) : null);

            // Deletion stack for undo support
            const deletedRowsStack = [];
            let toastTimeout = null;

            function setSaveStatus(status) {
                if (!saveStatusIndicator) return;
                if (status === 'saved') {
                    saveStatusIndicator.innerHTML = '<span style="color: #10b981;">✓ Synced with Database</span>';
                } else if (status === 'unsaved') {
                    saveStatusIndicator.innerHTML = '<span style="color: #f59e0b;">● Unsaved Changes</span>';
                } else if (status === 'saving') {
                    saveStatusIndicator.innerHTML = '<span style="color: #38bdf8;">Saving...</span>';
                }
            }

            function isRowInbound(data = {}) {
                const receiptVal = data.receiptQty !== undefined && data.receiptQty !== null ? String(data.receiptQty).trim() : '';
                const issVal = data.issuanceQty !== undefined && data.issuanceQty !== null ? String(data.issuanceQty).trim() : '';

                const hasReceipt = receiptVal !== '' && !isNaN(Number(receiptVal)) && Number(receiptVal) > 0;
                const hasIssuance = issVal !== '' && !isNaN(Number(issVal)) && Number(issVal) > 0;

                if (hasReceipt && !hasIssuance) return true;
                if (hasIssuance) return false;

                if (data.po && !issVal) return true;
                if (data.risOffice) {
                    const risUpper = String(data.risOffice).toUpperCase();
                    if (risUpper.includes('RIS')) return false;
                    return true;
                }
                return false;
            }

            function updateRowRisStyling(row) {
                if (!row) return;
                const receiptVal = row.querySelector('[data-col="receiptQty"]')?.textContent.trim() || '';
                const issVal = row.querySelector('[data-col="issuanceQty"]')?.textContent.trim() || '';
                const poVal = row.querySelector('[data-col="po"]')?.textContent.trim() || '';
                const risEl = row.querySelector('[data-col="risOffice"]');
                if (!risEl) return;

                const risText = risEl.textContent.trim();
                const inbound = isRowInbound({
                    receiptQty: receiptVal,
                    issuanceQty: issVal,
                    po: poVal,
                    risOffice: risText
                });

                if (inbound) {
                    risEl.style.fontWeight = '700';
                    risEl.classList.add('is-office-code');
                    risEl.classList.remove('is-ris-no');
                } else {
                    risEl.style.fontWeight = '400';
                    risEl.classList.add('is-ris-no');
                    risEl.classList.remove('is-office-code');
                }
            }

            function formatToMMDDYY(dateStr) {
                if (!dateStr) return '';
                const trimmed = String(dateStr).trim();
                if (!trimmed) return '';
                // If mm/dd/yy or m/d/yy or mm/dd/yyyy
                const slashMatch = trimmed.match(/^(\d{1,2})\/(\d{1,2})\/(\d{2,4})$/);
                if (slashMatch) {
                    const m = slashMatch[1].padStart(2, '0');
                    const d = slashMatch[2].padStart(2, '0');
                    let y = slashMatch[3];
                    if (y.length === 4) y = y.slice(-2);
                    return `${m}/${d}/${y}`;
                }
                // If yyyy-mm-dd
                const dashMatch = trimmed.match(/^(\d{4})-(\d{1,2})-(\d{1,2})/);
                if (dashMatch) {
                    const y = dashMatch[1].slice(-2);
                    const m = dashMatch[2].padStart(2, '0');
                    const d = dashMatch[3].padStart(2, '0');
                    return `${m}/${d}/${y}`;
                }
                // Fallback attempt
                const dt = new Date(trimmed);
                if (!isNaN(dt.getTime())) {
                    const m = String(dt.getMonth() + 1).padStart(2, '0');
                    const d = String(dt.getDate()).padStart(2, '0');
                    const y = String(dt.getFullYear()).slice(-2);
                    return `${m}/${d}/${y}`;
                }
                return trimmed;
            }

            // Create a table row DOM element
            function createRowElement(data = {}) {
                const tr = document.createElement('tr');
                tr.className = 'sc-data-row';

                const inbound = isRowInbound(data);
                const risClass = inbound ? 'is-office-code' : 'is-ris-no';
                const risWeight = inbound ? '700' : '400';
                const formattedDate = formatToMMDDYY(data.date || '');

                tr.innerHTML = `
                    <td class="cell-date"><div class="cell-content" contenteditable="true" spellcheck="false" data-col="date">${escapeHtml(formattedDate)}</div></td>
                    <td class="cell-po"><div class="cell-content" contenteditable="true" spellcheck="false" data-col="po">${escapeHtml(data.po || '')}</div></td>
                    <td class="cell-receipt-qty"><div class="cell-content" contenteditable="true" spellcheck="false" data-col="receiptQty">${escapeHtml(data.receiptQty || '')}</div></td>
                    <td class="cell-ris"><div class="cell-content ${risClass}" style="font-weight: ${risWeight};" contenteditable="true" spellcheck="false" data-col="risOffice">${escapeHtml(data.risOffice || '')}</div></td>
                    <td class="cell-iss-qty"><div class="cell-content" contenteditable="true" spellcheck="false" data-col="issuanceQty">${escapeHtml(data.issuanceQty || '')}</div></td>
                    <td class="cell-bal-qty"><div class="cell-content" contenteditable="true" spellcheck="false" data-col="balanceQty">${escapeHtml(data.balanceQty || '')}</div></td>
                    <td class="cell-remarks"><div class="cell-content" contenteditable="true" spellcheck="false" data-col="remarks">${escapeHtml(data.remarks || '')}</div></td>
                    <td class="row-action-cell no-print">
                        <button type="button" class="row-del-btn" title="Delete this row">×</button>
                    </td>
                `;

                return tr;
            }

            function escapeHtml(str) {
                if (!str) return '';
                return String(str)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            // Recalculate running balance across all rows
            function recalculateBalances() {
                if (!tableBody || !autoComputeBalance) return;

                const rows = tableBody.querySelectorAll('tr.sc-data-row');
                let runningBalance = null;

                rows.forEach((row) => {
                    const receiptEl = row.querySelector('[data-col="receiptQty"]');
                    const issEl = row.querySelector('[data-col="issuanceQty"]');
                    const balEl = row.querySelector('[data-col="balanceQty"]');

                    const receiptVal = receiptEl ? receiptEl.textContent.trim() : '';
                    const issVal = issEl ? issEl.textContent.trim() : '';

                    const receiptNum = receiptVal !== '' && !isNaN(Number(receiptVal)) ? Number(receiptVal) : 0;
                    const issNum = issVal !== '' && !isNaN(Number(issVal)) ? Number(issVal) : 0;

                    const hasTransaction = receiptVal !== '' || issVal !== '';

                    if (hasTransaction) {
                        if (runningBalance === null) {
                            runningBalance = receiptNum - issNum;
                        } else {
                            runningBalance = runningBalance + receiptNum - issNum;
                        }
                        if (balEl) balEl.textContent = String(Math.max(0, runningBalance));
                    } else if (balEl && runningBalance === null && balEl.textContent.trim() !== '') {
                        // User manual initial balance
                        const manualNum = Number(balEl.textContent.trim());
                        if (!isNaN(manualNum)) {
                            runningBalance = manualNum;
                        }
                    }
                });
            }

            // Render table with specific rows array and pad up to totalRows
            function renderTable(rowsData = [], totalRows = DEFAULT_ROW_COUNT) {
                if (!tableBody) return;
                tableBody.innerHTML = '';
                const count = Math.max(rowsData.length, totalRows);

                for (let i = 0; i < count; i++) {
                    const rowData = rowsData[i] || {};
                    const tr = createRowElement(rowData);
                    tableBody.appendChild(tr);
                }

                recalculateBalances();
                tableBody.querySelectorAll('tr.sc-data-row').forEach(updateRowRisStyling);
                saveStateToLocalStorage();
            }

            // Save state to local storage for persistence across reloads
            function saveStateToLocalStorage() {
                if (!tableBody) return;
                try {
                    const rows = [];
                    tableBody.querySelectorAll('tr.sc-data-row').forEach(row => {
                        const date = row.querySelector('[data-col="date"]')?.textContent.trim() || '';
                        const po = row.querySelector('[data-col="po"]')?.textContent.trim() || '';
                        const receiptQty = row.querySelector('[data-col="receiptQty"]')?.textContent.trim() || '';
                        const risOffice = row.querySelector('[data-col="risOffice"]')?.textContent.trim() || '';
                        const issuanceQty = row.querySelector('[data-col="issuanceQty"]')?.textContent.trim() || '';
                        const balanceQty = row.querySelector('[data-col="balanceQty"]')?.textContent.trim() || '';
                        const remarks = row.querySelector('[data-col="remarks"]')?.textContent.trim() || '';

                        if (date || po || receiptQty || risOffice || issuanceQty || balanceQty || remarks) {
                            rows.push({ date, po, receiptQty, risOffice, issuanceQty, balanceQty, remarks });
                        }
                    });

                    const state = {
                        office: officeField.textContent.trim(),
                        itemName: itemNameField.textContent.trim(),
                        unit: unitField.textContent.trim(),
                        stockNo: stockNoField.textContent.trim(),
                        autoCompute: autoComputeBalance,
                        rows: rows
                    };

                    localStorage.setItem('warehouse_stock_card_state_v2', JSON.stringify(state));
                } catch (e) {
                    console.warn('Could not save to localStorage:', e);
                }
            }

            // Restore from database preloaded data or localStorage
            function restoreState() {
                if (Array.isArray(preloadedRows) && preloadedRows.length > 0) {
                    renderTable(preloadedRows, DEFAULT_ROW_COUNT);
                    setSaveStatus('saved');
                    return;
                }

                try {
                    const saved = localStorage.getItem('warehouse_stock_card_state_v2');
                    if (saved) {
                        const state = JSON.parse(saved);
                        if (typeof state.office === 'string' && state.office) officeField.textContent = state.office;
                        if (typeof state.itemName === 'string') itemNameField.textContent = state.itemName;
                        if (typeof state.unit === 'string') unitField.textContent = state.unit;
                        if (typeof state.stockNo === 'string') stockNoField.textContent = state.stockNo;
                        if (typeof state.autoCompute === 'boolean') {
                            autoComputeBalance = state.autoCompute;
                            updateAutoComputeUI();
                        }
                        if (Array.isArray(state.rows) && state.rows.length > 0) {
                            renderTable(state.rows, DEFAULT_ROW_COUNT);
                            setSaveStatus('unsaved');
                            return;
                        }
                    }
                } catch (e) {
                    console.warn('Could not restore from localStorage:', e);
                }

                renderTable([], DEFAULT_ROW_COUNT);
                setSaveStatus('saved');
            }

            function updateAutoComputeUI() {
                if (autoComputeBalance) {
                    autoComputeBtn.className = 'btn-action btn-toggle-active';
                    autoComputeLabel.textContent = 'Auto Balance: ON';
                } else {
                    autoComputeBtn.className = 'btn-action btn-toggle-inactive';
                    autoComputeLabel.textContent = 'Auto Balance: OFF';
                }
            }

            // Toggle Auto-compute
            if (autoComputeBtn) {
                autoComputeBtn.addEventListener('click', () => {
                    autoComputeBalance = !autoComputeBalance;
                    updateAutoComputeUI();
                    if (autoComputeBalance) recalculateBalances();
                    saveStateToLocalStorage();
                });
            }

            // Add 1 Row
            if (addRowBtn) {
                addRowBtn.addEventListener('click', () => {
                    const tr = createRowElement();
                    tableBody.appendChild(tr);
                    saveStateToLocalStorage();
                });
            }

            // Add 5 Rows
            if (add5RowsBtn) {
                add5RowsBtn.addEventListener('click', () => {
                    for (let i = 0; i < 5; i++) {
                        tableBody.appendChild(createRowElement());
                    }
                    saveStateToLocalStorage();
                });
            }

            // Clear Table
            if (clearTableBtn) {
                clearTableBtn.addEventListener('click', () => {
                    if (confirm('Clear all entries and reset to blank lined stock card?')) {
                        const existingRows = [];
                        tableBody.querySelectorAll('tr.sc-data-row').forEach(row => {
                            const date = row.querySelector('[data-col="date"]')?.textContent.trim() || '';
                            const po = row.querySelector('[data-col="po"]')?.textContent.trim() || '';
                            const receiptQty = row.querySelector('[data-col="receiptQty"]')?.textContent.trim() || '';
                            const risOffice = row.querySelector('[data-col="risOffice"]')?.textContent.trim() || '';
                            const issuanceQty = row.querySelector('[data-col="issuanceQty"]')?.textContent.trim() || '';
                            const balanceQty = row.querySelector('[data-col="balanceQty"]')?.textContent.trim() || '';
                            const remarks = row.querySelector('[data-col="remarks"]')?.textContent.trim() || '';

                            if (date || po || receiptQty || risOffice || issuanceQty || balanceQty || remarks) {
                                existingRows.push({ date, po, receiptQty, risOffice, issuanceQty, balanceQty, remarks });
                            }
                        });

                        if (existingRows.length > 0) {
                            deletedRowsStack.push({
                                isBulk: true,
                                rows: existingRows
                            });
                            updateUndoUI();
                            showUndoToast('Cleared table entries.');
                        }

                        renderTable([], DEFAULT_ROW_COUNT);
                    }
                });
            }

            // Delete row with undo tracking
            function deleteRow(row) {
                if (!row) return;

                const allRows = Array.from(tableBody.querySelectorAll('tr.sc-data-row'));
                const index = allRows.indexOf(row);

                const date = row.querySelector('[data-col="date"]')?.textContent.trim() || '';
                const po = row.querySelector('[data-col="po"]')?.textContent.trim() || '';
                const receiptQty = row.querySelector('[data-col="receiptQty"]')?.textContent.trim() || '';
                const risOffice = row.querySelector('[data-col="risOffice"]')?.textContent.trim() || '';
                const issuanceQty = row.querySelector('[data-col="issuanceQty"]')?.textContent.trim() || '';
                const balanceQty = row.querySelector('[data-col="balanceQty"]')?.textContent.trim() || '';
                const remarks = row.querySelector('[data-col="remarks"]')?.textContent.trim() || '';

                const hasContent = date || po || receiptQty || risOffice || issuanceQty || balanceQty || remarks;

                deletedRowsStack.push({
                    index: index >= 0 ? index : allRows.length,
                    data: { date, po, receiptQty, risOffice, issuanceQty, balanceQty, remarks }
                });

                row.remove();
                recalculateBalances();
                saveStateToLocalStorage();
                updateUndoUI();

                showUndoToast(hasContent ? `Deleted row (PO/RIS: ${po || risOffice || 'N/A'})` : 'Deleted row');
            }

            function undoLastDeletedRow() {
                if (deletedRowsStack.length === 0) return;

                const lastItem = deletedRowsStack.pop();

                if (lastItem.isBulk && Array.isArray(lastItem.rows)) {
                    renderTable(lastItem.rows, DEFAULT_ROW_COUNT);
                    showUndoToast('Cleared table entries restored!');
                } else if (lastItem.data) {
                    const tr = createRowElement(lastItem.data);
                    const currentRows = tableBody.querySelectorAll('tr.sc-data-row');
                    if (lastItem.index >= 0 && lastItem.index < currentRows.length) {
                        tableBody.insertBefore(tr, currentRows[lastItem.index]);
                    } else {
                        tableBody.appendChild(tr);
                    }

                    recalculateBalances();
                    saveStateToLocalStorage();

                    // Flash highlight on restored row
                    tr.style.backgroundColor = '#ecfdf5';
                    setTimeout(() => {
                        tr.style.transition = 'background-color 0.8s ease';
                        tr.style.backgroundColor = '';
                    }, 500);

                    // Focus restored row
                    const firstCell = tr.querySelector('.cell-content');
                    if (firstCell) firstCell.focus();

                    showUndoToast('Row restored!');
                }

                updateUndoUI();
            }

            function updateUndoUI() {
                if (!undoBtn) return;
                const count = deletedRowsStack.length;
                if (count > 0) {
                    undoBtn.disabled = false;
                    if (undoCountBadge) {
                        undoCountBadge.textContent = count;
                        undoCountBadge.style.display = 'inline-block';
                    }
                } else {
                    undoBtn.disabled = true;
                    if (undoCountBadge) {
                        undoCountBadge.style.display = 'none';
                    }
                }
            }

            function showNotification(msg, mode = 'info') {
                if (!undoToast) return;
                if (toastTimeout) clearTimeout(toastTimeout);

                if (undoToastText) undoToastText.textContent = msg || '';

                if (toastUndoBtn) {
                    toastUndoBtn.style.display = (mode === 'undo') ? 'inline-flex' : 'none';
                }

                undoToast.style.display = 'flex';

                toastTimeout = setTimeout(() => {
                    hideUndoToast();
                }, mode === 'undo' ? 7000 : 4000);
            }

            function hideUndoToast() {
                if (!undoToast) return;
                undoToast.style.display = 'none';
                if (toastTimeout) {
                    clearTimeout(toastTimeout);
                    toastTimeout = null;
                }
            }

            // Undo buttons listeners
            if (undoBtn) undoBtn.addEventListener('click', undoLastDeletedRow);
            if (toastUndoBtn) toastUndoBtn.addEventListener('click', undoLastDeletedRow);
            if (toastDismissBtn) toastDismissBtn.addEventListener('click', hideUndoToast);

            // Global keyboard undo (Ctrl+Z / Cmd+Z when not typing inside an active cell)
            document.addEventListener('keydown', (e) => {
                if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'z' && !e.shiftKey) {
                    const active = document.activeElement;
                    const isEditingCell = active && (active.classList?.contains('cell-content') || active.tagName === 'INPUT');
                    if (!isEditingCell && deletedRowsStack.length > 0) {
                        e.preventDefault();
                        undoLastDeletedRow();
                    }
                }
            });

            // Dynamic Load Stock Card via Server Data Endpoint
            async function loadStockCardData(vId, oId) {
                const variantId = vId !== undefined ? String(vId || '') : String(variantHiddenInput?.value || '');
                const officeId = oId !== undefined ? String(oId || '') : String(officeHiddenInput?.value || '');

                if (variantId === 'all' || (isAllItemsModeActive && vId !== undefined)) {
                    const url = new URL(window.location.href);
                    if (variantId) url.searchParams.set('variant_id', variantId);
                    else url.searchParams.delete('variant_id');
                    if (officeId) url.searchParams.set('office_id', officeId);
                    else url.searchParams.delete('office_id');
                    window.location.href = url.toString();
                    return;
                }

                if (!variantId) {
                    currentStockCardId = null;
                    if (stockCardIdInput) stockCardIdInput.value = '';
                    if (currentVariantIdInput) currentVariantIdInput.value = '';
                    if (itemNameField) itemNameField.textContent = '';
                    if (unitField) unitField.textContent = '';
                    if (stockNoField) stockNoField.textContent = '';

                    const offObj = officeLookup[officeId];
                    if (officeField) officeField.textContent = offObj?.office_name || '';

                    renderTable([], DEFAULT_ROW_COUNT);
                    setSaveStatus('saved');

                    const url = new URL(window.location.href);
                    url.searchParams.delete('variant_id');
                    if (officeId) {
                        url.searchParams.set('office_id', officeId);
                    } else {
                        url.searchParams.delete('office_id');
                    }
                    history.pushState(null, '', url.pathname + url.search);
                    return;
                }

                try {
                    setSaveStatus('saving');
                    const currentUrlParams = new URLSearchParams(window.location.search);
                    const fDate = currentUrlParams.get('from_date');
                    const tDate = currentUrlParams.get('to_date');
                    let fetchUrl = `{{ route('admin.reports.stock-card.data') }}?variant_id=${variantId}${officeId ? '&office_id=' + officeId : ''}`;
                    if (fDate) fetchUrl += `&from_date=${encodeURIComponent(fDate)}`;
                    if (tDate) fetchUrl += `&to_date=${encodeURIComponent(tDate)}`;

                    const res = await fetch(fetchUrl);
                    const data = await res.json();

                    if (data.success) {
                        const pageUrl = new URL(window.location.href);
                        pageUrl.searchParams.set('variant_id', variantId);
                        if (officeId) {
                            pageUrl.searchParams.set('office_id', officeId);
                        } else {
                            pageUrl.searchParams.delete('office_id');
                        }
                        history.pushState(null, '', pageUrl.pathname + pageUrl.search);

                        const defaultOfficeName = officeLookup[officeId]?.office_name || '';

                        if (data.stock_card) {
                            currentStockCardId = data.stock_card.id;
                            if (stockCardIdInput) stockCardIdInput.value = data.stock_card.id;
                            if (officeField) officeField.textContent = data.stock_card.office || defaultOfficeName;
                            if (itemNameField) itemNameField.textContent = data.stock_card.item_name || '';
                            if (unitField) unitField.textContent = data.stock_card.unit || '';
                            if (stockNoField) stockNoField.textContent = data.stock_card.stock_no || '';
                        } else if (data.defaults) {
                            currentStockCardId = null;
                            if (stockCardIdInput) stockCardIdInput.value = '';
                            if (officeField) officeField.textContent = data.defaults.office || defaultOfficeName;
                            if (itemNameField) itemNameField.textContent = data.defaults.item_name || '';
                            if (unitField) unitField.textContent = data.defaults.unit || '';
                            if (stockNoField) stockNoField.textContent = data.defaults.stock_no || '';
                        }

                        renderTable(data.rows || [], DEFAULT_ROW_COUNT);
                        setSaveStatus('saved');
                        showNotification(data.stock_card ? 'Loaded saved Stock Card.' : 'Stock card ledger ready.', 'info');
                    }
                } catch (err) {
                    console.error('Error fetching stock card data:', err);
                    setSaveStatus('unsaved');
                }
            }

            // Save Stock Card and Transactions to Database
            async function saveStockCardToDatabase() {
                if (!tableBody) return;
                const originalText = saveCardBtnText ? saveCardBtnText.textContent : 'Save Card';
                const selectedVariantId = variantHiddenInput?.value || (currentVariantIdInput?.value || null);
                const selectedOfficeId = officeHiddenInput?.value || (currentOfficeIdInput?.value || null);
                const office = officeField ? officeField.textContent.trim() : '';
                const itemName = itemNameField ? itemNameField.textContent.trim() : '';
                const unit = unitField ? unitField.textContent.trim() : '';
                const stockNo = stockNoField ? stockNoField.textContent.trim() : '';

                if (!itemName) {
                    showNotification('Please provide an Item Name before saving.', 'info');
                    if (itemNameField) itemNameField.focus();
                    return;
                }

                const rows = [];
                tableBody.querySelectorAll('tr.sc-data-row').forEach(row => {
                    const date = row.querySelector('[data-col="date"]')?.textContent.trim() || '';
                    const po = row.querySelector('[data-col="po"]')?.textContent.trim() || '';
                    const receiptQty = row.querySelector('[data-col="receiptQty"]')?.textContent.trim() || '';
                    const risOffice = row.querySelector('[data-col="risOffice"]')?.textContent.trim() || '';
                    const issuanceQty = row.querySelector('[data-col="issuanceQty"]')?.textContent.trim() || '';
                    const balanceQty = row.querySelector('[data-col="balanceQty"]')?.textContent.trim() || '';
                    const remarks = row.querySelector('[data-col="remarks"]')?.textContent.trim() || '';

                    if (date || po || receiptQty || risOffice || issuanceQty || balanceQty || remarks) {
                        rows.push({
                            date,
                            po,
                            receiptQty: receiptQty !== '' ? parseFloat(receiptQty) : null,
                            risOffice,
                            issuanceQty: issuanceQty !== '' ? parseFloat(issuanceQty) : null,
                            balanceQty: balanceQty !== '' ? parseFloat(balanceQty) : 0,
                            remarks
                        });
                    }
                });

                try {
                    if (saveCardBtn) saveCardBtn.disabled = true;
                    if (saveCardBtnText) saveCardBtnText.textContent = 'Saving...';
                    setSaveStatus('saving');

                    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    const res = await fetch('{{ route('admin.reports.stock-card.save') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': token || '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            stock_card_id: currentStockCardId,
                            variant_id: selectedVariantId && selectedVariantId !== 'all' ? parseInt(selectedVariantId) : null,
                            office_id: selectedOfficeId ? parseInt(selectedOfficeId) : null,
                            office,
                            item_name: itemName,
                            unit,
                            stock_no: stockNo,
                            rows
                        })
                    });

                    const data = await res.json();
                    if (data.success) {
                        currentStockCardId = data.stock_card_id;
                        if (stockCardIdInput) stockCardIdInput.value = data.stock_card_id;
                        saveStateToLocalStorage();
                        setSaveStatus('saved');
                        showNotification('✓ ' + (data.message || 'Stock Card saved to database!'), 'info');
                    } else {
                        showNotification('Error: ' + (data.message || 'Could not save stock card.'), 'info');
                        setSaveStatus('unsaved');
                    }
                } catch (err) {
                    console.error('Save error:', err);
                    showNotification('Failed to save stock card. Please try again.', 'info');
                    setSaveStatus('unsaved');
                } finally {
                    if (saveCardBtn) saveCardBtn.disabled = false;
                    if (saveCardBtnText) saveCardBtnText.textContent = originalText;
                }
            }

            // Bind Save Button
            if (saveCardBtn) {
                saveCardBtn.addEventListener('click', saveStockCardToDatabase);
            }

            // DropdownSearch Office Selection Listener
            if (officeHiddenInput) {
                officeHiddenInput.addEventListener('change', () => {
                    const val = officeHiddenInput.value;
                    if (isAllItemsModeActive) {
                        const url = new URL(window.location.href);
                        if (val) url.searchParams.set('office_id', val);
                        else url.searchParams.delete('office_id');
                        window.location.href = url.toString();
                    } else {
                        loadStockCardData();
                    }
                });
                officeHiddenInput.addEventListener('dropdown-selected', (e) => {
                    const val = e.detail && e.detail.value !== undefined ? e.detail.value : officeHiddenInput.value;
                    if (isAllItemsModeActive) {
                        const url = new URL(window.location.href);
                        if (val) url.searchParams.set('office_id', val);
                        else url.searchParams.delete('office_id');
                        window.location.href = url.toString();
                    } else {
                        loadStockCardData(undefined, val);
                    }
                });
                officeHiddenInput.addEventListener('dropdown-cleared', () => {
                    if (isAllItemsModeActive) {
                        const url = new URL(window.location.href);
                        url.searchParams.delete('office_id');
                        window.location.href = url.toString();
                    } else {
                        loadStockCardData(undefined, '');
                    }
                });
            }

            // DropdownSearch Inventory Item Selection Listener
            if (variantHiddenInput) {
                variantHiddenInput.addEventListener('change', () => {
                    const val = variantHiddenInput.value;
                    if (val === 'all' || isAllItemsModeActive) {
                        const url = new URL(window.location.href);
                        if (val) url.searchParams.set('variant_id', val);
                        else url.searchParams.delete('variant_id');
                        window.location.href = url.toString();
                    } else {
                        loadStockCardData();
                    }
                });
                variantHiddenInput.addEventListener('dropdown-selected', (e) => {
                    const val = e.detail && e.detail.value !== undefined ? e.detail.value : variantHiddenInput.value;
                    if (val === 'all' || isAllItemsModeActive) {
                        const url = new URL(window.location.href);
                        if (val) url.searchParams.set('variant_id', val);
                        else url.searchParams.delete('variant_id');
                        window.location.href = url.toString();
                    } else {
                        loadStockCardData(val, undefined);
                    }
                });
                variantHiddenInput.addEventListener('dropdown-cleared', () => {
                    const url = new URL(window.location.href);
                    url.searchParams.delete('variant_id');
                    window.location.href = url.toString();
                });
            }

            // Activity Date Filter Listeners
            const filterFromDate = document.getElementById('filterFromDate');
            const filterToDate = document.getElementById('filterToDate');
            const clearDateFilterBtn = document.getElementById('clearDateFilterBtn');

            function applyActivityDateFilter() {
                const fVal = filterFromDate?.value || '';
                const tVal = filterToDate?.value || '';

                const url = new URL(window.location.href);
                if (fVal) {
                    url.searchParams.set('from_date', fVal);
                } else {
                    url.searchParams.delete('from_date');
                }

                if (tVal) {
                    url.searchParams.set('to_date', tVal);
                } else {
                    url.searchParams.delete('to_date');
                }

                // If no specific variant is selected, default to all-items so all active cards within range display
                if (!url.searchParams.get('variant_id')) {
                    url.searchParams.set('variant_id', 'all');
                }

                window.location.href = url.toString();
            }

            if (filterFromDate) {
                filterFromDate.addEventListener('change', applyActivityDateFilter);
            }
            if (filterToDate) {
                filterToDate.addEventListener('change', applyActivityDateFilter);
            }
            if (clearDateFilterBtn) {
                clearDateFilterBtn.addEventListener('click', () => {
                    const url = new URL(window.location.href);
                    url.searchParams.delete('from_date');
                    url.searchParams.delete('to_date');
                    window.location.href = url.toString();
                });
            }

            // Event Delegation for Content Editing & Real-time Balance
            if (tableBody) {
                tableBody.addEventListener('input', (e) => {
                    const target = e.target;
                    if (!target.classList.contains('cell-content')) return;

                    const col = target.dataset.col;
                    if (col === 'receiptQty' || col === 'issuanceQty' || col === 'risOffice' || col === 'po') {
                        if (col === 'receiptQty' || col === 'issuanceQty') {
                            recalculateBalances();
                        }
                        const row = target.closest('tr');
                        if (row) updateRowRisStyling(row);
                    }

                    setSaveStatus('unsaved');
                    saveStateToLocalStorage();
                });

                // Focusout to format date input to mm/dd/yy automatically
                tableBody.addEventListener('focusout', (e) => {
                    const target = e.target;
                    if (!target.classList.contains('cell-content')) return;
                    if (target.dataset.col === 'date') {
                        const rawVal = target.textContent.trim();
                        const formatted = formatToMMDDYY(rawVal);
                        if (formatted && formatted !== rawVal) {
                            target.textContent = formatted;
                            setSaveStatus('unsaved');
                            saveStateToLocalStorage();
                        }
                    }
                });

                // Delete row handler
                tableBody.addEventListener('click', (e) => {
                    const delBtn = e.target.closest('.row-del-btn');
                    if (delBtn) {
                        const row = delBtn.closest('tr');
                        if (row) {
                            deleteRow(row);
                        }
                    }
                });

                // Keyboard Spreadsheet Navigation (Enter, Tab, Up, Down)
                tableBody.addEventListener('keydown', (e) => {
                    const target = e.target;
                    if (!target.classList.contains('cell-content')) return;

                    const currentCell = target.closest('td');
                    const currentRow = currentCell ? currentCell.closest('tr') : null;
                    if (!currentRow) return;

                    const cellIndex = Array.from(currentRow.children).indexOf(currentCell);

                    // ENTER: Move to cell below or add new row if on last row
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        let nextRow = currentRow.nextElementSibling;
                        if (!nextRow) {
                            nextRow = createRowElement();
                            tableBody.appendChild(nextRow);
                            setSaveStatus('unsaved');
                        }
                        const targetCell = nextRow.children[cellIndex]?.querySelector('.cell-content');
                        if (targetCell) targetCell.focus();
                    }

                    // ARROW DOWN
                    else if (e.key === 'ArrowDown') {
                        const nextRow = currentRow.nextElementSibling;
                        if (nextRow) {
                            const targetCell = nextRow.children[cellIndex]?.querySelector('.cell-content');
                            if (targetCell) targetCell.focus();
                        }
                    }

                    // ARROW UP
                    else if (e.key === 'ArrowUp') {
                        const prevRow = currentRow.previousElementSibling;
                        if (prevRow) {
                            const targetCell = prevRow.children[cellIndex]?.querySelector('.cell-content');
                            if (targetCell) targetCell.focus();
                        }
                    }
                });
            }

            // Save on header input/blur
            [officeField, itemNameField, unitField, stockNoField].forEach(field => {
                if (field) {
                    field.addEventListener('input', () => {
                        setSaveStatus('unsaved');
                        saveStateToLocalStorage();
                    });
                    field.addEventListener('blur', saveStateToLocalStorage);
                }
            });

            // Initial restore
            if (!isAllItemsModeActive && tableBody) {
                restoreState();
            }
        });
    </script>
</body>

</html>