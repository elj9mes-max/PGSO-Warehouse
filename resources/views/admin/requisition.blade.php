<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PGSO Warehouse - Requisition Review & Issuance</title>
    
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
            --brand-emerald: #059669;
            --brand-amber: #d97706;
            --brand-rose: #e11d48;
            --brand-indigo: #4f46e5;
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

        .req-admin-content-area {
            padding: 32px 36px;
            max-width: 1480px;
            margin: 0 auto;
            width: 100%;
        }

        /* Top Page Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .header-title-group h1 {
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            color: var(--text-primary);
            line-height: 1.25;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header-title-group p {
            font-size: 0.8438rem;
            color: var(--text-muted);
            margin-top: 4px;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-outline {
            height: 38px;
            padding: 0 16px;
            border-radius: 8px;
            background-color: #ffffff;
            border: 1px solid var(--border-subtle);
            font-family: inherit;
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--text-secondary);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .btn-outline:hover {
            border-color: var(--border-hover);
            color: var(--text-primary);
            background-color: #f4f4f5;
        }

        .btn-outline svg {
            width: 16px;
            height: 16px;
        }

        .btn-primary {
            height: 38px;
            padding: 0 16px;
            border-radius: 8px;
            background-color: var(--btn-bg);
            border: 1px solid var(--btn-bg);
            font-family: inherit;
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--btn-text);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.15s ease;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .btn-primary:hover {
            background-color: #27272a;
            border-color: #27272a;
        }

        /* Metrics Strip */
        .metrics-strip {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 18px;
            margin-bottom: 28px;
        }

        .metric-card {
            background-color: var(--bg-surface);
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            padding: 18px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-decoration: none;
            color: inherit;
            transition: all 0.15s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
            position: relative;
            overflow: hidden;
        }

        .metric-card:hover {
            border-color: var(--border-hover);
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .metric-card.active-tab {
            border-color: var(--text-primary);
            box-shadow: 0 0 0 1px var(--text-primary);
        }

        .metric-info {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .metric-label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .metric-value {
            font-size: 1.625rem;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.03em;
            line-height: 1.1;
        }

        .metric-subtext {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .metric-icon-box {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .metric-icon-box svg {
            width: 20px;
            height: 20px;
        }

        .metric-icon-box.total {
            background-color: #f4f4f5;
            color: #18181b;
        }

        .metric-icon-box.pending {
            background-color: #fef3c7;
            color: #b45309;
        }

        .metric-icon-box.approved {
            background-color: #dcfce7;
            color: #15803d;
        }

        .metric-icon-box.rejected {
            background-color: #ffe4e6;
            color: #be123c;
        }

        /* Pulsing Dot */
        .pulse-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #f59e0b;
            display: inline-block;
            box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.7);
            animation: pulseRing 1.8s infinite cubic-bezier(0.66, 0, 0, 1);
        }

        @keyframes pulseRing {
            0% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.7); }
            70% { box-shadow: 0 0 0 6px rgba(245, 158, 11, 0); }
            100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0); }
        }

        /* Alert Banners */
        .alert-banner {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
            padding: 12px 18px;
            border-radius: 10px;
            margin-bottom: 24px;
            font-size: 0.8438rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            animation: fadeIn 0.2s ease-out;
        }

        .alert-banner.error {
            background-color: #fef2f2;
            border-color: #fecaca;
            color: #991b1b;
        }

        .alert-content {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-close {
            background: none;
            border: none;
            cursor: pointer;
            color: inherit;
            opacity: 0.7;
        }

        .alert-close:hover {
            opacity: 1;
        }

        /* Main Workspace Card */
        .workspace-card {
            background-color: var(--bg-surface);
            border: 1px solid var(--border-subtle);
            border-radius: 14px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
            overflow: hidden;
        }

        /* Filter Toolbar */
        .filter-toolbar {
            padding: 18px 24px;
            border-bottom: 1px solid var(--border-subtle);
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .filter-top-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .status-tabs-group {
            display: flex;
            align-items: center;
            background-color: #f4f4f5;
            padding: 4px;
            border-radius: 10px;
            gap: 3px;
            flex-wrap: wrap;
        }

        .status-tab-btn {
            background: none;
            border: none;
            padding: 6px 14px;
            font-family: inherit;
            font-size: 0.7813rem;
            font-weight: 600;
            color: var(--text-secondary);
            border-radius: 7px;
            cursor: pointer;
            transition: all 0.15s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .status-tab-btn:hover {
            color: var(--text-primary);
        }

        .status-tab-btn.active {
            background-color: #ffffff;
            color: var(--text-primary);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        .tab-badge {
            font-size: 0.6875rem;
            padding: 1px 6px;
            border-radius: 9999px;
            background-color: rgba(0, 0, 0, 0.06);
            color: var(--text-secondary);
            font-family: var(--font-mono);
        }

        .status-tab-btn.active .tab-badge {
            background-color: #09090b;
            color: #ffffff;
        }

        .filter-bottom-row {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .search-box-wrap {
            position: relative;
            flex: 1;
            min-width: 260px;
        }

        .search-icon-svg {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            color: #a1a1aa;
            pointer-events: none;
        }

        .search-input {
            width: 100%;
            height: 38px;
            padding: 0 12px 0 36px;
            border: 1px solid var(--border-subtle);
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.8125rem;
            color: var(--text-primary);
            background-color: #ffffff;
            outline: none;
            transition: all 0.15s ease;
        }

        .search-input:focus {
            border-color: var(--text-primary);
            box-shadow: 0 0 0 3px rgba(9, 9, 11, 0.06);
        }

        .filter-select {
            height: 38px;
            padding: 0 32px 0 12px;
            border: 1px solid var(--border-subtle);
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.8125rem;
            color: var(--text-primary);
            background-color: #ffffff;
            outline: none;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2371717a' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            transition: all 0.15s ease;
        }

        .filter-select:focus {
            border-color: var(--text-primary);
        }

        .btn-reset-filter {
            font-size: 0.7813rem;
            color: var(--text-muted);
            text-decoration: none;
            padding: 6px 10px;
            border-radius: 6px;
            transition: color 0.15s;
        }

        .btn-reset-filter:hover {
            color: var(--text-primary);
        }

        /* Requests Table */
        .requests-table-wrap {
            width: 100%;
            overflow-x: auto;
        }

        .requests-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.8125rem;
            text-align: left;
        }

        .requests-table thead th {
            background-color: #fafafa;
            padding: 12px 18px;
            font-size: 0.7188rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border-subtle);
            white-space: nowrap;
        }

        .requests-table tbody tr {
            border-bottom: 1px solid #f4f4f5;
            transition: background-color 0.12s ease;
        }

        .requests-table tbody tr:hover {
            background-color: #fafafa;
        }

        .requests-table td {
            padding: 16px 18px;
            vertical-align: middle;
        }

        /* Tracking Number & Type Badges */
        .tracking-number-box {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .req-mono-number {
            font-family: var(--font-mono);
            font-weight: 600;
            font-size: 0.8125rem;
            color: var(--text-primary);
            letter-spacing: -0.01em;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .badge-type {
            display: inline-flex;
            align-items: center;
            font-size: 0.6563rem;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 5px;
            letter-spacing: 0.04em;
            width: fit-content;
        }

        .badge-type.ris {
            background-color: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #dbeafe;
        }

        .badge-type.pr {
            background-color: #fdf2f8;
            color: #be185d;
            border: 1px solid #fce7f3;
        }

        /* Office & Requester */
        .office-staff-cell {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .office-name-row {
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .office-code-chip {
            font-size: 0.6563rem;
            font-weight: 700;
            padding: 1px 6px;
            border-radius: 4px;
            background-color: #f4f4f5;
            color: #52525b;
            font-family: var(--font-mono);
        }

        .staff-name-sub {
            font-size: 0.75rem;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* Purpose & Items Cell */
        .purpose-cell {
            max-width: 280px;
        }

        .purpose-text {
            font-size: 0.8125rem;
            color: var(--text-secondary);
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.35;
        }

        .items-preview-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.7188rem;
            font-weight: 600;
            color: var(--text-muted);
            background-color: #f4f4f5;
            padding: 2px 8px;
            border-radius: 6px;
            margin-top: 5px;
        }

        /* Status Badges */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 9999px;
            font-size: 0.7188rem;
            font-weight: 600;
            letter-spacing: 0.02em;
            white-space: nowrap;
        }

        .status-badge.pending {
            background-color: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        .status-badge.approved {
            background-color: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .status-badge.approved_with_adjustments {
            background-color: #eff6ff;
            color: #1e40af;
            border: 1px solid #bfdbfe;
        }

        .status-badge.issued {
            background-color: #f4f4f5;
            color: #18181b;
            border: 1px solid #e4e4e7;
        }

        .status-badge.rejected {
            background-color: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        /* Reason Callout Note in Table */
        .reason-callout-snippet {
            margin-top: 6px;
            font-size: 0.7188rem;
            color: #b91c1c;
            background-color: #fff1f2;
            border: 1px dashed #fecdd3;
            padding: 4px 8px;
            border-radius: 6px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.3;
        }

        .reason-callout-snippet.adjusted-note {
            color: #1d4ed8;
            background-color: #eff6ff;
            border-color: #bfdbfe;
        }

        /* Row Actions */
        .action-cell {
            white-space: nowrap;
            text-align: right;
        }

        .btn-action-review {
            height: 32px;
            padding: 0 13px;
            border-radius: 7px;
            background-color: #09090b;
            color: #ffffff;
            border: 1px solid #09090b;
            font-family: inherit;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.15s ease;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .btn-action-review:hover {
            background-color: #27272a;
        }

        .btn-action-view {
            height: 32px;
            padding: 0 12px;
            border-radius: 7px;
            background-color: #ffffff;
            color: var(--text-secondary);
            border: 1px solid var(--border-subtle);
            font-family: inherit;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.15s ease;
        }

        .btn-action-view:hover {
            background-color: #f4f4f5;
            color: var(--text-primary);
        }

        /* Empty State */
        .empty-state-wrap {
            padding: 60px 24px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .empty-icon-box {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background-color: #f4f4f5;
            color: #a1a1aa;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 14px;
        }

        .empty-icon-box svg {
            width: 26px;
            height: 26px;
        }

        .empty-title {
            font-size: 0.9375rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 4px;
        }

        .empty-desc {
            font-size: 0.8125rem;
            color: var(--text-muted);
            max-width: 380px;
            margin-bottom: 18px;
        }

        /* ==========================================================================
           REVIEW MODAL STYLING
           ========================================================================== */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(9, 9, 11, 0.6);
            backdrop-filter: blur(4px);
            z-index: 10000;
            align-items: center;
            justify-content: center;
            padding: 24px;
            overflow-y: auto;
        }

        .modal-overlay.open {
            display: flex;
        }

        .modal-dialog {
            background-color: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 16px;
            width: 100%;
            max-width: 960px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            display: flex;
            flex-direction: column;
            max-height: 90vh;
            overflow: hidden;
            animation: modalPopIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes modalPopIn {
            0% { transform: scale(0.97); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }

        .modal-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border-subtle);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #ffffff;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .modal-title-group {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .modal-title-row {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .modal-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.02em;
        }

        .modal-subtitle {
            font-size: 0.7813rem;
            color: var(--text-muted);
        }

        .modal-close-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: none;
            border: 1px solid var(--border-subtle);
            color: var(--text-secondary);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s;
        }

        .modal-close-btn:hover {
            background-color: #f4f4f5;
            color: var(--text-primary);
        }

        .modal-body {
            padding: 22px 24px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 20px;
            flex: 1;
            background-color: #fafafa;
        }

        /* Summary Info Card in Modal */
        .req-info-summary-card {
            background-color: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            padding: 16px 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }

        .summary-item {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .summary-label {
            font-size: 0.6875rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
        }

        .summary-value {
            font-size: 0.8438rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        /* Section Title in Modal */
        .modal-section-title {
            font-size: 0.8125rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Items Review Table in Modal */
        .review-table-card {
            background-color: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            overflow: hidden;
        }

        .review-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.8125rem;
            text-align: left;
        }

        .review-table thead th {
            background-color: #f8fafc;
            padding: 11px 16px;
            font-size: 0.6875rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border-subtle);
            white-space: nowrap;
        }

        .review-table tbody tr {
            border-bottom: 1px solid #f1f5f9;
            transition: background-color 0.1s ease;
        }

        .review-table tbody tr.row-reduced {
            background-color: #fffbeb;
        }

        .review-table tbody tr.row-zeroed {
            background-color: #fef2f2;
            opacity: 0.85;
        }

        .review-table td {
            padding: 14px 16px;
            vertical-align: middle;
        }

        .item-spec-title {
            font-weight: 600;
            color: var(--text-primary);
            line-height: 1.25;
        }

        .item-spec-variant {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 2px;
        }

        /* Warehouse Availability Badge */
        .wh-stock-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 8px;
            border-radius: 6px;
            font-family: var(--font-mono);
            font-size: 0.7188rem;
            font-weight: 600;
        }

        .wh-stock-pill.sufficient {
            background-color: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
        }

        .wh-stock-pill.low {
            background-color: #fffbeb;
            color: #b45309;
            border: 1px solid #fde68a;
        }

        .wh-stock-pill.out {
            background-color: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        /* Quantity Stepper Control */
        .qty-stepper-wrap {
            display: inline-flex;
            align-items: center;
            border: 1px solid var(--border-subtle);
            border-radius: 8px;
            background-color: #ffffff;
            overflow: hidden;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
        }

        .btn-stepper {
            width: 32px;
            height: 34px;
            background: #fafafa;
            border: none;
            color: var(--text-secondary);
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.12s;
        }

        .btn-stepper:hover {
            background: #f4f4f5;
            color: var(--text-primary);
        }

        .input-stepper-qty {
            width: 58px;
            height: 34px;
            border: none;
            border-left: 1px solid var(--border-subtle);
            border-right: 1px solid var(--border-subtle);
            text-align: center;
            font-family: var(--font-mono);
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--text-primary);
            outline: none;
            padding: 0 4px;
        }

        .input-stepper-qty:focus {
            background-color: #fff;
        }

        /* Line item quick buttons */
        .line-quick-btn-group {
            display: flex;
            gap: 4px;
            margin-top: 4px;
        }

        .btn-mini-chip {
            background: none;
            border: 1px solid var(--border-subtle);
            border-radius: 4px;
            font-size: 0.6563rem;
            font-weight: 600;
            color: var(--text-muted);
            padding: 1px 5px;
            cursor: pointer;
            transition: all 0.12s;
        }

        .btn-mini-chip:hover {
            background-color: #f4f4f5;
            color: var(--text-primary);
            border-color: var(--border-hover);
        }

        /* Diff Badge */
        .diff-status-pill {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 8px;
            border-radius: 6px;
            font-size: 0.7188rem;
            font-weight: 600;
        }

        .diff-status-pill.full {
            background-color: #f0fdf4;
            color: #15803d;
        }

        .diff-status-pill.reduced {
            background-color: #fffbeb;
            color: #b45309;
            border: 1px solid #fde68a;
        }

        .diff-status-pill.zeroed {
            background-color: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        /* ==========================================================================
           REASON SECTION (CRITICAL COMPONENT)
           ========================================================================== */
        .reason-decision-box {
            background-color: #ffffff;
            border: 2px solid var(--border-subtle);
            border-radius: 12px;
            padding: 18px 20px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            transition: all 0.2s ease;
        }

        .reason-decision-box.mandatory-active {
            border-color: #f59e0b;
            background-color: #fffdf5;
            box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1);
        }

        .reason-decision-box.reject-active {
            border-color: #e11d48;
            background-color: #fffafa;
            box-shadow: 0 0 0 4px rgba(225, 29, 72, 0.1);
        }

        .reason-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 8px;
        }

        .reason-title-stack {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .reason-title {
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .reason-required-badge {
            font-size: 0.6875rem;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 9999px;
            background-color: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
            display: none;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .reason-required-badge.visible {
            display: inline-block;
        }

        .reason-prompt-notice {
            font-size: 0.7813rem;
            color: #b45309;
            background-color: #fef3c7;
            padding: 8px 12px;
            border-radius: 8px;
            display: none;
            align-items: center;
            gap: 8px;
        }

        .reason-prompt-notice.visible {
            display: flex;
        }

        .reason-textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border-subtle);
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.8125rem;
            color: var(--text-primary);
            background-color: #ffffff;
            outline: none;
            resize: vertical;
            min-height: 72px;
            transition: all 0.15s ease;
        }

        .reason-textarea:focus {
            border-color: var(--text-primary);
            box-shadow: 0 0 0 3px rgba(9, 9, 11, 0.06);
        }

        .reason-textarea.has-error {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.15);
        }

        /* Quick Reason Suggestion Chips */
        .reason-chips-container {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .chips-label {
            font-size: 0.7188rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .chips-list {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .chip-reason-btn {
            background-color: #f4f4f5;
            border: 1px solid var(--border-subtle);
            border-radius: 9999px;
            padding: 4px 10px;
            font-size: 0.7188rem;
            font-weight: 500;
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.12s ease;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .chip-reason-btn:hover {
            background-color: #e4e4e7;
            color: var(--text-primary);
            border-color: var(--border-hover);
        }

        /* Signee / Approval Meta */
        .approval-meta-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 12px;
            background-color: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            padding: 16px 20px;
        }

        .meta-input-group {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .meta-label {
            font-size: 0.7188rem;
            font-weight: 600;
            color: var(--text-secondary);
        }

        .meta-input {
            height: 36px;
            padding: 0 10px;
            border: 1px solid var(--border-subtle);
            border-radius: 7px;
            font-family: inherit;
            font-size: 0.8125rem;
            color: var(--text-primary);
            background-color: #fafafa;
            outline: none;
        }

        .meta-input:focus {
            background-color: #fff;
            border-color: var(--text-primary);
        }

        /* Modal Footer */
        .modal-footer {
            padding: 16px 24px;
            background-color: #ffffff;
            border-top: 1px solid var(--border-subtle);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            position: sticky;
            bottom: 0;
            z-index: 10;
        }

        .footer-left-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .footer-right-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-modal-reject {
            height: 40px;
            padding: 0 18px;
            border-radius: 8px;
            background-color: #ffffff;
            border: 1px solid #fecaca;
            color: #dc2626;
            font-family: inherit;
            font-size: 0.8125rem;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.15s ease;
        }

        .btn-modal-reject:hover {
            background-color: #fef2f2;
            border-color: #f87171;
        }

        .btn-modal-cancel {
            height: 40px;
            padding: 0 16px;
            border-radius: 8px;
            background: none;
            border: 1px solid transparent;
            color: var(--text-secondary);
            font-family: inherit;
            font-size: 0.8125rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .btn-modal-cancel:hover {
            color: var(--text-primary);
            background-color: #f4f4f5;
        }

        .btn-modal-approve {
            height: 40px;
            padding: 0 20px;
            border-radius: 8px;
            background-color: #09090b;
            border: 1px solid #09090b;
            color: #ffffff;
            font-family: inherit;
            font-size: 0.8125rem;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.15s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn-modal-approve:hover {
            background-color: #27272a;
        }

        .btn-modal-issue {
            height: 40px;
            padding: 0 18px;
            border-radius: 8px;
            background-color: #059669;
            border: 1px solid #059669;
            color: #ffffff;
            font-family: inherit;
            font-size: 0.8125rem;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.15s ease;
            box-shadow: 0 2px 4px rgba(5, 150, 105, 0.15);
        }

        .btn-modal-issue:hover {
            background-color: #047857;
        }

        /* Responsive Breakpoints */
        @media (max-width: 900px) {
            .req-admin-content-area {
                padding: 20px 16px;
            }

            .modal-footer {
                flex-direction: column-reverse;
                align-items: stretch;
            }

            .footer-left-actions, .footer-right-actions {
                width: 100%;
                justify-content: stretch;
            }

            .footer-right-actions button, .footer-left-actions button {
                flex: 1;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <x-sidebar>
        <div class="req-admin-content-area">

            <!-- Page Header -->
            <div class="page-header">
                <div class="header-title-group">
                    <h1>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <line x1="16" y1="13" x2="8" y2="13"/>
                            <line x1="16" y1="17" x2="8" y2="17"/>
                            <polyline points="10 9 9 9 8 9"/>
                        </svg>
                        <span>Requisitions & Stock Requests</span>
                    </h1>
                    <p>Review submitted office requests, adjust quantities, record reasons for cuts or rejections, and issue inventory stock.</p>
                </div>

                <div class="header-actions">
                    <button type="button" class="btn-outline" onclick="window.location.reload()">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/>
                            <path d="M21 3v5h-5"/>
                            <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/>
                            <path d="M8 16H3v5"/>
                        </svg>
                        <span>Refresh List</span>
                    </button>
                    <a href="{{ route('admin.releases.index') }}" class="btn-primary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10 5a2 2 0 0 0-1.344.519l-6.328 5.74a1 1 0 0 0 0 1.481l6.328 5.741A2 2 0 0 0 10 19h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2z"/>
                            <path d="m12 9 6 6"/><path d="m18 9-6 6"/>
                        </svg>
                        <span>Direct Release</span>
                    </a>
                </div>
            </div>

            <!-- Flash Status Messages -->
            @if(session('status'))
                <div class="alert-banner" role="alert">
                    <div class="alert-content">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                            <polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                        <span>{{ session('status') }}</span>
                    </div>
                    <button type="button" class="alert-close" onclick="this.closest('.alert-banner').remove()" aria-label="Close">✕</button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert-banner error" role="alert">
                    <div class="alert-content">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="12"/>
                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                        <div>
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                    <button type="button" class="alert-close" onclick="this.closest('.alert-banner').remove()" aria-label="Close">✕</button>
                </div>
            @endif

            <!-- Metrics Overview Strip -->
            <div class="metrics-strip">
                <!-- All Requests -->
                <a href="{{ route('admin.requisitions.index') }}" class="metric-card {{ empty($statusFilter) || $statusFilter === 'all' ? 'active-tab' : '' }}">
                    <div class="metric-info">
                        <span class="metric-label">Total Requests</span>
                        <span class="metric-value">{{ number_format($totalCount) }}</span>
                        <span class="metric-subtext">All submitted RIS & PR</span>
                    </div>
                    <div class="metric-icon-box total">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="18" height="18" x="3" y="3" rx="2"/>
                            <path d="M3 9h18"/>
                            <path d="M9 21V9"/>
                        </svg>
                    </div>
                </a>

                <!-- Pending Review -->
                <a href="{{ route('admin.requisitions.index', ['status' => 'pending']) }}" class="metric-card {{ ($statusFilter ?? '') === 'pending' ? 'active-tab' : '' }}">
                    <div class="metric-info">
                        <span class="metric-label">
                            @if($pendingCount > 0)
                                <span class="pulse-dot"></span>
                            @endif
                            Pending Review
                        </span>
                        <span class="metric-value">{{ number_format($pendingCount) }}</span>
                        <span class="metric-subtext">Requires admin action</span>
                    </div>
                    <div class="metric-icon-box pending">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                    </div>
                </a>

                <!-- Approved & Adjusted -->
                <a href="{{ route('admin.requisitions.index', ['status' => 'approved']) }}" class="metric-card {{ ($statusFilter ?? '') === 'approved' ? 'active-tab' : '' }}">
                    <div class="metric-info">
                        <span class="metric-label">Approved</span>
                        <span class="metric-value">{{ number_format($approvedCount) }}</span>
                        <span class="metric-subtext">Full & adjusted approvals</span>
                    </div>
                    <div class="metric-icon-box approved">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                            <polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                    </div>
                </a>

                <!-- Issued or Rejected -->
                <a href="{{ route('admin.requisitions.index', ['status' => 'rejected']) }}" class="metric-card {{ ($statusFilter ?? '') === 'rejected' ? 'active-tab' : '' }}">
                    <div class="metric-info">
                        <span class="metric-label">Rejected / Cancelled</span>
                        <span class="metric-value">{{ number_format($rejectedCount) }}</span>
                        <span class="metric-subtext">With recorded reasons</span>
                    </div>
                    <div class="metric-icon-box rejected">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="15" y1="9" x2="9" y2="15"/>
                            <line x1="9" y1="9" x2="15" y2="15"/>
                        </svg>
                    </div>
                </a>
            </div>

            <!-- Main Requisition List Workspace Card -->
            <div class="workspace-card">
                
                <!-- Filter Toolbar -->
                <div class="filter-toolbar">
                    <form method="GET" action="{{ route('admin.requisitions.index') }}" id="filterForm">
                        <div class="filter-top-row">
                            <!-- Status Tabs -->
                            <div class="status-tabs-group">
                                <a href="{{ route('admin.requisitions.index', array_merge(request()->query(), ['status' => 'all'])) }}" 
                                   class="status-tab-btn {{ empty($statusFilter) || $statusFilter === 'all' ? 'active' : '' }}">
                                    <span>All</span>
                                    <span class="tab-badge">{{ $totalCount }}</span>
                                </a>

                                <a href="{{ route('admin.requisitions.index', array_merge(request()->query(), ['status' => 'pending'])) }}" 
                                   class="status-tab-btn {{ ($statusFilter ?? '') === 'pending' ? 'active' : '' }}">
                                    @if($pendingCount > 0)
                                        <span class="pulse-dot" style="width: 6px; height: 6px;"></span>
                                    @endif
                                    <span>Pending</span>
                                    <span class="tab-badge">{{ $pendingCount }}</span>
                                </a>

                                <a href="{{ route('admin.requisitions.index', array_merge(request()->query(), ['status' => 'approved'])) }}" 
                                   class="status-tab-btn {{ ($statusFilter ?? '') === 'approved' ? 'active' : '' }}">
                                    <span>Approved</span>
                                    <span class="tab-badge">{{ $approvedCount }}</span>
                                </a>

                                <a href="{{ route('admin.requisitions.index', array_merge(request()->query(), ['status' => 'issued'])) }}" 
                                   class="status-tab-btn {{ ($statusFilter ?? '') === 'issued' ? 'active' : '' }}">
                                    <span>Issued</span>
                                    <span class="tab-badge">{{ $issuedCount }}</span>
                                </a>

                                <a href="{{ route('admin.requisitions.index', array_merge(request()->query(), ['status' => 'rejected'])) }}" 
                                   class="status-tab-btn {{ ($statusFilter ?? '') === 'rejected' ? 'active' : '' }}">
                                    <span>Rejected</span>
                                    <span class="tab-badge">{{ $rejectedCount }}</span>
                                </a>
                            </div>

                            <!-- Keep status in form query -->
                            <input type="hidden" name="status" value="{{ $statusFilter ?? 'all' }}">
                        </div>

                        <div class="filter-bottom-row" style="margin-top: 10px;">
                            <!-- Search Field -->
                            <div class="search-box-wrap">
                                <svg class="search-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8"/>
                                    <path d="m21 21-4.3-4.3"/>
                                </svg>
                                <input type="text" 
                                       name="search" 
                                       id="liveSearchInput"
                                       class="search-input" 
                                       placeholder="Filter by Request #, RIS No, Office, Requester, or Item..." 
                                       value="{{ $search ?? '' }}">
                            </div>

                            <!-- Type Filter -->
                            <select name="type" class="filter-select" onchange="this.form.submit()">
                                <option value="all" {{ empty($typeFilter) || $typeFilter === 'all' ? 'selected' : '' }}>All Request Types</option>
                                <option value="RIS" {{ ($typeFilter ?? '') === 'RIS' ? 'selected' : '' }}>RIS (Requisition & Issue Slip)</option>
                                <option value="PR" {{ ($typeFilter ?? '') === 'PR' ? 'selected' : '' }}>PR (Purchase Request)</option>
                            </select>

                            <!-- Office Filter -->
                            <select name="office" class="filter-select" onchange="this.form.submit()">
                                <option value="all" {{ empty($officeFilter) || $officeFilter === 'all' ? 'selected' : '' }}>All Offices</option>
                                @foreach($offices as $officeItem)
                                    <option value="{{ $officeItem->id }}" {{ ($officeFilter ?? '') == $officeItem->id ? 'selected' : '' }}>
                                        {{ $officeItem->office_name }} ({{ $officeItem->office_code }})
                                    </option>
                                @endforeach
                            </select>

                            @if(!empty($search) || (!empty($typeFilter) && $typeFilter !== 'all') || (!empty($officeFilter) && $officeFilter !== 'all') || (!empty($statusFilter) && $statusFilter !== 'all'))
                                <a href="{{ route('admin.requisitions.index') }}" class="btn-reset-filter">Clear Filters</a>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Requests Table View -->
                <div class="requests-table-wrap">
                    @if($requests->isEmpty())
                        <div class="empty-state-wrap">
                            <div class="empty-icon-box">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/>
                                    <line x1="8" y1="12" x2="16" y2="12"/>
                                </svg>
                            </div>
                            <h3 class="empty-title">No Requisitions Found</h3>
                            <p class="empty-desc">No requisition requests match your selected filters or criteria. Try resetting the filters or check back later.</p>
                            <a href="{{ route('admin.requisitions.index') }}" class="btn-outline">Reset Filter View</a>
                        </div>
                    @else
                        <table class="requests-table" id="requisitionsTable">
                            <thead>
                                <tr>
                                    <th>Tracking & Form</th>
                                    <th>Requesting Custody</th>
                                    <th>Purpose & Line Items</th>
                                    <th>Date Submitted</th>
                                    <th>Status & Notes</th>
                                    <th style="text-align: right;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($requests as $req)
                                    @php
                                        $itemsCount = $req->items->count();
                                        $totalUnits = $req->items->sum('quantity_requested');
                                        $approvedUnits = $req->items->sum('quantity_issued');
                                    @endphp
                                    <tr class="req-table-row" 
                                        data-search-corpus="{{ strtolower($req->request_number . ' ' . $req->ris_no . ' ' . ($req->office?->office_name ?? '') . ' ' . ($req->office?->office_code ?? '') . ' ' . ($req->requested_by_name ?? '') . ' ' . ($req->purpose ?? '') . ' ' . $req->items->pluck('item_name')->join(' ')) }}">
                                        
                                        <!-- Tracking ID & Type -->
                                        <td>
                                            <div class="tracking-number-box">
                                                <span class="req-mono-number">
                                                    {{ $req->request_number }}
                                                </span>
                                                <div style="display: flex; gap: 6px; align-items: center;">
                                                    <span class="badge-type {{ strtolower($req->request_type) }}">
                                                        {{ $req->request_type }}
                                                    </span>
                                                    @if($req->ris_no)
                                                        <span style="font-size: 0.6875rem; color: #71717a; font-family: var(--font-mono);">
                                                            #{{ $req->ris_no }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Office & Requester -->
                                        <td>
                                            <div class="office-staff-cell">
                                                <div class="office-name-row">
                                                    <span>{{ $req->office?->office_name ?? 'Assigned Office' }}</span>
                                                    @if($req->office?->office_code)
                                                        <span class="office-code-chip">{{ $req->office->office_code }}</span>
                                                    @endif
                                                </div>
                                                <div class="staff-name-sub">
                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                                                        <circle cx="12" cy="7" r="4"/>
                                                    </svg>
                                                    <span>{{ $req->requested_by_name ?: ($req->user?->full_name ?? 'Staff') }}</span>
                                                    @if($req->requested_by_designation)
                                                        <span>• {{ $req->requested_by_designation }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Purpose & Items Summary -->
                                        <td>
                                            <div class="purpose-cell">
                                                <div class="purpose-text" title="{{ $req->purpose }}">
                                                    {{ $req->purpose ?: 'General office supply requisition' }}
                                                </div>
                                                <div class="items-preview-pill">
                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="m7.5 4.27 9 5.15"/>
                                                        <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/>
                                                    </svg>
                                                    <span>{{ $itemsCount }} {{ Str::plural('item', $itemsCount) }} ({{ $totalUnits }} units)</span>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Date -->
                                        <td>
                                            <div style="display: flex; flex-direction: column; gap: 2px;">
                                                <span style="font-weight: 600; color: var(--text-primary);">
                                                    {{ $req->created_at->format('M d, Y') }}
                                                </span>
                                                <span style="font-size: 0.7188rem; color: var(--text-muted); font-family: var(--font-mono);">
                                                    {{ $req->created_at->format('h:i A') }} • {{ $req->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                        </td>

                                        <!-- Status & Recorded Reasons -->
                                        <td>
                                            <div style="display: flex; flex-direction: column; gap: 4px;">
                                                @if($req->status === 'pending')
                                                    <span class="status-badge pending">
                                                        <span class="pulse-dot"></span>
                                                        <span>Pending Review</span>
                                                    </span>
                                                @elseif($req->status === 'approved')
                                                    <span class="status-badge approved">
                                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                                        <span>Approved in Full</span>
                                                    </span>
                                                @elseif($req->status === 'approved_with_adjustments')
                                                    <span class="status-badge approved_with_adjustments">
                                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                                                        <span>Approved (Reduced)</span>
                                                    </span>
                                                    @if($req->rejection_reason || $req->remarks)
                                                        <div class="reason-callout-snippet adjusted-note" title="{{ $req->rejection_reason ?: $req->remarks }}">
                                                            <strong>Note:</strong> {{ $req->rejection_reason ?: $req->remarks }}
                                                        </div>
                                                    @endif
                                                @elseif($req->status === 'issued')
                                                    <span class="status-badge issued">
                                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                                        <span>Issued & Released</span>
                                                    </span>
                                                @elseif($req->status === 'rejected')
                                                    <span class="status-badge rejected">
                                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                                                        <span>Rejected</span>
                                                    </span>
                                                    @if($req->rejection_reason)
                                                        <div class="reason-callout-snippet" title="{{ $req->rejection_reason }}">
                                                            <strong>Reason:</strong> {{ $req->rejection_reason }}
                                                        </div>
                                                    @endif
                                                @else
                                                    <span class="status-badge">
                                                        {{ ucfirst($req->status) }}
                                                    </span>
                                                @endif
                                            </div>
                                        </td>

                                        <!-- Action Column -->
                                        <td class="action-cell">
                                            @if($req->status === 'pending')
                                                <button type="button" 
                                                        class="btn-action-review" 
                                                        onclick="openReviewModal({{ json_encode($req) }})">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                    </svg>
                                                    <span>Review & Process</span>
                                                </button>
                                            @else
                                                <button type="button" 
                                                        class="btn-action-view" 
                                                        onclick="openReviewModal({{ json_encode($req) }}, true)">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                                        <circle cx="12" cy="12" r="3"/>
                                                    </svg>
                                                    <span>View Details</span>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

        </div>
    </x-sidebar>

    <!-- ==========================================================================
         CONFIRM REVIEW & EDIT QUANTITY MODAL
         ========================================================================== -->
    <div class="modal-overlay" id="reviewModalOverlay" aria-modal="true" role="dialog">
        <div class="modal-dialog">
            
            <!-- Modal Header -->
            <div class="modal-header">
                <div class="modal-title-group">
                    <div class="modal-title-row">
                        <h2 class="modal-title" id="modalTitle">Review & Process Requisition</h2>
                        <span class="badge-type ris" id="modalTypeBadge">RIS</span>
                        <span class="status-badge pending" id="modalStatusBadge">Pending</span>
                    </div>
                    <span class="modal-subtitle">
                        Tracking Number: <strong id="modalReqNumber" style="font-family: var(--font-mono); color: var(--text-primary);">RIS-XXXX</strong>
                    </span>
                </div>
                <button type="button" class="modal-close-btn" onclick="closeReviewModal()" aria-label="Close modal">✕</button>
            </div>

            <!-- Review Form -->
            <form id="reviewActionForm" method="POST" action="">
                @csrf
                <input type="hidden" name="action" id="formActionInput" value="approve">

                <div class="modal-body">
                    
                    <!-- Requester & Office Context Strip -->
                    <div class="req-info-summary-card">
                        <div class="summary-item">
                            <span class="summary-label">Requesting Office</span>
                            <span class="summary-value" id="modalOfficeName">Office Name</span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label">Requester & Designation</span>
                            <span class="summary-value" id="modalRequesterName">Requester Name</span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label">Date Submitted</span>
                            <span class="summary-value" id="modalSubmittedDate">Date</span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label">Requisition Purpose</span>
                            <span class="summary-value" id="modalPurposeText" style="font-weight: 500; color: var(--text-secondary);">Purpose</span>
                        </div>
                    </div>

                    <!-- Items Review Table Header -->
                    <div class="modal-section-title">
                        <span>Line Items & Stock Availability</span>
                        <div style="display: flex; gap: 8px;">
                            <button type="button" class="btn-mini-chip" id="btnMatchAllRequested" onclick="matchAllRequestedQuantities()">
                                Reset All to Requested
                            </button>
                        </div>
                    </div>

                    <div style="font-size: 0.78rem; color: #64748b; margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                        <span>Approving or issuing will deduct approved quantities from office custody and officially release stock from main warehouse inventory.</span>
                    </div>

                    <!-- Items Review Table Card -->
                    <div class="review-table-card">
                        <table class="review-table">
                            <thead>
                                <tr>
                                    <th>Item Description</th>
                                    <th>Stock No</th>
                                    <th>Stock (Main & Office)</th>
                                    <th style="text-align: center;">Requested</th>
                                    <th style="text-align: center; width: 170px;">Approved Quantity</th>
                                    <th style="text-align: center;">Status</th>
                                </tr>
                            </thead>
                            <tbody id="modalItemsTableBody">
                                <!-- Dynamically populated by openReviewModal -->
                            </tbody>
                        </table>
                    </div>

                    <!-- REASON SECTION (MANDATORY WHEN REDUCED OR REJECTED) -->
                    <div class="reason-decision-box" id="reasonDecisionBox">
                        <div class="reason-header">
                            <div class="reason-title-stack">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="color: #d97706;">
                                    <path d="M12 9v4"/><path d="M12 17h.01"/>
                                    <path d="M3.34 19A2 2 0 0 0 5 22h14a2 2 0 0 0 1.66-3L13.66 4a2 2 0 0 0-3.32 0z"/>
                                </svg>
                                <span class="reason-title">Justification Reason for Reduction or Rejection</span>
                                <span class="reason-required-badge" id="reasonRequiredBadge">* Required</span>
                            </div>
                        </div>

                        <!-- Dynamic Alert Prompts -->
                        <div class="reason-prompt-notice" id="reductionNotice">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            <span><strong>Quantity Reduction Detected:</strong> One or more items have had quantities reduced or excluded. You <strong>must provide a reason</strong> so the requesting office is transparently notified.</span>
                        </div>

                        <div class="reason-prompt-notice" id="rejectionNotice" style="background-color: #fee2e2; color: #991b1b;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                            <span><strong>Rejection Justification:</strong> You are rejecting this requisition. Please clearly explain why this request cannot be fulfilled.</span>
                        </div>

                        <!-- Reason Textarea -->
                        <textarea name="reason" 
                                  id="reviewReasonTextarea" 
                                  class="reason-textarea" 
                                  placeholder="State the justification reason why requested quantity is reduced or request is rejected (e.g., Limited buffer stock, Office quarterly quota reached, Items pending supplier delivery...)"
                                  rows="3"></textarea>

                        <!-- Quick Reason Chips -->
                        <div class="reason-chips-container" id="reasonChipsWrap">
                            <span class="chips-label">Quick Suggestion Reasons (Click to append):</span>
                            <div class="chips-list">
                                <button type="button" class="chip-reason-btn" onclick="applyReasonChip('Buffer stock limited in warehouse')">
                                    + Buffer stock limited
                                </button>
                                <button type="button" class="chip-reason-btn" onclick="applyReasonChip('Exceeds quarterly office allocation quota')">
                                    + Exceeds office quota
                                </button>
                                <button type="button" class="chip-reason-btn" onclick="applyReasonChip('Partial allocation pending replenishment arrival')">
                                    + Supplier delivery pending
                                </button>
                                <button type="button" class="chip-reason-btn" onclick="applyReasonChip('Item temporarily depleted - reorder in progress')">
                                    + Item out of stock
                                </button>
                                <button type="button" class="chip-reason-btn" onclick="applyReasonChip('Reserved for emergency / calamity response buffer')">
                                    + Emergency reserve
                                </button>
                                <button type="button" class="chip-reason-btn" onclick="applyReasonChip('Duplicate or superseded requisition slip')">
                                    + Duplicate request
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Approver Details -->
                    <div class="approval-meta-grid" id="approverMetaSection">
                        <div class="meta-input-group">
                            <label class="meta-label">Approved By (Official Signee)</label>
                            <input type="text" 
                                   name="approved_by_name" 
                                   id="inputApproverName" 
                                   class="meta-input" 
                                   value="{{ auth()->user()->full_name ?? auth()->user()->name }}" 
                                   placeholder="Full Name">
                        </div>
                        <div class="meta-input-group">
                            <label class="meta-label">Signee Designation</label>
                            <input type="text" 
                                   name="approved_by_designation" 
                                   id="inputApproverDesignation" 
                                   class="meta-input" 
                                   value="PGDH - PGSO" 
                                   placeholder="Designation">
                        </div>
                        <div class="meta-input-group">
                            <label class="meta-label">General Office Remarks (Optional)</label>
                            <input type="text" 
                                   name="remarks" 
                                   id="inputGeneralRemarks" 
                                   class="meta-input" 
                                   placeholder="Warehouse notes / instructions">
                        </div>
                    </div>

                </div>

                <!-- Modal Actions Footer -->
                <div class="modal-footer">
                    <div class="footer-left-actions">
                        <button type="button" 
                                id="btnRejectRequest" 
                                class="btn-modal-reject" 
                                onclick="submitRejection()">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                            <span>Reject Requisition</span>
                        </button>
                    </div>

                    <div class="footer-right-actions">
                        <button type="button" class="btn-modal-cancel" onclick="closeReviewModal()">
                            Cancel
                        </button>

                        <button type="button" 
                                id="btnApproveRequest" 
                                class="btn-modal-approve" 
                                onclick="submitApproval('approve')">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                            <span id="btnApproveText">Approve Requisition</span>
                        </button>

                        <button type="button" 
                                id="btnIssueRequest" 
                                class="btn-modal-issue" 
                                onclick="submitApproval('issue')">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="16" height="13" x="1" y="3" rx="2"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                            <span>Approve & Issue</span>
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <!-- ==========================================================================
         JAVASCRIPT LOGIC
         ========================================================================== -->
    <script>
        // Modal and form elements
        const reviewModalOverlay = document.getElementById('reviewModalOverlay');
        const reviewActionForm = document.getElementById('reviewActionForm');
        const formActionInput = document.getElementById('formActionInput');
        
        const modalTitle = document.getElementById('modalTitle');
        const modalTypeBadge = document.getElementById('modalTypeBadge');
        const modalStatusBadge = document.getElementById('modalStatusBadge');
        const modalReqNumber = document.getElementById('modalReqNumber');
        const modalOfficeName = document.getElementById('modalOfficeName');
        const modalRequesterName = document.getElementById('modalRequesterName');
        const modalSubmittedDate = document.getElementById('modalSubmittedDate');
        const modalPurposeText = document.getElementById('modalPurposeText');
        const modalItemsTableBody = document.getElementById('modalItemsTableBody');
        
        const reasonDecisionBox = document.getElementById('reasonDecisionBox');
        const reasonRequiredBadge = document.getElementById('reasonRequiredBadge');
        const reductionNotice = document.getElementById('reductionNotice');
        const rejectionNotice = document.getElementById('rejectionNotice');
        const reviewReasonTextarea = document.getElementById('reviewReasonTextarea');
        const reasonChipsWrap = document.getElementById('reasonChipsWrap');
        
        const btnRejectRequest = document.getElementById('btnRejectRequest');
        const btnApproveRequest = document.getElementById('btnApproveRequest');
        const btnApproveText = document.getElementById('btnApproveText');
        const btnIssueRequest = document.getElementById('btnIssueRequest');
        const btnMatchAllRequested = document.getElementById('btnMatchAllRequested');
        const approverMetaSection = document.getElementById('approverMetaSection');

        let currentActiveRequest = null;
        let isReadOnlyView = false;

        /**
         * Open the Review Modal for a given requisition object
         */
        function openReviewModal(request, readOnly = false) {
            if (!request) return;
            currentActiveRequest = request;
            isReadOnlyView = readOnly || request.status !== 'pending';

            // Populate Request Header & Info
            modalTitle.textContent = isReadOnlyView ? 'Requisition Details' : 'Review & Process Requisition';
            modalReqNumber.textContent = request.request_number;
            
            modalTypeBadge.textContent = request.request_type || 'RIS';
            modalTypeBadge.className = `badge-type ${(request.request_type || 'ris').toLowerCase()}`;
            
            modalStatusBadge.textContent = formatStatusLabel(request.status);
            modalStatusBadge.className = `status-badge ${request.status}`;

            modalOfficeName.textContent = request.office ? `${request.office.office_name}` : 'General Office';
            modalRequesterName.textContent = request.requested_by_name || (request.user ? `${request.user.first_name} ${request.user.last_name}` : 'Staff');
            if (request.requested_by_designation) {
                modalRequesterName.textContent += ` • ${request.requested_by_designation}`;
            }

            modalSubmittedDate.textContent = new Date(request.created_at).toLocaleDateString('en-US', {
                year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit'
            });
            modalPurposeText.textContent = request.purpose || 'No purpose specification entered';

            // Set Form Action URL
            reviewActionForm.action = `/admin/requisitions/${request.id}/review`;

            // Reset Reason & Validation styling
            reviewReasonTextarea.value = request.rejection_reason || request.remarks || '';
            reviewReasonTextarea.classList.remove('has-error');

            // Render Table Line Items
            renderReviewItems(request.items || []);

            // Handle read-only controls state
            if (isReadOnlyView) {
                btnRejectRequest.style.display = 'none';
                btnApproveRequest.style.display = 'none';
                btnIssueRequest.style.display = 'none';
                btnMatchAllRequested.style.display = 'none';
                reasonChipsWrap.style.display = 'none';
                reviewReasonTextarea.disabled = true;
                approverMetaSection.style.display = 'none';
            } else {
                btnRejectRequest.style.display = 'inline-flex';
                btnApproveRequest.style.display = 'inline-flex';
                btnIssueRequest.style.display = 'inline-flex';
                btnMatchAllRequested.style.display = 'inline-flex';
                reasonChipsWrap.style.display = 'flex';
                reviewReasonTextarea.disabled = false;
                approverMetaSection.style.display = 'grid';
            }

            checkReductionState();

            reviewModalOverlay.classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        /**
         * Render each item into the review table
         */
        function renderReviewItems(items) {
            modalItemsTableBody.innerHTML = '';

            if (items.length === 0) {
                modalItemsTableBody.innerHTML = `
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 24px; color: #a1a1aa;">
                            No items attached to this requisition.
                        </td>
                    </tr>
                `;
                return;
            }

            items.forEach((item, index) => {
                const tr = document.createElement('tr');
                tr.id = `itemRow_${item.id}`;
                tr.setAttribute('data-requested', item.quantity_requested);

                const currentWarehouseQty = item.variant ? item.variant.quantity : 0;
                const requestedQty = item.quantity_requested;
                const initialApprovedQty = item.quantity_issued > 0 ? item.quantity_issued : (currentActiveRequest.status === 'pending' ? requestedQty : item.quantity_issued);

                let currentOfficeQty = 0;
                if (item.variant && Array.isArray(item.variant.office_inventories) && currentActiveRequest) {
                    const oi = item.variant.office_inventories.find(o => o.office_id === currentActiveRequest.office_id);
                    if (oi) {
                        currentOfficeQty = parseInt(oi.quantity) || 0;
                    }
                }

                // Stock pill styling
                let stockPillClass = 'sufficient';
                let stockPillLabel = `${currentWarehouseQty} in main`;
                if (currentWarehouseQty <= 0) {
                    stockPillClass = 'out';
                    stockPillLabel = 'Out of Stock (0)';
                } else if (currentWarehouseQty < requestedQty) {
                    stockPillClass = 'low';
                    stockPillLabel = `Low: ${currentWarehouseQty} in main`;
                }

                tr.innerHTML = `
                    <!-- Item Name & Variant -->
                    <td>
                        <input type="hidden" name="items[${index}][id]" value="${item.id}">
                        <div class="item-spec-title">${escapeHtml(item.item_name)}</div>
                        <div class="item-spec-variant">${escapeHtml(item.variant_name || 'Standard specification')}</div>
                    </td>

                    <!-- SKU / Stock No -->
                    <td style="font-family: var(--font-mono); font-size: 0.75rem; color: #71717a;">
                        ${escapeHtml(item.stock_no || 'N/A')}
                    </td>

                    <!-- Stock in Main Warehouse & Office Custody -->
                    <td>
                        <div style="display: flex; flex-direction: column; gap: 4px;">
                            <span class="wh-stock-pill ${stockPillClass}">
                                ${stockPillLabel}
                            </span>
                            <span style="font-size: 0.72rem; color: #71717a; font-family: var(--font-mono);">
                                Office Custody: <strong style="color: ${currentOfficeQty > 0 ? '#10b981' : '#a1a1aa'};">${currentOfficeQty} ${escapeHtml(item.unit || 'pcs')}</strong>
                            </span>
                        </div>
                    </td>

                    <!-- Requested Qty -->
                    <td style="text-align: center; font-weight: 700; font-family: var(--font-mono);">
                        ${requestedQty} <span style="font-weight: 400; font-size: 0.75rem; color: #71717a;">${escapeHtml(item.unit || 'pcs')}</span>
                    </td>

                    <!-- Approved Quantity (Stepper) -->
                    <td style="text-align: center;">
                        ${isReadOnlyView ? `
                            <div style="font-family: var(--font-mono); font-weight: 700; font-size: 0.9375rem; color: ${initialApprovedQty < requestedQty ? '#b45309' : '#059669'};">
                                ${initialApprovedQty} ${escapeHtml(item.unit || 'pcs')}
                            </div>
                        ` : `
                            <div class="qty-stepper-wrap">
                                <button type="button" class="btn-stepper" onclick="adjustItemQty(${item.id}, -1)">−</button>
                                <input type="number" 
                                       name="items[${index}][quantity]" 
                                       id="qtyInput_${item.id}"
                                       class="input-stepper-qty" 
                                       value="${initialApprovedQty}" 
                                       min="0" 
                                       max="${requestedQty}" 
                                       oninput="handleQtyInputChange(${item.id})">
                                <button type="button" class="btn-stepper" onclick="adjustItemQty(${item.id}, 1)">+</button>
                            </div>
                            <div class="line-quick-btn-group" style="justify-content: center;">
                                <button type="button" class="btn-mini-chip" onclick="setItemQtyDirect(${item.id}, ${requestedQty})">Full (${requestedQty})</button>
                                ${currentWarehouseQty < requestedQty && currentWarehouseQty > 0 ? `
                                    <button type="button" class="btn-mini-chip" onclick="setItemQtyDirect(${item.id}, ${currentWarehouseQty})">Max Stock (${currentWarehouseQty})</button>
                                ` : ''}
                                <button type="button" class="btn-mini-chip" onclick="setItemQtyDirect(${item.id}, 0)">Zero (0)</button>
                            </div>
                        `}
                    </td>

                    <!-- Status / Diff Badge -->
                    <td style="text-align: center;">
                        <span id="diffBadge_${item.id}" class="diff-status-pill full">
                            ✓ Full Approval
                        </span>
                    </td>
                `;

                modalItemsTableBody.appendChild(tr);
                updateItemDiffBadge(item.id, initialApprovedQty, requestedQty);
            });
        }

        /**
         * Update an item's quantity stepper
         */
        function adjustItemQty(itemId, delta) {
            const input = document.getElementById(`qtyInput_${itemId}`);
            if (!input) return;
            const current = parseInt(input.value) || 0;
            const row = document.getElementById(`itemRow_${itemId}`);
            const max = parseInt(row.getAttribute('data-requested')) || 9999;
            const next = Math.max(0, Math.min(max, current + delta));
            input.value = next;
            handleQtyInputChange(itemId);
        }

        function setItemQtyDirect(itemId, val) {
            const input = document.getElementById(`qtyInput_${itemId}`);
            if (!input) return;
            input.value = val;
            handleQtyInputChange(itemId);
        }

        function handleQtyInputChange(itemId) {
            const input = document.getElementById(`qtyInput_${itemId}`);
            const row = document.getElementById(`itemRow_${itemId}`);
            if (!input || !row) return;

            const requested = parseInt(row.getAttribute('data-requested')) || 0;
            let current = parseInt(input.value);

            if (isNaN(current) || current < 0) {
                current = 0;
                input.value = 0;
            }

            updateItemDiffBadge(itemId, current, requested);
            checkReductionState();
        }

        function updateItemDiffBadge(itemId, current, requested) {
            const badge = document.getElementById(`diffBadge_${itemId}`);
            const row = document.getElementById(`itemRow_${itemId}`);
            if (!badge || !row) return;

            row.classList.remove('row-reduced', 'row-zeroed');

            if (current === requested) {
                badge.className = 'diff-status-pill full';
                badge.innerHTML = `✓ Full (${current})`;
            } else if (current === 0) {
                row.classList.add('row-zeroed');
                badge.className = 'diff-status-pill zeroed';
                badge.innerHTML = `✕ Excluded (0)`;
            } else if (current < requested) {
                row.classList.add('row-reduced');
                badge.className = 'diff-status-pill reduced';
                badge.innerHTML = `⚠ Cut by ${requested - current}`;
            } else {
                badge.className = 'diff-status-pill';
                badge.innerHTML = `${current} units`;
            }
        }

        function matchAllRequestedQuantities() {
            if (!currentActiveRequest || !currentActiveRequest.items) return;
            currentActiveRequest.items.forEach(item => {
                setItemQtyDirect(item.id, item.quantity_requested);
            });
        }

        /**
         * Check if any item has been reduced and toggle reason requirement
         */
        function checkReductionState() {
            if (isReadOnlyView) return false;

            let hasReduction = false;
            let allZero = true;

            const rows = modalItemsTableBody.querySelectorAll('tr[id^="itemRow_"]');
            rows.forEach(row => {
                const requested = parseInt(row.getAttribute('data-requested')) || 0;
                const input = row.querySelector('.input-stepper-qty');
                if (input) {
                    const current = parseInt(input.value) || 0;
                    if (current < requested) {
                        hasReduction = true;
                    }
                    if (current > 0) {
                        allZero = false;
                    }
                }
            });

            // If quantity reduction exists:
            if (hasReduction) {
                reasonDecisionBox.classList.add('mandatory-active');
                reasonRequiredBadge.classList.add('visible');
                reductionNotice.classList.add('visible');
                btnApproveText.textContent = 'Approve with Reductions';
            } else {
                reasonDecisionBox.classList.remove('mandatory-active');
                reasonRequiredBadge.classList.remove('visible');
                reductionNotice.classList.remove('visible');
                btnApproveText.textContent = 'Approve Requisition';
            }

            return hasReduction;
        }

        /**
         * Append reason chip text to the textarea
         */
        function applyReasonChip(chipText) {
            const current = reviewReasonTextarea.value.trim();
            if (current.length === 0) {
                reviewReasonTextarea.value = chipText;
            } else if (!current.includes(chipText)) {
                reviewReasonTextarea.value = current + "; " + chipText;
            }
            reviewReasonTextarea.classList.remove('has-error');
            reviewReasonTextarea.focus();
        }

        /**
         * Submit Approval (Full or Reduced) or Direct Issuance
         */
        function submitApproval(actionType) {
            formActionInput.value = actionType;
            const hasReduction = checkReductionState();
            const reason = reviewReasonTextarea.value.trim();

            // Strict Validation: If quantity is reduced, reason MUST NOT be empty
            if (hasReduction && reason.length === 0) {
                reviewReasonTextarea.classList.add('has-error');
                reasonDecisionBox.classList.add('mandatory-active');
                reviewReasonTextarea.focus();
                alert('Please state a reason why one or more requested item quantities were reduced before confirming approval.');
                return;
            }

            const confirmMsg = actionType === 'issue'
                ? 'Confirm approval and mark this requisition as ISSUED? Approved stock will be deducted from office custody and released from warehouse inventory.'
                : (hasReduction
                    ? 'Confirm approval of this requisition with ADJUSTED quantities? Approved stock will be deducted from office custody and released from warehouse inventory.'
                    : 'Confirm full approval of this requisition? Approved stock will be deducted from office custody and released from warehouse inventory.');

            if (confirm(confirmMsg)) {
                reviewActionForm.submit();
            }
        }

        /**
         * Submit Rejection with Reason Validation
         */
        function submitRejection() {
            formActionInput.value = 'reject';
            reasonDecisionBox.classList.add('reject-active');
            rejectionNotice.classList.add('visible');
            reasonRequiredBadge.classList.add('visible');

            const reason = reviewReasonTextarea.value.trim();

            if (reason.length === 0) {
                reviewReasonTextarea.classList.add('has-error');
                reviewReasonTextarea.focus();
                alert('A clear justification reason is REQUIRED when rejecting a requisition request.');
                return;
            }

            if (confirm(`Are you sure you want to REJECT requisition #${currentActiveRequest.request_number}?\n\nReason: "${reason}"`)) {
                reviewActionForm.submit();
            }
        }

        function closeReviewModal() {
            reviewModalOverlay.classList.remove('open');
            document.body.style.overflow = '';
            currentActiveRequest = null;
        }

        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && reviewModalOverlay.classList.contains('open')) {
                closeReviewModal();
            }
        });

        // Close when clicking modal backdrop
        reviewModalOverlay.addEventListener('click', function(e) {
            if (e.target === reviewModalOverlay) {
                closeReviewModal();
            }
        });

        // Instant Live Search Filter in table
        const liveSearchInput = document.getElementById('liveSearchInput');
        if (liveSearchInput) {
            liveSearchInput.addEventListener('input', function() {
                const term = this.value.toLowerCase().trim();
                const rows = document.querySelectorAll('.req-table-row');
                rows.forEach(row => {
                    const corpus = row.getAttribute('data-search-corpus') || '';
                    if (term === '' || corpus.includes(term)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }

        function formatStatusLabel(status) {
            switch(status) {
                case 'pending': return 'Pending Review';
                case 'approved': return 'Approved';
                case 'approved_with_adjustments': return 'Approved (Reduced)';
                case 'issued': return 'Issued';
                case 'rejected': return 'Rejected';
                default: return status ? status.toUpperCase() : 'Pending';
            }
        }

        function escapeHtml(text) {
            if (!text) return '';
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.toString().replace(/[&<>"']/g, function(m) { return map[m]; });
        }
    </script>
</body>
</html>
