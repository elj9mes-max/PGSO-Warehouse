<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PGSO Warehouse - Stock Release & Issuance</title>

    <!-- Modern Sans & Mono Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        /* Base Reset & Variables */
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --bg-page: #fafafa;
            --bg-surface: #ffffff;
            --text-primary: #09090b;
            --text-secondary: #52525b;
            --text-muted: #71717a;
            --border-subtle: #e4e4e7;
            --border-hover: #d4d4d8;
            --btn-bg: #09090b;
            --btn-text: #ffffff;
            --font-sans: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
            --primary-accent: #2563eb;
            --primary-light: #eff6ff;
            --success-color: #16a34a;
            --success-bg: #f0fdf4;
            --warning-color: #d97706;
            --warning-bg: #fffbeb;
            --danger-color: #dc2626;
            --danger-bg: #fef2f2;
        }

        body {
            font-family: var(--font-sans);
            background-color: var(--bg-page);
            color: var(--text-primary);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        .release-content-area {
            padding: 28px 32px;
            max-width: 1600px;
            margin: 0 auto;
            width: 100%;
        }

        /* Top Page Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .header-title-group h1 {
            font-size: 1.625rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            color: var(--text-primary);
            margin-bottom: 4px;
        }

        .header-title-group p {
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        .header-stats {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .stat-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            background: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 9999px;
            font-size: 0.8125rem;
            font-weight: 500;
            color: var(--text-secondary);
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
        }

        .stat-pill strong {
            color: var(--text-primary);
            font-weight: 700;
        }

        /* Alerts */
        .alert-banner {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 0.875rem;
            animation: fadeIn 0.3s ease;
        }

        .alert-success {
            background-color: var(--success-bg);
            border: 1px solid #bbf7d0;
            color: #166534;
        }

        .alert-error {
            background-color: var(--danger-bg);
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        .alert-banner svg {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }

        /* Two-Column Split Layout */
        .release-layout {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 420px;
            gap: 28px;
            align-items: start;
        }

        @media (max-width: 1200px) {
            .release-layout {
                grid-template-columns: 1fr;
            }
        }

        /* Left Column: Office Selector & Item Catalog */
        .office-selection-card {
            background: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 16px;
            padding: 20px 24px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .office-picker-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }

        .office-picker-title {
            font-size: 0.875rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .office-picker-title svg {
            width: 18px;
            height: 18px;
            color: #2563eb;
        }

        .selected-office-meta-badge {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            gap: 12px;
        }

        .office-badge-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .office-badge-name {
            font-size: 0.875rem;
            font-weight: 700;
            color: #0f172a;
        }

        .office-badge-code {
            font-family: var(--font-mono);
            font-size: 0.6875rem;
            color: #64748b;
        }

        .office-badge-stats {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-shrink: 0;
            font-size: 0.75rem;
        }

        .stat-item {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 1px;
        }

        .stat-item span {
            color: var(--text-muted);
            font-size: 0.6875rem;
        }

        .stat-item strong {
            color: var(--text-primary);
            font-size: 0.875rem;
        }

        /* Filter & Search Bar */
        .catalog-controls {
            background: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 14px;
            padding: 16px 20px;
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            gap: 14px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        }

        .search-and-toggles {
            display: flex;
            gap: 14px;
            align-items: center;
            flex-wrap: wrap;
        }

        .search-input-wrapper {
            position: relative;
            flex: 1;
            min-width: 260px;
        }

        .search-input-wrapper svg {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 17px;
            height: 17px;
            color: #a1a1aa;
            pointer-events: none;
        }

        .search-input {
            width: 100%;
            padding: 10px 14px 10px 42px;
            border: 1px solid var(--border-subtle);
            border-radius: 10px;
            font-size: 0.875rem;
            font-family: var(--font-sans);
            color: var(--text-primary);
            background: #fafafa;
            transition: all 0.2s ease;
        }

        .search-input:focus {
            outline: none;
            background: #ffffff;
            border-color: #09090b;
            box-shadow: 0 0 0 3px rgba(9, 9, 11, 0.06);
        }

        .category-pills {
            display: flex;
            align-items: center;
            gap: 8px;
            overflow-x: auto;
            padding-bottom: 2px;
            scrollbar-width: thin;
        }

        .category-pill-btn {
            padding: 6px 13px;
            border-radius: 8px;
            border: 1px solid var(--border-subtle);
            background: #ffffff;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-secondary);
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.15s ease;
        }

        .category-pill-btn:hover {
            border-color: var(--border-hover);
            color: var(--text-primary);
        }

        .category-pill-btn.active {
            background: #09090b;
            border-color: #09090b;
            color: #ffffff;
        }

        /* Items Grid */
        .items-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 18px;
        }

        /* Item Card */
        .release-item-card {
            background: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 16px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }

        .release-item-card:hover {
            border-color: #cbd5e1;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .release-item-card.staged {
            border-color: #3b82f6;
            background: #f8faff;
        }

        .card-top {
            padding: 16px 18px 12px 18px;
            border-bottom: 1px solid #f4f4f5;
        }

        .card-header-pills {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
        }

        .cat-tag {
            font-size: 0.6875rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            padding: 2px 7px;
            background: #f4f4f5;
            color: var(--text-secondary);
            border-radius: 6px;
        }

        .item-code-tag {
            font-family: var(--font-mono);
            font-size: 0.6875rem;
            font-weight: 600;
            padding: 2px 6px;
            background: #f1f5f9;
            color: #334155;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
        }

        .card-title-group {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 4px;
        }

        .color-dot {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            border: 1px solid rgba(0, 0, 0, 0.1);
            flex-shrink: 0;
        }

        .card-variant-name {
            font-size: 1rem;
            font-weight: 800;
            color: var(--text-primary);
            line-height: 1.3;
        }

        .card-parent-name {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-bottom: 6px;
        }

        .card-barcode-sku {
            font-family: var(--font-mono);
            font-size: 0.6875rem;
            color: #64748b;
        }

        /* Card Stock Badges */
        .card-stock-row {
            padding: 12px 18px;
            background: #fafafa;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid #f4f4f5;
        }

        .office-stock-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 10px;
            border-radius: 8px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            font-size: 0.75rem;
            font-weight: 700;
            color: #166534;
        }

        .office-stock-pill strong {
            font-size: 0.9375rem;
            color: #15803d;
        }

        .system-stock-pill {
            font-size: 0.6875rem;
            color: var(--text-muted);
            text-align: right;
        }

        /* Action Row inside Card */
        .card-action-row {
            padding: 14px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .qty-stepper {
            display: inline-flex;
            align-items: center;
            border: 1px solid var(--border-subtle);
            border-radius: 8px;
            overflow: hidden;
            background: #ffffff;
        }

        .qty-step-btn {
            background: transparent;
            border: none;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--text-secondary);
            font-weight: 700;
            transition: background 0.15s ease;
        }

        .qty-step-btn:hover {
            background: #f4f4f5;
            color: var(--text-primary);
        }

        .qty-input {
            width: 44px;
            height: 28px;
            border: none;
            text-align: center;
            font-size: 0.8125rem;
            font-weight: 700;
            font-family: var(--font-sans);
            color: var(--text-primary);
            background: transparent;
        }

        .qty-input:focus {
            outline: none;
            background: #f8fafc;
        }

        .btn-stage-release {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 700;
            background: #09090b;
            color: #ffffff;
            border: none;
            cursor: pointer;
            transition: all 0.15s ease;
            white-space: nowrap;
        }

        .btn-stage-release:hover {
            background: #27272a;
            transform: translateY(-1px);
        }

        .btn-stage-release.staged-btn {
            background: #2563eb;
        }

        /* Right Column: Sticky Live Staged Release Cart */
        .live-release-card {
            background: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 18px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 24px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .live-release-header {
            padding: 22px 24px 18px 24px;
            background: linear-gradient(180deg, #fafafa 0%, #ffffff 100%);
            border-bottom: 1px solid var(--border-subtle);
        }

        .live-card-title-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .live-card-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.125rem;
            font-weight: 800;
            color: var(--text-primary);
        }

        .live-card-title svg {
            width: 22px;
            height: 22px;
            color: #dc2626;
        }

        .live-badge-indicator {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.6875rem;
            font-weight: 700;
            padding: 3px 8px;
            background: #fee2e2;
            color: #991b1b;
            border-radius: 9999px;
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }

        /* Issuance Form Metadata */
        .issuance-meta-fields {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 10px;
        }

        .meta-field {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .meta-field label {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--text-secondary);
        }

        .meta-input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid var(--border-subtle);
            border-radius: 8px;
            font-size: 0.8125rem;
            font-family: inherit;
            color: var(--text-primary);
            background: #ffffff;
            transition: all 0.15s ease;
        }

        .meta-input:focus {
            outline: none;
            border-color: #09090b;
            box-shadow: 0 0 0 2px rgba(9, 9, 11, 0.05);
        }

        /* Staged Cart Body */
        .live-release-body {
            padding: 20px 24px;
            display: flex;
            flex-direction: column;
            gap: 14px;
            max-height: calc(100vh - 420px);
            overflow-y: auto;
        }

        .staged-section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .staged-section-title {
            font-size: 0.8125rem;
            font-weight: 700;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .staged-count-pill {
            padding: 2px 7px;
            background: #09090b;
            color: #ffffff;
            border-radius: 9999px;
            font-size: 0.6875rem;
            font-weight: 700;
        }

        .clear-staged-btn {
            background: transparent;
            border: none;
            font-size: 0.75rem;
            color: var(--text-muted);
            cursor: pointer;
            text-decoration: underline;
        }

        .clear-staged-btn:hover {
            color: #dc2626;
        }

        /* Staged Item Card */
        .staged-item {
            background: #fafafa;
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            padding: 12px 14px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            animation: slideDown 0.2s ease;
        }

        .staged-item-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 8px;
        }

        .staged-item-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .staged-parent-title {
            font-size: 0.6875rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
        }

        .staged-variant-title {
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .btn-remove-staged {
            background: transparent;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 2px;
            border-radius: 4px;
            transition: color 0.15s ease;
        }

        .btn-remove-staged:hover {
            color: #dc2626;
        }

        .staged-item-controls {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding-top: 6px;
            border-top: 1px dashed #e2e8f0;
        }

        .staged-calc-indicator {
            font-size: 0.6875rem;
            color: var(--text-secondary);
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .staged-calc-indicator strong {
            color: #dc2626;
        }

        /* Empty State in Cart */
        .empty-staged-box {
            text-align: center;
            padding: 36px 16px;
            color: var(--text-muted);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }

        .empty-staged-box svg {
            width: 42px;
            height: 42px;
            color: #cbd5e1;
        }

        .empty-staged-box p {
            font-size: 0.8125rem;
            line-height: 1.45;
            max-width: 240px;
        }

        /* Live Release Footer & CTA */
        .live-release-footer {
            padding: 20px 24px;
            background: #ffffff;
            border-top: 1px solid var(--border-subtle);
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .release-summary-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 16px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.8125rem;
            color: var(--text-secondary);
        }

        .summary-row.total-row {
            padding-top: 6px;
            border-top: 1px dashed #cbd5e1;
            font-weight: 800;
            color: var(--text-primary);
            font-size: 0.875rem;
        }

        .btn-confirm-release {
            width: 100%;
            padding: 13px 20px;
            background: #dc2626;
            color: #ffffff;
            border: none;
            border-radius: 12px;
            font-size: 0.9375rem;
            font-weight: 700;
            font-family: var(--font-sans);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s ease;
            box-shadow: 0 2px 6px rgba(220, 38, 38, 0.2);
        }

        .btn-confirm-release:hover:not(:disabled) {
            background: #b91c1c;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
        }

        .btn-confirm-release:disabled {
            background: #e4e4e7;
            color: #a1a1aa;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* Empty Office State */
        .empty-office-state {
            background: #ffffff;
            border: 1px dashed var(--border-subtle);
            border-radius: 16px;
            padding: 56px 24px;
            text-align: center;
            color: var(--text-muted);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }

        .empty-office-state svg {
            width: 44px;
            height: 44px;
            color: #cbd5e1;
        }

        .empty-office-state h3 {
            font-size: 1.0625rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .empty-office-state p {
            font-size: 0.8438rem;
            max-width: 360px;
            line-height: 1.45;
        }

        .btn-primary-sm {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            background: #09090b;
            color: #ffffff;
            border-radius: 8px;
            font-size: 0.8125rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .btn-primary-sm:hover {
            background: #27272a;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-4px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Toast Feedback */
        .toast-notification {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: #09090b;
            color: #ffffff;
            padding: 12px 20px;
            border-radius: 10px;
            font-size: 0.875rem;
            font-weight: 600;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            z-index: 9999;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: fadeIn 0.3s ease;
        }
    </style>
</head>
<body>
    <x-sidebar>
        <div class="release-content-area">

            <!-- Page Header -->
            <div class="page-header">
                <div class="header-title-group">
                    <h1>Release & Issuance of Stock</h1>
                    <p>Record item takeout and consumption from office custody • Automatically deducts from office inventory & system stock on hand</p>
                </div>

                <div class="header-stats">
                    @php
                        $officesWithStock = $offices->filter(fn($o) => $o->officeInventories->count() > 0)->count();
                        $totalOfficeUnits = $offices->sum(fn($o) => $o->officeInventories->sum('quantity'));
                    @endphp
                    <div class="stat-pill">
                        <svg style="width: 14px; height: 14px; color: #2563eb;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect width="16" height="20" x="4" y="2" rx="2" ry="2"/>
                            <path d="M9 22v-4h6v4"/>
                        </svg>
                        <span>Offices with Stock: <strong>{{ $officesWithStock }} / {{ $offices->count() }}</strong></span>
                    </div>

                    <div class="stat-pill">
                        <svg style="width: 14px; height: 14px; color: #dc2626;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                            <polyline points="10 17 15 12 10 7"/>
                            <line x1="15" y1="12" x2="3" y2="12"/>
                        </svg>
                        <span>Total Custody Units: <strong>{{ number_format($totalOfficeUnits) }}</strong></span>
                    </div>
                </div>
            </div>

            <!-- Session Alert Banner -->
            @if(session('status'))
                <div class="alert-banner alert-success">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="alert-banner alert-error">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <!-- Main Split Layout -->
            <div class="release-layout">

                <!-- Left Column: Office Selector & Stock Catalog -->
                <div class="catalog-section">

                    <!-- Office Selection Card -->
                    <div class="office-selection-card">
                        <div class="office-picker-header">
                            <span class="office-picker-title">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 21h18"/>
                                    <path d="M9 8h1"/>
                                    <path d="M9 12h1"/>
                                    <path d="M9 16h1"/>
                                    <path d="M14 8h1"/>
                                    <path d="M14 12h1"/>
                                    <path d="M14 16h1"/>
                                    <path d="M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16"/>
                                </svg>
                                <span>Releasing Office (Source of Stock) *</span>
                            </span>

                            <a href="{{ route('admin.transfers.index') }}" style="font-size: 0.75rem; color: #2563eb; font-weight: 600; text-decoration: none;">
                                Need to transfer more stocks? →
                            </a>
                        </div>

                        <!-- DropdownSearch Component for Selecting Office -->
                        <x-dropdown-search 
                            name="releasing_office_id"
                            id="releaseOfficeSelect"
                            :options="$offices"
                            valueKey="id"
                            labelKey="office_name"
                            subtextKey="office_code"
                            :selected="$selectedOfficeId"
                            placeholder="-- Choose Office Releasing Stock --"
                            searchPlaceholder="Type office name or code to search..."
                            :allowClear="false"
                            required
                        />

                        <!-- Selected Office Active Info Banner -->
                        <div class="selected-office-meta-badge" id="selectedOfficeMetaBanner" style="{{ empty($selectedOfficeId) ? 'display: none;' : '' }}">
                            <div class="office-badge-info">
                                <span class="office-badge-name" id="officeBannerNameText">-</span>
                                <span class="office-badge-code" id="officeBannerCodeText">-</span>
                            </div>

                            <div class="office-badge-stats">
                                <div class="stat-item">
                                    <span>Allocated Items</span>
                                    <strong id="officeBannerItemsCount">0 Variants</strong>
                                </div>
                                <div class="stat-item">
                                    <span>Total Units</span>
                                    <strong id="officeBannerUnitsCount" style="color: #16a34a;">0 Units</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Search & Filter Controls -->
                    <div class="catalog-controls" id="catalogControlsBox" style="{{ empty($selectedOfficeId) ? 'display: none;' : '' }}">
                        <div class="search-and-toggles">
                            <div class="search-input-wrapper">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                </svg>
                                <input type="text" id="releaseCatalogSearch" class="search-input" placeholder="Search item, variant name, barcode, SKU...">
                            </div>
                        </div>

                        <!-- Category Filter Pills -->
                        <div class="category-pills">
                            <button type="button" class="category-pill-btn active" data-category="ALL">All Categories</button>
                            @foreach($categories as $category)
                                <button type="button" class="category-pill-btn" data-category="{{ $category }}">{{ $category }}</button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Office Items Grid Container -->
                    <div class="items-grid" id="releaseItemsGrid">
                        <!-- Populated dynamically via JS based on selected office -->
                    </div>

                    <!-- Empty State for Office with No Stocks -->
                    <div class="empty-office-state" id="emptyOfficeState" style="display: none;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <rect width="16" height="20" x="4" y="2" rx="2" ry="2"/>
                            <path d="M9 22v-4h6v4"/>
                            <path d="M8 6h.01"/>
                            <path d="M16 6h.01"/>
                        </svg>
                        <h3 id="emptyOfficeTitle">No Items in Office Custody</h3>
                        <p id="emptyOfficeSubtitle">This department currently has 0 items allocated in its custody.</p>
                        <a href="{{ route('admin.transfers.index') }}" class="btn-primary-sm" id="emptyOfficeTransferLink">
                            <svg style="width: 14px; height: 14px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            <span>Transfer Stock to this Office</span>
                        </a>
                    </div>

                    <!-- Initial State when No Office is Chosen -->
                    <div class="empty-office-state" id="initialNoOfficeState" style="{{ !empty($selectedOfficeId) ? 'display: none;' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                            <polyline points="10 17 15 12 10 7"/>
                            <line x1="15" y1="12" x2="3" y2="12"/>
                        </svg>
                        <h3>Select an Office to Begin</h3>
                        <p>Choose an office above using the searchable dropdown to view and release items currently held in its inventory.</p>
                    </div>

                    <!-- No Match Filter Message -->
                    <div id="noMatchMessage" style="display: none; background: #ffffff; border: 1px dashed var(--border-subtle); border-radius: 16px; padding: 48px; text-align: center; color: var(--text-muted); margin-top: 16px;">
                        <svg style="width: 36px; height: 36px; color: #a1a1aa; margin-bottom: 8px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <h3 style="font-size: 1rem; font-weight: 700; color: var(--text-primary); margin-bottom: 4px;">No matching items found</h3>
                        <p style="font-size: 0.8125rem;">Try adjusting your search query or reset the category filters.</p>
                    </div>

                </div>

                <!-- Right Column: Sticky Live Staged Release Cart -->
                <aside class="live-card-column">
                    <div class="live-release-card">

                        <!-- Header -->
                        <div class="live-release-header">
                            <div class="live-card-title-row">
                                <div class="live-card-title">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                                        <polyline points="10 17 15 12 10 7"/>
                                        <line x1="15" y1="12" x2="3" y2="12"/>
                                    </svg>
                                    <span>Stock Release</span>
                                </div>
                                <span class="live-badge-indicator">
                                    <span style="width: 6px; height: 6px; border-radius: 50%; background: #dc2626;"></span>
                                    Deduction
                                </span>
                            </div>

                            <!-- Optional Issuance Form Fields -->
                            <div class="issuance-meta-fields">
                                <div class="meta-field">
                                    <label for="releaseRecipientInput">Released To / Recipient <span style="color: #dc2626;">*</span></label>
                                    <input type="text" id="releaseRecipientInput" class="meta-input" placeholder="e.g. Juan Dela Cruz / Field Unit" required>
                                </div>

                                <div class="meta-field">
                                    <label for="releasePurposeInput">Purpose / Remarks <span style="font-weight: 400; color: var(--text-muted);">(Optional)</span></label>
                                    <input type="text" id="releasePurposeInput" class="meta-input" placeholder="e.g. Office consumption, project use">
                                </div>
                            </div>
                        </div>

                        <!-- Staged Items Section -->
                        <div class="live-release-body">
                            <div class="staged-section-header">
                                <span class="staged-section-title">
                                    <span>Staged for Takeout</span>
                                    <span class="staged-count-pill" id="stagedCountPill">0</span>
                                </span>
                                <button type="button" class="clear-staged-btn" id="clearStagedBtn" onclick="clearAllStaged()" style="display: none;">Clear All</button>
                            </div>

                            <!-- Staged Items Container -->
                            <div id="stagedItemsContainer" style="display: flex; flex-direction: column; gap: 10px;">
                                <!-- Dynamically populated via JS -->
                            </div>

                            <!-- Empty Staged State -->
                            <div class="empty-staged-box" id="emptyStagedBox">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M16 16v1a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h11a2 2 0 0 1 2 2v1"/>
                                    <path d="M18 8h4a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1h-4"/>
                                    <circle cx="5.5" cy="18.5" r="2.5"/>
                                    <circle cx="18.5" cy="18.5" r="2.5"/>
                                </svg>
                                <p>Select an office and add item variants from the left to stage for release.</p>
                            </div>
                        </div>

                        <!-- Footer & Submit Form -->
                        <div class="live-release-footer">
                            <div class="release-summary-box">
                                <div class="summary-row">
                                    <span>Source Office</span>
                                    <strong id="summaryOfficeName">None Selected</strong>
                                </div>
                                <div class="summary-row">
                                    <span>Variants Staged</span>
                                    <span id="summaryVariantsCount">0 items</span>
                                </div>
                                <div class="summary-row total-row">
                                    <span>Total Units to Deduct</span>
                                    <span id="summaryTotalUnits" style="color: #dc2626;">0 units</span>
                                </div>
                            </div>

                            <!-- Hidden Form for POST submission -->
                            <form id="releaseForm" method="POST" action="{{ route('admin.releases.store') }}" onsubmit="validateBeforeSubmit(event); return false;">
                                @csrf
                                <input type="hidden" name="office_id" id="formOfficeId" value="{{ $selectedOfficeId ?? '' }}">
                                <input type="hidden" name="recipient" id="formRecipient" value="" required>
                                <input type="hidden" name="purpose" id="formPurpose" value="">
                                <div id="formReleasesInputs">
                                    <!-- Injected on submit -->
                                </div>

                                <button type="submit" class="btn-confirm-release" id="submitReleaseBtn" disabled>
                                    <svg style="width: 18px; height: 18px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                                        <polyline points="10 17 15 12 10 7"/>
                                        <line x1="15" y1="12" x2="3" y2="12"/>
                                    </svg>
                                    <span>Confirm & Deduct Stocks</span>
                                </button>
                            </form>
                        </div>

                    </div>
                </aside>

            </div>

        </div>
    </x-sidebar>

    <!-- Embedded Offices Holdings Data -->
    <script>
        const officesData = {
            @foreach($offices as $office)
                "{{ $office->id }}": {
                    id: {{ $office->id }},
                    name: @json($office->office_name),
                    code: @json($office->office_code),
                    items: [
                        @foreach($office->officeInventories as $oi)
                            @if($oi->variant)
                                {
                                    id: {{ $oi->id }},
                                    variantId: {{ $oi->variant->id }},
                                    variantName: @json($oi->variant->variant_name),
                                    itemName: @json($oi->variant->inventoryItem?->name ?? 'Standard Item'),
                                    itemCode: @json($oi->variant->inventoryItem?->item_code ?? ''),
                                    category: @json($oi->variant->inventoryItem?->category ?? 'General'),
                                    barcode: @json($oi->variant->barcode),
                                    sku: @json($oi->variant->sku ?? ''),
                                    officeQuantity: {{ (int) $oi->quantity }},
                                    systemQuantity: {{ (int) $oi->variant->quantity }},
                                    unit: @json($oi->variant->effective_unit)
                                },
                            @endif
                        @endforeach
                    ]
                },
            
            @endforeach
        };

        // Application State
        let selectedOfficeId = @json($selectedOfficeId ?? '');
        let stagedItems = []; // [{ variantId, variantName, itemName, unit, officeQuantity, systemQuantity, quantity, barcode }]
        let activeCategory = 'ALL';

        document.addEventListener('DOMContentLoaded', () => {
            const officeInput = document.getElementById('releaseOfficeSelect');
            if (officeInput) {
                officeInput.addEventListener('change', () => {
                    handleOfficeSelection(officeInput.value);
                });
            }

            if (selectedOfficeId) {
                handleOfficeSelection(selectedOfficeId);
            }

            setupSearchAndFilters();
        });

        function handleOfficeSelection(officeId) {
            selectedOfficeId = officeId;
            document.getElementById('formOfficeId').value = selectedOfficeId;

            // Clear any staged items from previous office
            clearAllStaged();

            updateOfficeUI();
            renderCatalogItems();
        }

        function updateOfficeUI() {
            const banner = document.getElementById('selectedOfficeMetaBanner');
            const controls = document.getElementById('catalogControlsBox');
            const nameEl = document.getElementById('officeBannerNameText');
            const codeEl = document.getElementById('officeBannerCodeText');
            const itemsCountEl = document.getElementById('officeBannerItemsCount');
            const unitsCountEl = document.getElementById('officeBannerUnitsCount');
            const summaryNameEl = document.getElementById('summaryOfficeName');
            const initialPrompt = document.getElementById('initialNoOfficeState');

            if (!selectedOfficeId || !officesData[selectedOfficeId]) {
                banner.style.display = 'none';
                controls.style.display = 'none';
                summaryNameEl.textContent = 'None Selected';
                initialPrompt.style.display = 'flex';
                return;
            }

            initialPrompt.style.display = 'none';
            const office = officesData[selectedOfficeId];
            const totalUnits = office.items.reduce((sum, item) => sum + item.officeQuantity, 0);

            banner.style.display = 'flex';
            controls.style.display = 'flex';
            nameEl.textContent = office.name;
            codeEl.textContent = office.code;
            itemsCountEl.textContent = `${office.items.length} Variants`;
            unitsCountEl.textContent = `${totalUnits} Units`;
            summaryNameEl.textContent = office.code;
        }

        function renderCatalogItems() {
            const grid = document.getElementById('releaseItemsGrid');
            const emptyOfficeState = document.getElementById('emptyOfficeState');
            const transferLink = document.getElementById('emptyOfficeTransferLink');

            if (!selectedOfficeId || !officesData[selectedOfficeId]) {
                grid.innerHTML = '';
                emptyOfficeState.style.display = 'none';
                return;
            }

            const office = officesData[selectedOfficeId];
            if (office.items.length === 0) {
                grid.innerHTML = '';
                emptyOfficeState.style.display = 'flex';
                document.getElementById('emptyOfficeTitle').textContent = `No Items in ${office.name}`;
                document.getElementById('emptyOfficeSubtitle').textContent = `This office currently has 0 items allocated in custody. Transfer stock to this office first before releasing.`;
                transferLink.href = `{{ route('admin.transfers.index') }}?office=${office.id}`;
                return;
            }

            emptyOfficeState.style.display = 'none';

            grid.innerHTML = office.items.map(item => {
                const lowerName = item.variantName.toLowerCase();
                const swatchColor = getSwatchColor(lowerName);
                const searchBlob = `${item.itemName} ${item.variantName} ${item.itemCode} ${item.barcode} ${item.sku} ${item.category}`.toLowerCase();

                return `
                    <div class="release-item-card" 
                         id="itemCard-${item.variantId}"
                         data-variant-id="${item.variantId}"
                         data-search="${escapeHtml(searchBlob)}"
                         data-category="${escapeHtml(item.category)}">
                        
                        <div class="card-top">
                            <div class="card-header-pills">
                                <span class="cat-tag">${escapeHtml(item.category)}</span>
                                ${item.itemCode ? `<span class="item-code-tag">${escapeHtml(item.itemCode)}</span>` : ''}
                            </div>

                            <div class="card-title-group">
                                <span class="color-dot" style="background-color: ${swatchColor};"></span>
                                <h3 class="card-variant-name">${escapeHtml(item.variantName)}</h3>
                            </div>

                            <div class="card-parent-name">${escapeHtml(item.itemName)}</div>
                            <div class="card-barcode-sku">Barcode: ${escapeHtml(item.barcode)} ${item.sku ? `• SKU: ${escapeHtml(item.sku)}` : ''}</div>
                        </div>

                        <div class="card-stock-row">
                            <div class="office-stock-pill">
                                <span>In Office:</span>
                                <strong>${item.officeQuantity} ${escapeHtml(item.unit)}</strong>
                            </div>

                            <div class="system-stock-pill">
                                <div>Total System Stock:</div>
                                <strong>${item.systemQuantity} ${escapeHtml(item.unit)}</strong>
                            </div>
                        </div>

                        <div class="card-action-row">
                            <div class="qty-stepper">
                                <button type="button" class="qty-step-btn" onclick="stepItemQty(${item.variantId}, -1)">-</button>
                                <input type="number" 
                                       id="inputQty-${item.variantId}" 
                                       class="qty-input" 
                                       value="1" 
                                       min="1" 
                                       max="${item.officeQuantity}"
                                       onchange="validateInputQty(${item.variantId}, ${item.officeQuantity})">
                                <button type="button" class="qty-step-btn" onclick="stepItemQty(${item.variantId}, 1, ${item.officeQuantity})">+</button>
                            </div>

                            <button type="button" 
                                    class="btn-stage-release" 
                                    id="stageBtn-${item.variantId}"
                                    onclick="stageItem(${item.variantId})">
                                <svg style="width: 13px; height: 13px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                                    <polyline points="10 17 15 12 10 7"/>
                                    <line x1="15" y1="12" x2="3" y2="12"/>
                                </svg>
                                <span>Stage Release</span>
                            </button>
                        </div>
                    </div>
                `;
            }).join('');

            applyCardFilters();
        }

        function getSwatchColor(lowerName) {
            if (lowerName.includes('blue')) return '#2563eb';
            if (lowerName.includes('red')) return '#dc2626';
            if (lowerName.includes('black')) return '#09090b';
            if (lowerName.includes('green')) return '#16a34a';
            if (lowerName.includes('yellow')) return '#eab308';
            if (lowerName.includes('orange')) return '#ea580c';
            if (lowerName.includes('purple')) return '#9333ea';
            if (lowerName.includes('white')) return '#f4f4f5';
            return '#71717a';
        }

        // Stepper Quantity in Catalog Card
        function stepItemQty(variantId, delta, maxAvailable = 999999) {
            const input = document.getElementById(`inputQty-${variantId}`);
            if (!input) return;

            let current = parseInt(input.value) || 1;
            let next = current + delta;
            if (next < 1) next = 1;
            if (next > maxAvailable) next = maxAvailable;

            input.value = next;
        }

        function validateInputQty(variantId, maxAvailable) {
            const input = document.getElementById(`inputQty-${variantId}`);
            if (!input) return;

            let val = parseInt(input.value) || 1;
            if (val < 1) val = 1;
            if (val > maxAvailable) val = maxAvailable;
            input.value = val;
        }

        // Stage Item into Release Queue
        function stageItem(variantId) {
            if (!selectedOfficeId || !officesData[selectedOfficeId]) {
                showToast('⚠️ Please select a releasing office first!');
                return;
            }

            const item = officesData[selectedOfficeId].items.find(i => i.variantId === variantId);
            if (!item) {
                showToast('⚠️ Item not found in selected office');
                return;
            }

            const input = document.getElementById(`inputQty-${variantId}`);
            const qtyToAdd = input ? parseInt(input.value) || 1 : 1;

            if (qtyToAdd <= 0) {
                showToast('⚠️ Please enter a valid quantity');
                return;
            }

            if (item.officeQuantity <= 0) {
                showToast(`⚠️ No available stock in office for '${item.variantName}'`);
                return;
            }

            const existingIndex = stagedItems.findIndex(i => i.variantId === variantId);

            if (existingIndex > -1) {
                const currentQty = stagedItems[existingIndex].quantity;
                if (currentQty >= item.officeQuantity) {
                    showToast(`⚠️ '${item.variantName}' is already at maximum available stock (${item.officeQuantity} ${item.unit})`);
                    return;
                }

                let newQty = currentQty + qtyToAdd;
                if (newQty > item.officeQuantity) {
                    newQty = item.officeQuantity;
                    showToast(`Updated '${item.variantName}' to maximum office stock (${newQty} ${item.unit})`);
                } else {
                    showToast(`Updated '${item.variantName}' to ${newQty} ${item.unit}`);
                }
                stagedItems[existingIndex].quantity = newQty;
            } else {
                let initialQty = qtyToAdd;
                if (initialQty > item.officeQuantity) {
                    initialQty = item.officeQuantity;
                }
                stagedItems.push({
                    variantId: item.variantId,
                    variantName: item.variantName,
                    itemName: item.itemName,
                    unit: item.unit,
                    officeQuantity: item.officeQuantity,
                    systemQuantity: item.systemQuantity,
                    barcode: item.barcode,
                    quantity: initialQty
                });
                showToast(`Staged ${initialQty}x '${item.variantName}' for takeout`);
            }

            renderStagedUI();
            updateCardHighlight(variantId);
        }

        function updateCardHighlight(variantId) {
            const card = document.getElementById(`itemCard-${variantId}`);
            const btn = document.getElementById(`stageBtn-${variantId}`);
            if (!card || !btn) return;

            const staged = stagedItems.find(i => i.variantId === variantId);
            if (staged) {
                card.classList.add('staged');
                btn.classList.add('staged-btn');
                btn.innerHTML = `<span>✓ Staged (${staged.quantity})</span>`;
            } else {
                card.classList.remove('staged');
                btn.classList.remove('staged-btn');
                btn.innerHTML = `
                    <svg style="width: 13px; height: 13px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                        <polyline points="10 17 15 12 10 7"/>
                        <line x1="15" y1="12" x2="3" y2="12"/>
                    </svg>
                    <span>Stage Release</span>
                `;
            }
        }

        // Render Staged UI in Right Sidebar
        function renderStagedUI() {
            const container = document.getElementById('stagedItemsContainer');
            const emptyBox = document.getElementById('emptyStagedBox');
            const clearBtn = document.getElementById('clearStagedBtn');
            const countPill = document.getElementById('stagedCountPill');

            countPill.textContent = stagedItems.length;

            if (stagedItems.length === 0) {
                container.innerHTML = '';
                emptyBox.style.display = 'flex';
                clearBtn.style.display = 'none';
                updateSummaryAndSubmitState();
                return;
            }

            emptyBox.style.display = 'none';
            clearBtn.style.display = 'inline-block';

            container.innerHTML = stagedItems.map(item => {
                const remOffice = item.officeQuantity - item.quantity;
                const remSystem = item.systemQuantity - item.quantity;

                return `
                    <div class="staged-item">
                        <div class="staged-item-top">
                            <div class="staged-item-info">
                                <span class="staged-parent-title">${escapeHtml(item.itemName)}</span>
                                <span class="staged-variant-title">${escapeHtml(item.variantName)}</span>
                            </div>
                            <button type="button" class="btn-remove-staged" onclick="removeStagedItem(${item.variantId})" title="Remove item">
                                <svg style="width: 16px; height: 16px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </button>
                        </div>

                        <div class="staged-item-controls">
                            <div class="staged-calc-indicator">
                                <div>Office: ${item.officeQuantity} → <strong>${remOffice} ${escapeHtml(item.unit)}</strong></div>
                                <div style="color: #64748b; font-size: 0.625rem;">Total: ${item.systemQuantity} → ${remSystem} ${escapeHtml(item.unit)}</div>
                            </div>

                            <div class="qty-stepper">
                                <button type="button" class="qty-step-btn" onclick="adjustStagedQty(${item.variantId}, -1)">-</button>
                                <input type="number" 
                                       class="qty-input" 
                                       value="${item.quantity}" 
                                       min="1" 
                                       max="${item.officeQuantity}" 
                                       onchange="setStagedQtyDirect(${item.variantId}, this.value)">
                                <button type="button" class="qty-step-btn" onclick="adjustStagedQty(${item.variantId}, 1)">+</button>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');

            updateSummaryAndSubmitState();
        }

        function adjustStagedQty(variantId, delta) {
            const item = stagedItems.find(i => i.variantId === variantId);
            if (!item) return;

            let next = item.quantity + delta;
            if (next < 1) next = 1;
            if (next > item.officeQuantity) next = item.officeQuantity;

            item.quantity = next;
            renderStagedUI();
            updateCardHighlight(variantId);
        }

        function setStagedQtyDirect(variantId, rawValue) {
            const item = stagedItems.find(i => i.variantId === variantId);
            if (!item) return;

            let val = parseInt(rawValue) || 1;
            if (val < 1) val = 1;
            if (val > item.officeQuantity) val = item.officeQuantity;

            item.quantity = val;
            renderStagedUI();
            updateCardHighlight(variantId);
        }

        function removeStagedItem(variantId) {
            stagedItems = stagedItems.filter(i => i.variantId !== variantId);
            renderStagedUI();
            updateCardHighlight(variantId);
            showToast('Item removed from takeout stage');
        }

        function clearAllStaged() {
            const ids = stagedItems.map(i => i.variantId);
            stagedItems = [];
            renderStagedUI();
            ids.forEach(id => updateCardHighlight(id));
        }

        function updateSummaryAndSubmitState() {
            const totalVariants = stagedItems.length;
            const totalUnits = stagedItems.reduce((acc, curr) => acc + curr.quantity, 0);

            document.getElementById('summaryVariantsCount').textContent = `${totalVariants} variant(s)`;
            document.getElementById('summaryTotalUnits').textContent = `${totalUnits} units`;

            const submitBtn = document.getElementById('submitReleaseBtn');
            const canSubmit = selectedOfficeId && totalVariants > 0;
            submitBtn.disabled = !canSubmit;
        }

        // Validate Before Submit Form
        let isConfirmedSubmit = false;

        async function validateBeforeSubmit(e) {
            if (isConfirmedSubmit) return true;

            if (e && e.preventDefault) {
                e.preventDefault();
            }

            if (!selectedOfficeId) {
                if (window.showAlert) {
                    showAlert({
                        type: 'warning',
                        title: 'Office Required',
                        message: 'Please choose the releasing office before proceeding.'
                    });
                } else {
                    alert('Please select the releasing office.');
                }
                return false;
            }

            if (stagedItems.length === 0) {
                if (window.showAlert) {
                    showAlert({
                        type: 'warning',
                        title: 'No Items Staged',
                        message: 'Please stage at least one item variant to release.'
                    });
                } else {
                    alert('Please stage at least one item variant to release.');
                }
                return false;
            }

            const recipientInput = document.getElementById('releaseRecipientInput');
            const recipientVal = (recipientInput?.value || '').trim();
            if (!recipientVal) {
                if (window.showAlert) {
                    showAlert({
                        type: 'warning',
                        title: 'Recipient Required',
                        message: 'Please enter the recipient (Released To / Recipient) before confirming release.'
                    });
                } else {
                    alert('Please enter the recipient (Released To / Recipient) before confirming release.');
                }
                if (recipientInput) {
                    recipientInput.focus();
                }
                return false;
            }

            const officeName = officesData[selectedOfficeId]?.name || 'the selected office';
            const totalUnits = stagedItems.reduce((acc, curr) => acc + curr.quantity, 0);

            let confirmed = false;
            if (window.showConfirm) {
                confirmed = await showConfirm({
                    type: 'warning',
                    title: 'Confirm Stock Release',
                    message: `Are you sure you want to release ${totalUnits} units (${stagedItems.length} variants) from ${officeName}? This will permanently deduct them from office stock and warehouse quantity on hand.`,
                    confirmText: 'Yes, Release Stocks',
                    cancelText: 'Cancel'
                });
            } else {
                confirmed = confirm(`Are you sure you want to release ${totalUnits} units (${stagedItems.length} variants) from ${officeName}? This will permanently deduct them from the office stock and warehouse quantity on hand.`);
            }

            if (!confirmed) {
                return false;
            }

            // Sync office, recipient and purpose
            document.getElementById('formOfficeId').value = selectedOfficeId;
            document.getElementById('formRecipient').value = recipientVal;
            document.getElementById('formPurpose').value = document.getElementById('releasePurposeInput').value || '';

            // Inject hidden form fields
            const container = document.getElementById('formReleasesInputs');
            container.innerHTML = '';

            stagedItems.forEach((item, index) => {
                const varInput = document.createElement('input');
                varInput.type = 'hidden';
                varInput.name = `releases[${index}][variant_id]`;
                varInput.value = item.variantId;
                container.appendChild(varInput);

                const qtyInput = document.createElement('input');
                qtyInput.type = 'hidden';
                qtyInput.name = `releases[${index}][quantity]`;
                qtyInput.value = item.quantity;
                container.appendChild(qtyInput);
            });

            isConfirmedSubmit = true;
            document.getElementById('releaseForm').submit();
            return true;
        }

        // Search & Category Filters
        function setupSearchAndFilters() {
            const searchInput = document.getElementById('releaseCatalogSearch');
            if (searchInput) {
                searchInput.addEventListener('input', applyCardFilters);
            }

            const categoryBtns = document.querySelectorAll('.category-pill-btn');
            categoryBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    categoryBtns.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    activeCategory = btn.getAttribute('data-category');
                    applyCardFilters();
                });
            });
        }

        function applyCardFilters() {
            const searchInput = document.getElementById('releaseCatalogSearch');
            const query = (searchInput ? searchInput.value : '').trim().toLowerCase();
            const cards = document.querySelectorAll('.release-item-card');
            let matchedCount = 0;

            cards.forEach(card => {
                const searchData = card.getAttribute('data-search') || '';
                const category = card.getAttribute('data-category');

                const matchesQuery = query === '' || searchData.includes(query);
                const matchesCategory = activeCategory === 'ALL' || category === activeCategory;

                if (matchesQuery && matchesCategory) {
                    card.style.display = 'flex';
                    matchedCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            const noMatch = document.getElementById('noMatchMessage');
            if (noMatch) {
                noMatch.style.display = (cards.length > 0 && matchedCount === 0) ? 'block' : 'none';
            }
        }

        function showToast(msg) {
            const existing = document.querySelector('.toast-notification');
            if (existing) existing.remove();

            const toast = document.createElement('div');
            toast.className = 'toast-notification';
            toast.innerHTML = `
                <svg style="width: 16px; height: 16px; color: #10b981;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <span>${escapeHtml(msg)}</span>
            `;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transition = 'opacity 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>
</body>
</html>
