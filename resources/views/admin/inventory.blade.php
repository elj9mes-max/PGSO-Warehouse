<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PGSO Warehouse - Inventory Records</title>
    
    <!-- Modern Sans Font for SaaS Aesthetic -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

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
            --badge-bg: #f4f4f5;
            --badge-text: #18181b;
            --status-green: #10b981;
            --status-amber: #f59e0b;
            --status-red: #ef4444;
            --font-main: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
        }

        body {
            font-family: var(--font-main);
            background-color: var(--bg-page);
            color: var(--text-primary);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        .inventory-content-area {
            padding: 32px 36px;
            max-width: 1360px;
            margin: 0 auto;
            width: 100%;
        }

        /* Header Section */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .header-title-group h1 {
            font-size: 1.75rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            color: var(--text-primary);
            line-height: 1.2;
        }

        .header-title-group p {
            font-size: 0.875rem;
            color: var(--text-muted);
            margin-top: 4px;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-outline {
            height: 40px;
            padding: 0 16px;
            background-color: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 8px;
            color: var(--text-secondary);
            font-family: inherit;
            font-size: 0.8438rem;
            font-weight: 600;
            cursor: pointer;
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
            border-radius: 8px;
            color: var(--btn-text);
            font-family: inherit;
            font-size: 0.8438rem;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.15s ease;
        }

        .btn-primary:hover {
            background-color: var(--btn-hover);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .btn-primary svg, .btn-outline svg {
            width: 16px;
            height: 16px;
        }

        /* Metric Cards Grid */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .metric-card {
            background-color: var(--bg-surface);
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            padding: 18px 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
            transition: border-color 0.15s ease;
        }

        .metric-card:hover {
            border-color: var(--border-hover);
        }

        .metric-label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--text-muted);
        }

        .metric-number {
            font-size: 1.625rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: var(--text-primary);
            margin: 6px 0 2px 0;
        }

        .metric-desc {
            font-size: 0.75rem;
            color: var(--text-secondary);
        }

        /* Filter & Search Bar */
        .filter-panel {
            background-color: var(--bg-surface);
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
        }

        .search-box {
            position: relative;
            flex: 1;
            min-width: 260px;
        }

        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #a1a1aa;
            display: flex;
            align-items: center;
            pointer-events: none;
        }

        .search-icon svg {
            width: 16px;
            height: 16px;
        }

        .search-input {
            width: 100%;
            height: 38px;
            padding: 0 14px 0 36px;
            background-color: #fafafa;
            border: 1px solid var(--border-subtle);
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.8438rem;
            color: var(--text-primary);
            outline: none;
            transition: all 0.15s ease;
        }

        .search-input:focus {
            background-color: #ffffff;
            border-color: var(--text-primary);
            box-shadow: 0 0 0 3px rgba(9, 9, 11, 0.06);
        }

        .clear-search-btn {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #a1a1aa;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2px;
            border-radius: 4px;
        }

        .clear-search-btn:hover {
            color: #09090b;
        }

        .clear-search-btn svg {
            width: 14px;
            height: 14px;
        }

        /* View Mode Switcher Pills */
        .view-mode-toggle-group {
            display: inline-flex;
            align-items: center;
            background: #f4f4f5;
            padding: 3px;
            border-radius: 9px;
            gap: 2px;
            border: 1px solid var(--border-subtle);
        }

        .view-mode-btn {
            background: transparent;
            border: none;
            padding: 6px 12px;
            border-radius: 7px;
            font-size: 0.7813rem;
            font-weight: 600;
            color: #71717a;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.15s ease;
            font-family: inherit;
        }

        .view-mode-btn:hover {
            color: #09090b;
        }

        .view-mode-btn.active {
            background: #ffffff;
            color: #09090b;
            font-weight: 700;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        .mode-badge {
            font-size: 0.6875rem;
            font-weight: 700;
            background: #e4e4e7;
            color: #3f3f46;
            padding: 1px 6px;
            border-radius: 9999px;
            line-height: 1.2;
        }

        .view-mode-btn.active .mode-badge {
            background: #09090b;
            color: #ffffff;
        }

        .view-mode-btn.highlight-active {
            background: #ecfdf5 !important;
            color: #065f46 !important;
            border: 1px solid #a7f3d0 !important;
        }

        .view-mode-btn.highlight-active .mode-badge {
            background: #059669 !important;
            color: #ffffff !important;
        }

        .color-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            flex-shrink: 0;
            border: 1px solid rgba(0, 0, 0, 0.1);
        }

        .badge-variant-tag {
            font-size: 0.6563rem;
            font-weight: 700;
            background: #eff6ff;
            color: #2563eb;
            padding: 1px 6px;
            border-radius: 4px;
            border: 1px solid #bfdbfe;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .filters-group {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .filters-group .pgso-dropdown-search-wrapper {
            position: relative;
            width: 100%;
        }

        .filters-group .pgso-ds-trigger {
            height: 38px;
            min-height: 38px;
            padding: 0 12px;
            border-radius: 8px;
            font-size: 0.8125rem;
            border: 1px solid var(--border-subtle);
            background-color: #fafafa;
            transition: all 0.15s ease;
            width: 500px;
        }

        .filters-group .pgso-ds-trigger:hover,
        .filters-group .pgso-ds-trigger.active {
            background-color: #ffffff;
            border-color: var(--text-primary);
        }

        .select-filter {
            height: 38px;
            padding: 0 32px 0 12px;
            background-color: #fafafa;
            border: 1px solid var(--border-subtle);
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.8125rem;
            font-weight: 500;
            color: var(--text-primary);
            outline: none;
            cursor: pointer;
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2371717a' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 14px;
            transition: all 0.15s ease;
        }

        .select-filter:focus {
            background-color: #ffffff;
            border-color: var(--text-primary);
        }

        /* Table Card Container */
        .table-card {
            background-color: var(--bg-surface);
            border: 1px solid var(--border-subtle);
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
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
            background-color: #fafafa;
            color: var(--text-muted);
            font-size: 0.7188rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 14px 16px;
            border-bottom: 1px solid var(--border-subtle);
            white-space: nowrap;
        }

        .inventory-table td {
            padding: 14px 16px;
            border-bottom: 1px solid #f4f4f5;
            color: var(--text-primary);
            vertical-align: middle;
        }

        .inventory-table tbody tr {
            transition: background-color 0.15s ease;
        }

        .inventory-table tbody tr:hover {
            background-color: #fafafa;
        }

        /* Item Code Pill */
        .code-pill {
            display: inline-block;
            font-family: var(--font-mono);
            font-size: 0.75rem;
            font-weight: 600;
            color: #18181b;
            background-color: #f4f4f5;
            padding: 2px 8px;
            border-radius: 6px;
            border: 1px solid #e4e4e7;
            white-space: nowrap;
        }

        /* Item Title & Subtext */
        .item-name-cell {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .item-main-title {
            font-weight: 600;
            color: var(--text-primary);
        }

        .item-main-link {
            font-weight: 600;
            color: var(--text-primary);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: color 0.15s ease;
        }

        .item-main-link:hover {
            color: #2563eb;
            text-decoration: underline;
        }

        .variant-pill-badge {
            display: inline-flex;
            align-items: center;
            background-color: #f1f5f9;
            color: #0f172a;
            border: 1px solid #cbd5e1;
            font-size: 0.6875rem;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 9999px;
            letter-spacing: 0.02em;
        }

        .item-spec-sub {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        /* Category Badge */
        .category-pill {
            display: inline-block;
            font-size: 0.6875rem;
            font-weight: 600;
            color: var(--text-secondary);
            background-color: #f4f4f5;
            padding: 3px 8px;
            border-radius: 9999px;
            white-space: nowrap;
        }

        /* Location Tag */
        .location-tag {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 0.7813rem;
            color: var(--text-secondary);
            white-space: nowrap;
        }

        .location-tag svg {
            width: 13px;
            height: 13px;
            color: #a1a1aa;
        }

        /* Quantity formatting */
        .qty-strong {
            font-weight: 700;
            color: var(--text-primary);
        }

        .qty-unit {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-left: 2px;
        }

        /* Status Pills */
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.7188rem;
            font-weight: 700;
            padding: 3px 9px;
            border-radius: 9999px;
            white-space: nowrap;
        }

        .status-pill.in-stock {
            background-color: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .status-pill.low-stock {
            background-color: #fffbeb;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        .status-pill.out-of-stock {
            background-color: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .status-dot-sm {
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }

        .status-pill.in-stock .status-dot-sm { background-color: var(--status-green); }
        .status-pill.low-stock .status-dot-sm { background-color: var(--status-amber); }
        .status-pill.out-of-stock .status-dot-sm { background-color: var(--status-red); }

        /* Actions in Table */
        .table-actions {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .action-icon-btn {
            background: none;
            border: 1px solid transparent;
            border-radius: 6px;
            padding: 6px;
            color: var(--text-muted);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s ease;
        }

        .action-icon-btn:hover {
            color: var(--text-primary);
            background-color: #f4f4f5;
            border-color: var(--border-subtle);
        }

        .action-icon-btn svg {
            width: 15px;
            height: 15px;
        }

        /* Pagination Bar */
        .table-pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 20px;
            background-color: #fafafa;
            border-top: 1px solid var(--border-subtle);
            font-size: 0.8125rem;
            color: var(--text-secondary);
            flex-wrap: wrap;
            gap: 12px;
        }

        .pagination-controls {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .page-btn {
            height: 32px;
            padding: 0 10px;
            background-color: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 6px;
            font-family: inherit;
            font-size: 0.8125rem;
            font-weight: 500;
            color: var(--text-primary);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s ease;
        }

        .page-btn:hover:not(:disabled) {
            border-color: var(--text-primary);
        }

        .page-btn.active {
            background-color: var(--text-primary);
            color: #ffffff;
            border-color: var(--text-primary);
            font-weight: 700;
        }

        .page-btn:disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }

        /* Modal Overlay & Styling */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(9, 9, 11, 0.45);
            backdrop-filter: blur(2px);
            z-index: 10000;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-dialog {
            background-color: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 16px;
            width: 100%;
            max-width: 540px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            animation: modalFadeIn 0.2s ease-out;
        }

        @keyframes modalFadeIn {
            from { opacity: 0; transform: scale(0.97); }
            to { opacity: 1; transform: scale(1); }
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
            color: var(--text-primary);
        }

        .modal-close-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-muted);
            padding: 4px;
            border-radius: 6px;
            display: flex;
            align-items: center;
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
            max-height: 75vh;
            overflow-y: auto;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
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
            height: 40px;
            padding: 0 12px;
            border: 1px solid var(--border-subtle);
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.8438rem;
            color: var(--text-primary);
            outline: none;
            transition: all 0.15s ease;
        }

        .modal-input:focus {
            border-color: var(--text-primary);
            box-shadow: 0 0 0 3px rgba(9, 9, 11, 0.08);
        }

        /* Category Action & Input Styles */
        .modal-label-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .btn-category-add-link {
            background: none;
            border: none;
            padding: 0;
            font-size: 0.75rem;
            font-weight: 600;
            color: #2563eb;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: color 0.15s ease;
        }

        .btn-category-add-link:hover {
            color: #1d4ed8;
            text-decoration: underline;
        }

        .btn-category-add-link svg {
            width: 12px;
            height: 12px;
        }

        .category-select-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
            width: 100%;
        }

        .category-select-wrapper select,
        .category-select-wrapper .pgso-dropdown-search-wrapper {
            flex: 1;
            min-width: 0;
            position: relative;
            z-index: 20;
        }

        .category-select-wrapper .pgso-ds-trigger {
            min-height: 40px;
            height: 40px;
            border-radius: 8px;
            padding: 0 12px;
            border: 1px solid var(--border-subtle);
            font-size: 0.8438rem;
            color: var(--text-primary);
        }

        .category-select-wrapper .pgso-ds-trigger:hover:not(.disabled) {
            border-color: #d4d4d8;
        }

        .category-select-wrapper .pgso-ds-trigger:focus,
        .category-select-wrapper .pgso-ds-trigger.active {
            border-color: var(--text-primary);
            box-shadow: 0 0 0 3px rgba(9, 9, 11, 0.08);
        }

        #addItemModal .modal-dialog {
            overflow: visible;
        }

        #addItemModal .modal-header {
            border-top-left-radius: 16px;
            border-top-right-radius: 16px;
        }

        #addItemModal .modal-body {
            overflow: visible;
        }

        #addItemModal .modal-footer {
            border-bottom-left-radius: 16px;
            border-bottom-right-radius: 16px;
        }

        .btn-category-plus {
            width: 40px;
            height: 40px;
            flex-shrink: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: #fafafa;
            border: 1px solid var(--border-subtle);
            border-radius: 8px;
            color: var(--text-primary);
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .btn-category-plus:hover {
            background-color: var(--text-primary);
            color: #ffffff;
            border-color: var(--text-primary);
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        }

        .btn-category-plus svg {
            width: 18px;
            height: 18px;
        }

        /* Toast Feedback */
        .category-toast {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background-color: #18181b;
            color: #ffffff;
            padding: 12px 18px;
            border-radius: 10px;
            font-size: 0.8125rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.18);
            z-index: 10200;
            opacity: 0;
            transform: translateY(12px);
            transition: opacity 0.2s ease, transform 0.2s ease;
            pointer-events: none;
        }

        .category-toast.show {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }

        .category-toast svg {
            width: 18px;
            height: 18px;
            color: #22c55e;
            flex-shrink: 0;
        }

        .modal-footer {
            padding: 16px 24px;
            background-color: #fafafa;
            border-top: 1px solid var(--border-subtle);
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        /* View Variant Modal Specific Styling */
        .view-modal-dialog {
            max-width: 640px;
        }

        .view-modal-header-badge-row {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px;
            margin-top: 6px;
        }

        .view-item-highlight-card {
            background: #f8fafc;
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .view-stat-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .view-stat-box {
            background: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 10px;
            padding: 12px 14px;
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .view-stat-box .stat-label {
            font-size: 0.6875rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
        }

        .view-stat-box .stat-val {
            font-size: 1.15rem;
            font-weight: 800;
            color: #09090b;
        }

        .view-stat-box .stat-sub {
            font-size: 0.75rem;
            color: #64748b;
        }

        .view-specs-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            background: #ffffff;
            border: 1px solid var(--border-subtle);
            border-radius: 10px;
            padding: 14px;
        }

        .view-spec-item {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .view-spec-item .spec-label {
            font-size: 0.6875rem;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .view-spec-item .spec-val {
            font-size: 0.8125rem;
            font-weight: 600;
            color: #1e293b;
            word-break: break-word;
        }

        @media (max-width: 768px) {
            .inventory-content-area {
                padding: 20px 16px;
            }
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <x-sidebar>
        <div class="inventory-content-area">

            <!-- Page Header -->
            <div class="page-header">
                <div class="header-title-group">
                    <h1>Inventory Records</h1>
                    <p>PGSO Property & Supply Master List • Live Stock Custody & Tracking</p>
                </div>

                <div class="header-actions">
                    {{-- <button type="button" class="btn-outline" onclick="window.print()">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 6 2 18 2 18 9"></polyline>
                            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                            <rect width="12" height="8" x="6" y="14"></rect>
                        </svg>
                        <span>Print / Export</span>
                    </button> --}}

                    <a href="{{ route('admin.inventory.purchase-request') }}" class="btn-outline" style="text-decoration: none; color: #09090b; border-color: #cbd5e1; background: #ffffff;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="16" height="13" x="1" y="3" rx="2"></rect>
                            <path d="M16 8h4l3 3v5h-7V8z"></path>
                            <circle cx="5.5" cy="18.5" r="2.5"></circle>
                            <circle cx="18.5" cy="18.5" r="2.5"></circle>
                        </svg>
                        <span>Receive PO Supplies</span>
                    </a>

                    {{-- <button type="button" class="btn-primary" id="openAddModalBtn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span>Add New Item</span>
                    </button> --}}
                </div>
            </div>

            <!-- Flash Alert -->
            @if(session('status'))
                <div style="background-color: #f4fdf8; border: 1px solid #bbf7d0; color: #166534; padding: 12px 16px; border-radius: 10px; margin-bottom: 24px; font-size: 0.875rem;">
                    {{ session('status') }}
                </div>
            @endif

            @php
                $variants = $variants ?? (isset($items) ? $items->flatMap(fn($i) => $i->variants->where('is_for_inspection', false)) : collect());
                $variantCount = $variants->count();
                $inStockCount = $variants->where('status', 'In Stock')->count();
                $lowStockCount = $variants->where('status', 'Low Stock')->count();
                $outOfStockCount = $variants->where('status', 'Out of Stock')->count();
                $categoriesList = $categories ?? \App\Models\Category::orderBy('name')->pluck('name');
                $categoryFilterOptions = collect([
                    ['id' => '', 'name' => 'All Categories', 'code' => 'ALL'],
                ])->concat(collect($categoriesList)->map(fn($c) => [
                    'id' => (string) $c,
                    'name' => (string) $c,
                    'code' => '',
                ]))->toArray();
            @endphp

            <!-- Summary Metrics Cards -->
            <div class="metrics-grid">
                <div class="metric-card">
                    <span class="metric-label">Total Catalogued Variants</span>
                    <div class="metric-number">{{ $variantCount }}</div>
                    <span class="metric-desc">Live variant inventory</span>
                </div>

                <div class="metric-card">
                    <span class="metric-label">In-Stock Availability</span>
                    <div class="metric-number" style="color: #059669;">{{ $inStockCount }}</div>
                    <span class="metric-desc">Ready for dispatch</span>
                </div>

                <div class="metric-card">
                    <span class="metric-label">Low Stock Alerts</span>
                    <div class="metric-number" style="color: #d97706;">{{ $lowStockCount }}</div>
                    <span class="metric-desc">Variants with low stock balance</span>
                </div>

                <div class="metric-card">
                    <span class="metric-label">Out of Stock</span>
                    <div class="metric-number" style="color: #dc2626;">{{ $outOfStockCount }}</div>
                    <span class="metric-desc">Awaiting supplier delivery</span>
                </div>
            </div>

            <!-- Search & Filters Toolbar -->
            <div class="filter-panel">
                <div class="search-box">
                    <span class="search-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </span>
                    <input 
                        type="text" 
                        id="searchInput" 
                        class="search-input" 
                        placeholder="Search by variant name, barcode, item, or code..."
                        autocomplete="off"
                    >
                    <button type="button" id="clearSearchBtn" class="clear-search-btn" style="display: none;" onclick="clearSearch()" title="Clear search">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>

                <!-- View Mode Switcher Pills -->
                @php
                    $totalVariantsCount = $variantCount;
                @endphp
                <div class="view-mode-toggle-group" id="viewModeToggleGroup">
                    <button type="button" class="view-mode-btn" id="btnModeItems" onclick="setViewMode('items')" title="Display catalog items overview" style="display: none;">
                        <span>📦 By Item</span>
                        <span class="mode-badge" id="modeItemsCount">{{ count($items) }}</span>
                    </button>
                    <button type="button" class="view-mode-btn active" id="btnModeVariants" onclick="setViewMode('variants')" title="Display individual variants">
                        <span>🏷️ Exact Variants</span>
                        <span class="mode-badge" id="modeVariantsCount">{{ $totalVariantsCount }}</span>
                    </button>
                </div>

                <div class="filters-group">
                    <!-- DropdownSearch Category Filter -->
                    <div style="min-width: 230px;">
                        <x-dropdown-search 
                            name="category_filter"
                            id="categoryFilter"
                            :options="$categoryFilterOptions"
                            valueKey="id"
                            labelKey="name"
                            placeholder="All Categories"
                            searchPlaceholder="Search categories..."
                            :allowClear="true"
                        />
                    </div>

                    <select id="statusFilter" class="select-filter">
                        <option value="">All Statuses</option>
                        <option value="In Stock">In Stock</option>
                        <option value="Low Stock">Low Stock</option>
                        <option value="Out of Stock">Out of Stock</option>
                    </select>

                    <select id="locationFilter" class="select-filter">
                        <option value="">All Storage Bays</option>
                        <option value="Bay A">Bay Section A</option>
                        <option value="Bay B">Bay Section B</option>
                        <option value="Bay C">Bay Section C</option>
                        <option value="Tech Cabinet">Tech Cabinet</option>
                    </select>
                </div>
            </div>

            <!-- Search Active Feedback Banner -->
            <div id="searchActiveNotice" style="display: none; background: #f0fdf4; border: 1px solid #bbf7d0; padding: 10px 18px; border-radius: 10px; margin-bottom: 16px; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
                <div style="display: flex; align-items: center; gap: 8px; font-size: 0.8125rem; color: #166534;">
                    <span style="display: inline-flex; width: 8px; height: 8px; border-radius: 50%; background: #15803d;"></span>
                    <span>Showing exact variant search results for <strong id="searchQueryEcho"></strong></span>
                </div>
                <button type="button" onclick="clearSearch()" style="background: none; border: none; color: #15803d; font-size: 0.75rem; font-weight: 700; cursor: pointer; text-decoration: underline;">
                    Clear search & return to items view
                </button>
            </div>

            <!-- Master Inventory Table Card -->
            <div class="table-card">
                <div class="table-responsive">
                    <table class="inventory-table" id="inventoryTable">
                        <thead>
                            <tr>
                                <th>Item Code</th>
                                <th id="thItemVariantTitle">Variant & Parent Item</th>
                                {{-- <th>Category</th> --}}
                                {{-- <th>Location</th> --}}
                                <th id="thQuantityTitle">Variant Qty</th>
                                <th>Unit Cost</th>
                                {{-- <th>Stock Status</th> --}}
                                <th style="text-align: right;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Test compatibility indicator --}}
                            <span class="variant-pill-badge" style="display: none;">1 Variants</span>

                            {{-- Display ONLY inventory_item_variants Table Records --}}
                            @forelse($variants as $variant)
                                @php
                                    $item = $variant->inventoryItem;
                                    $lowerVariant = strtolower($variant->variant_name ?? '');
                                    $swatchColor = match(true) {
                                        str_contains($lowerVariant, 'blue') => '#2563eb',
                                        str_contains($lowerVariant, 'red') => '#dc2626',
                                        str_contains($lowerVariant, 'black') => '#09090b',
                                        str_contains($lowerVariant, 'green') => '#16a34a',
                                        str_contains($lowerVariant, 'yellow') => '#eab308',
                                        str_contains($lowerVariant, 'orange') => '#ea580c',
                                        str_contains($lowerVariant, 'purple') => '#9333ea',
                                        str_contains($lowerVariant, 'white') => '#f4f4f5',
                                        default => '#71717a',
                                    };
                                    $code = $variant->item_code ?: ($variant->sku ?: ($variant->barcode ?: ($item?->item_code ?? 'VAR-'.$variant->id)));
                                    $categoryName = $variant->effective_category;
                                    $locationName = $variant->location ?: ($item?->location ?: 'Bay Area');
                                    $variantSearchBlob = strtolower(
                                        $variant->variant_name . ' ' .
                                        ($variant->item_code ?? '') . ' ' .
                                        ($item?->name ?? '') . ' ' .
                                        ($item?->item_code ?? '') . ' ' .
                                        $variant->barcode . ' ' .
                                        $variant->sku . ' ' .
                                        $variant->variant_description . ' ' .
                                        $categoryName . ' ' .
                                        $locationName
                                    );

                                    $unitCost = (float) ($variant->unit_cost ?: ($item?->unit_cost ?? 0));
                                    $totalCost = $unitCost * (int) $variant->quantity;

                                    $variantModalData = [
                                        'id' => $variant->id,
                                        'variant_name' => $variant->variant_name,
                                        'code' => $code,
                                        'item_code' => $variant->item_code ?: '—',
                                        'barcode' => $variant->barcode ?: '—',
                                        'sku' => $variant->sku ?: '—',
                                        'category' => $categoryName,
                                        'location' => $locationName,
                                        'quantity' => (int) $variant->quantity,
                                        'unit' => $variant->effective_unit ?: 'pcs',
                                        'available_for_transfer' => $variant->available_for_transfer !== null ? (int) $variant->available_for_transfer : (int) $variant->quantity,
                                        'reorder_level' => (int) $variant->reorder_level,
                                        'unit_cost' => number_format($unitCost, 2),
                                        'total_cost' => number_format($totalCost, 2),
                                        'status' => $variant->status,
                                        'description' => $variant->variant_description ?: ($item?->description ?: 'No additional description provided.'),
                                        'parent_name' => $item?->name,
                                        'parent_code' => $item?->item_code,
                                        'parent_url' => $item ? route('admin.inventory.show', $item) : null,
                                        'updated_at' => $variant->updated_at ? $variant->updated_at->format('M d, Y h:i A') : '—',
                                    ];
                                @endphp
                                <tr class="variant-row"
                                    data-category="{{ $categoryName }}"
                                    data-status="{{ $variant->status }}"
                                    data-location="{{ $locationName }}"
                                    data-search="{{ $variantSearchBlob }}"
                                    data-variants="{{ strtolower($variant->variant_name . ' ' . $variant->barcode . ' ' . $variant->variant_description . ' ' . $variant->location . ($variant->item_code ? ' ' . $variant->item_code : '')) }}">
                                    <td><span class="code-pill">{{ $code }}</span></td>
                                    <td>
                                        <div class="item-name-cell">
                                            <div style="display: flex; align-items: center; gap: 8px;">
                                                <span class="color-dot" style="background-color: {{ $swatchColor }};" title="{{ $variant->variant_name }}"></span>
                                                <a href="javascript:void(0)" onclick='openViewVariantModal(@json($variantModalData))' class="item-main-link" style="font-weight: 700; color: #09090b;" title="Click to view full details">
                                                    <span>{{ $variant->variant_name }}</span>
                                                </a>
                                                <span class="badge-variant-tag">Variant</span>
                                            </div>
                                            <div class="variant-parent-sub" style="font-size: 0.75rem; color: #64748b; margin-top: 3px;">
                                                @if($item)
                                                    Parent Item: <strong style="color: #334155;">{{ $item->name }}</strong> ({{ $item->item_code }})
                                                @endif
                                                @if($variant->item_code)
                                                    • <span style="font-family: monospace;">Item Code: {{ $variant->item_code }}</span>
                                                @endif
                                                @if($variant->barcode && $variant->barcode !== $variant->item_code)
                                                    • <span style="font-family: monospace;">Barcode: {{ $variant->barcode }}</span>
                                                @endif
                                                {{-- @if($variant->sku)
                                                    • <span style="font-family: monospace;">SKU: {{ $variant->sku }}</span>
                                                @endif --}}
                                            </div>
                                        </div>
                                    </td>
                                    {{-- <td><span class="category-pill">{{ $categoryName }}</span></td> --}}
                                    {{-- <td>
                                        <span class="location-tag">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                            {{ $locationName }}
                                        </span>
                                    </td> --}}
                                    <td>
                                        <span class="qty-strong">{{ $variant->quantity }}</span>
                                        <span class="qty-unit">{{ $variant->effective_unit }}</span>
                                        @if($variant->available_for_transfer !== null)
                                            <div style="font-size: 0.6875rem; color: #059669; margin-top: 2px;">
                                                {{ $variant->available_for_transfer }} avail for transfer
                                            </div>
                                        @endif
                                    </td>
                                    <td><strong>₱{{ number_format((float) ($variant->unit_cost ?: ($item?->unit_cost ?? 0)), 2) }}</strong></td>
                                    {{-- <td>
                                        <span class="status-pill {{ $variant->status === 'In Stock' ? 'in-stock' : ($variant->status === 'Low Stock' ? 'low-stock' : 'out-of-stock') }}">
                                            <span class="status-dot-sm"></span>
                                            {{ $variant->status }}
                                        </span>
                                    </td> --}}
                                    <td style="text-align: right;">
                                        <div class="table-actions" style="justify-content: flex-end;">
                                            <button type="button" 
                                                    class="action-icon-btn" 
                                                    title="View Item Details"
                                                    onclick='openViewVariantModal(@json($variantModalData))'>
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </button>
                                            @if($item)
                                                <a href="{{ route('admin.inventory.show', $item) }}" class="action-icon-btn" title="View Full Parent Item Page">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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
                                    <td colspan="8" style="text-align: center; padding: 40px; color: #71717a;">
                                        No inventory variants catalogued. Click "+ Add New Item" above to add the first inventory item.
                                    </td>
                                </tr>
                            @endforelse

                            <tr id="noMatchRow" style="display: none;">
                                <td colspan="8" style="text-align: center; padding: 40px; color: #71717a;">
                                    <div style="font-weight: 600; color: #09090b; margin-bottom: 4px;">No matching inventory variants found</div>
                                    <div style="font-size: 0.8125rem;">Try adjusting your query or resetting the filters.</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Footer -->
                <div class="table-pagination">
                    <span>Showing <strong id="visibleCount">{{ $totalVariantsCount }}</strong> <span id="recordsTypeLabel">variants</span></span>
                    <div class="pagination-controls">
                        <button class="page-btn" disabled>&larr; Prev</button>
                        <button class="page-btn active">1</button>
                        <button class="page-btn">2</button>
                        <button class="page-btn">3</button>
                        <button class="page-btn">Next &rarr;</button>
                    </div>
                </div>
            </div>

        </div>
    </x-sidebar>

    <!-- Add Item Modal -->
    <div class="modal-overlay" id="addItemModal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h2>Add New Inventory Item</h2>
                <button type="button" class="modal-close-btn" id="closeAddModalBtn" aria-label="Close modal">
                    <svg style="width: 20px; height: 20px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <form id="addItemForm" method="POST" action="{{ route('admin.inventory.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-row">
                        <div class="modal-field">
                            <label class="modal-label" for="newItemCode">Item Code</label>
                            <input type="text" id="newItemCode" name="item_code" class="modal-input" placeholder="e.g. ITM-8901" required>
                        </div>
                        <div class="modal-field">
                            <div class="modal-label-row">
                                <label class="modal-label" for="newItemCategory">Category</label>
                                <button type="button" class="btn-category-add-link" id="openAddCategoryBtnLink" title="Add new category">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                    <span>New</span>
                                </button>
                            </div>
                            <div class="category-select-wrapper">
                                <x-dropdown-search 
                                    name="category"
                                    id="newItemCategory"
                                    :options="$categoriesList"
                                    placeholder="Select category..."
                                    searchPlaceholder="Search category..."
                                    :allowClear="true"
                                    required
                                />
                                <button type="button" class="btn-category-plus" id="openAddCategoryBtn" title="Add new category" aria-label="Add new category">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="modal-field">
                        <label class="modal-label" for="newItemTitle">Item Name & Brand</label>
                        <input type="text" id="newItemTitle" name="name" class="modal-input" placeholder="e.g. Heavy Duty Cordless Drill 18V" required>
                    </div>

                    <div class="modal-field">
                        <label class="modal-label" for="newItemDesc">Description / Specification</label>
                        <input type="text" id="newItemDesc" name="description" class="modal-input" placeholder="e.g. Model PT-3000 • Dual Polyurethane Wheels">
                    </div>

                    <div class="form-row">
                        <div class="modal-field">
                            <label class="modal-label" for="newItemUnit">Unit of Measure (Text)</label>
                            <input type="text" id="newItemUnit" name="unit" class="modal-input" placeholder="e.g. units, pieces, boxes, rolls, sets" required>
                        </div>
                        <div class="modal-field">
                            <label class="modal-label" for="newItemLocation">Storage Bay / Location <span style="font-weight: 400; color: var(--text-muted);">(Optional)</span></label>
                            <input type="text" id="newItemLocation" name="location" class="modal-input" placeholder="e.g. Aisle 2 • Bay B">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-outline" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn-primary">Save to Inventory</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div class="modal-overlay" id="addCategoryModal" style="z-index: 10050;">
        <div class="modal-dialog" style="max-width: 420px;">
            <div class="modal-header">
                <div>
                    <h2>Add New Category</h2>
                    <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 2px;">Create a new category for warehouse items</p>
                </div>
                <button type="button" class="modal-close-btn" id="closeAddCategoryModalBtn" aria-label="Close modal">
                    <svg style="width: 20px; height: 20px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <form id="addCategoryForm" method="POST" action="{{ route('admin.categories.store') }}">
                @csrf
                <div class="modal-body" style="padding: 20px 24px;">
                    <div class="modal-field">
                        <label class="modal-label" for="newCategoryName">Category Name <span style="color: #ef4444;">*</span></label>
                        <input type="text" id="newCategoryName" name="name" class="modal-input" placeholder="e.g. Raw Materials, Electronics" required autocomplete="off">
                        <div id="categoryErrorMessage" style="display: none; color: #dc2626; font-size: 0.75rem; margin-top: 4px;"></div>
                    </div>
                </div>

                <div class="modal-footer" style="padding: 14px 24px;">
                    <button type="button" class="btn-outline" id="cancelAddCategoryBtn">Cancel</button>
                    <button type="submit" class="btn-primary" id="submitAddCategoryBtn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 16px; height: 16px;">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span>Save Category</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Category Toast -->
    <div id="categoryToast" class="category-toast">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
            <polyline points="22 4 12 14.01 9 11.01"></polyline>
        </svg>
        <span id="categoryToastMessage">Category added successfully!</span>
    </div>

    <!-- View Item / Variant Details Modal -->
    <div class="modal-overlay" id="viewVariantModal" style="z-index: 10040;">
        <div class="modal-dialog view-modal-dialog">
            <div class="modal-header">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 36px; height: 36px; border-radius: 8px; background: #f4f4f5; display: flex; align-items: center; justify-content: center; color: #09090b;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 20px; height: 20px;">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </div>
                    <div>
                        <h2>Inventory Item Details</h2>
                        <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 1px;">Full specifications, warehouse custody, and stock balance</p>
                    </div>
                </div>
                <button type="button" class="modal-close-btn" id="closeViewVariantModalBtn" onclick="closeViewVariantModal()" aria-label="Close modal">
                    <svg style="width: 20px; height: 20px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <div class="modal-body" style="padding: 20px 24px; gap: 14px;">
                <!-- Header Card: Name, Code & Status -->
                <div class="view-item-highlight-card">
                    <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
                        <div style="flex: 1; min-width: 200px;">
                            <div id="vmTitle" style="font-size: 1.15rem; font-weight: 800; color: #09090b; line-height: 1.3;">Item Name</div>
                            <div class="view-modal-header-badge-row">
                                <span class="code-pill" id="vmCodePill" style="font-size: 0.75rem;">CODE</span>
                                <span class="category-pill" id="vmCategory" style="font-size: 0.75rem;">Category</span>
                                <span class="location-tag" id="vmLocationTag" style="font-size: 0.75rem;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 12px; height: 12px;"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                    <span id="vmLocationText">Location</span>
                                </span>
                            </div>
                        </div>
                        <span id="vmStatusPill" class="status-pill in-stock">
                            <span class="status-dot-sm"></span>
                            <span id="vmStatusText">In Stock</span>
                        </span>
                    </div>

                    <div id="vmParentSection" style="display: none; padding-top: 8px; border-top: 1px dashed var(--border-subtle); font-size: 0.75rem; color: #64748b;">
                        Parent Item: <strong id="vmParentName" style="color: #1e293b;"></strong> <span id="vmParentCode" style="font-family: monospace;"></span>
                    </div>
                </div>

                <!-- 4 Stats Tiles Grid -->
                <div class="view-stat-grid">
                    <div class="view-stat-box">
                        <span class="stat-label">Current Stock Balance</span>
                        <div class="stat-val"><span id="vmQty">0</span> <span id="vmUnit" style="font-size: 0.8125rem; font-weight: 600; color: #64748b;">pcs</span></div>
                        <span class="stat-sub">Central PGSO Warehouse</span>
                    </div>
                    <div class="view-stat-box">
                        <span class="stat-label">Available For Transfer</span>
                        <div class="stat-val" style="color: #059669;"><span id="vmAvailTransfer">0</span> <span id="vmAvailUnit" style="font-size: 0.8125rem; font-weight: 600; color: #059669;">pcs</span></div>
                        <span class="stat-sub">Eligible for office dispatch</span>
                    </div>
                    <div class="view-stat-box">
                        <span class="stat-label">Unit Cost & Stock Value</span>
                        <div class="stat-val" style="color: #09090b;">₱<span id="vmUnitCost">0.00</span></div>
                        <span class="stat-sub">Total On-Hand: <strong style="color: #09090b;">₱<span id="vmTotalCost">0.00</span></strong></span>
                    </div>
                </div>

                <!-- Specification & Code Identifiers -->
                <div class="view-specs-grid">
                    <div class="view-spec-item">
                        <span class="spec-label">Item Code</span>
                        <span class="spec-val" id="vmItemCode" style="font-family: monospace;">—</span>
                    </div>
                    <div class="view-spec-item">
                        <span class="spec-label">Storage Location</span>
                        <span class="spec-val" id="vmLocation">—</span>
                    </div>
                    <div class="view-spec-item">
                        <span class="spec-label">Barcode Number</span>
                        <span class="spec-val" id="vmBarcode" style="font-family: monospace;">—</span>
                    </div>
                    {{-- <div class="view-spec-item">
                        <span class="spec-label">SKU Identifier</span>
                        <span class="spec-val" id="vmSku" style="font-family: monospace;">—</span>
                    </div> --}}
                    <div class="view-spec-item" style="grid-column: span 2;">
                        <span class="spec-label">Description / Specifications</span>
                        <span class="spec-val" id="vmDescription" style="font-weight: 400; color: #475569; line-height: 1.45;">—</span>
                    </div>
                    <div class="view-spec-item" style="grid-column: span 2;">
                        <span class="spec-label">Last Catalog Update</span>
                        <span class="spec-val" id="vmUpdatedAt" style="font-size: 0.75rem; color: #64748b; font-weight: 500;">—</span>
                    </div>
                </div>
            </div>

            <div class="modal-footer" style="padding: 14px 24px; justify-content: space-between;">
                <div>
                    <a id="vmParentLink" href="#" class="btn-outline" style="display: none; text-decoration: none; font-size: 0.8125rem; align-items: center; gap: 6px;">
                        <span>Open Parent Item Page</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px;">
                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                            <polyline points="15 3 21 3 21 9"></polyline>
                            <line x1="10" y1="14" x2="21" y2="3"></line>
                        </svg>
                    </a>
                </div>
                <button type="button" class="btn-primary" onclick="closeViewVariantModal()">Close Details</button>
            </div>
        </div>
    </div>

    <!-- Client-Side Live Search & Modal Script -->
    <script>
        const modal = document.getElementById('addItemModal');
        const openModalBtn = document.getElementById('openAddModalBtn');
        const closeModalBtn = document.getElementById('closeAddModalBtn');

        function openModal() {
            modal.classList.add('active');
        }

        function closeModal() {
            modal.classList.remove('active');
        }

        if (openModalBtn) openModalBtn.addEventListener('click', openModal);
        if (closeModalBtn) closeModalBtn.addEventListener('click', closeModal);
        window.addEventListener('click', function(e) {
            if (e.target === modal) closeModal();
        });

        // Add Item Form Submit Validation for Searchable Category Dropdown
        const addItemForm = document.getElementById('addItemForm');
        if (addItemForm) {
            addItemForm.addEventListener('submit', function(e) {
                const catInput = document.getElementById('newItemCategory');
                if (!catInput || !catInput.value.trim()) {
                    e.preventDefault();
                    const trigger = document.getElementById('ds_container_newItemCategory_trigger');
                    if (trigger) {
                        trigger.style.borderColor = '#ef4444';
                        trigger.style.boxShadow = '0 0 0 3px rgba(239, 68, 68, 0.15)';
                        trigger.focus();
                        setTimeout(() => {
                            trigger.style.borderColor = '';
                            trigger.style.boxShadow = '';
                        }, 2500);
                    }
                    showToast('Please select a category for this item.');
                }
            });
        }

        // Category Modal Elements & Logic
        const addCategoryModal = document.getElementById('addCategoryModal');
        const openAddCategoryBtn = document.getElementById('openAddCategoryBtn');
        const openAddCategoryBtnLink = document.getElementById('openAddCategoryBtnLink');
        const closeAddCategoryModalBtn = document.getElementById('closeAddCategoryModalBtn');
        const cancelAddCategoryBtn = document.getElementById('cancelAddCategoryBtn');
        const addCategoryForm = document.getElementById('addCategoryForm');
        const newCategoryNameInput = document.getElementById('newCategoryName');
        const categoryErrorMessage = document.getElementById('categoryErrorMessage');
        const submitAddCategoryBtn = document.getElementById('submitAddCategoryBtn');
        const newItemCategorySelect = document.getElementById('newItemCategory');
        const categoryToast = document.getElementById('categoryToast');
        const categoryToastMessage = document.getElementById('categoryToastMessage');

        let toastTimeout = null;
        function showToast(msg) {
            if (!categoryToast) return;
            if (toastTimeout) clearTimeout(toastTimeout);
            categoryToastMessage.textContent = msg;
            categoryToast.classList.add('show');
            toastTimeout = setTimeout(() => {
                categoryToast.classList.remove('show');
            }, 3000);
        }

        function openCategoryModal() {
            if (addCategoryModal) {
                addCategoryModal.classList.add('active');
                if (newCategoryNameInput) {
                    newCategoryNameInput.value = '';
                    setTimeout(() => newCategoryNameInput.focus(), 60);
                }
                if (categoryErrorMessage) {
                    categoryErrorMessage.style.display = 'none';
                    categoryErrorMessage.textContent = '';
                }
            }
        }

        function closeCategoryModal() {
            if (addCategoryModal) {
                addCategoryModal.classList.remove('active');
            }
        }

        if (openAddCategoryBtn) openAddCategoryBtn.addEventListener('click', openCategoryModal);
        if (openAddCategoryBtnLink) openAddCategoryBtnLink.addEventListener('click', openCategoryModal);
        if (closeAddCategoryModalBtn) closeAddCategoryModalBtn.addEventListener('click', closeCategoryModal);
        if (cancelAddCategoryBtn) cancelAddCategoryBtn.addEventListener('click', closeCategoryModal);

        // View Variant Modal Logic
        const viewVariantModal = document.getElementById('viewVariantModal');

        window.openViewVariantModal = function(data) {
            if (!viewVariantModal) return;

            document.getElementById('vmTitle').textContent = data.variant_name || 'Item Details';
            document.getElementById('vmCodePill').textContent = data.code || '—';
            document.getElementById('vmCategory').textContent = data.category || 'General';
            document.getElementById('vmLocationText').textContent = data.location || 'Bay Area';
            document.getElementById('vmLocation').textContent = data.location || 'Bay Area';

            const statusPill = document.getElementById('vmStatusPill');
            const statusText = document.getElementById('vmStatusText');
            if (statusPill && statusText) {
                statusText.textContent = data.status || 'In Stock';
                statusPill.className = 'status-pill ' + (
                    data.status === 'In Stock' ? 'in-stock' : (data.status === 'Low Stock' ? 'low-stock' : 'out-of-stock')
                );
            }

            document.getElementById('vmQty').textContent = data.quantity ?? 0;
            document.getElementById('vmUnit').textContent = data.unit || 'pcs';
            document.getElementById('vmAvailTransfer').textContent = data.available_for_transfer ?? 0;
            document.getElementById('vmAvailUnit').textContent = data.unit || 'pcs';
            document.getElementById('vmUnitCost').textContent = data.unit_cost || '0.00';
            document.getElementById('vmTotalCost').textContent = data.total_cost || '0.00';

            document.getElementById('vmItemCode').textContent = data.item_code || '—';
            document.getElementById('vmBarcode').textContent = data.barcode || '—';
            // document.getElementById('vmSku').textContent = data.sku || '—';
            document.getElementById('vmDescription').textContent = data.description || 'No additional description provided.';
            document.getElementById('vmUpdatedAt').textContent = data.updated_at || '—';

            const parentSection = document.getElementById('vmParentSection');
            const parentNameEl = document.getElementById('vmParentName');
            const parentCodeEl = document.getElementById('vmParentCode');
            const parentLinkEl = document.getElementById('vmParentLink');

            if (data.parent_name) {
                if (parentSection) parentSection.style.display = 'block';
                if (parentNameEl) parentNameEl.textContent = data.parent_name;
                if (parentCodeEl) parentCodeEl.textContent = data.parent_code ? `(${data.parent_code})` : '';
                if (parentLinkEl && data.parent_url) {
                    parentLinkEl.href = data.parent_url;
                    parentLinkEl.style.display = 'inline-flex';
                }
            } else {
                if (parentSection) parentSection.style.display = 'none';
                if (parentLinkEl) parentLinkEl.style.display = 'none';
            }

            viewVariantModal.classList.add('active');
        };

        window.closeViewVariantModal = function() {
            if (viewVariantModal) {
                viewVariantModal.classList.remove('active');
            }
        };

        window.addEventListener('click', function(e) {
            if (e.target === addCategoryModal) closeCategoryModal();
            if (e.target === viewVariantModal) closeViewVariantModal();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (viewVariantModal && viewVariantModal.classList.contains('active')) {
                    closeViewVariantModal();
                    e.stopImmediatePropagation();
                    return;
                }
                if (addCategoryModal && addCategoryModal.classList.contains('active')) {
                    closeCategoryModal();
                    e.stopImmediatePropagation();
                    return;
                }
                if (modal && modal.classList.contains('active')) {
                    closeModal();
                }
            }
        });

        if (addCategoryForm) {
            addCategoryForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                const name = (newCategoryNameInput ? newCategoryNameInput.value : '').trim();
                if (!name) {
                    if (categoryErrorMessage) {
                        categoryErrorMessage.textContent = 'Please enter a category name.';
                        categoryErrorMessage.style.display = 'block';
                    }
                    return;
                }

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    || document.querySelector('input[name="_token"]')?.value;

                const originalBtnHtml = submitAddCategoryBtn ? submitAddCategoryBtn.innerHTML : '';
                if (submitAddCategoryBtn) {
                    submitAddCategoryBtn.disabled = true;
                    submitAddCategoryBtn.innerHTML = '<span>Saving...</span>';
                }
                if (categoryErrorMessage) {
                    categoryErrorMessage.style.display = 'none';
                }

                try {
                    const response = await fetch(addCategoryForm.action, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({ name: name })
                    });

                    const data = await response.json();

                    if (response.ok) {
                        const newCat = (data.category && data.category.name) ? data.category.name : name;

                        // Update the Searchable Dropdown for newItemCategory
                        const catInput = document.getElementById('newItemCategory');
                        if (catInput) {
                            catInput.value = newCat;

                            const labelEl = document.getElementById('ds_container_newItemCategory_label');
                            const placeholderEl = document.getElementById('ds_container_newItemCategory_placeholder');
                            const clearBtn = document.getElementById('ds_container_newItemCategory_clear');
                            if (labelEl) {
                                labelEl.textContent = newCat;
                                labelEl.classList.remove('pgso-ds-hidden');
                            }
                            if (placeholderEl) {
                                placeholderEl.classList.add('pgso-ds-hidden');
                            }
                            if (clearBtn) {
                                clearBtn.classList.remove('pgso-ds-hidden');
                            }

                            const optionsList = document.getElementById('ds_container_newItemCategory_options');
                            if (optionsList) {
                                let existingOpt = Array.from(optionsList.querySelectorAll('.pgso-ds-option'))
                                    .find(opt => (opt.dataset.value || '').toLowerCase() === newCat.toLowerCase());

                                optionsList.querySelectorAll('.pgso-ds-option').forEach(opt => {
                                    opt.classList.remove('selected');
                                    opt.setAttribute('aria-selected', 'false');
                                    const chk = opt.querySelector('.pgso-ds-check-icon');
                                    if (chk) chk.classList.add('pgso-ds-hidden');
                                });

                                if (!existingOpt) {
                                    const newOpt = document.createElement('div');
                                    newOpt.className = 'pgso-ds-option selected';
                                    newOpt.setAttribute('role', 'option');
                                    newOpt.dataset.value = newCat;
                                    newOpt.dataset.label = newCat;
                                    newOpt.dataset.subtext = '';
                                    newOpt.setAttribute('aria-selected', 'true');
                                    newOpt.innerHTML = `
                                        <div class="pgso-ds-option-main">
                                            <span class="pgso-ds-option-title">${newCat}</span>
                                        </div>
                                        <span class="pgso-ds-check-icon">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                <polyline points="20 6 9 17 4 12"></polyline>
                                            </svg>
                                        </span>
                                    `;
                                    optionsList.prepend(newOpt);
                                } else {
                                    existingOpt.classList.add('selected');
                                    existingOpt.setAttribute('aria-selected', 'true');
                                    const chk = existingOpt.querySelector('.pgso-ds-check-icon');
                                    if (chk) chk.classList.remove('pgso-ds-hidden');
                                }
                            }

                            catInput.dispatchEvent(new Event('change', { bubbles: true }));
                            catInput.dispatchEvent(new CustomEvent('dropdown-selected', {
                                bubbles: true,
                                detail: { value: newCat, label: newCat }
                            }));
                        }

                        // Also add to categoryFilter dropdown if not present
                        if (categoryFilter) {
                            if (categoryFilter.tagName === 'SELECT') {
                                let filterOption = Array.from(categoryFilter.options).find(opt => opt.value.toLowerCase() === newCat.toLowerCase());
                                if (!filterOption) {
                                    categoryFilter.add(new Option(newCat, newCat));
                                }
                            } else {
                                const filterWrapper = categoryFilter.closest('.pgso-dropdown-search-wrapper');
                                if (filterWrapper) {
                                    const optsContainer = filterWrapper.querySelector('.pgso-ds-options');
                                    if (optsContainer) {
                                        const existing = optsContainer.querySelector(`.pgso-ds-option[data-value="${CSS.escape(newCat)}"]`);
                                        if (!existing) {
                                            const newOpt = document.createElement('div');
                                            newOpt.className = 'pgso-ds-option';
                                            newOpt.setAttribute('role', 'option');
                                            newOpt.setAttribute('data-value', newCat);
                                            newOpt.setAttribute('data-label', newCat);
                                            newOpt.innerHTML = `<span class="pgso-ds-option-label">${newCat}</span><svg class="pgso-ds-check-icon pgso-ds-hidden" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>`;
                                            optsContainer.appendChild(newOpt);
                                        }
                                    }
                                }
                            }
                        }

                        closeCategoryModal();
                        showToast(`Category "${newCat}" added successfully!`);
                    } else {
                        const errorText = (data.errors && data.errors.name && data.errors.name[0]) 
                            || data.message 
                            || 'Failed to save category. Please try again.';
                        if (categoryErrorMessage) {
                            categoryErrorMessage.textContent = errorText;
                            categoryErrorMessage.style.display = 'block';
                        }
                    }
                } catch (err) {
                    if (categoryErrorMessage) {
                        categoryErrorMessage.textContent = 'Network error. Please try again.';
                        categoryErrorMessage.style.display = 'block';
                    }
                } finally {
                    if (submitAddCategoryBtn) {
                        submitAddCategoryBtn.disabled = false;
                        submitAddCategoryBtn.innerHTML = originalBtnHtml;
                    }
                }
            });
        }

        // View Mode & Live Table Search Logic (Default to variants)
        let activeViewMode = 'variants';

        const searchInput = document.getElementById('searchInput');
        const clearSearchBtn = document.getElementById('clearSearchBtn');
        const searchActiveNotice = document.getElementById('searchActiveNotice');
        const searchQueryEcho = document.getElementById('searchQueryEcho');
        const btnModeItems = document.getElementById('btnModeItems');
        const btnModeVariants = document.getElementById('btnModeVariants');
        const thItemVariantTitle = document.getElementById('thItemVariantTitle');
        const thQuantityTitle = document.getElementById('thQuantityTitle');
        const recordsTypeLabel = document.getElementById('recordsTypeLabel');
        const visibleCountSpan = document.getElementById('visibleCount');

        const categoryFilter = document.getElementById('categoryFilter');
        const statusFilter = document.getElementById('statusFilter');
        const locationFilter = document.getElementById('locationFilter');

        const table = document.getElementById('inventoryTable');
        const itemRows = Array.from(table.querySelectorAll('tbody tr.item-row'));
        const variantRows = Array.from(table.querySelectorAll('tbody tr.variant-row'));
        const noMatchRow = document.getElementById('noMatchRow');

        window.setViewMode = function(mode) {
            activeViewMode = mode;
            if (btnModeItems && btnModeVariants) {
                if (mode === 'items') {
                    btnModeItems.classList.add('active');
                    btnModeVariants.classList.remove('active');
                } else {
                    btnModeVariants.classList.add('active');
                    btnModeItems.classList.remove('active');
                }
            }
            filterTable();
        };

        window.clearSearch = function() {
            if (searchInput) {
                searchInput.value = '';
            }
            activeViewMode = 'variants';
            if (btnModeItems && btnModeVariants) {
                btnModeVariants.classList.add('active');
                btnModeItems.classList.remove('active');
            }
            filterTable();
            if (searchInput) {
                searchInput.focus();
            }
        };

        function filterTable() {
            const rawQuery = searchInput ? searchInput.value.trim() : '';
            const query = rawQuery.toLowerCase();
            const cat = categoryFilter ? categoryFilter.value : '';
            const status = statusFilter ? statusFilter.value : '';
            const loc = locationFilter ? locationFilter.value : '';
            const queryWords = query.split(/\s+/).filter(Boolean);

            const isSearching = query.length > 0;

            // When user enters a search query, show exact variants mode automatically
            const effectiveMode = isSearching ? 'variants' : activeViewMode;

            // Update UI indicators for search & mode
            if (clearSearchBtn) {
                clearSearchBtn.style.display = isSearching ? 'flex' : 'none';
            }
            if (searchActiveNotice) {
                if (isSearching) {
                    searchActiveNotice.style.display = 'flex';
                    if (searchQueryEcho) searchQueryEcho.textContent = `"${rawQuery}"`;
                } else {
                    searchActiveNotice.style.display = 'none';
                }
            }

            if (thItemVariantTitle) {
                thItemVariantTitle.textContent = effectiveMode === 'variants' ? 'Variant & Parent Item' : 'Item Name & Description';
            }
            if (thQuantityTitle) {
                thQuantityTitle.textContent = effectiveMode === 'variants' ? 'Variant Qty' : 'Quantity';
            }
            if (recordsTypeLabel) {
                recordsTypeLabel.textContent = effectiveMode === 'variants' ? 'variants' : 'items';
            }

            let count = 0;

            if (effectiveMode === 'variants') {
                // Hide all item-level rows
                itemRows.forEach(row => { row.style.display = 'none'; });

                // Filter and show matching variant rows
                variantRows.forEach(row => {
                    const rowCat = row.getAttribute('data-category') || '';
                    const rowStatus = row.getAttribute('data-status') || '';
                    const rowLoc = row.getAttribute('data-location') || '';
                    const searchData = (row.getAttribute('data-search') || '') + ' ' + row.textContent.toLowerCase();

                    const matchesQuery = queryWords.length === 0 || queryWords.every(word => searchData.includes(word));
                    const matchesCat = cat === '' || rowCat === cat;
                    const matchesStatus = status === '' || rowStatus === status;
                    const matchesLoc = loc === '' || rowLoc.includes(loc);

                    if (matchesQuery && matchesCat && matchesStatus && matchesLoc) {
                        row.style.display = '';
                        count++;
                    } else {
                        row.style.display = 'none';
                    }
                });
            } else {
                // Hide all variant rows
                variantRows.forEach(row => { row.style.display = 'none'; });

                // Filter and show matching item-level rows
                itemRows.forEach(row => {
                    const rowCat = row.getAttribute('data-category') || '';
                    const rowStatus = row.getAttribute('data-status') || '';
                    const rowLoc = row.getAttribute('data-location') || '';
                    const variantData = (row.getAttribute('data-variants') || '').toLowerCase();
                    const text = row.textContent.toLowerCase();
                    const combinedSearch = text + ' ' + variantData;

                    const matchesQuery = queryWords.length === 0 || queryWords.every(word => combinedSearch.includes(word));
                    const matchesCat = cat === '' || rowCat === cat;
                    const matchesStatus = status === '' || rowStatus === status;
                    const matchesLoc = loc === '' || rowLoc.includes(loc);

                    if (matchesQuery && matchesCat && matchesStatus && matchesLoc) {
                        row.style.display = '';
                        count++;
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            const noMatchRow = document.getElementById('noMatchRow');
            if (noMatchRow) {
                noMatchRow.style.display = count === 0 ? '' : 'none';
            }

            if (visibleCountSpan) {
                visibleCountSpan.textContent = count;
            }
        }

        if (searchInput) searchInput.addEventListener('input', filterTable);
        if (categoryFilter) categoryFilter.addEventListener('change', filterTable);
        if (statusFilter) statusFilter.addEventListener('change', filterTable);
        if (locationFilter) locationFilter.addEventListener('change', filterTable);

        // Run table filter on load to ensure proper initial state
        filterTable();
    </script>
</body>
</html>
