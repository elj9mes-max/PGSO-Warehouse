<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PGSO Warehouse - {{ $office->office_name }} Inventory</title>

    <!-- Modern Sans & Mono Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">

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
            --btn-hover: #27272a;
            --btn-text: #ffffff;
            --primary-accent: #2563eb;
            --primary-light: #eff6ff;
            --success-color: #16a34a;
            --success-bg: #f0fdf4;
            --warning-color: #d97706;
            --warning-bg: #fffbeb;
            --danger-color: #dc2626;
            --danger-bg: #fef2f2;
            --font-sans: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
        }

        body {
            font-family: var(--font-sans);
            background-color: var(--bg-page);
            color: var(--text-primary);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        .office-inventory-area {
            padding: 32px 36px;
            max-width: 1440px;
            margin: 0 auto;
            width: 100%;
        }

        /* Breadcrumbs */
        .breadcrumb-nav {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.8125rem;
            color: var(--text-muted);
            margin-bottom: 20px;
        }

        .breadcrumb-link {
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: color 0.15s ease;
        }

        .breadcrumb-link:hover {
            color: var(--text-primary);
        }

        .breadcrumb-separator {
            color: #d4d4d8;
        }

        .breadcrumb-current {
            color: var(--text-primary);
            font-weight: 600;
        }

        /* Top Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 28px;
            flex-wrap: wrap;
            gap: 20px;
        }

        .header-left {
            display: flex;
            align-items: flex-start;
            gap: 16px;
        }

        .office-icon-large {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            background: linear-gradient(135deg, #09090b 0%, #27272a 100%);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12);
            flex-shrink: 0;
        }

        .office-icon-large svg {
            width: 28px;
            height: 28px;
        }

        .office-title-box h1 {
            font-size: 1.625rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            color: var(--text-primary);
            line-height: 1.25;
            margin-bottom: 6px;
        }

        .office-meta-row {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .code-pill {
            font-family: var(--font-mono);
            font-size: 0.75rem;
            font-weight: 700;
            background-color: #f1f5f9;
            color: #1e293b;
            padding: 3px 9px;
            border-radius: 6px;
            border: 1px solid #cbd5e1;
        }

        .dept-tag {
            font-size: 0.75rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* Header Action Buttons */
        .header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .office-switcher {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            background: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 10px;
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--text-secondary);
        }

        .office-switcher select {
            border: none;
            background: transparent;
            font-family: inherit;
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--text-primary);
            cursor: pointer;
            outline: none;
        }

        .btn-outline {
            height: 40px;
            padding: 0 16px;
            background-color: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 10px;
            color: var(--text-secondary);
            font-family: inherit;
            font-size: 0.8125rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.15s ease;
        }

        .btn-outline:hover {
            border-color: var(--border-hover);
            color: var(--text-primary);
            background-color: #f4f4f5;
        }

        .btn-primary {
            height: 40px;
            padding: 0 18px;
            background-color: var(--btn-bg);
            border: 1px solid var(--btn-bg);
            border-radius: 10px;
            color: var(--btn-text);
            font-family: inherit;
            font-size: 0.8125rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.15s ease;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        }

        .btn-primary:hover {
            background-color: var(--btn-hover);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }

        .btn-primary svg, .btn-outline svg {
            width: 16px;
            height: 16px;
        }

        /* Header Metric Badges in Table Card Header */
        .header-metric-badges {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            background: #fafafa;
            border: 1px solid var(--border-subtle);
            padding: 5px 14px;
            border-radius: 9999px;
        }

        .header-metric-item {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.75rem;
        }

        .header-metric-label {
            color: var(--text-muted);
            font-weight: 500;
        }

        .header-metric-val {
            font-weight: 700;
            color: var(--text-primary);
        }

        .header-metric-val.emerald {
            color: #15803d;
        }

        .header-metric-val.blue {
            color: #1d4ed8;
        }

        .header-metric-val.purple {
            color: #7e22ce;
        }

        .header-metric-val.amber {
            color: #b45309;
        }

        .header-metric-divider {
            color: #d4d4d8;
            font-size: 0.75rem;
        }

        /* Filter Controls Card */
        .filter-controls-card {
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

        .filter-top-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
        }

        .search-box {
            position: relative;
            flex: 1;
            min-width: 280px;
        }

        .search-box svg {
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
            font-size: 0.8438rem;
            font-family: inherit;
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

        .category-filter-bar {
            display: flex;
            align-items: center;
            gap: 8px;
            overflow-x: auto;
            padding-bottom: 2px;
            scrollbar-width: thin;
        }

        .cat-filter-btn {
            padding: 6px 12px;
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

        .cat-filter-btn:hover {
            border-color: var(--border-hover);
            color: var(--text-primary);
        }

        .cat-filter-btn.active {
            background: #09090b;
            border-color: #09090b;
            color: #ffffff;
        }

        /* Inventory Table Card */
        .table-card {
            background: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
        }

        .table-card-header {
            padding: 14px 20px;
            border-bottom: 1px solid var(--border-subtle);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #ffffff;
            flex-wrap: wrap;
            gap: 12px;
        }

        .table-header-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .table-card-title {
            font-size: 0.9375rem;
            font-weight: 700;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .count-pill {
            display: inline-flex;
            align-items: center;
            padding: 2px 8px;
            background: #f4f4f5;
            color: var(--text-secondary);
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        .inventory-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.8438rem;
        }

        .inventory-table th {
            padding: 12px 20px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--text-muted);
            background-color: #fafafa;
            border-bottom: 1px solid var(--border-subtle);
            white-space: nowrap;
        }

        .inventory-table td {
            padding: 16px 20px;
            border-bottom: 1px solid #f4f4f5;
            vertical-align: middle;
            color: var(--text-secondary);
        }

        .inventory-table tbody tr {
            transition: background-color 0.15s ease;
        }

        .inventory-table tbody tr:hover {
            background-color: #fafafa;
        }

        .inventory-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Variant & Item Cell */
        .item-info-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .swatch-box {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            border: 1px solid rgba(0, 0, 0, 0.1);
            flex-shrink: 0;
        }

        .item-titles {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .variant-heading {
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .parent-item-heading {
            font-size: 0.75rem;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .category-tag {
            font-size: 0.6875rem;
            font-weight: 600;
            padding: 2px 7px;
            background: #f4f4f5;
            color: var(--text-secondary);
            border-radius: 6px;
            white-space: nowrap;
        }

        .code-snippet {
            font-family: var(--font-mono);
            font-size: 0.75rem;
            color: #334155;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 2px 6px;
            border-radius: 4px;
        }

        /* Quantity Badge Cell */
        .quantity-cell {
            display: inline-flex;
            align-items: baseline;
            gap: 6px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            padding: 6px 12px;
            border-radius: 8px;
            white-space: nowrap;
        }

        .quantity-strong {
            font-size: 1.0625rem;
            font-weight: 800;
            color: #15803d;
        }

        .quantity-unit {
            font-size: 0.75rem;
            font-weight: 600;
            color: #166534;
        }

        /* Warehouse status badge */
        .wh-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.75rem;
            padding: 3px 8px;
            border-radius: 6px;
            font-weight: 600;
            white-space: nowrap;
        }

        .wh-badge-avail {
            background: #eff6ff;
            color: #1e40af;
            border: 1px solid #dbeafe;
        }

        .wh-badge-empty {
            background: #f4f4f5;
            color: #71717a;
            border: 1px solid #e4e4e7;
        }

        /* Action Buttons Cell */
        .action-btns-group {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 6px;
        }

        .btn-table-action {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 10px;
            border-radius: 7px;
            font-size: 0.75rem;
            font-weight: 600;
            background: #ffffff;
            border: 1px solid var(--border-subtle);
            color: var(--text-secondary);
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .btn-table-action:hover {
            border-color: #09090b;
            color: #09090b;
            background: #f4f4f5;
        }

        .btn-table-action-edit {
            color: #0284c7;
        }

        .btn-table-action-edit:hover {
            border-color: #0284c7;
            background-color: #f0f9ff;
            color: #0369a1;
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(9, 9, 11, 0.5);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            padding: 20px;
        }

        .modal-overlay.open {
            display: flex;
        }

        .modal-dialog {
            background-color: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 14px;
            width: 100%;
            max-width: 560px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            animation: modalFadeIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: scale(0.97) translateY(8px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .modal-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border-subtle);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-header h2 {
            font-size: 1.125rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            color: var(--text-primary);
        }

        .modal-close-btn {
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: 4px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s ease;
        }

        .modal-close-btn:hover {
            color: var(--text-primary);
            background-color: #f4f4f5;
        }

        .modal-body {
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        @media (max-width: 640px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }

        .modal-field {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .modal-label {
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .modal-input {
            width: 100%;
            height: 40px;
            padding: 0 12px;
            border: 1px solid var(--border-subtle);
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.875rem;
            color: var(--text-primary);
            background-color: #ffffff;
            box-sizing: border-box;
            transition: all 0.15s ease;
        }

        .modal-input:focus {
            outline: none;
            border-color: #09090b;
            box-shadow: 0 0 0 3px rgba(9, 9, 11, 0.05);
        }

        .modal-footer {
            padding: 16px 24px;
            border-top: 1px solid var(--border-subtle);
            background-color: #fafafa;
            border-radius: 0 0 14px 14px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
        }

        /* Empty State */
        .empty-inventory-state {
            padding: 64px 24px;
            text-align: center;
            color: var(--text-muted);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 14px;
        }

        .empty-inventory-icon {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background-color: #f4f4f5;
            color: #a1a1aa;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .empty-inventory-icon svg {
            width: 28px;
            height: 28px;
        }

        .empty-inventory-state h3 {
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .empty-inventory-state p {
            font-size: 0.875rem;
            max-width: 380px;
            line-height: 1.45;
        }

        /* Print Specific Styling */
        @media print {
            body {
                background: #ffffff;
                color: #000000;
            }
            .header-actions, .breadcrumb-nav, .filter-controls-card, .btn-table-action, .office-switcher {
                display: none !important;
            }
            .office-inventory-area {
                padding: 0;
                max-width: 100%;
            }
            .table-card {
                border: 1px solid #000000;
                box-shadow: none;
            }
            .inventory-table th {
                background: #f0f0f0;
                color: #000000;
            }
            .inventory-table td {
                color: #000000;
            }
            .quantity-cell {
                border: 1px solid #000000;
                background: none;
            }
            .quantity-strong, .quantity-unit {
                color: #000000;
            }
        }
    </style>
</head>
<body>
    <x-sidebar>
        <div class="office-inventory-area">

            <!-- Flash Alert -->
            @if(session('status'))
                <div class="flash-alert" style="margin-bottom: 20px; display: flex; align-items: center; gap: 10px; padding: 12px 16px; background-color: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 10px; color: #065f46; font-size: 0.875rem; font-weight: 500;">
                    <svg style="width: 18px; height: 18px; flex-shrink: 0;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="flash-alert" style="margin-bottom: 20px; padding: 12px 16px; background-color: #fef2f2; border: 1px solid #fecaca; border-radius: 10px; color: #991b1b; font-size: 0.875rem;">
                    <ul style="margin: 0; padding-left: 18px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Breadcrumbs Navigation -->
            <nav class="breadcrumb-nav" aria-label="Breadcrumb">
                <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">
                    <svg style="width: 14px; height: 14px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    </svg>
                    <span>Dashboard</span>
                </a>
                <span class="breadcrumb-separator">/</span>
                <a href="{{ route('admin.offices.index') }}" class="breadcrumb-link">Offices Directory</a>
                <span class="breadcrumb-separator">/</span>
                <span class="breadcrumb-current">{{ $office->office_name }}</span>
            </nav>

            <!-- Page Header -->
            <div class="page-header">
                <div class="header-left">
                    <div class="office-icon-large">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="16" height="20" x="4" y="2" rx="2" ry="2"/>
                            <path d="M9 22v-4h6v4"/>
                            <path d="M8 6h.01"/>
                            <path d="M16 6h.01"/>
                            <path d="M8 10h.01"/>
                            <path d="M16 10h.01"/>
                            <path d="M8 14h.01"/>
                            <path d="M16 14h.01"/>
                        </svg>
                    </div>
                    <div class="office-title-box">
                        <h1>{{ $office->office_name }}</h1>
                        <div class="office-meta-row">
                            <span class="code-pill">{{ $office->office_code }}</span>
                            <span class="dept-tag">• Provincial Property Custody & Allocation Record</span>
                        </div>
                    </div>
                </div>

                <div class="header-actions">
                    <!-- Office Switcher -->
                    @if(isset($allOffices) && $allOffices->count() > 1)
                        <div class="office-switcher">
                            <svg style="width: 14px; height: 14px; color: #71717a;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                            <label for="officeSwitchSelect" style="font-size: 0.75rem; color: #71717a;">Office:</label>
                            <select id="officeSwitchSelect" onchange="window.location.href=this.value">
                                @foreach($allOffices as $otherOffice)
                                    <option value="{{ route('admin.offices.inventory', $otherOffice) }}" {{ $otherOffice->id === $office->id ? 'selected' : '' }}>
                                        {{ $otherOffice->office_name }} ({{ $otherOffice->office_code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <!-- Print Button -->
                    <button type="button" class="btn-outline" onclick="window.print()">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6 9 6 2 18 2 18 9"></polyline>
                            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                            <rect width="12" height="8" x="6" y="14"></rect>
                        </svg>
                        <span>Print Report</span>
                    </button>

                    <!-- Transfer Stocks CTA -->
                    <a href="{{ route('admin.transfers.index', ['office' => $office->id]) }}" class="btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span>Transfer Stocks to this Office</span>
                    </a>
                </div>
            </div>

            @php
                $inventories = $office->officeInventories;
                $totalUnits = $inventories->sum('quantity');
                $totalVariants = $inventories->count();
                $uniqueItemIds = $inventories->map(fn($oi) => $oi->variant?->inventory_item_id)->filter()->unique()->count();
                $totalValuation = $inventories->sum(function ($oi) {
                    $unitCost = (float) ($oi->variant?->unit_cost ?? $oi->variant?->inventoryItem?->unit_cost ?? 0);
                    return $unitCost * ((int) $oi->quantity);
                });
            @endphp

            <!-- Controls: Search & Category Filters -->
            <div class="filter-controls-card">
                <div class="filter-top-row">
                    <div class="search-box">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <input type="text" id="inventorySearch" class="search-input" placeholder="Search item, variant name, barcode, SKU, category..." autofocus>
                    </div>

                    <a href="{{ route('admin.transfers.index', ['office' => $office->id]) }}" class="btn-outline" style="white-space: nowrap;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                            <polyline points="10 17 15 12 10 7"/>
                            <line x1="15" y1="12" x2="3" y2="12"/>
                        </svg>
                        <span>Dispatch Stocks</span>
                    </a>
                </div>

                <!-- Category Filters -->
                <div class="category-filter-bar">
                    <button type="button" class="cat-filter-btn active" data-cat="ALL">All Categories</button>
                    @if(isset($categories))
                        @foreach($categories as $category)
                            <button type="button" class="cat-filter-btn" data-cat="{{ $category }}">{{ $category }}</button>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Inventory Table Card -->
            <div class="table-card">
                <div class="table-card-header">
                    <div class="table-header-left">
                        <div class="table-card-title">
                            <span>Office Custody Inventory</span>
                            <span class="count-pill" id="visibleCountPill">{{ $inventories->count() }} Variants</span>
                        </div>
                    </div>

                    <div class="header-metric-badges">
                        <div class="header-metric-item">
                            <span class="header-metric-label">Allocated:</span>
                            <span class="header-metric-val emerald">{{ number_format($totalUnits) }} units</span>
                        </div>
                        <span class="header-metric-divider">•</span>
                        <div class="header-metric-item">
                            <span class="header-metric-label">Variants:</span>
                            <span class="header-metric-val blue">{{ number_format($totalVariants) }}</span>
                        </div>
                        <span class="header-metric-divider">•</span>
                        <div class="header-metric-item">
                            <span class="header-metric-label">Items:</span>
                            <span class="header-metric-val purple">{{ number_format($uniqueItemIds) }}</span>
                        </div>
                        <span class="header-metric-divider">•</span>
                        <div class="header-metric-item">
                            <span class="header-metric-label">Total Value:</span>
                            <span class="header-metric-val amber">₱{{ number_format($totalValuation, 2) }}</span>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="inventory-table" id="inventoryTable">
                        <thead>
                            <tr>
                                <th>Item & Variant Details</th>
                                <th>Barcode</th>
                                <th>Category</th>
                                <th>Quantity on Hand</th>
                                <th>Unit Cost</th>
                                <th>Total Value</th>
                                <th>Warehouse Avail</th>
                                <th>Last Transfer Date</th>
                                <th style="text-align: right;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($inventories as $inv)
                                @php
                                    $variant = $inv->variant;
                                    $parentItem = $variant?->inventoryItem;
                                    $lowerName = strtolower($variant?->variant_name ?? '');
                                    $swatchColor = match(true) {
                                        str_contains($lowerName, 'blue') => '#2563eb',
                                        str_contains($lowerName, 'red') => '#dc2626',
                                        str_contains($lowerName, 'black') => '#09090b',
                                        str_contains($lowerName, 'green') => '#16a34a',
                                        str_contains($lowerName, 'yellow') => '#eab308',
                                        str_contains($lowerName, 'orange') => '#ea580c',
                                        str_contains($lowerName, 'purple') => '#9333ea',
                                        str_contains($lowerName, 'white') => '#f4f4f5',
                                        default => '#71717a',
                                    };
                                    $unitCost = (float) ($variant?->unit_cost ?: ($parentItem?->unit_cost ?? 0));
                                    $subTotal = $unitCost * (int) $inv->quantity;
                                    $searchBlob = strtolower(($variant?->variant_name ?? '') . ' ' . ($parentItem?->name ?? '') . ' ' . ($parentItem?->item_code ?? '') . ' ' . ($variant?->barcode ?? '') . ' ' . ($parentItem?->category ?? ''));
                                @endphp

                                <tr class="inv-row" 
                                    data-search="{{ $searchBlob }}" 
                                    data-category="{{ $parentItem?->category ?? '' }}">
                                    <!-- Item & Variant -->
                                    <td>
                                        <div class="item-info-cell">
                                            <span class="swatch-box" style="background-color: {{ $swatchColor }};" title="{{ $variant?->variant_name }}"></span>
                                            <div class="item-titles">
                                                <span class="variant-heading">{{ $variant?->variant_name ?? 'Unnamed Variant' }}</span>
                                                <span class="parent-item-heading">
                                                    {{ $parentItem?->name ?? 'Standard Item' }}
                                                    @if($parentItem?->item_code)
                                                        <span style="font-family: var(--font-mono); color: #64748b;">({{ $parentItem->item_code }})</span>
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Barcode / SKU -->
                                    <td>
                                        <div style="display: flex; flex-direction: column; gap: 3px;">
                                            <span class="code-snippet">{{ $variant?->barcode ?? '—' }}</span>
                                            {{-- @if($variant?->sku)
                                                <span style="font-family: var(--font-mono); font-size: 0.6875rem; color: #71717a;">SKU: {{ $variant->sku }}</span>
                                            @endif --}}
                                        </div>
                                    </td>

                                    <!-- Category -->
                                    <td>
                                        <span class="category-tag">{{ $parentItem?->category ?? 'General' }}</span>
                                    </td>

                                    <!-- Quantity in Office -->
                                    <td>
                                        <div class="quantity-cell">
                                            <span class="quantity-strong">{{ $inv->quantity }}</span>
                                            <span class="quantity-unit">{{ $variant?->effective_unit ?? 'units' }}</span>
                                        </div>
                                    </td>

                                    <!-- Unit Cost -->
                                    <td>
                                        <span>₱{{ number_format($unitCost, 2) }}</span>
                                    </td>

                                    <!-- Total Valuation -->
                                    <td>
                                        <strong style="color: var(--text-primary);">₱{{ number_format($subTotal, 2) }}</strong>
                                    </td>

                                    <!-- Central Warehouse Available Status -->
                                    <td>
                                        @if(($variant?->available_for_transfer ?? 0) > 0)
                                            <span class="wh-badge wh-badge-avail">
                                                {{ $variant->available_for_transfer }} in WH
                                            </span>
                                        @else
                                            <span class="wh-badge wh-badge-empty">0 in WH</span>
                                        @endif
                                    </td>

                                    <!-- Last Transfer Date -->
                                    <td style="font-size: 0.75rem;">
                                        {{ $inv->updated_at ? $inv->updated_at->format('M d, Y • h:i A') : '—' }}
                                    </td>

                                    <!-- Actions -->
                                    <td style="text-align: right;">
                                        <div class="action-btns-group">
                                            <!-- Update Item Button -->
                                            <button type="button" 
                                                    class="btn-table-action btn-table-action-edit" 
                                                    title="Update this item"
                                                    data-item="{{ json_encode([
                                                        'id' => $inv->id,
                                                        'quantity' => (int) $inv->quantity,
                                                        'variant_id' => $variant?->id,
                                                        'variant_name' => $variant?->variant_name ?? '',
                                                        'barcode' => $variant?->barcode ?? '',
                                                        'category' => $parentItem?->category ?? ($variant?->category ?? ''),
                                                        'unit' => $variant?->effective_unit ?? ($parentItem?->unit ?? 'units'),
                                                        'unit_cost' => (float) ($variant?->unit_cost ?: ($parentItem?->unit_cost ?? 0)),
                                                        'parent_item_name' => $parentItem?->name ?? '',
                                                        'parent_item_code' => $parentItem?->item_code ?? '',
                                                        'parent_item_id' => $parentItem?->id,
                                                    ]) }}"
                                                    onclick="openUpdateItemModal(JSON.parse(this.dataset.item))">
                                                <svg style="width: 12px; height: 12px; color: #0284c7;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                </svg>
                                                <span>Update</span>
                                            </button>

                                            @if(($variant?->available_for_transfer ?? 0) > 0)
                                                <a href="{{ route('admin.transfers.index', ['office' => $office->id]) }}" 
                                                   class="btn-table-action" 
                                                   title="Transfer More to this office">
                                                    <svg style="width: 12px; height: 12px; color: #2563eb;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                                    </svg>
                                                    <span>Transfer More</span>
                                                </a>
                                            @endif

                                            @if($parentItem)
                                                <a href="{{ route('admin.inventory.show', $parentItem) }}" 
                                                   class="btn-table-action" 
                                                   title="View item in warehouse catalog">
                                                    <svg style="width: 12px; height: 12px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                                        <polyline points="15 3 21 3 21 9"></polyline>
                                                        <line x1="10" y1="14" x2="21" y2="3"></line>
                                                    </svg>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9">
                                        <div class="empty-inventory-state">
                                            <div class="empty-inventory-icon">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <rect width="16" height="20" x="4" y="2" rx="2" ry="2"/>
                                                    <path d="M9 22v-4h6v4"/>
                                                    <path d="M8 6h.01"/>
                                                    <path d="M16 6h.01"/>
                                                </svg>
                                            </div>
                                            <h3>No Stock Allocated to this Office Yet</h3>
                                            <p>This office does not have any inventory items on hand. You can transfer stocks from the central warehouse catalog.</p>
                                            <a href="{{ route('admin.transfers.index', ['office' => $office->id]) }}" class="btn-primary" style="margin-top: 6px;">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                                                    <polyline points="10 17 15 12 10 7"/>
                                                    <line x1="15" y1="12" x2="3" y2="12"/>
                                                </svg>
                                                <span>Transfer Stocks to {{ $office->office_name }}</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </x-sidebar>

    <!-- Update Item Details Modal -->
    <div class="modal-overlay" id="updateItemModal">
        <div class="modal-dialog">
            <div class="modal-header">
                <div>
                    <h2>Update Item Details</h2>
                    <p style="font-size: 0.75rem; color: #71717a; margin-top: 2px;">
                        Office Custody: <strong style="color: #09090b;">{{ $office->office_name }}</strong>
                    </p>
                </div>
                <button type="button" class="modal-close-btn" id="closeUpdateItemModalBtn" aria-label="Close modal">
                    <svg style="width: 20px; height: 20px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <form id="updateItemForm" method="POST" action="">
                @csrf
                @method('PUT')

                <div class="modal-body">
                    <div class="modal-field">
                        <label class="modal-label" for="updateVariantName">Item / Variant Name *</label>
                        <input type="text" id="updateVariantName" name="variant_name" class="modal-input" required placeholder="e.g. Standard, Blue, 500ml">
                    </div>

                    <div class="form-row">
                        <div class="modal-field">
                            <label class="modal-label" for="updateBarcode">Barcode *</label>
                            <input type="text" id="updateBarcode" name="barcode" class="modal-input" required placeholder="Barcode / code">
                        </div>
                        <div class="modal-field">
                            <label class="modal-label" for="updateCategory">Category</label>
                            <input type="text" id="updateCategory" name="category" list="categorySuggestions" class="modal-input" placeholder="e.g. Office Supplies">
                            <datalist id="categorySuggestions">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}"></option>
                                @endforeach
                            </datalist>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="modal-field">
                            <label class="modal-label" for="updateQuantity">
                                Quantity in Office *
                                <span style="background-color: #dbeafe; color: #1e40af; font-size: 0.6875rem; padding: 1px 6px; border-radius: 4px; font-weight: 700; margin-left: 4px;">Office Stock</span>
                            </label>
                            <input type="number" id="updateQuantity" name="quantity" class="modal-input" min="0" required>
                        </div>
                        <div class="modal-field">
                            <label class="modal-label" for="updateUnit">Unit of Measure</label>
                            <input type="text" id="updateUnit" name="unit" class="modal-input" placeholder="e.g. pieces, boxes, reams">
                        </div>
                    </div>

                    <div class="modal-field">
                        <label class="modal-label" for="updateUnitCost">Unit Cost (₱)</label>
                        <input type="number" id="updateUnitCost" name="unit_cost" step="0.01" min="0" class="modal-input" placeholder="0.00">
                    </div>

                    <div id="parentCatalogLinkWrapper" style="display: none; padding-top: 4px;">
                        <a id="parentCatalogLink" href="#" target="_blank" style="font-size: 0.75rem; color: #2563eb; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                            <span>View item in warehouse catalog</span>
                            <svg style="width: 12px; height: 12px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                <polyline points="15 3 21 3 21 9"></polyline>
                                <line x1="10" y1="14" x2="21" y2="3"></line>
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-outline" id="cancelUpdateItemBtn">Cancel</button>
                    <button type="submit" class="btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span>Save Changes</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Interactive Client Filtering & Modals -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('inventorySearch');
            const catButtons = document.querySelectorAll('.cat-filter-btn');
            const rows = document.querySelectorAll('.inv-row');
            const countPill = document.getElementById('visibleCountPill');

            let activeCat = 'ALL';

            function filterRows() {
                const query = (searchInput.value || '').trim().toLowerCase();
                let visible = 0;

                rows.forEach(row => {
                    const searchData = row.getAttribute('data-search') || '';
                    const catData = row.getAttribute('data-category') || '';

                    const matchesQuery = query === '' || searchData.includes(query);
                    const matchesCat = activeCat === 'ALL' || catData === activeCat;

                    if (matchesQuery && matchesCat) {
                        row.style.display = '';
                        visible++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                if (countPill) {
                    countPill.textContent = `${visible} Variant(s)`;
                }
            }

            if (searchInput) {
                searchInput.addEventListener('input', filterRows);
            }

            catButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    catButtons.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    activeCat = btn.getAttribute('data-cat');
                    filterRows();
                });
            });

            // Update Item Modal Elements & Functions
            const updateModal = document.getElementById('updateItemModal');
            const updateForm = document.getElementById('updateItemForm');
            const closeUpdateBtn = document.getElementById('closeUpdateItemModalBtn');
            const cancelUpdateBtn = document.getElementById('cancelUpdateItemBtn');
            const parentLinkWrapper = document.getElementById('parentCatalogLinkWrapper');
            const parentLink = document.getElementById('parentCatalogLink');

            window.openUpdateItemModal = function(data) {
                if (!updateForm || !updateModal) return;
                updateForm.action = `/admin/offices/{{ $office->id }}/inventory/${data.id}`;
                document.getElementById('updateVariantName').value = data.variant_name || '';
                document.getElementById('updateBarcode').value = data.barcode || '';
                document.getElementById('updateCategory').value = data.category || '';
                document.getElementById('updateQuantity').value = data.quantity !== undefined ? data.quantity : 0;
                document.getElementById('updateUnit').value = data.unit || '';
                document.getElementById('updateUnitCost').value = data.unit_cost !== undefined ? data.unit_cost : '';

                if (data.parent_item_id && parentLink && parentLinkWrapper) {
                    parentLink.href = `/admin/inventory/${data.parent_item_id}`;
                    parentLinkWrapper.style.display = 'block';
                } else if (parentLinkWrapper) {
                    parentLinkWrapper.style.display = 'none';
                }

                updateModal.classList.add('open');
                setTimeout(() => {
                    document.getElementById('updateVariantName').focus();
                }, 50);
            };

            function closeUpdateModal() {
                if (updateModal) {
                    updateModal.classList.remove('open');
                }
            }

            if (closeUpdateBtn) closeUpdateBtn.addEventListener('click', closeUpdateModal);
            if (cancelUpdateBtn) cancelUpdateBtn.addEventListener('click', closeUpdateModal);

            if (updateModal) {
                updateModal.addEventListener('click', (e) => {
                    if (e.target === updateModal) {
                        closeUpdateModal();
                    }
                });
            }

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && updateModal && updateModal.classList.contains('open')) {
                    closeUpdateModal();
                }
            });
        });
    </script>
</body>
</html>
