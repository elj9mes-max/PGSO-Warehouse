<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PGSO Warehouse - Common Items & Standard Terms Directory (PR Reference)</title>

    <!-- Modern Sans & Mono Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --bg-page: #f8fafc;
            --bg-surface: #ffffff;
            --text-primary: #09090b;
            --text-secondary: #334155;
            --text-muted: #64748b;
            --border-subtle: #e2e8f0;
            --border-hover: #cbd5e1;
            --font-sans: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
            --primary: #0f172a;
            --brand-blue: #2563eb;
            --brand-blue-light: #eff6ff;
            --brand-blue-dark: #1e40af;
            --accent-emerald: #059669;
            --accent-emerald-light: #ecfdf5;
            --accent-amber: #d97706;
            --accent-rose: #e11d48;
        }

        body {
            font-family: var(--font-sans);
            background-color: var(--bg-page);
            color: var(--text-primary);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        /* Top Brand Header */
        .staff-topbar {
            background: #ffffff;
            border-bottom: 1px solid var(--border-subtle);
            padding: 14px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        }

        .brand-group {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: inherit;
        }

        .brand-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: #09090b;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
        }

        .brand-icon svg {
            width: 20px;
            height: 20px;
        }

        .brand-text strong {
            font-size: 0.9375rem;
            display: block;
            line-height: 1.2;
            color: var(--text-primary);
            font-weight: 700;
        }

        .brand-text span {
            font-size: 0.6875rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 600;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-pill {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #f8fafc;
            padding: 4px 12px 4px 6px;
            border-radius: 9999px;
            border: 1px solid var(--border-subtle);
        }

        .user-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .user-name-label {
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .btn-topbar-action {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #ffffff;
            color: var(--text-primary);
            border: 1px solid var(--border-subtle);
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 0.8438rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
        }

        .btn-topbar-action:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            transform: translateY(-1px);
        }

        .btn-topbar-primary {
            background: #0f172a;
            color: #ffffff;
            border-color: #0f172a;
            box-shadow: 0 2px 6px rgba(15, 23, 42, 0.15);
        }

        .btn-topbar-primary:hover {
            background: #1e293b;
            border-color: #1e293b;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.2);
        }

        /* Layout Container */
        .pr-container {
            max-width: 1440px;
            width: 100%;
            margin: 0 auto;
            padding: 28px 32px 120px;
            flex: 1;
        }

        /* Subheader & Breadcrumb */
        .pr-header-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 24px;
            gap: 20px;
            flex-wrap: wrap;
        }

        .breadcrumb-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.8125rem;
            margin-bottom: 8px;
        }

        .back-nav-link {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            color: var(--brand-blue);
            font-weight: 600;
            text-decoration: none;
            transition: color 0.15s ease;
        }

        .back-nav-link:hover {
            color: var(--brand-blue-dark);
            text-decoration: underline;
        }

        .crumb-sep {
            color: var(--text-muted);
        }

        .crumb-active {
            color: var(--text-secondary);
            font-weight: 500;
        }

        .pr-title-area h1 {
            font-size: 1.75rem;
            font-weight: 800;
            color: #09090b;
            letter-spacing: -0.025em;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            line-height: 1.25;
        }

        .form-code-badge {
            font-family: var(--font-mono);
            font-size: 0.6875rem;
            font-weight: 700;
            background: var(--brand-blue-light);
            color: var(--brand-blue-dark);
            border: 1px solid #bfdbfe;
            padding: 3px 10px;
            border-radius: 6px;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .form-status-pill {
            font-size: 0.6875rem;
            font-weight: 700;
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
            padding: 3px 10px;
            border-radius: 6px;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .pr-subtitle {
            font-size: 0.9063rem;
            color: var(--text-secondary);
            margin-top: 6px;
            max-width: 820px;
            line-height: 1.5;
        }

        .office-duty-card {
            display: flex;
            align-items: center;
            gap: 12px;
            background: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 14px;
            padding: 10px 18px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        }

        .station-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: var(--brand-blue-light);
            color: var(--brand-blue);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .station-text strong {
            display: block;
            font-size: 0.8438rem;
            color: var(--text-primary);
            font-weight: 700;
        }

        .station-text span {
            font-size: 0.7188rem;
            color: var(--text-muted);
            font-family: var(--font-mono);
            font-weight: 600;
        }

        /* Overview Stat Banner */
        .catalog-summary-banner {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .summary-metric-card {
            background: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 14px;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
            transition: border-color 0.15s ease;
        }

        .summary-metric-card:hover {
            border-color: var(--border-hover);
        }

        .metric-icon-circle {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .metric-icon-blue { background: #eff6ff; color: #2563eb; }
        .metric-icon-emerald { background: #ecfdf5; color: #059669; }
        .metric-icon-purple { background: #f5f3ff; color: #7c3aed; }
        .metric-icon-amber { background: #fffbeb; color: #d97706; }

        .metric-details {
            display: flex;
            flex-direction: column;
        }

        .metric-label {
            font-size: 0.75rem;
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .metric-val {
            font-size: 1.375rem;
            font-weight: 800;
            color: var(--text-primary);
            line-height: 1.2;
            margin-top: 2px;
        }

        /* Filter & Search Toolbar */
        .toolbar-panel {
            background: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 16px;
            padding: 16px 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
            box-shadow: 0 2px 10px rgba(15, 23, 42, 0.03);
            position: relative;
            z-index: 25;
        }

        .toolbar-left-group {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1;
            min-width: 320px;
            flex-wrap: wrap;
        }

        .search-input-wrap {
            position: relative;
            flex: 1;
            min-width: 260px;
            max-width: 420px;
        }

        .search-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 17px;
            height: 17px;
            color: var(--text-muted);
            pointer-events: none;
        }

        .search-clear-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: #e2e8f0;
            color: #64748b;
            border: none;
            display: none;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 13px;
            font-weight: 700;
            transition: all 0.15s ease;
            padding: 0;
            line-height: 1;
        }

        .search-clear-btn:hover {
            background: #cbd5e1;
            color: #0f172a;
        }

        .search-input {
            width: 100%;
            height: 42px;
            padding: 0 36px 0 40px;
            border-radius: 10px;
            border: 1px solid var(--border-subtle);
            font-family: inherit;
            font-size: 0.875rem;
            background: #f8fafc;
            color: var(--text-primary);
            transition: all 0.15s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--brand-blue);
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        .toolbar-dropdown-wrap {
            min-width: 240px;
            flex: 1;
            max-width: 300px;
        }

        .toolbar-dropdown-wrap-sm {
            min-width: 180px;
            flex: 1;
            max-width: 210px;
        }

        .toolbar-panel .pgso-dropdown-search-wrapper .pgso-ds-trigger {
            height: 42px;
            min-height: 42px;
            border-radius: 10px;
            border-color: var(--border-subtle);
            background: #f8fafc;
            font-size: 0.8438rem;
        }

        .toolbar-panel .pgso-dropdown-search-wrapper .pgso-ds-trigger:hover {
            background: #ffffff;
            border-color: #cbd5e1;
        }

        .toolbar-panel .pgso-dropdown-search-wrapper .pgso-ds-trigger.active,
        .toolbar-panel .pgso-dropdown-search-wrapper .pgso-ds-trigger:focus {
            border-color: var(--brand-blue);
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        .toolbar-right-group {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-left: auto;
        }

        .toolbar-stats-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 8px 14px;
            border-radius: 10px;
            font-size: 0.8125rem;
            color: var(--text-secondary);
            font-weight: 500;
            white-space: nowrap;
        }

        .toolbar-stats-badge .badge-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #10b981;
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
        }

        .toolbar-stats-badge strong {
            color: var(--text-primary);
            font-weight: 700;
        }

        .btn-reset-filters {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            border-radius: 10px;
            font-size: 0.8125rem;
            font-weight: 700;
            color: #dc2626;
            background: #fef2f2;
            border: 1px solid #fee2e2;
            cursor: pointer;
            transition: all 0.15s ease;
            white-space: nowrap;
        }

        .btn-reset-filters:hover {
            background: #fee2e2;
            color: #b91c1c;
            transform: translateY(-1px);
        }

        /* Modern List Container */
        .list-table-wrapper {
            background: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03), 0 4px 16px rgba(15, 23, 42, 0.02);
        }

        .list-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .list-table thead {
            background: #f8fafc;
            border-bottom: 1px solid var(--border-subtle);
        }

        .list-table th {
            padding: 14px 18px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #475569;
            white-space: nowrap;
        }

        .list-table th.col-num { width: 56px; text-align: center; }
        .list-table th.col-item { min-width: 320px; }
        .list-table th.col-cat { width: 180px; }
        .list-table th.col-code { width: 170px; }
        .list-table th.col-barcode { width: 180px; }
        .list-table th.col-unit { width: 110px; text-align: center; }
        .list-table th.col-cost { width: 150px; text-align: right; }
        .list-table th.col-actions { width: 90px; text-align: center; }

        .list-table tbody tr.item-row {
            border-bottom: 1px solid #f1f5f9;
            transition: background-color 0.15s ease, transform 0.1s ease;
        }

        .list-table tbody tr.item-row:last-child {
            border-bottom: none;
        }

        .list-table tbody tr.item-row:hover {
            background-color: #f8fafc;
        }

        .list-table td {
            padding: 16px 18px;
            font-size: 0.875rem;
            vertical-align: middle;
        }

        .row-index {
            font-family: var(--font-mono);
            font-size: 0.75rem;
            font-weight: 600;
            color: #94a3b8;
            text-align: center;
        }

        /* Item Info in Table */
        .item-main-info {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .item-title {
            font-size: 0.9688rem;
            font-weight: 700;
            color: #09090b;
            line-height: 1.35;
        }

        .item-parent-subtext {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.7813rem;
            color: #64748b;
            font-weight: 500;
        }

        .item-parent-subtext svg {
            width: 13px;
            height: 13px;
            color: #94a3b8;
            flex-shrink: 0;
        }

        /* Category Tag */
        .category-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            color: #1e40af;
            background: #eff6ff;
            border: 1px solid #dbeafe;
            padding: 3px 10px;
            border-radius: 9999px;
            white-space: nowrap;
        }

        .category-pill-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #3b82f6;
        }

        /* Monospace Code & Barcode Pills */
        .code-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-family: var(--font-mono);
            font-size: 0.7813rem;
            font-weight: 600;
            color: #0f172a;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            padding: 4px 9px;
            border-radius: 7px;
            white-space: nowrap;
        }

        .barcode-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-family: var(--font-mono);
            font-size: 0.7813rem;
            font-weight: 600;
            color: #1e3a8a;
            background: #f0f7ff;
            border: 1px solid #dbeafe;
            padding: 4px 9px;
            border-radius: 7px;
            white-space: nowrap;
        }

        .barcode-chip svg {
            width: 14px;
            height: 14px;
            color: #3b82f6;
        }

        .no-barcode-text {
            font-family: var(--font-mono);
            font-size: 0.75rem;
            color: #94a3b8;
            font-style: italic;
        }

        /* Unit of Measure Tag */
        .unit-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-family: var(--font-mono);
            font-size: 0.7188rem;
            font-weight: 700;
            text-transform: uppercase;
            color: #475569;
            background: #f1f5f9;
            padding: 4px 10px;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            white-space: nowrap;
        }

        /* Cost Column */
        .cost-display {
            text-align: right;
        }

        .cost-amount {
            font-family: var(--font-mono);
            font-size: 0.9375rem;
            font-weight: 700;
            color: #09090b;
        }

        .cost-label {
            font-size: 0.6875rem;
            color: #94a3b8;
            display: block;
            margin-top: 1px;
        }

        /* Copy Button */
        .btn-quick-copy {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #64748b;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .btn-quick-copy:hover {
            background: #0f172a;
            color: #ffffff;
            border-color: #0f172a;
            transform: scale(1.05);
        }

        .btn-quick-copy svg {
            width: 14px;
            height: 14px;
        }

        /* Empty State */
        .empty-list-state {
            padding: 64px 24px;
            text-align: center;
            background: #ffffff;
        }

        .empty-list-icon {
            width: 56px;
            height: 56px;
            margin: 0 auto 16px;
            border-radius: 16px;
            background: #f1f5f9;
            color: #94a3b8;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .empty-list-icon svg {
            width: 28px;
            height: 28px;
        }

        .empty-list-state h3 {
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 6px;
        }

        .empty-list-state p {
            font-size: 0.875rem;
            color: var(--text-muted);
            max-width: 440px;
            margin: 0 auto 16px;
            line-height: 1.5;
        }

        /* Notification Toast */
        .toast-notify {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: #0f172a;
            color: #ffffff;
            padding: 12px 18px;
            border-radius: 10px;
            font-size: 0.8438rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            z-index: 300;
            opacity: 0;
            transform: translateY(12px);
            pointer-events: none;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .toast-notify.visible {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }

        /* Responsive Mobile Cards for Table */
        @media (max-width: 900px) {
            .pr-container {
                padding: 18px 16px 100px;
            }

            .list-table-wrapper {
                border-radius: 12px;
            }

            .list-table thead {
                display: none;
            }

            .list-table,
            .list-table tbody,
            .list-table tr.item-row,
            .list-table td {
                display: block;
                width: 100%;
            }

            .list-table tr.item-row {
                padding: 16px;
                border-bottom: 1px solid var(--border-subtle);
                position: relative;
            }

            .list-table td {
                padding: 4px 0;
            }

            .list-table td.col-num {
                display: none;
            }

            .item-main-info {
                margin-bottom: 10px;
            }

            .item-title {
                font-size: 1rem;
            }

            .mobile-meta-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 8px 12px;
                padding-top: 10px;
                margin-top: 10px;
                border-top: 1px dashed var(--border-subtle);
            }

            .mobile-meta-item {
                display: flex;
                flex-direction: column;
                gap: 2px;
            }

            .mobile-meta-label {
                font-size: 0.6875rem;
                color: var(--text-muted);
                text-transform: uppercase;
                font-weight: 600;
                letter-spacing: 0.04em;
            }

            .cost-display {
                text-align: left;
            }

            .list-table td.col-actions {
                position: absolute;
                top: 16px;
                right: 16px;
                width: auto;
                padding: 0;
            }
        }

        /* Print Styles */
        @media print {
            .staff-topbar,
            .toolbar-panel,
            .breadcrumb-wrap,
            .col-actions,
            .btn-topbar-action,
            .toast-notify {
                display: none !important;
            }

            body {
                background: #ffffff;
                color: #000000;
            }

            .pr-container {
                padding: 0;
                max-width: 100%;
            }

            .list-table-wrapper {
                border: 1px solid #000000;
                box-shadow: none;
            }

            .list-table th,
            .list-table td {
                border-bottom: 1px solid #ddd;
                padding: 8px 10px;
            }
        }
    </style>
</head>
<body>
    @php
        $user = auth()->user()->load('office');
        $activeOffice = $user->office;
    @endphp

    <!-- Topbar -->
    <header class="staff-topbar">
        <a href="{{ route('staff.requisition.index') }}" class="brand-group">
            <div class="brand-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
            </div>
            <div class="brand-text">
                <strong>PGSO Inventory System</strong>
                <span>Provincial Warehouse Operations</span>
            </div>
        </a>

        <div class="topbar-actions">
            <div class="user-pill">
                <div class="user-avatar">{{ strtoupper(substr($user->first_name ?? 'S', 0, 1)) }}</div>
                <span class="user-name-label">{{ $user->full_name }}</span>
            </div>

            <!-- Print Catalog of Items -->
            <button type="button" onclick="window.print()" class="btn-topbar-action" title="Print Common Items Directory">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                <span>Print Directory</span>
            </button>

            <!-- Link to Official Printable Form -->
            <a href="{{ route('staff.requisition.pr-form') }}" class="btn-topbar-action btn-topbar-primary" title="Open Official Printable PR Form Template">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                <span>Official PR Form</span>
            </a>
        </div>
    </header>

    <!-- Main Container -->
    <main class="pr-container">
        <!-- Subheader & Breadcrumbs -->
        <div class="pr-header-row">
            <div class="pr-title-area">
                <div class="breadcrumb-wrap">
                    <a href="{{ route('staff.requisition.index') }}" class="back-nav-link">
                        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
                        <span>Requisitions</span>
                    </a>
                    <span class="crumb-sep">/</span>
                    <span class="crumb-active">Common Terms & Items (PR Reference)</span>
                </div>
                <h1>
                    <span>Common Terms & Items Directory</span>
                    <span class="form-code-badge">Purchase Request (PR) Reference</span>
                    <span class="form-status-pill">Appendix 60 • LGU Standard</span>
                </h1>
                <p class="pr-subtitle">
                    Standard catalog of common warehouse supplies, materials, and equipment terms. Browse official descriptions, standardized codes, barcodes, units, and baseline estimated rates for requisition and procurement documentation.
                </p>
            </div>

            <div class="office-duty-card">
                <div class="station-icon">
                    <svg viewBox="0 0 24 24" width="19" height="19" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M9 3v18"/><path d="m14 8 3 3-3 3"/></svg>
                </div>
                <div class="station-text">
                    <strong>{{ $activeOffice->office_name ?? 'Central Office' }}</strong>
                    <span>{{ $activeOffice->office_code ?? 'OFF-PGSO' }} • Center 1061</span>
                </div>
            </div>
        </div>

        @php
            // Pull categories from the Category model table
            $categoryRecords = $categories instanceof \Illuminate\Support\Collection 
                ? $categories 
                : collect($categories ?? \App\Models\Category::orderBy('name')->get());

            $categoryTableNames = $categoryRecords->map(fn ($c) => is_object($c) ? $c->name : (string) $c)->filter();

            // Include any category strings that may exist on inventory items in the warehouse
            $variantCategoryNames = $variants->map(function ($v) {
                return $v->inventoryItem?->categoryModel?->name 
                    ?: ($v->inventoryItem?->category ?: 'General');
            })->filter();

            // Merge both collections uniquely and sorted
            $allCategoryNames = $categoryTableNames->concat($variantCategoryNames)->unique()->sort()->values();

            $categoryOptions = collect([
                [
                    'id' => 'ALL',
                    'name' => 'All Categories',
                    'subtext' => $variants->count() . ' items',
                ]
            ])->concat(
                $allCategoryNames->map(function ($catName) use ($variants) {
                    $count = $variants->filter(function ($v) use ($catName) {
                        $itemCat = $v->inventoryItem?->categoryModel?->name 
                            ?: ($v->inventoryItem?->category ?: 'General');
                        return strcasecmp($itemCat, $catName) === 0;
                    })->count();

                    return [
                        'id' => $catName,
                        'name' => $catName,
                        'subtext' => $count . ' item' . ($count === 1 ? '' : 's'),
                    ];
                })
            )->values()->all();

            $sortOptions = [
                ['id' => 'name-asc', 'name' => 'Name (A to Z)'],
                ['id' => 'name-desc', 'name' => 'Name (Z to A)'],
                ['id' => 'code-asc', 'name' => 'Item Code (SKU)'],
                ['id' => 'cost-asc', 'name' => 'Unit Cost (Lowest First)'],
                ['id' => 'cost-desc', 'name' => 'Unit Cost (Highest First)'],
            ];
        @endphp

        <!-- Quick Summary Cards -->
        <div class="catalog-summary-banner">
            <div class="summary-metric-card">
                <div class="metric-icon-circle metric-icon-blue">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="m9 14 2 2 4-4"/></svg>
                </div>
                <div class="metric-details">
                    <span class="metric-label">Common Terms / Items</span>
                    <span class="metric-val">{{ $variants->count() }}</span>
                </div>
            </div>

            <div class="summary-metric-card">
                <div class="metric-icon-circle metric-icon-purple">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>
                </div>
                <div class="metric-details">
                    <span class="metric-label">Supply Categories</span>
                    <span class="metric-val">{{ $allCategoryNames->count() }}</span>
                </div>
            </div>

            <div class="summary-metric-card">
                <div class="metric-icon-circle metric-icon-emerald">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
                <div class="metric-details">
                    <span class="metric-label">Pricing Standard</span>
                    <span class="metric-val" style="font-size: 1.0625rem; font-weight: 700; color: #059669;">DBM / Contract Rate</span>
                </div>
            </div>

            <div class="summary-metric-card">
                <div class="metric-icon-circle metric-icon-amber">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                </div>
                <div class="metric-details">
                    <span class="metric-label">Form Compliance</span>
                    <span class="metric-val" style="font-size: 1.0625rem; font-weight: 700; color: #b45309;">Appendix 60 GAM</span>
                </div>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="toolbar-panel">
            <div class="toolbar-left-group">
                <!-- Live Search Box -->
                <div class="search-input-wrap">
                    <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    <input type="text" id="searchInput" class="search-input" placeholder="Search item term, code, barcode, category..." autocomplete="off">
                    <button type="button" class="search-clear-btn" id="clearSearchBtn" aria-label="Clear search text">&times;</button>
                </div>

                <!-- Category Searchable Dropdown -->
                <div class="toolbar-dropdown-wrap">
                    <x-dropdown-search 
                        name="category_filter"
                        id="prCategoryFilter"
                        :options="$categoryOptions"
                        valueKey="id"
                        labelKey="name"
                        subtextKey="subtext"
                        placeholder="All Categories"
                        searchPlaceholder="Filter category..."
                        :allowClear="true"
                    />
                </div>

                <!-- Sort Dropdown -->
                <div class="toolbar-dropdown-wrap-sm">
                    <x-dropdown-search 
                        name="sort_filter"
                        id="prSortFilter"
                        :options="$sortOptions"
                        valueKey="id"
                        labelKey="name"
                        selected="name-asc"
                        placeholder="Sort by..."
                        searchPlaceholder="Sort order..."
                        :allowClear="false"
                    />
                </div>
            </div>

            <div class="toolbar-right-group">
                <button type="button" class="btn-reset-filters" id="resetFiltersBtn" style="display: none;" title="Reset all active search and category filters">
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                    <span>Reset</span>
                </button>

                <div class="toolbar-stats-badge">
                    <span class="badge-dot"></span>
                    <span>Showing <strong id="visibleCount">{{ $variants->count() }}</strong> of <strong>{{ $variants->count() }}</strong> items</span>
                </div>
            </div>
        </div>

        <!-- Common Items List (Table on Desktop, Streamlined Rows on Mobile) -->
        <div class="list-table-wrapper">
            <table class="list-table">
                <thead>
                    <tr>
                        <th class="col-num">#</th>
                        <th class="col-item">Common Term / Item Specification</th>
                        {{-- <th class="col-cat">Category</th> --}}
                        <th class="col-code">Item Code (SKU)</th>
                        {{-- <th class="col-barcode">Barcode</th> --}}
                        <th class="col-unit">Unit</th>
                        <th class="col-cost">Est. Unit Cost</th>
                        {{-- <th class="col-actions">Copy</th> --}}
                    </tr>
                </thead>
                <tbody id="itemsListTbody">
                    @forelse($variants as $index => $variant)
                        @php
                            $code = $variant->sku ?: ($variant->inventoryItem?->item_code ?: 'N/A');
                            $barcode = $variant->barcode ?: '';
                            $category = $variant->effective_category;
                            $unit = $variant->unit ?: ($variant->inventoryItem?->unit ?: 'pcs');
                            $estCost = (float) ($variant->unit_cost ?: ($variant->inventoryItem?->unit_cost ?: 0));
                            $parentItemName = $variant->inventoryItem?->name ?? '';
                        @endphp
                        <tr class="item-row"
                            id="item-row-{{ $variant->id }}"
                            data-id="{{ $variant->id }}"
                            data-name="{{ $variant->variant_name }}"
                            data-item-name="{{ $parentItemName }}"
                            data-code="{{ $code }}"
                            data-barcode="{{ $barcode }}"
                            data-unit="{{ $unit }}"
                            data-cost="{{ $estCost }}"
                            data-category="{{ $category }}">
                            
                            <!-- Index -->
                            <td class="col-num row-index">{{ $index + 1 }}</td>

                            <!-- Item / Variant Term Description -->
                            <td class="col-item">
                                <div class="item-main-info">
                                    <div class="item-title">{{ $variant->variant_name }}</div>
                                    @if($parentItemName && $parentItemName !== $variant->variant_name)
                                        <div class="item-parent-subtext">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                                            <span>{{ $parentItemName }}</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Mobile Metadata Row (Visible on smaller viewports) -->
                                <div class="mobile-meta-grid" style="display: none;">
                                    <div class="mobile-meta-item">
                                        <span class="mobile-meta-label">Category</span>
                                        <span class="category-pill">
                                            <span class="category-pill-dot"></span>
                                            {{ $category }}
                                        </span>
                                    </div>
                                    <div class="mobile-meta-item">
                                        <span class="mobile-meta-label">Unit</span>
                                        <span class="unit-badge">{{ $unit }}</span>
                                    </div>
                                    <div class="mobile-meta-item">
                                        <span class="mobile-meta-label">Item Code</span>
                                        <span class="code-chip">{{ $code }}</span>
                                    </div>
                                    <div class="mobile-meta-item">
                                        <span class="mobile-meta-label">Est. Cost</span>
                                        <span class="cost-amount">₱{{ number_format($estCost, 2) }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Category -->
                            {{-- <td class="col-cat">
                                <span class="category-pill">
                                    <span class="category-pill-dot"></span>
                                    {{ $category }}
                                </span>
                            </td> --}}

                            <!-- Item Code (SKU) -->
                            <td class="col-code">
                                <span class="code-chip" title="Official Stock / Item Code">{{ $code }}</span>
                            </td>

                            <!-- Barcode -->
                            {{-- <td class="col-barcode">
                                @if($barcode)
                                    <span class="barcode-chip" title="Scannable Barcode">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 5v14"/><path d="M8 5v14"/><path d="M12 5v14"/><path d="M17 5v14"/><path d="M21 5v14"/></svg>
                                        {{ $barcode }}
                                    </span>
                                @else
                                    <span class="no-barcode-text">No Barcode</span>
                                @endif
                            </td> --}}

                            <!-- Unit -->
                            <td class="col-unit" style="text-align: center;">
                                <span class="unit-badge">{{ $unit }}</span>
                            </td>

                            <!-- Estimated Unit Cost -->
                            <td class="col-cost cost-display">
                                <div class="cost-amount">₱{{ number_format($estCost, 2) }}</div>
                                <span class="cost-label">per {{ $unit }}</span>
                            </td>

                            <!-- Quick Action: Copy Code -->
                            {{-- <td class="col-actions" style="text-align: center;">
                                <button type="button" class="btn-quick-copy" onclick="copyItemInfo('{{ addslashes($code) }}', 'Item Code')" title="Copy Item Code: {{ $code }}">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>
                                </button>
                            </td> --}}
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-list-state">
                                    <div class="empty-list-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>
                                    </div>
                                    <h3>No Inventory Item Variants Recorded</h3>
                                    <p>There are currently no item variants or common terms found in the warehouse database.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Dynamic Search / Filter Empty State -->
            <div id="noSearchResultsState" class="empty-list-state" style="display: none; border-top: 1px solid #f1f5f9;">
                <div class="empty-list-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/><path d="m9 9 4 4"/><path d="m13 9-4 4"/></svg>
                </div>
                <h3>No Matching Common Terms Found</h3>
                <p>Try modifying your search keywords or resetting the category filter to view all listed items.</p>
                <button type="button" onclick="document.getElementById('resetFiltersBtn')?.click()" style="display: inline-flex; align-items: center; gap: 6px; padding: 9px 18px; border-radius: 9px; font-size: 0.8125rem; font-weight: 700; color: #ffffff; background: #0f172a; border: none; cursor: pointer;">
                    <span>Reset All Filters</span>
                </button>
            </div>
        </div>
    </main>

    <!-- Notification Toast -->
    <div class="toast-notify" id="toastNotify">
        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="#10b981" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        <span id="toastMsg">Copied to clipboard</span>
    </div>

    <x-staffNav />

    <script>
        // DOM Elements
        const searchInput = document.getElementById('searchInput');
        const clearSearchBtn = document.getElementById('clearSearchBtn');
        const categoryFilterInput = document.getElementById('prCategoryFilter');
        const sortFilterInput = document.getElementById('prSortFilter');
        const resetFiltersBtn = document.getElementById('resetFiltersBtn');
        const itemsListTbody = document.getElementById('itemsListTbody');
        const noSearchResultsState = document.getElementById('noSearchResultsState');
        const visibleCount = document.getElementById('visibleCount');
        const toastNotify = document.getElementById('toastNotify');
        const toastMsg = document.getElementById('toastMsg');

        // Toast Helper
        let toastTimeout;
        function showToast(message) {
            clearTimeout(toastTimeout);
            toastMsg.textContent = message;
            toastNotify.classList.add('visible');
            toastTimeout = setTimeout(() => {
                toastNotify.classList.remove('visible');
            }, 3000);
        }

        // Quick Copy to Clipboard
        function copyItemInfo(text, label = 'Text') {
            if (!text || text === 'N/A') return;
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(() => {
                    showToast(`${label} copied: "${text}"`);
                }).catch(() => {
                    fallbackCopy(text, label);
                });
            } else {
                fallbackCopy(text, label);
            }
        }

        function fallbackCopy(text, label) {
            const textarea = document.createElement('textarea');
            textarea.value = text;
            textarea.style.position = 'fixed';
            textarea.style.opacity = '0';
            document.body.appendChild(textarea);
            textarea.select();
            try {
                document.execCommand('copy');
                showToast(`${label} copied: "${text}"`);
            } catch (err) {
                console.error(err);
            }
            document.body.removeChild(textarea);
        }

        // Live Search, Category Filtering & Sorting
        let allRows = Array.from(itemsListTbody.querySelectorAll('tr.item-row'));

        function filterItems() {
            const query = (searchInput?.value || '').toLowerCase().trim();
            const selectedCat = (categoryFilterInput?.value || 'ALL').trim();
            const selectedSort = sortFilterInput?.value || 'name-asc';

            if (clearSearchBtn) {
                clearSearchBtn.style.display = query ? 'flex' : 'none';
            }

            let visibleRows = [];

            allRows.forEach(row => {
                const name = (row.dataset.name || '').toLowerCase();
                const itemName = (row.dataset.itemName || '').toLowerCase();
                const code = (row.dataset.code || '').toLowerCase();
                const barcode = (row.dataset.barcode || '').toLowerCase();
                const cat = (row.dataset.category || 'General').trim();

                const matchesQuery = !query || 
                    name.includes(query) || 
                    itemName.includes(query) || 
                    code.includes(query) || 
                    barcode.includes(query) ||
                    cat.toLowerCase().includes(query);

                const matchesCat = !selectedCat || selectedCat === 'ALL' || cat.toLowerCase() === selectedCat.toLowerCase();

                if (matchesQuery && matchesCat) {
                    row.style.display = '';
                    visibleRows.push(row);
                } else {
                    row.style.display = 'none';
                }
            });

            // Sorting
            visibleRows.sort((a, b) => {
                const nameA = (a.dataset.name || '').toLowerCase();
                const nameB = (b.dataset.name || '').toLowerCase();
                const codeA = (a.dataset.code || '').toLowerCase();
                const codeB = (b.dataset.code || '').toLowerCase();
                const costA = parseFloat(a.dataset.cost) || 0;
                const costB = parseFloat(b.dataset.cost) || 0;

                switch (selectedSort) {
                    case 'name-desc':
                        return nameB.localeCompare(nameA);
                    case 'code-asc':
                        return codeA.localeCompare(codeB);
                    case 'cost-asc':
                        return costA - costB;
                    case 'cost-desc':
                        return costB - costA;
                    case 'name-asc':
                    default:
                        return nameA.localeCompare(nameB);
                }
            });

            // Re-order visible rows inside tbody and update index numbers
            visibleRows.forEach((row, idx) => {
                const indexCell = row.querySelector('.row-index');
                if (indexCell) {
                    indexCell.textContent = idx + 1;
                }
                itemsListTbody.appendChild(row);
            });

            if (visibleCount) {
                visibleCount.textContent = visibleRows.length;
            }

            const isFiltered = Boolean(query || (selectedCat && selectedCat !== 'ALL') || (selectedSort && selectedSort !== 'name-asc'));
            if (resetFiltersBtn) {
                resetFiltersBtn.style.display = isFiltered ? 'inline-flex' : 'none';
            }

            if (noSearchResultsState) {
                noSearchResultsState.style.display = (visibleRows.length === 0 && allRows.length > 0) ? 'block' : 'none';
            }
        }

        if (searchInput) searchInput.addEventListener('input', filterItems);

        if (clearSearchBtn) {
            clearSearchBtn.addEventListener('click', () => {
                searchInput.value = '';
                filterItems();
                searchInput.focus();
            });
        }

        if (categoryFilterInput) {
            categoryFilterInput.addEventListener('change', filterItems);
            categoryFilterInput.addEventListener('dropdown-selected', filterItems);
            categoryFilterInput.addEventListener('dropdown-cleared', () => {
                categoryFilterInput.value = 'ALL';
                filterItems();
            });
        }

        if (sortFilterInput) {
            sortFilterInput.addEventListener('change', filterItems);
            sortFilterInput.addEventListener('dropdown-selected', filterItems);
        }

        if (resetFiltersBtn) {
            resetFiltersBtn.addEventListener('click', () => {
                if (searchInput) searchInput.value = '';
                if (categoryFilterInput) {
                    categoryFilterInput.value = 'ALL';
                    const wrapper = categoryFilterInput.closest('.pgso-dropdown-search-wrapper');
                    if (wrapper) {
                        const clearBtn = wrapper.querySelector('.pgso-ds-clear-btn');
                        if (clearBtn) clearBtn.click();
                        else {
                            categoryFilterInput.dispatchEvent(new Event('change', { bubbles: true }));
                        }
                    }
                }
                if (sortFilterInput) {
                    sortFilterInput.value = 'name-asc';
                    sortFilterInput.dispatchEvent(new Event('change', { bubbles: true }));
                }
                filterItems();
            });
        }
    </script>
</body>
</html>
